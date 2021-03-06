<?php 

namespace App\Channel;

interface ChannelInterface {
	/**
	 * This constant is what the client will send in header to the server to join a channel
	 */
	const JOIN_CHANNEL_KEYWORD = "JOIN_CHANNEL";
	/**
	 * This will check if the sended header contains the join channel keywork 
	 * @param array of data sended by the user
	 * @return boolean
	 */
	public function checkIfHeaderContainsJoinChannelMessage(array $data) : bool;
	/**
	 * This method will add the file descriptor the the channel storage
	 * @param integer file descriptor generated by swoole php.
	 * @param string channel name sened by the user
	 * @return integer file descriptor
	 */
	public function join(int $fd, string $channelName) : int;
	/**
	 * This method will allocate RAM space to save listeners for a specifique channel if not exists
	 * @param string channel name
	 * @return string channel name
	 */
	public function createChannelIfNotExists(string $channelName) : string;
	/**
	 * This method will get all listeners of a gived channel
	 * @param string channel name 
	 * @return array of the saved listeners 
	 */
	public function listListeners(string $channelName) : array;
}