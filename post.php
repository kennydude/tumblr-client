<?php
require 'vendor/autoload.php';
require 'common.php';
// single post

$post = $client->getBlogPosts($_GET['name'], array(
	'id' => $_GET['id'],
	'reblog_info' => 'true',
	'notes_info' => 'true',
	'before_timestamp' => $_GET['before']
));

$title = "single post by " . $post->posts[0]->blog_name;

if($_GET['api']){
	require_official_api(); // sorry kids
	$notes = $client->getRequest("v2/blog/".$_GET['name'].".tumblr.com/notes", array(
		"id" => $_GET['id'],
		"before_timestamp" => $_GET['next']
	), true);
	$data = array("html" => "");

	foreach ($notes->notes as $note) {
		ob_start(); // oopsie; my mistake here
		require "theme/note.php";
		$html = ob_get_contents();
		ob_end_clean();

		$data['html'] .= $html;
		$data['next'] = $note->timestamp;
	}

	echo json_encode($data);
	exit();
}

require 'theme/header.php';
foreach($post->posts as $post){
	require 'theme/post.php';
	echo '<div class="notes">';
	foreach ($post->notes as $note) {
		require "theme/note.php";
		if($note->type == "posted"){ $ended = true; }
		$lasttime = $note->timestamp;
	}
	echo '</div>';
	if(!$_GET['nochrome'] && OFFICIAL_API){
		echo '<div id="moreNotesPlace"></div>';
		echo '<a class="btn btn-primary loadMoreNotes">Load more...</a>';	
	}
}

if(!$ended && OFFICIAL_API){
	$nextNotes = $lasttime;
	$url = "?name=" . $_GET['name'] . "&id=" . $_GET['id'] . '&api=true';
	$scripts = <<<EOF
	var noteCount = $nextNotes;
	$(".loadMoreNotes").on("click", function(){
		$(this).attr("disabled", "disabled").text("...");
		var self = this;
		var next = $("<div>").appendTo("#moreNotesPlace");
		$.getJSON("$url&next=" + noteCount, function(data){
			next.html(data.html);
			noteCount = data.next;
			if($(".note.posted").size() > 0){
				$(self).remove();
				$("<div>").text("No more notes").appendTo(next);
			}
			$(self).removeAttr("disabled").text("Load more...");
		});
	});
EOF;
}

if(!$_GET['nochrome']) require 'theme/footer.php';