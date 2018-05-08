<?php 
	include_once 'header.php';
	include_once 'footer.php';
	include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>
<section class="main-container">
	<div class="main-wrapper">
		<h2>Signup</h2>
		<form class="signup-form" action="includes/signup.inc.php" method="POST">
		<?php
			if (isset($_GET['first'])){
				$first = $_GET['first'];
				echo '<input type="text" name="first" placeholder="Firstname" value="'.$first.'">';	
			}
			else {
				echo '<input type="text" name="first" placeholder="Firstname" >';
			}
			if (isset($_GET['last'])){
				$last = $_GET['last'];
				echo '<input type="text" name="last" placeholder="Lastname" value="'.$last.'">';	
			}
			else {
				echo '<input type="text" name="last" placeholder="Lastname">';
			}
			
		?>
			<input type="text" name="email" placeholder="E-mail">
		<?php
			if (isset($_GET['uid'])){
				$uid = $_GET['uid'];
				echo '<input type="text" name="uid" placeholder="Username" value="'.$uid.'">';	
			}
			else {
				echo '<input type="text" name="uid" placeholder="Username">';
			}
		?>
			<input type="password" name="pwd" placeholder="Password">
			<button type="submit" name="submit" id="submit" class="button1">Sign up</a></button>
		</form>
	</div>

</section>

</body>

<?php 
	include_once 'footer.php';
	
	if (!isset($_GET['signup'])){
		exit();
	}
	else{
		$chkSignUp = $_GET['signup'];
		
		if ($chkSignUp == "empty"){
			echo "All fields not filled!!!";
			$error_type = "signup = " . $chkSignUp . "";
			$error_file = $_SERVER["SCRIPT_FILENAME"];
			$sql = "INSERT INTO log_errors (error_type, error_time, error_file) VALUES ('$error_type', NOW(), '$error_file');";
			mysqli_query($conn, $sql);
			exit();
		}
		elseif ($chkSignUp == "invalid"){
			echo "Invalid fields!!!";
			$error_type = "signup = " . $chkSignUp . "";
			$error_file = $_SERVER["SCRIPT_FILENAME"];
			$sql = "INSERT INTO log_errors (error_type, error_time, error_file) VALUES ('$error_type', NOW(), '$error_file');";
			mysqli_query($conn, $sql);
			exit();
		}
		elseif ($chkSignUp == "email"){
			echo "Invalid e-mail!!!";
			$error_type = "signup = " . $chkSignUp . "";
			$error_file = $_SERVER["SCRIPT_FILENAME"];
			$sql = "INSERT INTO log_errors (error_type, error_time, error_file) VALUES ('$error_type', NOW(), '$error_file');";
			mysqli_query($conn, $sql);
			exit();
		}
		elseif ($chkSignUp == "success"){
			echo '<h3 style="text-align: center; margin-top: 20px">User created successfully. You may now login.</h3>';
			exit();
		}
	}
?>

