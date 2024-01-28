<?php
namespace IndexViews;

class Indexviews {
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
	<?php }
	static public function footer() { ?>
	        <p class="footerDisclaimer">Chores App 1.4 built by <a href="https://www.jakekilmer.com" target="_blank">Kilmer</a> &#169; <?php echo date("Y"); ?></p>
			</main>
			<script src="js/script.js?t=<?php echo time(); ?>" /></script>
		</body>
		</html>
	<?php }
	static public function index($chores, $username) {
			Indexviews::header();
	?>
					<section class="inner-main">
					    <h2 class="username"><?php echo $username . "'s chores..."; ?></h2>
						<div class="ib1 inner-box animate">
							<?php
							$counter = 0;
							// echo the results to the browser
							foreach ($chores as $res) {
								$counter++;
								echo "<h2>{$counter}. {$res["chore"]}</h2>\n";
							}
							?>
				 		</div>
						<div class="ib2 inner-box">
							<form class="add_chore_form" action="index.php" method="POST">
								<input type="hidden" name="do" value="addNewChore">
								<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
								<input type="text" name="chore" value="" placeholder="type new chore">
								<input class="f1Submit" type="submit" name="add-chore" value="add">
							</form>
							<form class="remove_chore_form" action="index.php" method="POST">
								<input type="hidden" name="do" value="removeNewChore">
								<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
								<?php
								 if (sizeof($chores) > 0) {
									 // output data of each row
									 echo '<select class="remove-list" name="remove_chore"><option value="click here to select">click here to select</option>';
									 
									 foreach ($chores as $res) {
										 echo '<option value="' . $res["chore"] . '">' . $res["chore"] . '</option>';
									 }
									 echo '</select>';
								 }
								 else {
									 echo '<p>0 chores added.</p>';
								 }
								?>
								<input class="f2Submit" type="submit" name="remove-chore" value="done">
							</form>
							<?php
							$chorePlayListKFBCore = [
							        1 => [
							                'trackTitle' => "Dusty Dog",
							                'artist' => "The Kilmer Family Band"
							            ],
							        2 => [
							                'trackTitle' => "Swinging On The Porch",
							                'artist' => "The Kilmer Family Band"
							            ],
							        3 => [
							                'trackTitle' => "Old Coal Mine",
							                'artist' => "The Kilmer Family Band"
							            ],
							        4 => [
							                'trackTitle' => "Maria",
							                'artist' => "The Kilmer Family Band"
							            ],
							        5 => [
							                'trackTitle' => "Sleep Awake",
							                'artist' => "The Kilmer Family Band"
							            ],
							        6 => [
							                'trackTitle' => "Napping In The Warm Sun",
							                'artist' => "The Kilmer Family Band"
							            ],
							        7 => [
							                'trackTitle' => "Joaquine",
							                'artist' => "The Kilmer Family Band"
							            ],
							        8 => [
							                'trackTitle' => "Spooky, Sparky and Bunny",
							                'artist' => "The Kilmer Family Band"
							            ],
							        9 => [
							                'trackTitle' => "Joe & Cream",
							                'artist' => "The Kilmer Family Band"
							            ],
							        10 => [
							                'trackTitle' => "Sail On Sailor (Beach Boys)",
							                'artist' => "The Kilmer Family Band"
							            ]
							  ];
						    $chorePlayListTrack = rand(1, 10);
							?>
                            <a class="account" href="admin.php">account</a>
							<a class="log-out" href="index.php?do=logout&csrf=<?= $_SESSION["csrf"]; ?>">log out</a>
							<div class="audioBox">
							    <h3 class="trackTitle">"<?php echo $chorePlayListKFBCore[$chorePlayListTrack]['trackTitle']; ?>" <a href="https://music.apple.com/us/album/california-bluegrass/1543341877" target="_blank"><span><?php echo $chorePlayListKFBCore[$chorePlayListTrack]['artist']; ?></a></span></h3>
							    <audio controls>
                                <source src="mp3/1/<?php echo $chorePlayListTrack; ?>.mp3" type="audio/mp3">
                            </audio></div>
						</div>
					</section>
		<?php
			Indexviews::footer();
	}
	static public function indexSignup($error="") {
		 	Indexviews::header();
		?>
		<h2 class="h2CTA">A simple list to add and remove daily chores</h2>
		<form class="login_form" action="index.php" method="post">

			<input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">

			<input type="hidden" name="do" value="saveNewUser">
			<div>
				<input type="text" name="username" value="" placeholder="username">
			</div>
			<div>
				<input type="text" name="email" value="" placeholder="email">
			</div>
			<div>
				<input type="password" name="password" value="" placeholder="password">
			</div>
			<input class="fLogoutSubmit" type="submit" value="Signup">

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
		<a class="login" href="login.php">log in</a>
		<?php
		Indexviews::footer();
	}
}
