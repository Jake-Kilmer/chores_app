<?php

// require session to be loaded
require_once "session.php";

require_once "filter.php";
use \filter\filter;

require_once "indexmodels.php";
use \IndexModels\Indexmodels;

require_once "indexviews.php";
use \IndexViews\Indexviews;

if ( isset($_GET['do']) == true) {
	// check csrf token to make sure it matches
	// exit if any of these conditions are not met
	if (isset($_GET["csrf"]) == false || $_GET["csrf"] != $_SESSION["csrf"]) {
		exit;
	}  
	
	if ($_GET['do'] == "logout") {
		session_destroy();
		header("Location: /chores_app/login.php");
		exit;
	}
}
if ( isset($_POST["do"]) == true ) {
		// check csrf token to make sure it matches
		// exit if any of these conditions are not met
		if (isset($_POST["csrf"]) == false || $_POST["csrf"] != $_SESSION["csrf"]) {
			exit;
		}
		if ($_POST["do"] == "saveNewUser") {
			try {
			// validate the username - will throw an exception if not good
			$username = filter::username($_POST["username"]);

			// validate the email - will throw an exception if not good
			$email = filter::email($_POST["email"]);

			// validate the passowrd - will throw an exception if not good
			$password = filter::password($_POST["password"]);

			// decide what IndexModels method we should use, depending on the request
			indexmodels::saveNewUser($username, $email, $password);

				header("Location: /chores_app/login.php");
				exit;
			} catch (\Exception $e) {
			// display an error, depending on the situation the user is in
			Indexviews::indexSignup($e->getMessage());
			}
		}
		else if ( isset($_SESSION["loggedIn"]) == true || $_SESSION["loggedIn"] == true ) {
			if ($_POST["do"] == "addNewChore") {
				// echo '<pre>';
				// print_r($_POST);
				// echo '</pre>';
				// define a default
				$choreListId = "";
				$chore = "";
				$choreListId = $_SESSION["userId"];
				if (isset($_POST["chore"]) == true && $_POST["chore"] != "") {
					$chore = filter::choreTitles($_POST["chore"]);
				}
				// $obfuscatedId = $_POST['obfuscatedIds'];
				if ($chore != "") {
					Indexmodels::addNewChore($chore, $choreListId);
				}

				// after we've saved, forward the user back to our default screen
				header("Location: /chores_app/");
				exit;
			}
			if ($_POST["do"] == "removeNewChore") {

				// define a default
				$choreListId = "";
				$removedChore = "";
				$choreListId = $_SESSION["userId"];
				if (isset($_POST["remove_chore"]) == true && $_POST["remove_chore"] != "") {
					$removedChore = $_POST["remove_chore"];
				}

				if ($removedChore != "") {
					Indexmodels::removeNewChore($removedChore, $choreListId);
				}

				// after we've saved, forward the user back to our default screen
				header("Location: /chores_app/");
				exit;
			}

		}
}
// if we're not logged in, send the user to sinup
else if ( isset($_SESSION["loggedIn"]) == false || $_SESSION["loggedIn"] == false && isset($_POST["do"]) == false ) {
	Indexviews::indexSignup();
	exit;
}
else {
	// show the default screen, which is a list of chores
	$choreListId = "";
	$choreListId = $_SESSION["userId"];
	$chores = Indexmodels::choresIndex($choreListId);
	$username = Indexmodels::getUsername($choreListId);
	Indexviews::index($chores, $username['username']);
}
