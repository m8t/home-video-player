<?php
require "config.php";
require_once "hvp-playlist.class.php";
require_once "hvp-file-browser.class.php";
require_once "hvp-settings.class.php";

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
if (isset ($_GET['action']) && $_GET['action'] == "configuration" && !empty ($_POST)) {
	if (isset ($_POST['title']) && !empty ($_POST['title'])) {
		$settings->set_value ("/general/title", $_POST['title']);
	}

	if (isset ($_POST['video-backend'])) {
		$settings->set_value ("/general/video-backend", $_POST['video-backend']);
	}

	$value = (!isset ($_POST['autoplay'])) ? "false" : "true";
	$settings->set_value ("/general/autoplay", $value);
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
  <div id="content-wrapper">
    <div id="main-content">
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
$action = (isset ($_GET['action'])) ? $_GET['action'] : "";
switch ($action) {

	case "file-browser":
	default:
?>

<?php
/* ========== File browser ========== */
echo "<ul id=\"file-browser\">";
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
      <option <?php echo ($video_backend == "WMP") ? "selected" : "" ?>>WMP</option>
    </select>
  </li>

  <li>
    <input <?php echo ($settings->autoplay == "true") ? "checked" : "" ?> type="checkbox" name="autoplay" id="autoplay" value="true" />
    <label for="autoplay">Autoplay</label>
  </li>

</ul>

<div>
  <input type="submit" value="Update" />
</div>

</form>

<h1>Directories</h1>

<ul id="directories">
<?php
foreach ($directories as $directory) {
	echo "<li>$directory</li>";
}
?>
</ul>

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
    <div id="side-panel-wrapper"><div id="side-panel-inner"><div id="playlist">
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
    </div></div></div>
  </div>
  <footer>
<?php
require "footer.php";
?>
  </footer>
 </body>
</html>

