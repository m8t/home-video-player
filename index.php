<?php
require_once "hvp-settings.class.php";
require_once "hvp-playlist.class.php";
$settings = new Hvp\Settings ();

$interface = $settings->interface;
if (preg_match ("/\/$/", $_SERVER["REQUEST_URI"]) && $interface == "playlist") {
	header ("Location: playlist.php");
}

$video_backend = $settings->get_value ("/general/video-backend");

$playlist = new Hvp\Playlist ();
?>
<!DOCTYPE html>
<html>
 <head>
  <title><?php echo $settings->title ?></title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="style-video.css" />
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
  <div id="content-wrapper"><div id="video-player">
    <div id="main-content">

<?php
if ($video_backend == "HTML5") {
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
else if ($video_backend == "VLC") {
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
else if ($video_backend == "WMP") {
?>
<object id="wmp-embed"
	width="848" height="540"
	classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6"
	type="application/x-oleobject">
<?php
if (isset ($_GET['hash'])) {
	$source = $playlist->get_source ($_GET['hash']);
	if (!empty ($source)) {
		echo <<<EOF
	<param name="URL" value="${source['src']}" />
EOF;
	}
}
?>
	<param name="SendPlayStateChangeEvents" value="true">
	<param name="uiMode" value="full" />
	<param name="AutoStart" value="<?php echo ($settings->autoplay == "true") ? "true" : "false"; ?>" />
</object>
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
    <div id="side-panel-wrapper"><div id="side-panel-inner"><div id="playlist">
      <ul>
<?php
$items = $playlist->get_playlist ();
foreach ($items as $item) {
	if (isset ($_GET['hash']) && $_GET['hash'] == $item['hash'])
		$li = 'li class="selected"';
	else
		$li = 'li';
	echo "<$li><a style=\"float: right; position: absolute; margin-left: -5px; margin-top: -4px; color: #00bb00\" href=\"media-files/${item['hash']}\">⇩</a><a style=\"margin-left: 3px;\" href=\"index.php?hash=${item['hash']}\">${item['name']}</a></li>";
}
?>
      </ul>
    </div></div></div>
  </div></div>
  <footer>
<?php
require "footer.php";
?>
  </footer>
 </body>
</html>

