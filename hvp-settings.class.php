<?php
namespace Hvp;
require_once "hvp-data-connector.class.php";
class Settings {

	private $conn = NULL;

	function __construct () {
		/* create data-connector object */
		$this->conn = new DataConnector ();
	}

	public function get_value ($name) {
		$name_escaped = $this->conn->escapeString ($name);
		$result = $this->conn->query ("SELECT value FROM settings WHERE name = '$name_escaped';");
		if (($row = $result->fetchArray ()) == FALSE)
			return NULL;
		return $row[0];
	}

	public function set_value ($name, $value) {
		$name_escaped = $this->conn->escapeString ($name);
		$value_escaped = $this->conn->escapeString ($value);
		$this->conn->query ("INSERT OR REPLACE INTO settings (name, value) VALUES ('$name_escaped', '$value_escaped');");
	}

	public function __get ($name) {
		$name_escaped = $this->conn->escapeString ($name);
		$result = $this->conn->query ("SELECT value FROM settings WHERE name LIKE '/general/$name_escaped';");
		if (($row = $result->fetchArray ()) == FALSE)
			return NULL;
		return $row[0];
	}
}

