<?php
namespace Hvp;
require_once "hvp-data-connector.class.php";
class Playlist {

	private $conn = NULL;

	function __construct () {
		/* create data-connector object */
		$this->conn = new DataConnector ();
	}
	
	function __destruct () {
	}
	
	public function get_source ($hash) {
		if ($this->conn == NULL) {
			return;
		}
		$source = array ();
		$hash = $this->conn->escapeString ($hash);
		$result = $this->conn->query ("SELECT hash, name FROM videos WHERE hash = '$hash';");
		if (($row = $result->fetchArray ()) != FALSE) {
			$hash = $row[0];
			$name = $row[1];
			$extension = preg_replace ('/.*\.([a-z]+)$/', '$1', $name);
			switch ($extension) {
				case "avi":
					$mimetype = "video/divx";
					break;
				case "mkv":
					$mimetype = "video/x-matroska";
					break;
				default:
					$mimetype = NULL;
					break;
			}
			$source['mimetype'] = $mimetype;
			$source['src'] = "media-files/$hash";
		}
		return $source;
	}
	
	public function get_playlist () {
		if ($this->conn == NULL) {
			return;
		}
		$items = array ();
		/* read lines from db */
		$result = $this->conn->query ("SELECT hash, name FROM videos;");
		while (($row = $result->fetchArray ()) != FALSE) {
			array_push ($items, array ("hash" => $row[0], "name" => $row[1]));
		}
		return $items;
	}
	
	public function add_item ($path) {
		if ($this->conn == NULL) {
			return;
		}
		$hash = md5 ($path);
		/* insert line into db */
		$path_escaped = $this->conn->escapeString ($path);
		$name_escaped = $this->conn->escapeString (basename ($path));
		$this->conn->exec ("INSERT INTO videos (hash, path, name) VALUES ('$hash', '$path_escaped', '$name_escaped');");
		/* create symlink to video file */
		if (!is_dir ("media-files")) {
			mkdir ("media-files");
		}
		symlink ($path, "media-files/$hash");
	}
	
	public function remove_item ($hash) {
		if ($this->conn == NULL) {
			return;
		}
		/* delete line from db */
		$hash_escaped = $this->conn->escapeString ($hash);
		if ($this->conn->exec ("DELETE FROM videos WHERE hash = '$hash_escaped';") != FALSE) {
			unlink ("media-files/$hash");
		}
	}
}

