<?php

namespace LoginViews;

class LoginViews {
	static public function index($error = "") {
		LoginViews::header();

		?>
		<h2 class="h2CTA">A simple list to add and remove daily chores</h2>
		<form action="login.php" method="post" class="login_form animate">
		<input type="hidden" name="do" value="login">
		<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">

		<?php

		// if there is an error, echo it
		if ( $error != "" ) {
			?>
			<div class="error">
				<?= $error ?>
			</div>
			<?php
		}

		// show the log in form
		?>

		<!-- <label>Username</label> -->
		<div><input type="text" name="username" value="" placeholder="username"></div>
		<!-- <label>Password</label> -->
		<div><input type="password" name="password" value="" placeholder="password"></div>

		<input class="fLogoutSubmit" type="submit" value="Log In">
        <div>
            <a class="signup reset-pass" href="/chores_app/">signup</a>
		    <a class="reset-pass" href="/chores_app/login.php?do=forgotPass">forgot password</a>
		</div>

		</form>
		<?php

		LoginViews::footer();
	}


	static public function userResetPass($isResetPass = false, $error="") {

		?>
		<div class="create-user"><?= $isResetPass == true ? "Reset User Password" : "Edit User" ?></div>
		<form class="login_form" action="login.php" method="post">

			<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
			<input type="hidden" name="do" value="<?= $isResetPass == true ? "resetUserPass" : "resetNewUser" ?>">

			<div>
				<input type="text" name="username" value="" placeholder="username">
			</div>
			
			<div>
				<input type="text" name="email" value="" placeholder="email">
			</div>
			
			<input class="fLogoutSubmit" type="submit" value="Reset">

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
		<?php if ($isResetPass == true){ ?><a class="toChores" href="/chores_app/login.php">&larr; login</a><?php } ?>
		<?php
	}

	static public function header() {
		$site_title = "Chores App";
		?>
		<!DOCTYPE html>
		<html>
			<head>
				<title><?php echo $site_title; ?></title>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Open+Sans:wght@300&display=swap" rel="stylesheet">
				<link rel="stylesheet" href="style.css?t=<?php echo time(); ?>">
			</head>
			<body>
			 <main>
				 <h1 class="animate"><?php echo $site_title; ?></h1>
	 <?php
	 }

 	static public function footer() {
	?>
                <p class="footerDisclaimer">Chores App 1.4 built by <a href="https://www.jakekilmer.com" target="_blank">Kilmer</a> &#169; <?php echo date("Y"); ?></p>
				</main>
				<script src="js/script.js?t=<?php echo time(); ?>" /></script>
			</body>
		</html>
	<?php
	}
}
