<?php 

namespace App\Parser\Observer;

class ParserObserver {

	public function beforeParsing(string $payload, int $sender) : string {
		return $payload;
	}

	public function beforeEncode(array $payload) : array {
		return $payload;
	}

}