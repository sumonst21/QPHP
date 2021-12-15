<?php 

namespace App\Parser;

use App\Parser\ParserAbstract;

class Parser extends ParserAbstract {
	private array $payload;

	public function decode(string $payload) : array {
		$this->payload = json_decode($payload, true);
		return (JSON_ERROR_NONE !== json_last_error()) ? [] : $this->payload;
	}

	public function encode(array $payload) : string {
		return json_encode($payload);
	}
}