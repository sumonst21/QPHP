<?php

namespace App\Storage;

use App\Storage\ConnectionStorage;

class Storage Extends ConnectionStorage {

	public function store(string $key, array $value, $storageDriver) {
		return $storageDriver->table->set($key, $value);
	}

	public function delete(string $key, $storageDriver) {
		return $storageDriver->table->del($key);
	}

	public function get(string $key, $storageDriver) {
		return $storageDriver->table->get($key);
	}

	public function exists(string $key, $storageDriver) {
		return $storageDriver->table->exists($key);
	}

	public function getTable($storageDriver) {
		return $storageDriver->table;
	}

}