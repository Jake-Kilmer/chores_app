<?php

// create a namespace
namespace database;

include "config.php";
use \config\config;

// create a class named "db"
class db {

	// set a private variable that only the class can access
	// and store the created PDO object in it.
	private $db;

	// automatically set up mysql connection
	function __construct() {

		try {

			// make a connection to the MySQL server using db configuration
			$this->db = new \PDO("mysql:host=".config::$host.";dbname=".config::$dbName.";", config::$username, config::$password);

			// turn on PDO options
			$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

		} catch(\PDOException $e) {

			// check to see if mode is "dev" or "staging"/"prod"
			if (config::$mode == "dev") {
				// if dev, then we want to show errors in our browser
				echo "Couldn't connect to MySQL server:";
				echo $e->getMessage() . "";
				exit;
			} else {
				// if staging or prod, we only want errors to show up in our system log
				syslog(LOG_ERR, "Couldn't connect to MySQL server: " . $e->getMessage());
				exit;
			}

		}

	}

	//XSS security function
	private function xssFilter($value) {
		return str_replace("<", "&lt;", $value);
	}

	// do a query
	public function query($query, $data = []) {

		try {

			// prepare a query
			$prep = $this->db->prepare($query);

			// bind values to the query before executing
			foreach ($data as $k => $v) {

				// if the value is an integer, we have to specify that to PDO via PDO::PARAM_INT
				if (is_int($v)==true) {
					$prep->bindValue($k, $v, \PDO::PARAM_INT);
				} else {
					$filter = $this->xssFilter($v);
					$prep->bindValue($k, $filter);
				}

			}

			// execute the query
			$prep->execute();

			return $prep;

		} catch(\PDOException $e) {

			if (config::$mode == "dev") {
				// if dev, then we want to show errors in our browser
				echo "There was a database error:";
				echo $e->getMessage() . "";
				exit;
			} else {
				// if staging or prod, we only want errors to show up in our system log
				syslog(LOG_ERR, "There was a database error: " . $e->getMessage());
				exit;
			}

		}

		// return default empty array
		return [];
	}
	public function lastInsertId() {
		return $this->db->lastInsertId();
	}
}
