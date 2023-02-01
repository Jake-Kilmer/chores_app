<?php

namespace LoginModels;

require_once "db.php";
use \database\db;

class LoginModels {

	// default password when we're creating a user or resetting a password
	static private $defaultPassword = "jakeschorespass";

	static public function resetPassword($username, $email) {

		// hash default password
		$passwordHash = password_hash(LoginModels::$defaultPassword, PASSWORD_DEFAULT);

		$db = new db;
		$db->query(
			"update users set passwordHash = :passwordHash, forcePasswordChange = true where email = :email and username = :username",
			[ ":passwordHash"=>$passwordHash, ":username"=>$username, ":email"=>$email ]
		);
	}

	static public function getUser($username, $email) {
		$db = new db;
		return $db->query(
			"select email, username from users where username = :username, email = :email,",
			[ ":username"=>$username, ":email"=>$email ]
		)->fetchAll()[0];
	}


	// check the username and password against the database
	static public function checkLogin($username, $password) {
		// retrieve the record for the username, if it exists
		$db = new db;
		$results = $db->query(
			"select id, passwordHash, administrator from users where username = :username and isEnabled = true and isDeleted = false",
			[ ":username"=>$username, ]
		)->fetchAll();
		// if the username exists, test the password
		if (count($results) == 1) {
			// verify the password against the passwordHash
			if ( password_verify($password, $results[0]["passwordHash"]) == true ) {
				// print_r($results);
				// update the lastLogin field for this user id
				$db->query(
					"update users set lastLogin=now() where id = :id",
					[ ":id"=>$results[0]["id"], ]
				);
				$out = [];
				$out["loggedIn"] = true;
				// after doing the password verification
				$out["userId"] = $results[0]["id"];
				$out["administrator"] = $results[0]["administrator"];
				// pass true back to the controller to indicate loggedIn == true
				return $out;

			}
		}
		// if we get here, then nothing went right, so we'll throw an error
		throw new \Exception("That username and password are incorrect");
	}
}
