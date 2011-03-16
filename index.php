<?php
require_once "hvp-playlist.class.php";
require_once "hvp-settings.class.php";
$playlist = new Hvp\Playlist ();
$settings = new Hvp\Settings ();
$video_backend = $settings->get_value ("/general/video-backend");
?>
<!DOCTYPE html>
<html>
 <head>
  <title><?php echo $settings->title ?></title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
  <script>
//<!--
window.onload = function () {
<?php
if ($video_backend == "VLC") {
?>
	init_player_controls ();
	init_slider ('slider', '560px', '16px');
<?php
}
?>
}
//-->
  </script>
 </head>
 <body>
  <header>
    <h1><?php echo $settings->title ?></h1>
  </header>
  <div id="video-player">
    <div id="video">

<?php
if ($video_backend == "VLC") {
?>
<script src="vlc.js"></script>
<embed type="application/x-vlc-plugin"
	id="vlc-embed"
	name="video"
	width="848" height="540"
	hidden="no" autoplay="<?php echo ($settings->autoplay == "true") ? "yes" : "no"; ?>" loop="no"
<?php
if (isset ($_GET['hash'])) {
	$source = $playlist->get_source ($_GET['hash']);
	if (!empty ($source)) {
		echo <<<EOF
	target="${source['src']}"
EOF;
	}
}
?>
	/>
<?php
}
else if ($video_backend == "HTML5") {
?>
      <video width="848" height="540" controls="controls" <?php echo ($settings->autoplay == "true") ? "autoplay=\"autoplay\"" : ""; ?>>
<?php
if (isset ($_GET['hash'])) {
	$source = $playlist->get_source ($_GET['hash']);
	if (!empty ($source)) {
		$type = ($source['mimetype'] != NULL) ? "type=\"${source['mimetype']}\"" : NULL;
		echo "<source src=\"${source['src']}\" $type />\n";
	}
}
?>
      </video>
<?php
}
?>

<?php
if ($video_backend == "VLC") {
?>
      <div id="controls">
	<span id="video-length">0:00 / 0:00</span>
        <input id="button-play-pause" type="button" value="Play/Pause" title="Play/Pause [P]" />
        <input id="button-fullscreen" type="button" value="Fullscreen" title="Fullscreen [F]" />
	<div id="slider">
	  <div class="slider-bar">
	    <div class="slider-handle"></div>
	  </div>
	</div>
      </div>
<?php
}
?>

    </div>
    <div id="playlist">
      <ul>
<?php
$items = $playlist->get_playlist ();
foreach ($items as $item) {
	if (isset ($_GET['hash']) && $_GET['hash'] == $item['hash'])
		$li = 'li class="selected"';
	else
		$li = 'li';
	echo "<$li><a href=\"index.php?hash=${item['hash']}\">${item['name']}</a></li>";
}
?>
      </ul>
    </div>
  </div>
  <footer>
<?php
require "footer.php";
?>
  </footer>
 </body>
</html>

