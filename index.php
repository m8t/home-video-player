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
	$playlist->get_source ($_GET['hash']);
}
?>
      </video>
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
