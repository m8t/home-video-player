<?php
/* connect to psql */
$conn = pg_connect ("dbname=videos user=videos");
if ($conn == FALSE) {
	echo "Failed to connect to PostgreSQL!";
}
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
if ($conn != FALSE && isset ($_GET['hash'])) {
	$hash = pg_escape_string ($_GET['hash']);
	$result = pg_query ($conn, "SELECT hash, name FROM videos WHERE hash = '$hash';");
	if (($row = pg_fetch_row ($result)) != FALSE) {
		$hash = $row[0];
		$name = $row[1];
		$extension = preg_replace ('/.*\.([a-z]+)$/', '$1', $name);
		echo "<!-- $extension -->";
		switch ($extension) {
			case "avi":
				$mimetype = "video/divx";
				break;
			case "mkv":
				$mimetype = "video/x-matroska";
				break;
		}
		if (isset ($mimetype))
			$type = "type=\"$mimetype\"";
		echo "<source src=\"media-files/$hash\" $type />";
	}
}
?>
      </video>
    </div>
    <div id="playlist">
      <ul>
<?php
if ($conn != FALSE) {
	/* read lines from psql */
	$result = pg_query ($conn, "SELECT hash, name FROM videos;");
	while (($row = pg_fetch_row ($result)) != FALSE) {
		if (isset ($_GET['hash']) && $_GET['hash'] == $row[0])
			$li = 'li class="selected"';
		else
			$li = 'li';
		echo "<$li><a href=\"index.php?hash=${row[0]}\">${row[1]}</a></li>";
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
