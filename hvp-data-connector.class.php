<?php
namespace Hvp;
class DataConnector {

	private $conn;

	function __construct () {
		/* connect to db */
		if (!is_writable ('.') && !file_exists ("videos.db")) {
			echo "Failed to create SQLite database! Directory is not writable.";
		}
		else {
			// FIXME Exception is not caught through try/catch
			$this->conn = new \SQLite3 ("videos.db");
		}
	}

	public function escapeString ($string) {
		return $this->conn->escapeString ($string);
	}

	public function query ($query) {
		return $this->conn->query ($query);
	}

	public function exec ($query) {
		return $this->conn->exec ($query);
	}

}

