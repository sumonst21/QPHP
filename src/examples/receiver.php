<?php

$address = '0.0.0.0';
$port = 15674;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_connect($sock, $address, $port) === false) {
    echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}
// listen for message in channel "public" 
socket_write($sock, json_encode(["header" => "JOIN_CHANNEL", "payload" => ["CHANNEL_NAME" => "public"]])." \r\n");

$i = 0;
while($out = socket_read($sock, 5042, PHP_NORMAL_READ)) {
    echo $i."\n";
    var_dump($out);
    $i++;
}