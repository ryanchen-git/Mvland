<?
include("includes/_landing.php");

$error_msg = '';

if((isset($_GET['user']))&&(isset($_GET['stamp']))) {
	$user = $_GET['user'];
	$stamp = $_GET['stamp'];	
	$decode_time = base64_decode($stamp);
	
	$query = "UPDATE members SET active=1 WHERE username='$user' AND timestamp=$decode_time";
	$result = mysql_query($query);
	if(mysql_affected_rows() == 1) {
		
		$query = "SELECT email FROM members WHERE username='$user' AND active=1";
		$result = mysql_query($query);
		if($result && mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$email = $row['email'];
			
			$message ="<strong>Mvland謝謝您的註冊!</strong><br /><br />恭喜您順利完成Mvland的帳號註冊及啟動程序, 以下是您註冊的帳號:<br /><br />";
			$message .="$user<br /><br />";			
			$message .="您可以立即享受Mvland提供的各項服務, 如果有任何問題請到我們的<a href=\"http://www.mvland.com/help\">幫助首頁</a>.<br /><br />Mvland客服中心上";
			$message .="<br /><a href=\"http://www.mvland.com/\">http://www.mvland.com/</a>";
			$to = "$email";
			$subject = 'Mvland歡迎您'; 
			$headers = "Content-type: text/html\r\n";			
			$headers .= "From: \"Mvland\" <service@mvland.com>";
			mail($to, $subject, $message, $headers); 
			
			$error_msg = 1;
		}
	} else {
		$error_msg = 2;
	}
} else {
	header("Location: http://www.mvland.com/index");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 帳號啟動</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂" />
<meta name="description" content="" />

<? include("includes/header.php"); ?>
			
<div id="content">

<br />

<table style="width: 155%;"><tr><td>
<?
if ($error_msg == 1) { 
?>	<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>恭喜您<?=$user?>, 您的Mvland帳號已經啟動, 請到<a href="http://www.mvland.com/login">登錄首頁</a>進行登錄, 謝謝.</strong></h1> <?
} else {
?> <h1 style="font-size: 16px; padding-bottom: 10px;"><strong>抱歉,您的帳號啟動程序發生錯誤, 請到<a href="http://www.mvland.com/help">幫助首頁</a>查詢, 謝謝.</strong></h1> <?
}
?>
</td></tr></table>

<?
include("includes/footer.php")
?>