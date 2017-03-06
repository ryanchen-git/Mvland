<?
include("includes/_landing.php");

$error_msg = '';

if(isset($_POST['submit'])) {

	if(!empty($_POST['email'])) {
		$email = trim($_POST['email']);
		
		if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', "$email")) {
	
			if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
				unset($_SESSION['security_code']);
			} else {
			  $error_msg = 1;
			}
			
		} else {
			$error_msg = 2;
		}
		
	} else {
		$error_msg = 3;
	}
	
	$username_array = array();
	if($error_msg == '') {
		$query = "SELECT * FROM members WHERE email='$email' AND active=1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result)) {
			while($row = mysql_fetch_array($result)) {
				$user_name = $row['username'];		
				array_push($username_array, "$user_name");
			}
			
			$message ="<strong>會員 $email 您好:</strong><br /><br />以下是您在Mvland註冊的帳號:<br /><br />";
			foreach($username_array as $username) {
				$message .= $username."<br />";
			}
			$message .="<br />如果有任何問題請到我們的<a href=\"http://www.mvland.com/help\">幫助首頁</a>.<br /><br />Mvland客服中心上<br />";
			$message .="<a href=\"http://www.mvland.com/\">http://www.mvland.com/</a>";
			$to = "$email";
			$subject = '您的Mvland帳號'; 
			$headers = "Content-type: text/html\r\n";			
			$headers .= "From: \"Mvland\" <service@mvland.com>";
			mail($to, $subject, $message, $headers); 
			
			$error_msg = 5;				
		} else {
			$error_msg = 4;
		}
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 忘記帳號</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂" />
<meta name="description" content="" />
<script type="text/javascript">
window.onload = function() {
  document.getElementById('email_field').focus();
}
</script>

<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>小幫手</h1>
	<p class="first">
	填入註冊的Email, 我們將會把跟這個Email相關的帳號都寄給你.<br /><br />
	如果你記得帳號卻忘記密碼, 請到<a href="http://www.mvland.com/forgot_password">忘記密碼</a>.
	</p>
</div>

<div id="content">

<?
if(!empty($error_msg)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "您輸入的驗證碼錯誤, 請再試一次";
		break;
	case 2:
		echo "您輸入的Email不正確, 請再試一次";
		break;
	case 3:
		echo "請輸入Email";
		break;	
	case 4:
		echo "找不到您輸入的這個Email的資料喔";
		break;
	case 5:
		echo "帳號已送至您的Email信箱";
		break;						
	}
?> </div> <?
}
?>

<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>忘記帳號</strong></h1>
<div style="margin-bottom: 10px;">請輸入Email和驗證碼, 我們將會把帳號送到您的Email信箱.</div>

<form id="signupForm" method="post" action="http://www.mvland.com/forgot_username">
<table>

	<tr>
		<td>
		<fieldset class="form_blue">
		<legend>Forgot Username</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['email'])) || $error_msg == 2) { ?> <span class="error_text">Email: </span> <? } elseif($error_msg==8) { ?> <span class="error_text">Email: </span> <? } else { ?>Email: <? } ?></td>
				<td class="form_field"><input type="text" name="email" style="width: 155px;" id="email_field" <? if(isset($_POST['submit']) && $error_msg!=5) { $email = $_POST['email']; echo "value=\"$email\""; }?> /></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['security_code']))) { ?> <span class="error_text">驗證碼確認: </span> <? } else { ?>驗證碼確認: <? } ?></td>
				<td class="form_field"><input type="text" id="security_code" name="security_code" style="width: 155px;" /></td>
			</tr>
			<tr>
				<td></td><td><span style="padding-left: 5px;">請輸入以下圖片中的認證碼</span></td>
			</tr>
			<tr>
				<td></td><td><span style="padding-left: 5px;"><img src="CaptchaSecurityImages.php" border="1" /></span></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="left" colspan="2" style="padding-left: 85px;">
					<input type="submit" value="送出" style="width: 100px; height: 30px;"/>
				</td>
			</tr>						
		</table>
			<input type="hidden" name="submit" />
		</div>
		</fieldset>
		</td>
	</tr>

</table>
</form>
<br />	
<? 
include("includes/footer.php")
?>