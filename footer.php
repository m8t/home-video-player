    <ul>
      <li><a href="index.php">Player</a></li>
      <li><a href="admin.php">Administration</a></li>
<?php
if (HMP_VIDEO_BACKEND == "VLC") {
?>
      <li><a href="http://www.videolan.org/vlc/">Get VLC media player</a></li>
<?php
}
else if (HMP_VIDEO_BACKEND == "HTML5") {
?>
      <li><a href="http://www.divx.com/en/software/divx-plus/web-player">Get DivX Plus Web Player</a></li>
<?php
}
?>
    </ul>

