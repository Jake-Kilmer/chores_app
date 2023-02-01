<?php

namespace filter;

class filter {
	static public function choreTitles($in) {

		// trim whitespace from both ends
		$out = trim($in);

		// filter out everything, except letters and dashes via a "regular expression"
		$out = preg_replace('/"/', "", $out);

		// uppercase first letter
		// $out = ucfirst($out);

		return $out;
	}

		// validate username
	static public function username($username) {

		// throw an error if empty
		if ($username == "") {
			throw new \Exception("All fields are required");
		}

		// we only want letters, numbers, dots, and dashes via a "regular expression".
		// if anything else is passed, throw an error
		if (preg_match("/[^A-Za-z0-9\.\-]/", $username) == true) {
			throw new \Exception("Usernames can only contain letters, numbers, dots, and dashes");
		}

		// the username must be four or more chars long, otherwise throw an error
		if (strlen($username) < 4) {
			throw new \Exception("Username is not long enough");
		}

		// the username must be four or more chars long, otherwise throw an error
		if (strlen($username) > 128) {
			throw new \Exception("Username is too long");
		}

		// lowercase the username
		$username = strtolower($username);

		// return the filtered username back to the controller
		return $username;
	}

	// validate password
	static public function password($password) {

		// throw an error if empty
		if ($password == "") {
			throw new \Exception("All fields are required");
		}

		// the password must be ten or more chars long, otherwise throw an error
		if (strlen($password) < 10) {
			throw new \Exception("Password is not long enough");
		}

		// the password must not be than 72 chars, otherwise throw an error
		if (strlen($password) > 72) {
			throw new \Exception("Password is too long");
		}

		// we can also add other requirements such as forcing special characters and numbers...

		// return the filtered password back to the controller
		return $password;
	}
	static public function email($email) {

		if ($email == "") {
			throw new \Exception("All fields are required");
		}

		// use php to determine if the email is in a valid email format
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			throw new \Exception("Invalid email address");
		}

		// the email must be longer than nine chars, otherwise throw an error
		if (strlen($email) > 252) {
			throw new \Exception("Email address is too long");
		}

		$email = strtolower($email);

		return $email;
	}
}
