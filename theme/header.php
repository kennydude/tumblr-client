<?php
// Header
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0">

	<title><?php if(!$title) { ?>tumblr client by @kennydude<?php } else{ echo $title; } ?></title>

	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<style type="text/css">
		img{
			max-width: 100%;
		}
		.fullwidth{
			width: 100%;
		}
		.post-options{
			text-align: right;
			font-size: 20px;
		}
		.post-options > *{
			margin-left: 5px;
			cursor: pointer;
		}
		.reblogFrame{
			width: 100%;
			height: 200px;
			border: 0px;
		}
		.pad10{
			padding: 10px;
		}
		.panel-primary .panel-heading .cnt a{
			color: #FFF;
		}
		.headerXTY23{
			background: #428BCA;
			color: #FFF;
			font-size: 12px;
			margin-bottom: 9px;
			padding: 10px;
			position: fixed;
			z-index: 999999999;
			top: 0; left: 0; right: 0;
		}
		.c{
			text-align: center;
		}
		.headerXTY23 a{
			color: #FFF;
		}
		.audio{
			width: 100%;
		}
		.m10down{
			margin-bottom: 10px;
		}
		.caret.caret-reversed {
			border-top-width: 0;
			border-bottom: 4px solid #000000;
		}
		.panel-body{
			word-wrap: break-word;
			word-break: break-all;
		}
		<?php if(!defined("NOHEADER")){ ?>
		.mainBody{
			padding-top: 50px;
		}
		<?php } ?>
		blockquote blockquote{
			padding-right: 0px;
		}
		.likeButton.liked{
			color: #A94442;
		}
		.faded{
			opacity: 0.4;
		}
		.blognav{
			width: 200px;
			position: absolute;
			margin-left: -220px;
		}
		body .sp{
			padding-left: 6px;
			padding-right: 6px;
		}
		.body-postcard .body{
			white-space: pre;
			overflow-x: scroll;
			font-size: 20px;
		}
	</style>
</head>
<body>

<?php if(!defined("NOHEADER")){ ?>
	<div class="headerXTY23">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 c">
					<a class="btn btn-xs btn-primary" href="index.php">tumblr client by @kennydude</a>
				</div>
				<div class="col-md-1">
					<div class="dropdown">
						<button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="myMenu" data-toggle="dropdown">
							<i class="glyphicon glyphicon-align-justify"></i>
						</button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="myMenu">
							<li role="presentation" class="dropdown-header">My Blogs</li>
							<?php
							$blogs = get_my_blogs();
							foreach($blogs as $hd_blog){
							?>
							<li role="presentation">
								<a role="menuitem" title="<?php echo $hd_blog->title; ?>" tabindex="-1" href="blog.php?blog=<?php echo $hd_blog->name; ?>">
									<?php echo $hd_blog->name; ?>
								</a>
							</li>
							<?php
							}
							?>
							<li role="presentation" class="divider"></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

	<div class="container mainBody">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">