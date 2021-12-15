<?php 

namespace App\Storage;

class QueueStorage {

	public $table;

	public function __construct(array $config) {
		$table = new \Swoole\Table($config["QUEUE_TABEL_SIZE"]);
		$this->table = $table;
		$this->table->column('payload', \Swoole\Table::TYPE_STRING, 1024);
		$this->table->column('options', \Swoole\Table::TYPE_STRING, 1024);
		$this->table->column('created_at', \Swoole\Table::TYPE_INT, 64);
		$this->table->create();
	}

}