<?
include("includes/_landing.php");

if(isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];

	if(isset($_POST['song_id']) && isset($_POST['song_url'])) {
		$song_id = $_POST['song_id'];
		$song_url = $_POST['song_url'];
		
		/* The following query makes sure the person who login is the one who provided the song */
		$query = "SELECT s.*, m.* FROM songs s, members2songs_mapping m WHERE s.id=m.songs AND s.active=1 AND m.members=$user_id AND m.songs=$song_id";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result)) {

			$query = "UPDATE songs SET active=0 WHERE unique_id='$song_url'";
			$result = mysql_query($query);
		
			$query2 = "UPDATE members SET song_provide=(song_provide-1) WHERE id=$user_id AND active=1";
			$result2 = mysql_query($query2);	
			if ($result2) {
				header("Location: http://www.mvland.com/my_videos?edit=remove");
			}

		} else {
			header("Location: http://www.mvland.com/");
		}
	} else {
		header("Location: http://www.mvland.com/");
	}
} else {
	header("Location: http://www.mvland.com/");
}

?>