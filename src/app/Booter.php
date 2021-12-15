<?php 

namespace App;

use Dotenv\Dotenv;

class Booter {

	public function loadConfigurations(string $dir) {
		$dotenv = Dotenv::createImmutable($dir."/config/");
		return $dotenv->load();
	}

}