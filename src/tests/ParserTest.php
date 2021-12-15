<?php 

use App\Parser\Observer\ParserObserver;
use App\Parser\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase {

	public $parser;
	public $observer;

	public function setUp() : void {
		$this->parser = new Parser;
		$this->observer = new ParserObserver;
	}

	public function testParserObserverBeforeParsing() {
		$data = $this->observer->beforeParsing('{"user_id" : 1}', 1);
		$this->assertContainsOnly("string", [$data]);
	}

	public function testParserObserverBeforeEncoding() {
		$data = $this->observer->beforeEncode(["user_id" => 1]);
		$this->assertContainsOnly("array", [$data]);
	}

}