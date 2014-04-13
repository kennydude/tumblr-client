<?php
// Reply
require 'vendor/autoload.php';
require "common.php";

$client->postRequest("v2/user/post/reply", array(
	"post_id" => $_POST['postid'],
	"reblog_key" => $_POST['reblogkey'],
	"reply_text" => $_POST['content']
), false);