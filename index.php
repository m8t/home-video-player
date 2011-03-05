<?php
require "hmp-playlist.class.php";
$playlist = new Hmp\Playlist ();
?>
<!DOCTYPE html>
<html>
 <head>
  <title><?php echo HMP_TITLE ?></title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
 </head>
 <body>
  <header>
    <h1><?php echo HMP_TITLE ?></h1>
  </header>
  <div id="video-player">
    <div id="video">

<?php
if (HMP_VIDEO_BACKEND == "VLC") {
?>
<script src="vlc.js"></script>
<embed type="application/x-vlc-plugin"
	id="vlc-embed"
	name="video"
	width="848" height="540"
	hidden="no" autoplay="yes" loop="no"
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
else if (HMP_VIDEO_BACKEND == "HTML5") {
?>
      <video width="848" height="540" controls="controls">
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
if (HMP_VIDEO_BACKEND == "VLC") {
?>
      <div id="controls">
        <input id="button-play-pause" type="button" value="Play/Pause" alt="Play/Pause [P]" />
        <input id="button-fullscreen" type="button" value="Fullscreen" alt="Fullscreen [F]" />
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
    <ul>
      <li><a href="index.php">Player</a></li>
      <li><a href="admin.php">Administration</a></li>
      <li><a href="http://www.divx.com/en/software/divx-plus/web-player">Get DivX Plus Web Player</a></li>
    </ul>
  </footer>
 </body>
</html>

