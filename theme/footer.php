<?php
// footer
global $banned;
?>
</div></div></div>

<!-- Common Javascript -->
<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="bower_components/photoset-grid/jquery.photoset-grid.js"></script>
<script type="text/javascript" src="bower_components/momentjs/min/moment-with-langs.min.js"></script>
<script type="text/javascript" src="bower_components/video.js/video.js"></script>
<script type="text/javascript" src="bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="bower_components/jquery-keynav/jquery.keynav.js"></script>

<script type="text/javascript">
var banned = <?php echo json_encode($banned); ?>
</script>
<script type="text/javascript" src="theme/script.js"></script>
<?php if($scripts){
	echo '<script type="text/javascript">' . $scripts . '</script>';
} ?>

</body>
</html>