<?php

namespace IndexModels;

require_once "db.php";
use \database\db;

class indexmodels {
	static private $defaultPassword = "jakeschorespass";
	static public function index() {
		$db = new db;
		return $db->query("select obfuscatedId, unix_timestamp(lastLogin) as lastLogin, email, username, isEnabled from users where isDeleted = false order by username")->fetchAll();
	}
	static public function getUsername($id) {
	    $db = new db;
		$userId = $id;
		return $db->query("select username from users where id=:userId",
		[":userId"=>$userId]
		)->fetchAll()[0];
	}
	static public function choresIndex($id) {
		$db = new db;
		$choreListId = $id;
		return $db->query("select * from choreItems where choreListId=:choreListId",
		[":choreListId"=>$choreListId]
		)->fetchAll();
	}
	static public function addNewChore($item, $id) {
	$db = new db;
	$choreListId = $id;
	$db->query(
		"insert into choreItems set choreListId=:choreListId, chore=:chore",
		[ ":choreListId" => $choreListId, ":chore"=>$item ]
	);
	}
	static public function removeNewChore($item, $id) {
	$db = new db;
	$db->query(
		"delete from choreItems where chore=:chore and choreListId=:choreListId",
		[ ":chore"=>$item, ":choreListId"=>$id ]
	);
	}
	static public function saveNewUser($username, $email, $password) {
		// create new random obfuscatedId using PHP cryptographically secure random_bytes function
		// will generate a 16 byte string
		$obfuscatedId = bin2hex( random_bytes(8) );

		// hash default password
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);

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
}
