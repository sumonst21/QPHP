<?php 

namespace App\Server;
use App\Channel\Channel;
use App\Parser\Observer\ParserObserver;
use App\Parser\Parser;
use App\Server\ConnectionHandler;
use App\Server\ServerInterface;
use App\Server\Worker;
use App\Storage\ChannelStorage;
use App\Storage\ConnectionStorage;
use App\Storage\QueueStorage;
use App\Storage\Storage;


class Server  {

	public $server;

	public $storage;
	public $connectionStorage;
	public $channelStorage;
	public $queueStorage;
	public $parser;

	public function __construct(array $config) {
		$this->storage 					= 	new Storage;
		$this->config 					= 	$config;
		$this->connectionStorage 		= 	new ConnectionStorage;
		$this->channelStorage 			= 	new ChannelStorage;
		$this->queueStorage 			= 	new QueueStorage($config);
		$this->parser 					= 	new Parser;
		$this->parserObserver 			= 	new ParserObserver;
		$this->LuaSandbox 				= 	new \LuaSandbox;
		$this->LuaSandbox->registerLibrary( 'php', [
			'output' => function ( $string ) {
				echo "$string\n";
			}
		]);
		$this->LuaSandbox->loadstring(file_get_contents("scripts/helper.lua"))->call();
		$this->channel 					= 	new Channel($this->channelStorage, $this->storage);
		$this->connectionHandler 		= 	new ConnectionHandler($this->parser, $this->parserObserver, $this->storage, $this->connectionStorage, $this->channelStorage, $this->channel);
	}

	public function start() : void {
		$server = new \Swoole\Server('0.0.0.0', 15674);
		$server->set([
			// 'daemonize' 			=> 1,
			"max_conn" 				=> 		$this->config["MAX_CONN"], 
			'open_eof_check' 		=> 		true,
			'open_eof_split' 		=> 		true,
			'package_eof' 			=> 		"\r\n",
			'reactor_num' 			=> 		$this->config['REACTOR_NUM'],
			'worker_num' 			=> 		$this->config['WORKER_NUM'],
			"buffer_output_size" 	=> 		$this->config['BUFFER_OUTPUT_SIZE']
		]);
		$process = new \Swoole\Process([new Worker($server, $this->parser, $this->parserObserver, $this->storage, $this->queueStorage, $this->connectionStorage, $this->LuaSandbox, $this->channel, $this->channelStorage, $this->config["IGNORE_NO_LISTENERS"]), "handle"]);
		$server->addProcess($process);
		$server->on("Connect", [$this, "onConnect"]);
		$server->on('Receive', [$this, "onReceive"]);
		$server->on('Close', [$this, "onClose"]);
		$process->start();
		$server->start();
	}

	public function onConnect($server, int $fd) : void {
		$this->connectionHandler->accept($fd);
	}

	public function onReceive($server, int $fd, int $fromId, string $data) : void {
		$data = $this->LuaSandbox->callfunction("onReceive", ["data" => $data])[0];
		$data = $this->connectionHandler->read($data, $fd);
		if ($this->channel->checkIfHeaderContainsJoinChannelMessage($data)) {
			$this->channel->join($fd, $data["payload"]["CHANNEL_NAME"]);
		}else {
			$this->storage->store(md5(uniqid()), [
				"payload" 		=> 		json_encode($data["payload"]),
				"options" 		=> 		json_encode($data["options"]),
				"created_at" 	=> 		time(),
				"fd"			=> 		$fd
			], $this->queueStorage);
		}
	}

	public function onClose($server, int $fd) : void {
		$this->connectionHandler->close($fd);
	}
}