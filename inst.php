<?php
$servername = "localhost";
$username = "root";
$password = "Master@1234";
$dbname = "mihad";

$first=$_POST['first'];
$last=$_POST['last'];
$email=$_POST['email'];
$uid=$_POST['uid'];
$hashedPwd=$_POST['pwd'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = $sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
