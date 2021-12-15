<?php 

namespace App\Server;

use App\Booter;
use App\Channel\Channel;
use App\Parser\Observer\ParserObserver;
use App\Parser\Parser;
use App\Server\Server;
use App\Storage\ChannelStorage;
use App\Storage\ConnectionStorage;
use App\Storage\QueueStorage;
use App\Storage\Storage;

class Worker {

	public function __construct(
		public $server, 
		public Parser $parser, 
		public ParserObserver $parserObserver, 
		public Storage $storage, 
		public QueueStorage $queueStorage, 
		public ConnectionStorage $connectionStorage, 
		public \LuaSandbox $LuaSandbox, 
		public Channel $channel,
		public ChannelStorage $channelStorage,
		public bool $ignoreListeners) {}

	/**
	 * This method will start infinit loop to iterate over stored queues and stored connections (listenres).
	 * There is two types of dispatch 
	 * 		1. Direct dispatch : no channel (tube) , this will dispatch the queue to all listeners.
	 * 		2. Using channel : This will dispatch the queue only to the listeners joined the channel
	 */
	public function handle($process) {
		while(true) {
			$sended 			= 	false;
			$queueStorage 		= 	$this->queueStorage->table;
			$connectionStorage 	= 	$this->connectionStorage->table;
			if ($connectionStorage->count() > 0 OR $this->ignoreListeners) {
				$queueStorage->rewind();
				while($queueStorage->valid()) {
					if ($this->allowedToDispatch($queueStorage->current())) {
						$data = $this->parser->encode($this->parserObserver->beforeEncode(json_decode($this->LuaSandbox->callfunction("beforeEncode", ["data" => $queueStorage->current()["payload"]])[0], true)))."\n";
						if ($this->channelInOption($queueStorage->current())) {
							$channelListeners = $this->getChannelListeners($queueStorage->current());
							foreach($channelListeners as $listener) {
								if ($this->server->exists($listener)) {
									$this->server->send($listener, $data);
									$sended = true;
								}
							}
						}else {
							$connectionStorage->rewind();
							while($connectionStorage->valid()) {
								if ($connectionStorage->current()["id"] !== $queueStorage->current()["fd"]) {
									if ($this->server->exists($connectionStorage->current()["id"]) AND $this->notSubscribedToAnyChannel($connectionStorage->current()["id"], $this->storage, $this->channelStorage)) {
										$this->server->send($connectionStorage->current()["id"], $data);
										$sended = true;
									}
								}
								$connectionStorage->next();
							}
						}
						if ($sended) {
							$queueStorage->del($queueStorage->key());
						}
					}
					$queueStorage->next();
				}
			}
		} 
	}

	public function allowedToDispatch(array $queueStorage) : bool {
		$options = json_decode($queueStorage["options"], true); 
		if (array_key_exists("delay", $options)) {
			return !((time() - $queueStorage["created_at"]) < $options["delay"]);
		}
		return true;
	}
	
	public function channelInOption(array $queueStorage) : bool {
		return array_key_exists("channel", json_decode($queueStorage["options"], true));
	}

	public function getChannelListeners(array $queue) : array {
		return json_decode($this->channel->listListeners(json_decode($queue["options"], true)["channel"])["listeners"], true);
	}

	public function notSubscribedToAnyChannel(int $listener, Storage $storage, ChannelStorage $channelStorage) : bool {
		$channels = $storage->getTable($channelStorage);
		$channels->rewind();
		while($channels->valid()) {
			if (in_array($listener, json_decode($channels->current()["listeners"], true))) {
				return false;
			}
			$channels->next();
		}
		return true;
	}

}