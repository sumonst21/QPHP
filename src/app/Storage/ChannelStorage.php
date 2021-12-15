<?php 

namespace App\Storage;

class ChannelStorage {

	public $table;

	public function __construct() {
		$table = new \Swoole\Table(1024);
		$this->table = $table;
		$this->table->column('name', \Swoole\Table::TYPE_STRING, 64);
		$this->table->column('listeners', \Swoole\Table::TYPE_STRING, 1024);
		$this->table->create();
	}

}