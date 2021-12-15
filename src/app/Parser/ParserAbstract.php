<?php 

namespace App\Parser;
use App\Parser\Observer\ParserObserver;

abstract class ParserAbstract extends ParserObserver {
	private array $payload;
	abstract public function decode(string $payload) : array;
	abstract public function encode(array $payload) : string;
}