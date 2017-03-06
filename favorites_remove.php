<?
include("includes/_landing.php");

if(isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];

	if(isset($_POST['fav_id']) && isset($_POST['song_url'])) {
		$fav_id = $_POST['fav_id'];
		$song_url = $_POST['song_url'];
		
		$query = "SELECT * FROM members2favorites_mapping WHERE members=$user_id AND id=$fav_id AND active=1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result)) {

			$query = "UPDATE members2favorites_mapping SET active=0 WHERE members=$user_id AND id=$fav_id";
			$result = mysql_query($query);
			if ($result) {
				$query2 = "UPDATE songs SET favorites=(favorites-1) WHERE unique_id='$song_url' AND active=1";
				$result2 = mysql_query($query2);
				if($result2) {
					header("Location: http://www.mvland.com/my_favorites?fav=remove");
				}	
			} 

		} else {
			header("Location: http://www.mvland.com/index");
		}
	} else {
		header("Location: http://www.mvland.com/index");
	}
} else {
	header("Location: http://www.mvland.com/index");
}

?>