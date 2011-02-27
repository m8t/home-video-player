<?php
require "hmp-playlist.class.php";
require "hmp-file-browser.class.php";
$playlist = new Hmp\Playlist ();
if (isset ($_GET['path']) && is_file ($_GET['path'])) {
	$path = urldecode (path);
	$playlist->add_item ($path);
}
else if (isset ($_GET['hash'])) {
	$playlist->remove_item ($_GET['hash']);
}
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Home Video Player Administration</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
 </head>
 <body>
  <header>
    <h1>Home Video Player Administration</h1>
  </header>
  <div id="video-player">
    <div id="video">
      <div style="padding: 1em; font-size: 0.8em; overflow: auto; height: 520px;">
<?php
/* File browser */
echo "<ul>";
if (!isset ($directories)) {
	echo "<li>File directories.php not set</li>";
}
else {
	foreach ($directories as $directory) {
		echo "<li><strong>$directory</strong></li>";
		echo "<ul>";
		Hmp\FileBrowser::read_dir ($directory);
		echo "</ul>";
	}
}
echo "</ul>";
?>
      </div>
    </div>
    <div id="playlist">
      <ul>
<?php
$playlist->get_playlist ();
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
