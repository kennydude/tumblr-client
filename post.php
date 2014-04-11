<?php
require 'vendor/autoload.php';
require 'common.php';
// single post

$post = $client->getBlogPosts($_GET['name'], array(
	'id' => $_GET['id'],
	'reblog_info' => 'true'
));

$title = "single post by " . $post->posts[0]->blog_name;

require 'theme/header.php';
foreach($post->posts as $post){
	require 'theme/post.php';
}
require 'theme/footer.php';