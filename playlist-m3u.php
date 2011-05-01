<?php
require_once "hvp-playlist.class.php";

header ("Content-Type: video/vnd.mpegurl");
header ("Content-Disposition: attachment;filename=hvp-playlist.m3u");
header ("Pragma: no-cache");
header ("Expires: 0");

$playlist = new Hvp\Playlist ();
$items = $playlist->get_playlist ();
foreach ($items as $item) {
	$prefix = "${_SERVER['SERVER_ADDR']}:${_SERVER['SERVER_PORT']}";
	$prefix .= dirname ($_SERVER['SCRIPT_NAME']);
	echo "http://${prefix}/media-files/${item['hash']}\n";
}

