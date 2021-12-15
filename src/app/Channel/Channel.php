<?php 

namespace App\Channel;

use App\Channel\ChannelInterface;
use App\Storage\ChannelStorage;
use App\Storage\Storage;

class Channel implements ChannelInterface {

	public function __construct(public ChannelStorage $channelStorage, public Storage $storage) {
	}

	public function checkIfHeaderContainsJoinChannelMessage(array $data) : bool {
		return $data["header"] === ChannelInterface::JOIN_CHANNEL_KEYWORD;
	}

	public function join(int $fd, string $channelName) : int {
		$this->createChannelIfNotExists($channelName);
		$listeners = json_decode($this->listListeners($channelName)["listeners"], true);
		array_push($listeners, $fd);
		$this->storage->store($channelName, ["name" => $channelName, "listeners" => json_encode($listeners)], $this->channelStorage);
		return $fd;
	}

	public function leave(int $fd, string $channelName) : int {
		$listeners = json_decode($this->listListeners($channelName)["listeners"], true);
		$key = array_search($fd, $listeners);
		if (is_int($key)) {
			unset($listeners[$key]);
		}
		$this->storage->store($channelName, ["name" => $channelName, "listeners" => json_encode($listeners)], $this->channelStorage);
		return $fd;
	}

	public function createChannelIfNotExists(string $channelName) : string {
		if(!$this->storage->exists($channelName, $this->channelStorage)) {
			$this->storage->store($channelName, ["name" => $channelName, "listeners" => json_encode([])], $this->channelStorage);
		}
		return $channelName;
	}

	public function listListeners(string $channelName) : array {
		$listeners = $this->storage->get($channelName, $this->channelStorage);
		return (!is_array($listeners)) ? ["listeners" => json_encode([])] : $listeners;
	}

}