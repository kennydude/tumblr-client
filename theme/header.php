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
	<link rel="stylesheet" href="bower_components/video.js/video-js.min.css">
	<link rel="stylesheet" href="bower_components/magnific-popup/dist/magnific-popup.css"> 
	<link rel="stylesheet" href="theme/style.css">
	<style type="text/css">
<?php if(!defined("NOHEADER")){ ?>
.mainBody{
	padding-top: 50px;
}
<?php } ?>
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
							<li role="presentation">
								<a role="menuitem" href="index.php?show=likes" tabindex="-1">Likes</a>
							</li>
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