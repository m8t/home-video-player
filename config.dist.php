<?php
$directories = array ('/home/<USER>/Videos',
		'/media/Movies');

define ('PSQLITE_HOSTNAME', 'localhost');
define ('PSQLITE_DATABASE', 'videos');
define ('PSQLITE_USERNAME', 'videos');

/**
 * HMP_VIDEO_BACKEND:
 * Type of backend to use for video playback. Can be:
 * - HTML5: uses the HTML5 <video> tag.
 * - VLC: uses the VLC Plugin with <embed> tag.
 */
define ('HMP_VIDEO_BACKEND', 'HTML5');

define ('HMP_TITLE', 'Home Video Player');

