<?php
require 'config.php';
require 'rb.phar';

R::setup("sqlite:cache/db.db", 'x', 'x');

$memCache = array();

function get_my_blog_names(){
	$blogs = get_my_blogs();
	$ret = array();
	foreach ($blogs as $blog){
		$ret[] = $blog->name;
	}
	return $ret;
}

function get_my_blogs($cache = true){
	$me = get_userinfo($cache);
	return $me->user->blogs;
}

function get_userinfo($cache = true){
	global $client, $memCache;
	if($cache == true){
		if($memCache['me']){
			return $memCache['me'];
		}
		if(file_exists("cache/me.json")){
			if(filemtime("cache/me.json") > time() - (60*60*9)){
				// less than 9 hours old
				$f = json_decode(file_get_contents("cache/me.json"));
				$memCache['me'] = $f;
				return $f;
			}
		}
		return get_userinfo(false);
	} else{
		$me = $client->getUserInfo();
		file_put_contents("cache/me.json", json_encode($me));
		return get_userinfo(true);
	}
}

function nocdn($in){
	return preg_replace( "/[0-9]+\.media\.tumblr\.com\//", "media.tumblr.com/", $in);
}