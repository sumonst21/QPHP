<?php 

use App\Parser\Observer\ParserObserver;
use App\Parser\Parser;
use App\Server\ConnectionHandler;
use App\Storage\ConnectionStorage;
use App\Storage\Storage;
use PHPUnit\Framework\TestCase;

class ConnectionHandlerTest extends TestCase {

	public $connectionHandler;
	public $storage;
	public $connectionStorage;

	public function setUp() : void {
		$this->storage = new Storage;
		$this->connectionStorage = new ConnectionStorage;
		$this->connectionHandler 		= 	new ConnectionHandler(new Parser, new ParserObserver, $this->storage, $this->connectionStorage);
	}

	public function testAcceptingConnection() {
		$this->connectionHandler->accept(1);
		return $this->assertTrue($this->storage->exists(1, $this->connectionStorage));
	}

	public function testClosingConnection() {
		$this->connectionHandler->close(1);
		return $this->assertFalse($this->storage->exists(1, $this->connectionStorage));
	}
	
	public function testReadingMessage() {
		$data = $this->connectionHandler->read('{"user_id" : 1}', 1);
		$this->assertIsArray($data);
		$this->assertEquals($data, ["user_id" => 1]);
	}

}