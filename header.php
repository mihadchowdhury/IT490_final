<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>

	<header>
		<nav>
			<div class="main-wrapper">
				<ul>
					<li><a href="index.php">Home</a></li>
				</ul>
				<div class="nav-login">
					<form action="includes/login.inc.php" method="POST">
						<input type="text" name="uid" placeholder="Username">
						<input type="password" name="pwd" placeholder="password">
						<button type="submit" name="submit">Login</button>
						<button id="logout"><a href="signup.php?logout=1">Logout</a></button>
					
					</form>
					<a href="signup.php">Sign Up</a>
				</div>
			</div>
		</nav>
	</header>

	<?php

	if(isset($_GET['logout'])) {
		session_unset();
		session_destroy();
	}


	?>
