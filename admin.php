<?php
/* connect to psql */
$conn = pg_connect ("dbname=videos user=videos");
if ($conn == FALSE) {
	echo "Failed to connect to PostgreSQL!";
}
if (isset ($_GET['path']) && is_file ($_GET['path']) && $conn != FALSE) {
	$path = urldecode ($_GET['path']);
	$md5 = md5 ($path);
	/* insert line into pgsql */
	$path_escaped = pg_escape_string ($conn, $path);
	$name_escaped = pg_escape_string ($conn, basename ($path));
	pg_query ($conn, "INSERT INTO videos (hash, path, name) VALUES ('$md5', '$path_escaped', '$name_escaped');");
	/* create symlink to video file */
	if (!is_dir ("media-files")) {
		mkdir ("media-files");
	}
	symlink ($path, "media-files/$md5");
}
else if (isset ($_GET['hash']) && $conn != FALSE) {
	/* delete line from pgsql */
	$hash_escaped = pg_escape_string ($conn, $_GET['hash']);
	if (pg_query ($conn, "DELETE FROM videos WHERE hash = '$hash_escaped';") != FALSE) {
		unlink ("media-files/${_GET['hash']}");
	}
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
function _read_dir ($directory) {
	$handle = opendir ($directory);
	$filenames = array ();
	while (($name = readdir ($handle)) != null) {
		if (($name != '.' && $name != '..') && is_dir ($directory . '/' . $name)) {
			echo "<li><strong>$name</strong></li>";
			echo "<ul>";
			_read_dir ($directory . '/' . $name);
			echo "</ul>";
		}
		if (preg_match ('/\.(avi|mkv)$/', $name) == 0)
			continue;
		array_push ($filenames, $name);
	}
	asort ($filenames);
	if (empty ($filenames)) {
		echo "<li>No files</li>";
	}
	else {
		foreach ($filenames as $name) {
			echo "<li><a href=\"admin.php?path=".urlencode($directory."/".$name)."\">$name</a></li>";
		}
	}
}
echo "<ul>";
@include "directories.php";
if (!isset ($directories)) {
	echo "<li>File directories.php not set</li>";
}
else {
	foreach ($directories as $directory) {
		echo "<li><strong>$directory</strong></li>";
		echo "<ul>";
		_read_dir ($directory);
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
if ($conn != FALSE) {
	/* read lines from psql */
	$result = pg_query ($conn, "SELECT hash, name FROM videos;");
	while (($row = pg_fetch_row ($result)) != FALSE) {
		echo "<li><a href=\"admin.php?hash=${row[0]}\">${row[1]}</a></li>";
	}
	/* close psql connection */
	pg_close ($conn);
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
