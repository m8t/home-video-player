<?php
namespace Hvp;
require "config.php";
class Playlist {

	private $conn = FALSE;

	function __construct () {
		/* connect to psql */
		$this->conn = pg_connect ("host=".PSQLITE_HOSTNAME." dbname=".PSQLITE_DATABASE." user=".PSQLITE_USERNAME);
		if ($this->conn == FALSE) {
			echo "Failed to connect to PostgreSQL!";
		}
	}
	
	function __destruct () {
		/* close psql connection */
		pg_close ($this->conn);
	}
	
	public function get_source ($hash) {
		if ($this->conn == FALSE) {
			return;
		}
		$source = array ();
		$hash = pg_escape_string ($hash);
		$result = pg_query ($this->conn, "SELECT hash, name FROM videos WHERE hash = '$hash';");
		if (($row = pg_fetch_row ($result)) != FALSE) {
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
		if ($this->conn == FALSE) {
			return;
		}
		$items = array ();
		/* read lines from psql */
		$result = pg_query ($this->conn, "SELECT hash, name FROM videos;");
		while (($row = pg_fetch_row ($result)) != FALSE) {
			array_push ($items, array ("hash" => $row[0], "name" => $row[1]));
		}
		return $items;
	}
	
	public function add_item ($path) {
		if ($this->conn == FALSE) {
			return;
		}
		$hash = md5 ($path);
		/* insert line into pgsql */
		$path_escaped = pg_escape_string ($this->conn, $path);
		$name_escaped = pg_escape_string ($this->conn, basename ($path));
		pg_query ($this->conn, "INSERT INTO videos (hash, path, name) VALUES ('$hash', '$path_escaped', '$name_escaped');");
		/* create symlink to video file */
		if (!is_dir ("media-files")) {
			mkdir ("media-files");
		}
		symlink ($path, "media-files/$hash");
	}
	
	public function remove_item ($hash) {
		if ($this->conn == FALSE) {
			return;
		}
		/* delete line from pgsql */
		$hash_escaped = pg_escape_string ($this->conn, $hash);
		if (pg_query ($this->conn, "DELETE FROM videos WHERE hash = '$hash_escaped';") != FALSE) {
			unlink ("media-files/$hash");
		}
	}
}

