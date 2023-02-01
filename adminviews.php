<?php

namespace AdminViews;

class AdminViews {
	// $users is a list of obfuscatedId, lastLogin, email, username, and isEnabled
	static public function index($users, $error="") {
		AdminViews::header();

		// list a table of users

		// show a table header
		?>
		<a class="toChores" href="/chores_app/">&larr; Chores</a>
		<div class="user-list">

			<div class="container header">
				<div class="last-login">
					Last Login
				</div>
				<div class="user">
					Username
				</div>
				<div class="email">
					Email
				</div>
				<div class="delete">
					Delete Account
				</div>
				<?php
				if ( sizeof($users) > 1 ) { ?>
				<div class="disable">
					Disable
				</div>
				<?php } ?>
				<div class="reset-password">
					Reset Password
				</div>
			</div>

			<?php

			// echo the results to the browser
			foreach ($users as $user) {
				// is disabled?
				$enabled = $user["isEnabled"] == 1 ? "" : "disabled";

				?>

					<div class="container <?= $enabled ?>">
						<div class="last-login">
							<?= date("m/d/Y", $user["lastLogin"]) ?>
						</div>
						<div class="user">
							<a href="admin.php?do=update&obfuscatedId=<?= $user["obfuscatedId"] ?>&csrf=<?= $_SESSION["csrf"] ?>">
								<?= $user["username"] ?>
							</a>
						</div>
						<div class="email">
							<a href="admin.php?do=update&obfuscatedId=<?= $user["obfuscatedId"] ?>&csrf=<?= $_SESSION["csrf"] ?>">
								<?= $user["email"] ?>
							</a>
						</div>
						<div class="delete">
							<a href="admin.php?do=delete&obfuscatedId=<?= $user["obfuscatedId"] ?>&csrf=<?= $_SESSION["csrf"] ?>">Delete Account</a>
						</div>
						<?php if ( sizeof($users) > 1) { ?>
						<div class="disable">
							<a href="admin.php?do=disable&obfuscatedId=<?= $user["obfuscatedId"] ?>&csrf=<?= $_SESSION["csrf"] ?>">
								<?= $user["isEnabled"] == 1 ? "Disable" : "Enable"; ?>
							</a>
						</div>
						<?php } ?>
						<div class="reset-password">
							<a href="admin.php?do=updatePassword&obfuscatedId=<?= $user["obfuscatedId"] ?>&csrf=<?= $_SESSION["csrf"] ?>">Reset Password</a>
						</div>
					</div>

				<?php
			}

		?>
		</div>
		<?php

		AdminViews::footer();
	}

	static public function adminAddUser($isUpdate = false, $userRecord = ["obfuscatedId"=>"", "username"=>"", "email"=>"", ], $error="") {
		// create user form
		AdminViews::userEditForm(
			false,
			[
				"username"=>isset($_POST["username"]) == true ? $_POST["username"] : "",
				"email"=>isset($_POST["email"]) == true ? $_POST["email"] : "",
			],
			$error
		);
	}

	static public function update($userRecord, $error = "") {
		AdminViews::header();
		AdminViews::userEditForm(true, $userRecord, $error);
		AdminViews::footer();
	}

	static public function updatePass($userRecord, $error = "") {
		AdminViews::header();
		AdminViews::userEditPass(true, $userRecord, $error);
		AdminViews::footer();
	}

	static public function userEditPass($isUpdatePass = false, $userRecord = ["obfuscatedId"=>"", "username"=>"", "email"=>"", ], $error="") {

		?>
		<?php if ($isUpdatePass == true){ ?><a class="toChores" href="/chores_app/admin.php">&larr; Back</a><?php } ?>
		<div class="create-user"><?= $isUpdatePass == true ? "Reset User Password" : "Edit User" ?></div>
		<form class="login_form" action="/chores_app/admin.php" method="post">

			<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">

			<input type="hidden" name="do" value="<?= $isUpdatePass == true ? "saveUserPass" : "saveUserPass" ?>">
			<?= $isUpdatePass == true ? '<input type="hidden" name="obfuscatedId" value="'.$userRecord["obfuscatedId"].'">' : '' ?>
			<div>
				<input type="password" name="password" value="" placeholder="password">
			</div>
			
			<input class="fLogoutSubmit" type="submit" value="Save">

			<?php
			// if there is an error, echo it
			if ( $error != "" ) {
				?>
				<div class="error">
					<?= $error ?>
				</div>
				<?php
			}
			?>


		</form>
		<?php
	}

	static public function userEditForm($isUpdate = false, $userRecord = ["obfuscatedId"=>"", "username"=>"", "email"=>"", ], $error="") {

		?>
		<?php if ($isUpdate == true){ ?><a class="toChores" href="/chores_app/admin.php">&larr; Back</a><?php } ?>
		<div class="create-user"><?= $isUpdate == true ? "Update User" : "Create New User" ?></div>
		<form class="login_form" action="/chores_app/admin.php" method="post">

			<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">

			<input type="hidden" name="do" value="<?= $isUpdate == true ? "saveUserUpdate" : "saveNewUser" ?>">
			<?= $isUpdate == true ? '<input type="hidden" name="obfuscatedId" value="'.$userRecord["obfuscatedId"].'">' : '' ?>
			<div>
				<input type="text" name="username" value="<?= $userRecord["username"] ?>" placeholder="username">
			</div>
			
			<div>
				<input type="text" name="email" value="<?= $userRecord["email"] ?>" placeholder="email">
			</div>

			<input class="fLogoutSubmit" type="submit" value="Save">

			<?php
			// if there is an error, echo it
			if ( $error != "" ) {
				?>
				<div class="error">
					<?= $error ?>
				</div>
				<?php
			}
			?>


		</form>
		<?php
	}

static public function header() {
$site_title = "Chores App";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title><?php echo $site_title; ?></title>
	<!--<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Open+Sans:wght@300&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css?t=<?php echo time(); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		html {
			width: 100%;
	    /* background-image: linear-gradient(-45deg, pink, purple); */ 
	    margin: 0;
		}
		body{
			color: #fff;
	    margin: 0;
	    position: relative;
		/* background-image: linear-gradient(-45deg, pink, purple); */
		background-repeat: no-repeat;
		}
		div.body {
            max-width: 1000px;
            margin: 0px auto;
        }
		main {
	    color: #fff;
	    width: 100%;
	    height: auto;
	    margin: 0;
	    position: relative;
	    overflow: hidden;
	    text-align: center;
		}
		a{ color: #fff; }
		@-webkit-keyframes bounceInDown {
		  from,
		  60%,
		  75%,
		  90%,
		  to {
		    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		  }

		  0% {
		    opacity: 0;
		    -webkit-transform: translate3d(0, -3000px, 0);
		    transform: translate3d(0, -3000px, 0);
		  }

		  60% {
		    opacity: 1;
		    -webkit-transform: translate3d(0, 25px, 0);
		    transform: translate3d(0, 25px, 0);
		  }

		  75% {
		    -webkit-transform: translate3d(0, -10px, 0);
		    transform: translate3d(0, -10px, 0);
		  }

		  90% {
		    -webkit-transform: translate3d(0, 5px, 0);
		    transform: translate3d(0, 5px, 0);
		  }

		  to {
		    -webkit-transform: translate3d(0, 0, 0);
		    transform: translate3d(0, 0, 0);
		  }
		}
		@keyframes bounceInDown {
		  from,
		  60%,
		  75%,
		  90%,
		  to {
		    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		  }

		  0% {
		    opacity: 0;
		    -webkit-transform: translate3d(0, -3000px, 0);
		    transform: translate3d(0, -3000px, 0);
		  }

		  60% {
		    opacity: 1;
		    -webkit-transform: translate3d(0, 25px, 0);
		    transform: translate3d(0, 25px, 0);
		  }

		  75% {
		    -webkit-transform: translate3d(0, -10px, 0);
		    transform: translate3d(0, -10px, 0);
		  }

		  90% {
		    -webkit-transform: translate3d(0, 5px, 0);
		    transform: translate3d(0, 5px, 0);
		  }

		  to {
		    -webkit-transform: translate3d(0, 0, 0);
		    transform: translate3d(0, 0, 0);
		  }
		}
		h1 {
		  text-align: center;
		  margin: 0;
		  font-size: 25px;
		  padding: 30px 60px;
		  border-radius: 60px;
		  margin: 60px auto;
		  color: #fff;
		  background-color: #5d0c80;
		  text-transform: uppercase;
		  display: inline-block;
		  font-weight: bold;
		  box-shadow: 3px 3px #fff;
		}
		h1.animate {
		  -webkit-animation-name: bounceInDown;
		  animation-name: bounceInDown;
		  animation-duration: 1.2s;
		}
		h1 {
			text-align: center;
		}
		.toChores {
			display: inline-block;
			position: relative;
			color: #43065e;
			margin: 0 0 22px 20px;
    	    float: left;
			transition: .3s;
			-webkit-transition: .3s;
			text-decoration: none;
		}
		.toChores:hover {
			color: #fff;
		}
		.error{
			color: black;
			background-color: pink;
			margin-top: 10px;
			padding: 5px;
		}
		.body{
			margin: auto;
		}
		.user-list{
			font-family: 'Open Sans', sans-serif;
			text-align: left;
			margin: 0 0 40px 0;
			clear: left;
		}
		.container{
			display: block;
			/* grid-template-columns: 150px 120px auto 100px 100px 120px; */
			grid-template-columns: auto auto auto auto auto auto;
			padding: 15px 20px 10px 20px;
			border-bottom: 1px solid rgba(255,255,255,0.2);
		}
		.header{
			background-color: rgba(255,255,255,0.1);
			border-bottom: 0;
		}
		.container.header {font-weight: bold;}
		.container:last-of-type {
			border-bottom: 0;
		}
		.container > div {
			padding: 0 0 10px 0;
		}
		@-webkit-keyframes bounceInLeft {
		  from,
		  60%,
		  75%,
		  90%,
		  to {
		    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		  }

		  0% {
		    opacity: 0;
		    -webkit-transform: translate3d(-3000px, 0, 0);
		    transform: translate3d(-3000px, 0, 0);
		  }

		  60% {
		    opacity: 1;
		    -webkit-transform: translate3d(25px, 0, 0);
		    transform: translate3d(25px, 0, 0);
		  }

		  75% {
		    -webkit-transform: translate3d(-10px, 0, 0);
		    transform: translate3d(-10px, 0, 0);
		  }

		  90% {
		    -webkit-transform: translate3d(5px, 0, 0);
		    transform: translate3d(5px, 0, 0);
		  }

		  to {
		    -webkit-transform: translate3d(0, 0, 0);
		    transform: translate3d(0, 0, 0);
		  }
		}

		@keyframes bounceInLeft {
		  from,
		  60%,
		  75%,
		  90%,
		  to {
		    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		  }

		  0% {
		    opacity: 0;
		    -webkit-transform: translate3d(-3000px, 0, 0);
		    transform: translate3d(-3000px, 0, 0);
		  }

		  60% {
		    opacity: 1;
		    -webkit-transform: translate3d(25px, 0, 0);
		    transform: translate3d(25px, 0, 0);
		  }

		  75% {
		    -webkit-transform: translate3d(-10px, 0, 0);
		    transform: translate3d(-10px, 0, 0);
		  }

		  90% {
		    -webkit-transform: translate3d(5px, 0, 0);
		    transform: translate3d(5px, 0, 0);
		  }

		  to {
		    -webkit-transform: translate3d(0, 0, 0);
		    transform: translate3d(0, 0, 0);
		  }
		}

		.bounceInLeft {
		  -webkit-animation-name: bounceInLeft;
		  animation-name: bounceInLeft;
		}
		.user-list .container a {
		    transition: .3s;
		    color: #43065e;
		    text-decoration: underline;
		}
		.user-list .container a:hover {
		    color: #fff;
		}
		.login_form {
	    max-width: 600px;
	    margin: 40px auto 0 auto;
			text-align: center;
		}
		.login_form.animate {
	    -webkit-animation-name: bounceInLeft;
	    animation-name: bounceInLeft;
	    animation-duration: 1.2s;
		}
		.login_form input {
	    display: inline-block;
	    background-color: transparent;
	    width: calc(100% - 120px);
	    margin: 0 10px 30px 0;
	    outline: 0;
	    border: 0;
	    border-bottom: 1px solid #fff;
	    color: #fff;
	    vertical-align: bottom;
	    font-size: 18px;
		}
		.login_form input.fLogoutSubmit {
	    display: inline-block;
	    color: #fff;
	    width: 94px;
	    box-sizing: border-box;
	    background-color: #5d0c80;
	    border: 0;
	    border-radius: 35px;
	    box-shadow: 3px 3px #fff;
	    outline: 0;
	    padding: 8px 0;
	    transition: .3s;
	    -webkit-transition: .3s;
		}
		.login_form input.fLogoutSubmit:hover {
	    cursor: pointer;
	    background-color: #801dab;
	    box-shadow: 1px 1px #fff;
		}
		.login_form input::placeholder {
		  color: #fff;
		}
		.disabled{
			color: gray;
		}
		.last-login{
			grid-column: 1;
		}
		.user{
			grid-column: 2;
		}
		.email{
			grid-column: 3;
		}
		.delete{
			grid-column: 4;
		}
		.disabled{
			grid-column: 5;
		}
		.reset-password{
			grid-column: 6;
		}
		.create-user{
			padding: 10px;
			clear: left;
			margin: 0 auto 40px;
			font-size: 25px;
			max-width: 1000px;
			background-color: rgba(255,255,255,0.1);
			text-align: center;
		}
		@media screen and (min-width: 350px) {
			.container {
				padding: 20px 30px 10px 30px;
			}
		}
		@media screen and (min-width: 600px) {
			.container {display: grid;}
			.user-list{
				margin: 0 0 60px 0;
			}
		}
	</style>
</head>
<body>
	<main>
		<h1 class="animate">Account</h1>
		<div class="body">

		<?php
			}

			static public function footer() {
		?>
		<p class="footerDisclaimer">Chores App 1.4 built by <a href="https://www.jakekilmer.com" target="_blank">Kilmer</a> &#169; 2023</p>
		</div>
	</main>
</body>
</html>
<?php
	}
}
