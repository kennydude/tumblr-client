// video.js
document.createElement('video');document.createElement('audio');document.createElement('track');
videojs.options.flash.swf = "bower_components/video.js/video-js.swf";
// general
$(document).ready(function(){
	$(".photoset").photosetGrid({ "gutter" : "5px" });
	$('.photoset').each(function() {$(this).magnificPopup({delegate: 'img',type: 'image',gallery:{enabled:true},removalDelay: 300,mainClass: 'mfp-fade'});});
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
			var x = $(this).height();
			x = x > 400 ? x : 400;
			videojs($(this).get(0), {
				"width" : $(this).parent().width() + "px",
				"height" : x + "px"
			});
		});
	}, 1000);

	$(".body a").each(function(){
		if($(this).attr("href").indexOf("/post/") !== -1){ // Likely to be a tumblr post
			var parts = $(this).attr("href").split("/");
			var hostname = parts[2];
			if(parts[3] == "post" && /^[0-9]+$/.test(parts[4])){ // Yup
				$(this).attr("href", "post.php?id=" + parts[4] + "&name=" + hostname);
			}
		}
	});
});