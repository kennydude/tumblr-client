<?php
// blog page
require 'vendor/autoload.php';
require 'common.php';

$myblogs = get_my_blog_names();
$blog = $_GET['blog'];
$this_blog = $blog;

if(!$_GET['action']){
	$_GET['action'] = "posts";
}

if(in_array($blog, $myblogs)){
	$my_blogs = get_my_blogs( $_GET['refresh'] == "true" ? false : true );
	foreach ($my_blogs as $xx_blog) {
		if($xx_blog->name == $blog){
			$bloginfo = $xx_blog;
			define("VIEWING_MY_BLOG", true);
		}
	}
}
if(!$bloginfo){
	$bloginfo = $client->getBlogInfo($blog);
}

function blog_header(){
	global $blog, $myblogs, $bloginfo, $client;
	$actions = array("posts", "text", "photos", "quotes", "links", "chats", "audio", "videos", "answers");
	if(OFFICIAL_API == true && in_array($blog, $myblogs)){
		$actions[] = "notes";
	}
	if(in_array($blog, $myblogs)){
		$actions[] = "queue";
		$actions[] = "drafts";
		$actions[] = "messages";
	}
	?>
	<div class="bloginfo post row m10down">
		<div class="col-md-2">
			<img src="http://api.tumblr.com/v2/blog/<?php echo $bloginfo->name; ?>.tumblr.com/avatar/64" />
		</div>
		<div class="col-md-10">
			<h5><?php echo $bloginfo->name; ?></h5>
			<p><?php echo $bloginfo->description; ?></p>
			<p class="stats">
				<strong>
				<?php
					echo $bloginfo->posts . " posts ";
					if($bloginfo->followers){
						echo $bloginfo->followers . " followers ";
					}
				?>
				</strong>
				<?php if(defined("VIEWING_MY_BLOG")){ ?>
				<a href="?<?php echo $_SERVER['QUERY_STRING']; ?>&refresh=true">
					<i 	class="glyphicon glyphicon-refresh"
						title="Refresh data">
					</i>
				</a>
				<?php } if(defined("DEBUG")){ ?>
				<i 	class="glyphicon glyphicon-screenshot sourceButton pull-right"
					title="View full data returned from tumblr">
				</i>
				<pre class="hidden postsource"><?php ob_start(); var_dump($bloginfo); $x = ob_get_contents(); ob_end_clean(); echo htmlspecialchars($x); ?></pre><?php } ?>
			</p>
		</div>
	</div>
	<ul class="nav nav-pills nav-stacked m10down blognav">
		<?php
		$a = $_GET['action'];
		if($_GET['type']){
			$a = $_GET['type'];
		}
		foreach ($actions as $action) {
			echo '<li ';
			echo $action == $a ? ' class="active"' : '';
			echo '>';
			echo '<a href="?blog=' . $blog;
			echo $action == "posts" ? '' : '&action=' . $action;
			echo '">';

			if($bloginfo->$action !== NULL){
				echo '<span class="badge pull-right">'.$bloginfo->$action.'</span>';
			}

			echo ucfirst($action) . '</a></li>';
		}
		?>
	</ul>
	<?php
}

switch($_GET['action']){
	case "notes":
		require_official_api();

		$title = $blog  . " - notes";
		require "theme/header.php";
		blog_header();

		$notes = $client->getRequest("v2/blog/".$blog.".tumblr.com/notifications", array(
			"before" => $_GET['before'],
			"rfg" => true
		), false);
		foreach ($notes->notifications as $note) {
			require "theme/note.php";
			$lasttime = $note->before;
		}

		?>
		<div class="pagination">
			<a href="?blog=<?php echo $blog; ?>&action=notes&before=<?php echo $lasttime; ?>" class="btn btn-lg btn-primary">Forward!</a>
		</div>
		<?php

		require "theme/footer.php";
		break;
	case "text":
	case "photos":
	case "quotes":
	case "links":
	case "chats": 
	case "audio":
	case "videos":
	case "answers":
		$_GET['type'] = $_GET['action']; // no break
		$type = $_GET['type'];
		if(stripos($_GET['type'], "s")){
			$type = substr($_GET['type'], 0, strlen($_GET['type'])-1);
		}
		$_GET['action'] = 'posts';
	case "posts":
	case "queue":
	case "drafts":
	case "messages":
		$opts = array(
			'reblog_info' => 'true'
		);
		if($_GET['offset']){
			$opts['offset'] = $_GET['offset'];
		}
		if($_GET['before']){
			$opts['before'] = $_GET['before'];
		}
		if($_GET['max_id']){
			// this could be official only :/
			$opts['before_id'] = $_GET['max_id'];
		}

		if($type){
			$opts['type'] = $type;
			$ex .= "&type=" . $_GET['type'];
		}

		switch ($_GET['action']) {
			case 'posts':
				$title = $blog . ' - posts';
				if($_GET['type']){
					$title .= ' - ' . $_GET['type'];
				}
				$dashboard = $client->getBlogPosts($blog, $opts);
				break;
			case 'queue':
				$title = $blog . ' - queue';
				$dashboard = $client->getQueuedPosts($blog, $opts);
				break;
			case 'drafts':
				$title = $blog . ' - drafts';
				$dashboard = $client->getDraftPosts($blog, $opts);
				break;
			case 'messages':
				$title = $blog . ' - messages';
				$dashboard = $client->getSubmissionPosts($blog, $opts);
				break;
			default:
				die('err');
				break;
		}
		if($dashboard->blog && !defined("VIEWING_MY_BLOG")){
			$bloginfo = $dashboard->blog;
		}
		$dashboard = $dashboard->posts;

		require "theme/header.php";
		blog_header();

		$i = $_GET['offset']*1;
		foreach($dashboard as $post){
			require 'theme/post.php';
			$lastid = $post->id;
			$i++;
			$t = $post->timestamp;
		}


		if($lastid){

			$ex = 'blog=' . $blog . '&action=';
			if($type){
				$ex .= $type;
			} else{
				$ex .= $_GET['action'];
			}

			if($_GET['tagged']){
				$ex .= '&before=' . $t;
			} else{
				$ex .= "&max_id=" . $lastid;
			}
			?>
			<div class="pagination">
				<a href="?<?php echo $ex; ?>" class="btn btn-lg btn-primary">Forward!</a>
			</div>
			<?php
		}
		require "theme/footer.php";
		break;
}