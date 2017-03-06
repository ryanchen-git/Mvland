<?
include("includes/_landing.php");

if(isset($_SESSION['user_id'])) {

	if(isset($_POST['message_id']) && isset($_POST['song_url'])) {
		$message_id = $_POST['message_id'];
		$song_url = $_POST['song_url'];

		$query = "UPDATE messages SET active=0 WHERE id=$message_id";
		$result = mysql_query($query);
		if ($result) {
			header("Location: http://www.mvland.com/watch/mv=$song_url");
		} 
	} else {
		header("Location: http://www.mvland.com/");
	}
} else {
	header("Location: http://www.mvland.com/");
}

?>