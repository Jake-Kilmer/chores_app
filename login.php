<?php

require_once "session.php";

require_once "filter.php";
use \filter\filter;

require_once "loginmodels.php";
use \LoginModels\LoginModels;

require_once "loginviews.php";
use \LoginViews\LoginViews;

// if we're already logged in, then just send the user to index.php
if ( isset($_SESSION["loggedIn"]) == true && $_SESSION["loggedIn"] == true ){
	header("Location: /chores_app/");
	exit;
}

if (isset($_GET["do"]) == true) {
    
if ($_GET["do"] == "forgotPass") {
    // $obfuscatedId = "";
    // if (isset($_GET["obfuscatedId"]) == true && $_GET["obfuscatedId"] != "") {
    //   $obfuscatedId = $_GET["obfuscatedId"];
    // }

    // if ($obfuscatedId != "") {
    //   LoginModels::resetPassword($obfuscatedId);
	LoginViews::header();
	LoginViews::userResetPass(true);
	LoginViews::footer();

     // header("Location: login.php");
      exit;
    // }
}
}

if (isset($_POST["do"]) == true) {

	// check csrf token to make sure it matches
	// exit if any of these conditions are not met
	if (isset($_POST["csrf"]) == false || $_POST["csrf"] != $_SESSION["csrf"]) {
	    exit;
	}

	// utilize this validation with saveUserUpdate
	if ($_POST["do"] == "resetUserPass") {
		try {
		// validate the username - will throw an exception if not good
		$username = filter::username($_POST["username"]);

		// validate the email - will throw an exception if not good
		$email = filter::email($_POST["email"]);


		// grab the nameId
		// $obfuscatedId = "";
		// if (isset($_POST["obfuscatedId"]) == true && $_POST["obfuscatedId"] != "") {
		// 	$obfuscatedId = $_POST["obfuscatedId"];
		// }

		// if ($obfuscatedId != "") {
		// 	AdminModels::saveUserUpdate($obfuscatedId, $username, $email);
		// }

		LoginModels::resetPassword($username, $email);

        // after we've saved, forward the user back to our default screen
		header("Location: /chores_app/login.php");
		exit;

		} catch (\Exception $e) {
		// show an error to the user
		// if (isset($_POST["obfuscatedId"]) == true && $_POST["obfuscatedId"] != "") {
		// 	AdminViews::update( AdminModels::getUser($_POST["obfuscatedId"]), $e->getMessage() );
		// }
		}
	}


	// is this a log in attempt?
	if ($_POST["do"] == "login") {
		// we'll use a try catch block here so that our filters can throw an error
		// if something is wrong.  we'll use the message to show an error to the user
		try {

			// validate the username - will throw an exception if not good
			$username = filter::username($_POST["username"]);

			// validate the password - will throw an exception if not good
			$password = filter::password($_POST["password"]);

			// check the username and password against the database - will throw an exception if not good
			$results = LoginModels::checkLogin($username, $password);

			// if we've gotten the all clear aka loggedIn = true,
			// then set our $_SESSION and redirect to index.php
			if ($results['loggedIn'] == true) {
				$_SESSION["loggedIn"] = true;
				$_SESSION["userId"] = $results["userId"];
				$_SESSION["administrator"] = $results["administrator"];
				header("Location: /chores_app/");
				exit;
			} else {
				LoginViews::index("There was an error logging in.");
			}

		} catch (\Exception $e) {
			// if we get here something was not right.  pass the error message into the
			// log in view and allow the user to try again
			LoginViews::index($e->getMessage());
			//echo $e->getMessage();

			// if someone didn't log in correctly, we'll log it to the syslog
			syslog(LOG_INFO, "---Log in error---" );
			syslog(LOG_INFO, '$_POST = ' . print_r($_POST, true) );
			syslog(LOG_INFO, print_r($e->getMessage(), true) );
			syslog(LOG_INFO, "------------------" );

		}

	}

} else {
	// show the default screen with no error
	LoginViews::index();
}
