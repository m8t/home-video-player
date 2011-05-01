<?php
require_once "hvp-settings.class.php";
require_once "hvp-playlist.class.php";
$settings = new Hvp\Settings ();
$playlist = new Hvp\Playlist ();

/* clear playlist */
if (isset ($_GET['action']) && $_GET['action'] == 'clear') {
	$items = $playlist->get_playlist ();
	foreach ($items as $item) {
		$playlist->remove_item ($item['hash']);
	}
}
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Playlist - <?php echo $settings->title ?></title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="style-playlist.css" />
 </head>
 <body>
  <header>
    <h1>Home Video Player Administration</h1>
  </header>
  <div id="content-wrapper">
    <div id="main-content">
      <div id="playlist">
        <p>Right click and copy the URL into your media player.</p>
        <ol>
<?php
$items = $playlist->get_playlist ();
foreach ($items as $item) {
	$name = "${item['name']}";
	$filename = "media-files/${item['hash']}";
	$filesize = filesize ($filename);
	$filesize_h = ceil ($filesize / 1024 / 1024);
	echo "<li>";
	echo "<a href=\"${filename}\">${name}</a> ";
	echo "${filesize_h} Mo";
	echo "</li>";
}
?>
        </ol>
      </div>
    </div>
    <div id="side-panel-wrapper">
      <div id="side-panel-inner">
        <div id="action-panel">
	  <ul>
	    <li><a href="playlist-m3u.php">Export playlist</a></li>
	    <li><a href="playlist.php?action=clear">Clear playlist</a></li>
	  </ul>
	</div>
      </div>
    </div>
  </div>
  <footer>
<?php
require "footer.php";
?>
  </footer>
 </body>
</html>

