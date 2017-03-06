<? include("includes/_landing.php"); 

//check for user session id
if(!isset($_SESSION['user_id'])) {
	header("Location: http://www.mvland.com/login");		
} else {
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['user_name'];
}

if(isset($_GET['url'])) {
	$url = $_GET['url'];
	$query = "SELECT id, name, singer FROM songs WHERE unique_id='$url' AND active=1";
	$result = mysql_query($query);
	if($result && mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
		$mv_id = $row['id'];
		$mv_name = $row['name'];
		$singer = $row['singer'];
	}
}

$error_msg = '';

if(isset($_POST['submit'])) {
	
	if(!empty($_POST['email'])) {
		$email = trim($_POST['email']);		
	} else {
		$error_msg = 1;
	}
	
	if(!empty($_POST['sender_email'])) {
		$sender_email = trim($_POST['sender_email']);		
	} else {
		$error_msg = 2;
	}	
	
	$sender_message = trim($_POST['sender_message']);	
	
	if(strlen($sender_message)>2500) {
		$error_msg = 3;
	}		
	
	if($error_msg == '') {
		$query = "INSERT INTO shares (user_id, mv_id, email, message, date) VALUES ('$user_id', '$mv_id', '$email', '$sender_message', NOW())";
		$result = mysql_query($query);
		if ($result) {
			$mv_url = "http://www.mvland.com/watch/mv=$url";
			$message ="您的好友" . $username . "透過了Mvland轉寄了一個MV給您:<br /><br />";
			$message .="<strong>MV資料:</strong><br />歌名: $mv_name <br />歌手: $singer<br /><br />";
			$message .="<strong>MV連結:</strong><br /> <a href=\"$mv_url\">$mv_url</a><br /><br />";		
			if($sender_message != '') {
				$message .="<strong>留言:</strong><br /> $sender_message<br /><br />";		
			}
			$message .=" - $username";			
			$to = "";
			$subject = '您的好友在Mvland寄了一個MV'; 
			$headers = "Content-type: text/html\r\n";
			$headers .= "Bcc: $email\r\n";			
			$headers .= "From: \"$sender_email\" <$sender_email>";
			
			mail($to, $subject, $message, $headers); 
			
			$error_msg = 4;
			
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - MV轉寄</title>
<meta name="keywords" content="Mvland, MV, Music Videos, MV轉寄, 音樂錄影帶" />
<meta name="description" content="" />
<link rel="stylesheet" href="http://www.mvland.com/common/style.css" type="text/css" media="screen,projection" />
<script type="text/javascript">
window.onload = function() {
  document.getElementById('email_field').focus();
}
</script>
<body>

<img src="http://www.mvland.com/images/mvland_logo.gif" border="0" style="padding-left: 10px;" />
<br /><br />

<?
if((!empty($error_msg)) && $error_msg != 4) {
?> <div class="error_msg" style="width: 290px; margin-left: 10px;"> <?
	switch($error_msg) {
	case 1: 
		echo "請輸入要轉寄的E-mail";
		break;
	case 2:
		echo "請輸入您的E-mail";
		break;	
	case 3:
		echo "您輸入的留言長度過長";
		break;			
	}
?> </div> <?
}

if ($error_msg == 4) { ?>	
	<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>您的MV已經成功寄出, 謝謝!</strong></h1>
	<div style="padding-left: 10px;"><a href="javascript: self.close()">關閉式窗</a></div> <?
} else { ?>

	<div style="padding-left: 10px;">
	<strong>轉寄MV: <?=$mv_name?> - <?=$singer?></strong>
	<br /><br />
	
	<form method="post" action="http://www.mvland.com/share_mv?url=<?=$url?>">
		
		<? if((isset($_POST['submit']))&&(empty($_POST['email']))) { ?> <span class="error_text">收件人E-mail, 請以逗號區隔: </span> <? } else { ?>收件人E-mail, 請以逗號區隔: <? } ?>
		 <br />
		<textarea name="email" id="email_field" style="width: 300px; height: 18px; border: 1px solid #999999;" /></textarea><br /><br />
		
		<? if((isset($_POST['submit']))&&(empty($_POST['sender_email']))) { ?> <span class="error_text">我的E-mail: </span> <? } else { ?>我的E-mail: <? } ?>
		 <br />
		<textarea name="sender_email" id="email_field" style="width: 300px; height: 18px; border: 1px solid #999999;" /></textarea><br /><br />		
		
		給朋友的話: <br />
		<textarea name="sender_message" style="width: 300px; height: 90px; border: 1px solid #999999;" /><? if(isset($_POST['submit'])) { $sender_message = $_POST['sender_message']; echo $sender_message; } ?></textarea><br /><br />
		
		<input type="submit" name="submit" value="送出" style="width: 100px; height: 30px;" />
	
	</form> 
	</div> <?
}
?>
</body>
</html>