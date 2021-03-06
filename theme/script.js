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
		var self = this;
		$("#reply" + $(this).data("postid")).focus();
		$("#replySubmit" + $(this).data("postid")).on("click", function(){
			$(this).attr("disabled", "disabled").text("Replying...");
			$.post("reply.php", {
				"postid" : $(self).data("postid"),
				"reblogkey" : $(self).data("reblogkey"),
				"content" : $("#reply" + $(self).data("postid")).val()
			}, function(){
				$("#replySubmit" + $(self).data("postid")).text("OK");
			});
		});
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
	$(".post-body").each(function(){
		var t = $(this).text().toLowerCase();
		for(var word in banned){
			word = banned[word];
			if(t.indexOf(word) != -1){
				var post = $(this).closest(".post");
				post.addClass("banned panel-danger hidden");
				var t = "";
				if($(".post-tags", post).html() != ""){
					t = "Tagged: " + $(".post-tags", post).html();
				}
				var p = $("<p>").addClass("bg-danger pad10").text("Post hidden because it contains banned word '" + word + "'" + t).insertBefore(post);
				$("<a>").addClass("btn btn-xs btn-default pull-right").text("Show").click(function(){
					$(post).toggleClass("hidden");
				}).prependTo(p);
				return;
			}
		}
	});

	$("img.rsp").each(function(){
		var width = $(this).data("width");
		var maxwidth = $(this).parent().width();
		var height = $(this).data("height");

		var ratio = maxwidth / width;
		var newHeight = height * ratio;

		$(this).css("height", newHeight + "px");
	});

	$(".post-container").keynav();
});