<?php 

namespace App\Server;

interface ConnectionHandlerInterface {
	/**
	 * The accept method is responsible or accepting and storing the connection in the memory
	 * @param int fd , file descriptor
	 * @return boolean if saved in memory or no need for that
	 * **/
	public function accept(int $fd) : bool;
	/**
	 * The read method is responsible for passing the payload message to the Message parser
	 * @param string payload, the queue payload
	 * @param int sender
	 * @return array of of the parsed payload
	 * @throws JsonException
	*/
	public function read(string $payload, int $sender) : array;
	/**
	 * The close method is resposible for removing the closed connection from the momory
	 * @param int fd, file descriptor
	 * @return boolean if deleted or not
	*/
	public function close(int $fd) : bool;
}