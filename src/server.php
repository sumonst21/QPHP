<?php 

require "vendor/autoload.php";
use App\Booter;
use App\Server\Server;

$server = new Server((new Booter)->loadConfigurations(__DIR__));
$server->start();
