<?php
// footer
?>
</div></div></div>

<!-- Common Javascript -->
<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="bower_components/photoset-grid/jquery.photoset-grid.js"></script>
<script type="text/javascript" src="bower_components/momentjs/min/moment-with-langs.min.js"></script>
<script src="bower_components/video.js/video.js"></script>
<script>
document.createElement('video');document.createElement('audio');document.createElement('track');
  videojs.options.flash.swf = "bower_components/video.js/video-js.swf"
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".photoset").photosetGrid({ "gutter" : "5px" });
		$(".timeago").each(function(){
			var now = moment.unix($(this).data("timestamp"));
			$(this).html( now.fromNow() + " <small>(" + now.format("dddd, MMMM Do YYYY h:mma") + ")</small>" );
		});
		$(".reblogButton").click(function(){
			if($(".reblogFrame", $(this).closest(".post")).size() > 0) return;
			$("<iframe>").addClass("reblogFrame").attr(
				"src",
				"reblog.php?postid=" +
					$(this).data("postid") + "&reblogkey=" +
					$(this).data("reblogkey") + "&reblogged_root_url=" +
					$(this).data("rooturl")
			).appendTo($(".panel-footer", $(this).closest(".post")));
		});
		$(".sourceButton").click(function(){
			$(".postsource", $(this).closest(".post")).removeClass('hidden');
		});
		$(".likeButton").click(function(){
			var edx = "";
			if($(this).hasClass("liked")){
				edx += "&unlike=true";
			}
			var self = this;
			$(this).addClass("faded");
			$.getJSON("likesvc.php?postid=" + $(this).data("postid") + "&reblogkey=" +
					$(this).data("reblogkey") + edx, function(data){
				$(self).toggleClass("liked").removeClass("faded");
			});
		});
		$('.replyButton').popover({ "html" : true }).on("click", function(){
			$("#reply" + $(this).data("postid")).focus();
		});
		setTimeout(function(){
			$("video").each(function(){
				videojs($(this).get(0), {
					"width" : $(this).parent().width() + "px",
					"height" : $(this).height() + "px"
				});
			});
		}, 1000);
	});
</script>

</body>
</html>