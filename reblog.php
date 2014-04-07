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

if($_POST){

	$state = $_POST['when'];
	if($_POST['delete_contents']){
		$state = 'draft'; // f u tumblr
	}

	$rsp = $client->reblogPost(
		$_POST['blog'] . '.tumblr.com',
		$_POST['postid'], 
		$_POST['reblogkey'],
		array(
			'comment' => $_POST['contents'],
			'state' => $state,
			'tags' => $_POST['tags']
		)
	);

	if(!$oldpost->id){
		$obj = R::dispense("reblog");
		$obj->url = $_POST['reblogged_root_url'];
		$obj->blog = $_POST['blog'];
		$obj->postid = $rsp->id;
		$obj->when = time();
		R::store($obj);
	}

	if($_POST['delete_contents']){
		// TODO
	}

	var_dump($rsp);

	?>
	<div class="alert alert-success">
		<p>Done</p>
	</div>
	<?php
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
<form method="post" action="?">
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
			<option value="draft">Draft</option>
			<option value="private">Private</option>
		</select>
	</label><br/>
	<textarea class="contents" name="contents" placeholder="Caption"></textarea>
	<label class="pull-right">
		<input type="checkbox" name="delete_contents" />
		Delete Contents
	</label>
	<button type="submit" class="btn btn-lg btn-primary">Reblog</button>
	<input type="text" name="tags" placeholder="tags" />
</form>
<?php

require 'theme/footer.php';