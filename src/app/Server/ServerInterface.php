<?php 

namespace App\Server;

interface ServerInterface {
	/**
	 * The start is responsible for starting the swoole server, proccess and table according to the configuration in config/.env file
	 * @param array config , list of user configurations
	 * @return void
	**/
	public function start(array $config) : void;
	/**
	 * https://www.swoole.co.uk/docs/modules/swoole-server-on-connect
	 * @return void
	**/
	public function onConnect($server, int $fd) : void;
	/**
	 * https://www.swoole.co.uk/docs/modules/swoole-server-on-receive
	 * @return void
	**/
	public function onReceive($server, int $fd, int $fromId, string $data) : void;
	/**
	 * https://www.swoole.co.uk/docs/modules/swoole-server-on-close
	 * @return void
	**/
	public function onClose($server, int $fd) : void;

}