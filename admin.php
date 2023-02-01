<?php


// require session to be loaded
require_once "session.php";

require_once "filter.php";
use \filter\filter;

require_once "adminmodels.php";
use \AdminModels\AdminModels;

require_once "adminviews.php";
use \AdminViews\AdminViews;

// if we're not logged in, send the user to login.php
if ( isset($_SESSION["loggedIn"]) == false || $_SESSION["loggedIn"] == false ) {
header("Location: login.php");
exit;
}

// print_r($_SESSION);

// handle GETS
if ( isset($_GET["do"]) == true ) {
  // check csrf token to make sure it matches
  // exit if any of these conditions are not met
  if (isset($_GET["csrf"]) == false || $_GET["csrf"] != $_SESSION["csrf"]) {
    exit;
  }

  // use $_GET here, because clicking a link and sending HTTP vars is a GET request
  if ($_GET["do"] == "update") {
    $obfuscatedId = 0;
    if (isset($_GET["obfuscatedId"]) == true && $_GET["obfuscatedId"] != "") {
      $obfuscatedId = $_GET["obfuscatedId"];
    }

    if ($obfuscatedId != "") {
      AdminViews::update( AdminModels::getUser($obfuscatedId) );
    }
    exit;
  }

  if ($_GET["do"] == "delete") {
    $obfuscatedId = "";
    if (isset($_GET["obfuscatedId"]) == true && $_GET["obfuscatedId"] != "") {
      $obfuscatedId = $_GET["obfuscatedId"];
    }

    if ($obfuscatedId != "") {
      AdminModels::deleteUser($obfuscatedId);
      if (isset($_SESSION["administrator"]) == 1 && $_SESSION["administrator"] == 1) {
        header("Location: /chores_app/admin.php");
      }
      else {
        header("Location: /chores_app/index.php?do=logout&csrf={$_SESSION["csrf"]}");   
      }
      exit;
    }
  }

  if ($_GET["do"] == "disable") {
    $obfuscatedId = "";
    if (isset($_GET["obfuscatedId"]) == true && $_GET["obfuscatedId"] != "") {
      $obfuscatedId = $_GET["obfuscatedId"];
    }

    if ($obfuscatedId != "") {
      AdminModels::disableUser($obfuscatedId);
      header("Location: /chores_app/admin.php");
      exit;
    }
  }

  if ($_GET["do"] == "resetPassword") {
    $obfuscatedId = "";
    if (isset($_GET["obfuscatedId"]) == true && $_GET["obfuscatedId"] != "") {
      $obfuscatedId = $_GET["obfuscatedId"];
    }

    if ($obfuscatedId != "") {
      AdminModels::resetPassword($obfuscatedId);
      header("Location:  /chores_app/admin.php");
      exit;
    }
  }

  if ($_GET["do"] == "updatePassword") {
    $obfuscatedId = 0;
    if (isset($_GET["obfuscatedId"]) == true && $_GET["obfuscatedId"] != "") {
      $obfuscatedId = $_GET["obfuscatedId"];
    }

    if ($obfuscatedId != "") {
      AdminViews::updatePass( AdminModels::getUser($obfuscatedId) );
    }
    exit;
  }

}


// handle POSTS
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

      // decide what IndexModels method we should use, depending on the request
      AdminModels::saveNewUser($username, $email);

      // after we've saved, forward the user back to our default screen
      header("Location: /chores_app/admin.php");
      exit;

    } catch (\Exception $e) {

      // display an error, depending on the situation the user is in
      $results = AdminModels::index();
      AdminViews::index($results, $e->getMessage());

    }
  }

  // utilize this validation with saveUserUpdate
  if ($_POST["do"] == "saveUserUpdate") {

    try {
      // validate the username - will throw an exception if not good
      $username = filter::username($_POST["username"]);

      // validate the email - will throw an exception if not good
      $email = filter::email($_POST["email"]);

      // grab the nameId
      $obfuscatedId = "";
      if (isset($_POST["obfuscatedId"]) == true && $_POST["obfuscatedId"] != "") {
        $obfuscatedId = $_POST["obfuscatedId"];
      }

      if ($obfuscatedId != "") {
        AdminModels::saveUserUpdate($obfuscatedId, $username, $email);
      }

      // after we've saved, forward the user back to our default screen
      header("Location: /chores_app/admin.php");
      exit;

    } catch (\Exception $e) {
      // show an error to the user
      if (isset($_POST["obfuscatedId"]) == true && $_POST["obfuscatedId"] != "") {
        AdminViews::update( AdminModels::getUser($_POST["obfuscatedId"]), $e->getMessage() );
      }
    }
  }

  // utilize this validation with saveUserPass
  if ($_POST["do"] == "saveUserPass") {

    try {
			// validate the passowrd - will throw an exception if not good
			$password = filter::password($_POST["password"]);

      // grab the nameId
      $obfuscatedId = "";
      if (isset($_POST["obfuscatedId"]) == true && $_POST["obfuscatedId"] != "") {
        $obfuscatedId = $_POST["obfuscatedId"];
      }

      if ($obfuscatedId != "") {
        AdminModels::saveUserPass($obfuscatedId, $password);
      }

      // after we've saved, forward the user back to our default screen
      header("Location: /chores_app/admin.php");
      exit;

    } catch (\Exception $e) {
      // show an error to the user
      if (isset($_POST["obfuscatedId"]) == true && $_POST["obfuscatedId"] != "") {

        
        AdminViews::updatePass( AdminModels::getUser($_POST["obfuscatedId"]), $e->getMessage() );
      }
    }
  }

}


// if we're not an administrator, send the user to the main chores page
if ( isset($_SESSION["administrator"]) == 1 && $_SESSION["administrator"] == 1 && isset($_POST["do"]) == false) {
  // show the default screen, which is a list of employees
  $results = AdminModels::index();
  AdminViews::index($results);
  AdminViews::adminAddUser();
}
else if ( isset($_POST["do"]) == false) {
  $userId = $_SESSION["userId"];
  // show the default screen, which the one user options
  $results[0] = AdminModels::nonAdminIndex($userId);
  AdminViews::index($results);
}
