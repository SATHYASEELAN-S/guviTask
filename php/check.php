<?php
require_once("../redis/vendor/predis/predis/autoload.php");
require_once("../redis/vendor/autoload.php");

$redis = new Predis\Client();
$redisKey = "user";
// $userEmail = $redis->hget($redisKey, 'email');
$userEmail = $redis->get("logged-mail");

$mongoDB = 'myDB'; 

echo $userEmail;