<?php
if (!isset ($settings)) {
	require_once "hvp-settings.class.php";
	$settings = new Hvp\Settings ();
	$video_backend = $settings->get_value ("/general/video-backend");
}
else {
	$video_backend = $settings->get_value ("/general/video-backend");
}
?>
    <ul>
      <li><a href="index.php">Player</a></li>
      <li><a href="playlist.php">Playlist</a></li>
      <li><a href="admin.php">Administration</a></li>
<?php
if ($video_backend == "HTML5") {
?>
      <li><a href="http://www.divx.com/en/software/divx-plus/web-player">Get DivX Plus Web Player</a></li>
<?php
}
else if ($video_backend == "VLC") {
?>
      <li><a href="http://www.videolan.org/vlc/">Get VLC media player</a></li>
<?php
}
else if ($video_backend == "WMP") {
?>
      <li><a href="http://windows.microsoft.com/en-US/windows/products/windows-media-player">Get Windows Media Player</a></li>
<?php
}
?>
    </ul>

