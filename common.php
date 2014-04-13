<?php
require 'config.php';
$client = new Tumblr\API\Client($consumerKey, $consumerSecret);
$client->setToken($token, $tokenSecret);

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
		return $me;
	}
}

function nocdn($in){
	return preg_replace( "/[0-9]+\.media\.tumblr\.com\//", "media.tumblr.com/", $in);
}

function require_official_api(){
	if(!OFFICIAL_API){
		die("Unfortunately, Tumblr have made this API official only. If you have aquired your official credentails, the OFFICIAL_API flag can be set");
	}
}



/*
# official api

ssshhh

If you use tumblr credentials, you can unlock magical features.

Config file needs define("OFFICIAL_API", true); set

Use root shell on android:

cat /data/data/com.tumblr/shared_prefs/tumblr.xml

ck: BUHsuO5U9DF42uJtc8QTZlOmnUaJmBJGuU1efURxeklbdiLn9L
cs: olOu3aRBCdqCuMFm8fmzNjMAWmICADSIuXWTnVSFng1ZcLU1cV

also vendor/tumblr/lib/Tumblr/API/Client.php needs changing
functions getRequest and postRequest need to be made public

Dear Tumblr: There is no reason for these APIs to be private
			 So please stop making them private.

*/