<?php
// Header
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0">

	<title><?php if(!$title) { ?>tumblr client by @kennydude<?php } else{ echo $title; } ?></title>

	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
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
		.panel-primary .panel-heading a{
			color: #FFF;
		}
		.headerXTY23{
			background: #428BCA;
			color: #FFF;
			font-size: 12px;
			margin-bottom: 9px;
			text-align: center;
			padding: 10px;
			position: fixed;
			z-index: 999999999;
			top: 0; left: 0; right: 0;
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
	</style>
</head>
<body>

<?php if(!defined("NOHEADER")){ ?>
	<div class="headerXTY23">
		<div class="container">
			<a href="index.php">tumblr client by @kennydude</a>
		</div>
	</div>
<?php } ?>

	<div class="container mainBody">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">