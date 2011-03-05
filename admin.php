<?php
require "hvp-playlist.class.php";
require "hvp-file-browser.class.php";
$playlist = new Hvp\Playlist ();
if (isset ($_GET['path']) && is_file ($_GET['path'])) {
	$path = urldecode ($_GET['path']);
	$playlist->add_item ($path);
}
else if (isset ($_GET['hash'])) {
	$playlist->remove_item ($_GET['hash']);
}
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Administration - Home Video Player</title>
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
		Hvp\FileBrowser::read_dir ($directory);
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
$items = $playlist->get_playlist ();
foreach ($items as $item) {
	if (isset ($_GET['hash']) && $_GET['hash'] == $item['hash'])
		$li = 'li class="selected"';
	else
		$li = 'li';
	echo "<$li><a href=\"admin.php?hash=${item['hash']}\">${item['name']}</a></li>";
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

