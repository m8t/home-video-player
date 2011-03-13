<?php
require "config.php";
require "hvp-playlist.class.php";
require "hvp-file-browser.class.php";
require "hvp-settings.class.php";

$settings = new Hvp\Settings ();

$playlist = new Hvp\Playlist ();

/* add a new item into the playlist or remove one */
if (isset ($_GET['path']) && is_file ($_GET['path'])) {
	$path = urldecode ($_GET['path']);
	$playlist->add_item ($path);
}
else if (isset ($_GET['hash'])) {
	$playlist->remove_item ($_GET['hash']);
}

/* update settings */
if (isset ($_GET['action']) && $_GET['action'] == "configuration") {
	if (isset ($_POST['title']) && !empty ($_POST['title'])) {
		$settings->set_value ("/general/title", $_POST['title']);
	}
	if (isset ($_POST['video-backend'])) {
		$settings->set_value ("/general/video-backend", $_POST['video-backend']);
	}
}
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Administration - <?php echo $settings->title; ?></title>
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
	  </ul>
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

<h1>General Settings</h1>

<form method="post" action="admin.php?action=configuration">
<ul>

  <li>
    Title:
    <input type="text" name="title" value="<?php echo $settings->title; ?>" />
  </li>

<?php
$video_backend = $settings->get_value ("/general/video-backend");
?>
  <li>
    Video backend:
    <select name="video-backend">
      <option <?php echo ($video_backend == "HTML5") ? "selected" : "" ?>>HTML5</option>
      <option <?php echo ($video_backend == "VLC") ? "selected" : "" ?>>VLC</option>
    </select>
  </li>

</ul>

<div>
  <input type="submit" value="Update" />
</div>

</form>

<h1>Directories</h1>

<?php
echo "<ul>";
foreach ($directories as $directory) {
	echo "<li>$directory</li>";
}
echo "</ul>";
?>

<div>
  <input type="button" value="Edit" />
</div>

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

