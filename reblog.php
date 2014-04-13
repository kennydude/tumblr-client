<?php
// Reblog
require 'vendor/autoload.php';
require 'common.php';

define('NOHEADER', true);
require 'theme/header.php';

// Lookup previous reblog
if($POST){
	$_GET['reblogged_root_url'] = $_POST['reblogged_root_url'];
}
$oldpost = R::findOne("reblog", "url = ?", array($_GET['reblogged_root_url']));

function do_post(){
	global $rsp, $client, $opts;

	$rsp = $client->reblogPost(
		$_POST['blog'] . '.tumblr.com',
		$_POST['postid'], 
		$_POST['reblogkey'],
		$opts
	);

	if($_POST['delete_contents']){
		$client->editPost($_POST['blog'], $rsp->id, array(
			"caption" => $_POST['contents'],
			"state" => $_POST['when']
		));
	}
}

if($_POST){

	$state = $_POST['when'];
	if($_POST['delete_contents']){
		$state = 'draft'; // f u tumblr
	}

	$opts = array(
		'comment' => $_POST['contents'],
		'state' => $state,
		'tags' => $_POST['tags']
	);

	if($_POST['contents'] == ""){ // Blank
		$_POST['contents'] = '<p></p>';
	}

	if($_POST['when'] == "slowqueue"){

		$obj = R::dispense("reblog");
		$obj->type = "slowqueue";
		$obj->url = $_POST['reblogged_root_url'];
		$obj->blog = $_POST['blog'];
		$obj->postid = $_POST['postid'];
		$obj->when = time();
		$obj->data = json_encode($_POST); // Encode everyting for the sake of it
		R::store($obj);

	} else{
		// Normal post

		do_post();

		if(!$oldpost->id){
			$obj = R::dispense("reblog");
			$obj->type = "normal";
			$obj->url = $_POST['reblogged_root_url'];
			$obj->blog = $_POST['blog'];
			$obj->postid = $rsp->id;
			$obj->when = time();
			R::store($obj);
		}

		if($_POST['when'] == "queue"){
			// See if we should release a slowqueue element
			$herd = @file_get_contents("cache/slowqueue-".md5($_POST['blog']).".txt");
			if(!$herd) $herd = 0;

			if($herd == "5"){
				// Get exact post info
				$reblog = R::findOne("reblog", " `type` = 'slowqueue' AND `blog` = ? ORDER BY `when` ASC", array(
					$_POST['blog']
				));
				if($reblog->id){

					$oldstate = $_POST['when'];
					$_POST = json_decode($reblog->data, true);

					$opts = array(
						'comment' => $_POST['contents'],
						'state' => $state,
						'tags' => $_POST['tags']
					);

					do_post();

					$reblog->type = "normal";
					R::store($reblog);

					$ex = <<<EOF
<div class="alert alert-success">
	<p><strong>Slow Queue:</strong> A slowly queued post was added to the queue</p>
</div>
EOF;

					$_POST['when'] = $oldstate;
					$herd = 0;
				}
			} else{
				$herd = $herd + 1;
			}

			file_put_contents("cache/slowqueue-".md5($_POST['blog']).".txt", $herd);

			if(defined("DEBUG")){
				echo '<p>dbg: herd key: ' . $herd . ' fb '.$_POST['blog'].'</p>';
			}
		}

	}

	?><br/>
	<div class="alert alert-success">
		<p><?php
switch ($_POST['when']) {
	case 'queue':
		echo 'Queued post';
		break;
	case 'slowqueue':
		echo '<strong>Slow Queue:</strong> Post Added</p><p>';
		echo 'The slow queue works by adding posts to the queue, every 5 that are nornally queued. This is useful for';
		echo ' preventing a sudden load of posts on a specific thing';
		break;
	default:
		echo 'Done';
		break;
}
		?></p>
	</div>
	<?php
	echo $ex;

	require 'theme/footer.php';
	exit;
}

?>
<style type="text/css">
	.contents{
		width: 100%;
		height: 50px;
	}
</style>
<p><strong>Reblog this post</strong></p>
<?php
if($oldpost->id){
	?>
<div class="alert alert-warning">
	<p><strong>You have already reblogged this post</strong> <span class="timeago" data-timestamp="<?php echo $oldpost->when; ?>"></span></p>
	<p><a href="http://<?php echo $oldpost->blog; ?>.tumblr.com/post/<?php echo $oldpost->postid; ?>">Link to original post</a></p>
</div>
	<?php
}
?>
<form method="post" action="?" id="theform">
	<input type="hidden" name="postid" value="<?php echo $_GET['postid']; ?>" />
	<input type="hidden" name="reblogkey" value="<?php echo $_GET['reblogkey']; ?>" />
	<input type="hidden" name="reblogged_root_url" value="<?php echo $_GET['reblogged_root_url']; ?>" />
	<label>
		Reblog to:
		<select name="blog">
			<?php
			$blogs = get_my_blogs();
			var_dump($blogs);
			foreach ($blogs as $blog) {
				echo '<option value="'.$blog->name.'">' . $blog->title . '</option>';
			} ?>
		</select>
	</label><br/>
	<label>
		State
		<select name="when">
			<option value="published">Publish Now</option>
			<option value="queue">Add to Queue</option>
			<option value="slowqueue">Add to slow queue</option>
			<option value="draft">Draft</option>
			<option value="private">Private</option>
		</select>
	</label><br/>
	<textarea class="contents" name="contents" placeholder="Caption"></textarea>
	<label class="pull-right">
		<input type="checkbox" name="delete_contents" />
		Delete Contents
	</label>
	<button type="submit" id="rbButton" class="btn btn-lg btn-primary">Reblog</button>
	<input type="text" name="tags" placeholder="tags" />
</form>
<?php
$scripts = <<<EOF
$("#theform").on("submit", function(){
	$("#rbButton").text("Reblogging...").attr("disabled", "disabled");
});
EOF;

require 'theme/footer.php';