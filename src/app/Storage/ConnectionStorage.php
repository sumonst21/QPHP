<?php 

namespace App\Storage;

class ConnectionStorage {

	public $table;

	public function __construct() {
		$table = new \Swoole\Table(1024);
		$this->table = $table;
		$this->table->column('id', \Swoole\Table::TYPE_INT);
		$this->table->create();
	}

}