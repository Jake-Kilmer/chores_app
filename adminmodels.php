<?php

namespace AdminModels;

require_once "db.php";
use \database\db;

class AdminModels {

	// default password when we're creating a user or resetting a password
	static private $defaultPassword = "jakeschorespass";

	static public function index() {
		$db = new db;
		return $db->query("select obfuscatedId, unix_timestamp(lastLogin) as lastLogin, email, username, isEnabled, administrator from users where isDeleted = false order by username")->fetchAll();
	}

	static public function nonAdminIndex($id) {
		$db = new db;
		return $db->query(
			"select obfuscatedId, unix_timestamp(lastLogin) as lastLogin, email, username, isEnabled, administrator from users where isDeleted = false and id = :id",
			[ ":id"=>$id, ]
		)->fetchAll()[0];
	}

	static public function getUser($obfuscatedId) {
		$db = new db;
		return $db->query(
			"select obfuscatedId, email, username from users where obfuscatedId = :obfuscatedId",
			[ ":obfuscatedId"=>$obfuscatedId, ]
		)->fetchAll()[0];
	}

	static public function deleteUser($obfuscatedId) {
		$db = new db;
		$db->query(
			"update users set isDeleted = true where obfuscatedId = :obfuscatedId",
			[ ":obfuscatedId"=>$obfuscatedId, ]
		);
	}

	static public function disableUser($obfuscatedId) {
		$db = new db;
		$db->query(
			"update users set isEnabled = !isEnabled where obfuscatedId = :obfuscatedId",
			[ ":obfuscatedId"=>$obfuscatedId, ]
		);
	}

	static public function resetPassword($obfuscatedId) {

		// hash default password
		$passwordHash = password_hash(AdminModels::$defaultPassword, PASSWORD_DEFAULT);

		$db = new db;
		$db->query(
			"update users set passwordHash = :passwordHash, forcePasswordChange = true where obfuscatedId = :obfuscatedId",
			[ ":passwordHash"=>$passwordHash, ":obfuscatedId"=>$obfuscatedId, ]
		);
	}

	static public function saveNewUser($username, $email) {

		// create new random obfuscatedId using PHP cryptographically secure random_bytes function
		// will generate a 16 byte string
		$obfuscatedId = bin2hex( random_bytes(8) );

		// hash default password
		$passwordHash = password_hash(AdminModels::$defaultPassword, PASSWORD_DEFAULT);

		$db = new db;
		$db->query(
			"insert into users set created = now(), lastLogin = now(), obfuscatedId = :obfuscatedId, username = :username, email = :email, passwordHash = :passwordHash",
			[ ":obfuscatedId"=>$obfuscatedId, ":username"=>$username, ":email"=>$email, ":passwordHash"=>$passwordHash, ]
		);

		$userId = $db->lastInsertId();
		$username = $username . "'s chores";
		$db = new db;
		$db->query(
			"insert into choreLists set userId=:userId, listName=:username",
			[ ":userId"=>$userId, ":username"=>$username,]
		);
	}

	static public function saveUserUpdate($obfuscatedId, $username, $email) {
		$db = new db;
		$db->query(
			"update users set username = :username, email = :email where obfuscatedId = :obfuscatedId",
			[ ":obfuscatedId"=>$obfuscatedId, ":username"=>$username, ":email"=>$email, ]
		);
	}

	static public function saveUserPass($obfuscatedId, $password) {

		// hash default password
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);

		$db = new db;
		$db->query(
			"update users set passwordHash = :passwordHash, forcePasswordChange = true where obfuscatedId = :obfuscatedId",
			[ ":passwordHash"=>$passwordHash, ":obfuscatedId"=>$obfuscatedId, ]
		);
	}

}
