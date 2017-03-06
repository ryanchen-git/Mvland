<?
include("includes/_landing.php");

$error_msg = '';

if(isset($_POST['submit'])) {

	if(!empty($_POST['username'])) {
		$username = trim($_POST['username']);

		if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
			unset($_SESSION['security_code']);
		} else {
		  $error_msg = 1;
		}
		
	} else {
		$error_msg = 2;
	}
	
	if($error_msg == '') {
		$timestamp = time();
		
		$query = "SELECT * FROM members WHERE username='$username' AND active=1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			$id = $row['id'];
			$email = $row['email'];	
			
			$query = "UPDATE members SET timestamp='$timestamp' WHERE id='$id' AND active=1";
			$result = mysql_query($query);
			if (mysql_affected_rows() == 1) {
				$timestamp_encode = base64_encode($timestamp);
				$url = "http://www.mvland.com/password_reset?user=$username&stamp=$timestamp_encode";
				$message ="<strong>會員 $username 您好:</strong><br /><br />請點選以下的連結來重新設定您的密碼:<br /><br />";
				$message .="<a href=\"$url\">$url</a><br /><br />";		
				$message .="若點選無效, 請將以上連結複製後貼到瀏覽器中.<br />";
				$message .="如果有任何問題請到我們的<a href=\"http://www.mvland.com/help\">幫助首頁</a>.<br /><br />Mvland客服中心上<br />";
				$message .="<a href=\"http://www.mvland.com/\">http://www.mvland.com/</a>";
				$to = "$email";
				$subject = '您的Mvland密碼';
				$headers = "Content-type: text/html\r\n";
				$headers .= "From: \"Mvland\" <service@mvland.com>";
				mail($to, $subject, $message, $headers);
				
				$error_msg = 4;				
			}
		} else {
			$error_msg = 3;	
		}
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 忘記密碼</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂" />
<meta name="description" content="" />
<script type="text/javascript">
window.onload = function() {
  document.getElementById('user_field').focus();
}
</script>

<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>小幫手</h1>
	<p class="first">
	填入你註冊的帳號, 我們將會把重新設定密碼的步驟寄到你的Email信箱.<br /><br />
	如果你記得密碼卻忘記帳號, 請到<a href="http://www.mvland.com/forgot_username">忘記帳號</a>.
	</p>
</div>

<div id="content">

<?
if((!empty($error_msg)) && $error_msg !=4) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "您輸入的驗證碼錯誤, 請再試一次";
		break;
	case 2:
		echo "請輸入帳號";
		break;	
	case 3:
		echo "您輸入的這個帳號錯誤, 請再試一次";
		break;
	}
?> </div> <?
}

if ($error_msg == 4) {
?>	<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>一封信已經送到您註冊的Email信箱, 請到那裡並按照指示進行密碼重新設定, 謝謝.</strong></h1> <?
} else {
?>

<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>忘記密碼</strong></h1>
<div style="margin-bottom: 10px;">請輸入帳號和驗證碼來重新設定您的密碼.</div>

<form id="signupForm" method="post" action="http://www.mvland.com/forgot_password">
<table>

	<tr>
		<td>
		<fieldset class="form_blue">
		<legend>Forgot Password</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['username']))) { ?> <span class="error_text">帳號: </span> <? } elseif($error_msg==9||$error_msg==10) { ?> <span class="error_text">帳號: </span> <? } else { ?>帳號: <? } ?></td>
				<td class="form_field"><input type="text" name="username" id="user_field" style="width: 155px;" <? if(isset($_POST['submit'])) { $username = $_POST['username']; echo "value=\"$username\""; }?> /></td>
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
<?
}
?>
<br />		
<? 
include("includes/footer.php")
?>