<?php
// POST THEME
$myblogs = get_my_blog_names();

if(!function_exists('post_set_vars')){
	function post_set_vars(){
		global $avatar, $post;
		$avatar = 'http://api.tumblr.com/v2/blog/'.$post->blog_name. '.tumblr.com/avatar/48';
		if($post->asking_url == NULL){ // anon
			$avatar = "http://placekitten.com/g/48/48";
		}
	}
}

if(!$post->reblogged_root_url){
	$post->reblogged_root_url = $post->post_url;
}
$posted_verb = 'posted';
$avatar = 'http://api.tumblr.com/v2/blog/'.$post->blog_name. '.tumblr.com/avatar/48';

if($post->type == "postcard"){
	$posted_verb = 'sent you fanmail';
} else if($post->type == "answer" && $post->state == "submission"){
	$posted_verb = 'asked';
	post_set_vars();
}

if($post->state == "submission" && ($post->type == "answer" || $post->type == "postcard")){ // ask
	if($post->answer != ""){
		$bn =  $post->blog_name;
		$post->blog_name = $post->asking_name;
		$posted_verb = 'replied to your ask';
		$post->asking_name = $bn;
	} else{
		$post->blog_name = $post->asking_name;
	}
	post_set_vars();
}

?>
<div class="panel <?php
if(in_array($post->blog_name, $myblogs)){
	echo 'panel-primary';
	$me = true;
} else{
	$me = false;
	echo 'panel-default';
} ?> post" id="post<?php echo $post->id; ?>">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-1 sp">
				<img src="<?php echo $avatar; ?>" />
			</div>
			<div class="col-md-9 sp cnt">
				<?php
					echo '<a href="blog.php?blog='.$post->blog_name.'">' . $post->blog_name . '</a>';
					if($post->reblogged_from_id){
						echo ' <a href="post.php?id='. $post->id . '&name=' . $post->blog_name . '">' .
							'reblogged</a> from <a href="post.php?id=' . $post->reblogged_from_id . '&name='.$post->reblogged_from_name.'">' . $post->reblogged_from_name . '</a>';
						if(in_array($post->reblogged_from_name, $myblogs)){
							echo ' <span class="label label-default">THAT\'S YOU</span>';
						}
					} else{
						echo ' <a href="post.php?id='. $post->id . '&name=' . $post->blog_name . '">'.$posted_verb.'</a>';
					}
					if($post->scheduled_publish_time){
						echo ' <span class="timeago" data-timestamp="'.$post->scheduled_publish_time.'">... ago</span> and was queued ';
					}
				?> <span class="timeago" data-timestamp="<?php echo $post->timestamp; ?>">... ago</span>
			</div>
			<div class="col-md-2">
				<div class="dropdown pull-right">
					<button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="pm<?php echo $post->id; ?>" data-toggle="dropdown">
						<i class="glyphicon glyphicon-align-justify"></i>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="pm<?php echo $post->id; ?>">
						<?php
						if($post->reblogged_from_id){
							$rootid = $post->reblogged_root_url;
							$index = stripos($rootid, "/post/") + 6;
							$e = $index - stripos($rootid, "/", $index);
							if($e < 0) $e = NULL;
							$rootid = substr($rootid, $index, $e);
							?>
						<li role="presentation">
							<a role="menuitem" tabindex="-1"
								href="post.php?id=<?php echo $post->reblogged_from_id; ?>&name=<?php echo $post->reblogged_from_name; ?>">
								Reblogged Post
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="post.php?id=<?php echo $rootid; ?>&name=<?php echo $post->reblogged_root_name; ?>">Root Post</a>
						</li>
						<?php } ?>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo $post->source_url; ?>">Source</a>
						</li>
					</ul>
				</div>
				<?php
				if($me){
					?><span class="label label-default pull-right">ME</span><?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="panel-body body-<?php echo $post->type; ?>">
		<?php
			$no_body = false;

			if($post->title){
				echo '<h3>' . $post->title . '</h3>';
			}

			if($post->photoset_layout){
				// Photoset
				echo '<div class="photoset m10down" data-layout="'.$post->photoset_layout.'">';
				foreach($post->photos as $photo){
					echo '<img data-width="'.$photo->original_size->width.'" data-height="'.$photo->original_size->height.'" src="'.nocdn($photo->original_size->url).'" />';
				}
				echo '</div>';
			} else if($post->photos){
				// Photo Post
				foreach($post->photos as $photo){
					echo '<img class="fullwidth m10down" src="'.nocdn($photo->original_size->url).'" />';
				}
			} else if($post->type == 'chat'){
				$no_body = true;
				foreach ($post->dialogue as $item) {
					echo '<p><strong>' . $item->label . '</strong> ' . $item->phrase . '</p>';
				}
			} else if($post->type == 'video'){
				$player = ''; $lw = 0;
				if($post->player){
					foreach($post->player as $pl){
						if($pl->width > $lw){
							$player = $pl->embed_code;
						}
					}
					echo '<div class="videoplayer m10down">' .$player . '</div>';
				} else if($post->permalink_url){
					echo '<a href="' . $post->permalink_url . '" target="_blank">';
					echo '<img src="' . $post->thumbnail_url  . '" class="fwidth" /></a>';
				}
			} else if($post->type == 'answer'){
				?>
				<p class="bg-info pad10"><?php echo $post->asking_name; ?> asked:</p>
				<p class="pad10"><?php echo $post->question; ?></p>
				<?php if($post->state != "submission" || $post->answer != ""){ ?>
				<p class="bg-info pad10">Answer:</p>
				<div class="pad10"><?php echo $post->answer; ?></div>
				<?php }
			} else if($post->type == "audio"){
				?>
				<audio controls class="audio" preload="none">
					<source type="audio/mp3" src="<?php echo $post->audio_url; ?>?plead=please-dont-download-this-or-our-lawyers-wont-let-us-host-audio" />
				</audio>
				<p class="m10down"><small><span class="caret caret-reversed"></span> <?php
					echo $post->track_name;
					if($post->artist){
						echo ' by ' . $post->artist;
					} if($post->album){
						echo ' on the album ' . $post->album;
					}
				?></small></p>
				<?php
			} else if($post->type == 'quote'){
				?>
				<blockquote>
					<p><?php echo $post->text; ?></p>
					<footer><?php echo $post->source; ?></footer>
				</blockquote>
				<?php
			}

			if(!$no_body){
				echo '<div class="body">' . $post->body . '</div>'; echo $post->caption;
			}
		?>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-8">
				<?php
					if($post->note_count > 0){
						echo $post->note_count . ' notes ';
					}
					foreach($post->tags as $tag){
						echo '<a href="?tagged='.urlencode($tag).'">#' . $tag . '</a> ';
					}
				?>
			</div>
			<div class="col-xs-4 post-options">
				<a href="<?php echo $post->post_url; ?>"><i class="glyphicon glyphicon-link permalink"></i></a>
				<i 	class="glyphicon glyphicon-heart <?php echo $post->liked ? 'liked' : ''; ?> likeButton"
					data-postid="<?php echo $post->id; ?>"
					data-reblogkey="<?php echo $post->reblog_key; ?>"
					title="Like this post">
				</i>
				<i	class="glyphicon glyphicon-retweet reblogButton"
					data-postid="<?php echo $post->id; ?>"
					data-rooturl="<?php echo $post->reblogged_root_url; ?>"
					data-reblogkey="<?php echo $post->reblog_key; ?>"
					title="Reblog this post">
				</i>
				<?php if(defined("DEBUG")){ ?>
				<i 	class="glyphicon glyphicon-screenshot sourceButton"
					title="View full data returned from tumblr">
				</i>
				<?php } ?>
			</div>
		</div>
		<?php if(defined("DEBUG")){ ?><pre class="hidden postsource"><?php ob_start(); var_dump($post); $x = ob_get_contents(); ob_end_clean(); echo htmlspecialchars($x); ?></pre><?php } ?>
	</div>
</div>