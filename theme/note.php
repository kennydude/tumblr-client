<?php
// a note
switch ($note->type) {
	case 'like':
		$cls = 'alert-danger';
		$verb = 'liked';
		break;
	case 'reblog':
		$cls = 'alert-success';
		$verb = 'reblogged';
		break;
	case 'follower':
		$cls = 'alert-info';
		break;
	default:
		$cls = 'alert-default';
		$verb = $note->type = 'ed';
		break;
}
// Do some addition for /notifications endpoint as it's shit
if(!$note->blog_name){ // We know it's a notification
	$note->blog_name = $note->from_tumblelog_name;
	$notification = true;
}
?>
<div class="note post alert <?php echo $note->type . ' ' . $cls; ?>">
	<img src="http://api.tumblr.com/v2/blog/<?php echo $note->blog_name; ?>.tumblr.com/avatar/24" />
	<?php
		switch ($note->type) {
			case 'follower':
				echo $note->blog_name . ' followed you';
				break;
			default:
				echo $note->blog_name . ' ' . $verb;
				if($notification){
					echo ' your <a href="post.php?id='.$note->target_post_id.'&name='.$note->target_tumblelog_name.'">post</a>';
				} else{
					echo ' this';
				}
				break;
		}
	?>
	<?php if(defined("DEBUG")){ ?>
	<i 	class="glyphicon glyphicon-screenshot sourceButton pull-right"
		title="View full data returned from tumblr">
	</i>
	<pre class="hidden postsource"><?php ob_start(); var_dump($note); $x = ob_get_contents(); ob_end_clean(); echo htmlspecialchars($x); ?></pre><?php } ?>
</div>