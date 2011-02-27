<?php
require "hmp-playlist.class.php";
$playlist = new Hmp\Playlist ();
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Home Video Player</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
  <script src="video.js"></script>
 </head>
 <body>
  <header>
    <h1>Home Video Player</h1>
  </header>
  <div id="video-player">
    <div id="video">
      <video width="848" height="540" controls="controls">
<?php
if (isset ($_GET['hash'])) {
	$source = $playlist->get_source ($_GET['hash']);
	if (!empty ($source)) {
		$type = ($source['mimetype'] != NULL) ? "type=\"${source['mimetype']}\"" : NULL;
		echo "<source src=\"${source['src']}\" $type />";
	}
}
?>
      </video>
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

