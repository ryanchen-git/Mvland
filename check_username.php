<?php
include("includes/_landing.php");

if(isset($_GET['username'])) {
	$username = $_GET['username']; 

	if(strlen($username) < 4 || strlen($username) > 16) {
		echo "invalid";
	}
	else {
		if(preg_match('/^[A-Za-z0-9]+$/', $username)) {
			$query = "SELECT username FROM members WHERE username = '$username'";
			$result = mysql_query($query);
			$num_rows = mysql_num_rows($result);
			
			if($num_rows == 0) {
				echo "okay";
			} else {
				echo "used";
			}
		} else {
			echo "invalid";
		}
	}
}
?>