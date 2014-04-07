<?php
require 'vendor/autoload.php';
require 'common.php';

// Dashboard

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

if($_GET['tagged']){
	$dashboard = $client->getTaggedPosts($_GET['tagged'], $opts);
	$ex .= "&tagged=" . $_GET['tagged'];
} else{
	$dashboard = $client->getDashboardPosts($opts);
	$dashboard = $dashboard->posts;
}

require 'theme/header.php';

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<form class="pull-right" method="get" action="?">
			<input type="text" name="tagged" placeholder="tagged" value="<?php echo $_GET['tagged']; ?>" />
		</form>
		<?php
		if($_GET['tagged']){
			echo 'tagged/' . $_GET['tagged'];
		} else{
			echo 'dashboard';
		} ?>
	</div>
	<div class="panel-body">
		<form method="get" action="?">
			<div class="form-group">
				<label for="type">Type</label>
				<select name="type" id="type">
					<option value="">All</option>
					<?php
						$options = array("text", "photo", "quote", "link", "chat", "audio", "video", "answer");
						foreach ($options as $opt) {
							$c = $_GET['type'] == $opt ? " selected='selected'" : "";
							echo '<option value="' . $opt . '"' . $c . '>' . ucfirst($opt) . '</option>';
						}
					?>
				</select>
			</div>
			<button type="submit" class="btn btn-primary">submit</button>
		</form>
	</div>
</div>
<?php

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

require 'theme/footer.php';