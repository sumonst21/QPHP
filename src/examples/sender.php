<?php 

$address = '0.0.0.0';
$port = 15674;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_connect($sock, $address, $port) === false) {
    echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

$i = 0;
// Send simple queue message
socket_write($sock, json_encode(["header" => "QUEUE", "payload" => ["user_id" => 5], "options" => ["channel" => "public"]])." \r\n");
// Send queue with delay in seconds
// socket_write($sock, json_encode(["header" => "QUEUE", "payload" => ["user_id" => 5], "options" => ["channel" => "notifications", "delay" => 5]])." \r\n");
$i++;
echo "Seneded ".$i." \n";
socket_close($sock);