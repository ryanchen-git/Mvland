<? include("includes/_landing.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 帳號查詢</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂" />
<meta name="description" content="" />
<link rel="stylesheet" href="http://www.mvland.com/common/style.css" type="text/css" media="screen,projection" />

<style>
.message {
	margin-top: 20px;
	padding: 5px; 
	width: 97%; 
	font-weight: bold;
	margin-bottom: 15px;
}
</style>
</head>

<body>
<img src="http://www.mvland.com/images/mvland_logo.gif" border="0" />
<?
if(isset($_GET['username'])) {
	
$username = $_GET['username']; 

	if(preg_match('/^[A-Za-z0-9]+$/', "$username")) {
	
		$query = "SELECT username FROM members WHERE username='$username'";
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		
		if($num_rows==0) {
		?> <div class="message"><span style="color: #000033;"><?=$username?></span> - <span style="color: #006633;">恭喜, 這個帳號可以使用</span></div> <?
		} else {
		?> <div class="message"><span style="color: #000033;"><?=$username?></span> - <span style="color: #FF0000;">抱歉, 這個帳號已被使用, 請再試一次</span></div> <?
		}
	} else {
		?> <div class="message"><span style="color: #000033;"><?=$username?></span> - <span style="color: #FF0000;">您輸入的帳號不是有效的帳號</span></div> <?
	}
?>	<div style="padding-left: 40px;"><a href="javascript: self.close()">關閉視窗</a></div>	<?
}			


?>
</body>
</html>