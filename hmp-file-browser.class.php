<?php
namespace Hmp;
class FileBrowser {
	function __construct () {
	}

	public static function read_dir ($directory) {
		$handle = opendir ($directory);
		$filenames = array ();
		while (($name = readdir ($handle)) != null) {
			if (($name != '.' && $name != '..') && is_dir ($directory . '/' . $name)) {
				echo "<li><strong>$name</strong></li>";
				echo "<ul>";
				FileBrowser::read_dir ($directory . '/' . $name);
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
}

