<?php
// like service
require 'vendor/autoload.php';
require 'common.php';

if($_GET['unlike']){
	$client->unlike($_GET['postid'], $_GET['reblogkey']);
} else{
	$client->like($_GET['postid'], $_GET['reblogkey']);
}

header("Content-type: application/x-json");
echo '{"status":"OK"}';