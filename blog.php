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

function blog_header(){
	global $blog, $myblogs, $bloginfo, $client;
	$actions = array("posts", "text", "photos", "quotes", "links", "chats", "audio", "videos", "answers");
	if(OFFICIAL_API == true && in_array($blog, $myblogs)){
		$actions[] = "notes";
	}
	if(in_array($blog, $myblogs)){
		$actions[] = "queue";
		$actions[] = "drafts";
		$actions[] = "submissions";
	}

	if(!$bloginfo){
		if(in_array($blog, $myblogs)){
			$my_blogs = get_my_blogs();
			foreach ($my_blogs as $xx_blog) {
				if($xx_blog->name == $blog){
					$bloginfo = $xx_blog;
				}
			}
		} else{
			$bloginfo = $client->getBlogInfo($blog);
		}
	}
	?>
	<div class="bloginfo row m10down">
		<div class="col-md-2">
			<img src="http://api.tumblr.com/v2/blog/<?php echo $bloginfo->name; ?>.tumblr.com/avatar/64" />
		</div>
		<div class="col-md-10">
			<h5><?php echo $bloginfo->name; ?></h5>
			<p><?php echo $bloginfo->description; ?></p>
		</div>
	</div>
	<ul class="nav nav-pills nav-stacked m10down blognav">
		<?php
		foreach ($actions as $action) {
			echo '<li ';
			echo $action == $_GET['action'] ? ' class="active"' : '';
			echo '><a href="?blog=' . $blog;
			echo $action == "posts" ? '' : '&action=' . $action;
			echo '">' . ucfirst($action) . '</a></li>';
		}
		?>
	</ul>
	<?php
}

switch($_GET['action']){
	case "notes":
		require_official_api();

		require "theme/header.php";
		blog_header();

		$notes = $client->getRequest("v2/blog/".$blog.".tumblr.com/notifications", array(), false);
		foreach ($notes->notifications as $note) {
			require "theme/note.php";
		}

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
	case "posts":
	case "queue":
	case "drafts":
	case "submissions":
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
			$opts['max_id'] = $_GET['max_id'];
		}

		if($_GET['type']){
			$opts['type'] = $_GET['type'];
			$ex .= "&type=" . $_GET['type'];
		}

		switch ($_GET['action']) {
			case 'posts':
				$dashboard = $client->getBlogPosts($blog, $opts);
				break;
			case 'queue':
				$dashboard = $client->getQueuedPosts($blog, $opts);
				break;
			case 'drafts':
				$dashboard = $client->getDraftPosts($blog, $opts);
				break;
			case 'submissions':
				$dashboard = $client->getSubmissionPosts($blog, $opts);
				break;
		}
		$bloginfo = $dashboard->blog;
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

			if($_GET['tagged']){
				$ex = 'before=' . $t . $ex;
			} else{
				$ex = "max_id=" . $lastid . $ex;
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