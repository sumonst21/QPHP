<?php 

namespace App\Server;

use App\Channel\Channel;
use App\Parser\Observer\ParserObserver;
use App\Parser\Parser;
use App\Server\ConnectionHandlerInterface;
use App\Storage\ChannelStorage;
use App\Storage\ConnectionStorage;
use App\Storage\Storage;
use function Swoole\Coroutine\batch;

class ConnectionHandler implements ConnectionHandlerInterface {

	public function __construct(public Parser $parser, public ParserObserver $parserObserver, public Storage $storage, public ConnectionStorage $connectionStorage, public ChannelStorage $channelStorage, public Channel $channel) {
	}

	public function accept(int $fd) : bool {
		return $this->storage->store($fd, ["id" => $fd], $this->connectionStorage);
	}

	public function read(string $payload, int $sender) : array {
		return $this->parser->decode($this->parserObserver->beforeParsing($payload, $sender));
	}

	public function close(int $fd) : bool {
		$storage = $this->storage;
		$channelStorage = $this->channelStorage;
		$connectionStorage = $this->connectionStorage;
		$channel = $this->channel;

		$storage->delete($fd, $this->connectionStorage);
		$channels = $storage->getTable($channelStorage);
		$channels->rewind();
		while($channels->valid()) {
			if (in_array($fd, json_decode($channels->current()["listeners"], true))) {
				$channel->leave($fd, $channels->current()["name"]);
			}
			$channels->next();
		}

		return true;
	}
}