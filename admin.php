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
  <link rel="stylesheet" href="style-admin.css" />
 </head>
 <body>
  <header>
    <h1>Home Video Player Administration</h1>
  </header>
  <div id="video-player">
    <div id="video">
      <div id="admin">

	<div id="admin-tabs">
	  <ul>
	    <li><a href="index.php">← Back</a></li>
	    <li><a href="?action=file-browser">File Browser</a></li>
	    <li><a href="?action=configuration">Configuration</a></li>
	</div>

        <div id="admin-content">

<?php
/* $_GET['action'] */
switch ($_GET['action']) {

	case "file-browser":
	default:
?>

<?php
/* ========== File browser ========== */
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

<?php
	break;
	case "configuration":
?>

<?php
/* ========== Configuration ========== */
?>

<h1>General</h1>

<ul>

  <li>
    Title:
    <input type="text" value="<?php echo HMP_TITLE; ?>" disabled />
  </li>

  <li>
    Video backend:
    <select disabled>
      <option <?php echo (HMP_VIDEO_BACKEND == "HTML5") ? "selected" : "" ?>>HTML5</option>
      <option <?php echo (HMP_VIDEO_BACKEND == "VLC") ? "selected" : "" ?>>VLC</option>
    </select>
  </li>

</ul>

<h1>Directories</h1>

<?php
echo "<ul>";
foreach ($directories as $directory) {
	echo "<li>$directory</li>";
}
echo "</ul>";
?>

<?php
/* !$_GET['action'] */
	break;
}
?>

        </div>

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
<?php
require "footer.php";
?>
  </footer>
 </body>
</html>

