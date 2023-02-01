<?php

// Set the cookie life time
$cookieLifetime = 86400; // seven days

// set our cookie life time settings
ini_set('session.gc_maxlifetime', $cookieLifetime);
session_set_cookie_params($cookieLifetime);

// start the session
session_start();

// check to see if we have a CSRF token set yet.
if ( isset($_SESSION["csrf"]) == false || $_SESSION["csrf"] == "" ) {
	// store our CSRF token into a session variable.
	$_SESSION["csrf"] = bin2hex( random_bytes(8) );
}
