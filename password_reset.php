<?
include("includes/_landing.php");

$error_msg = '';

if((isset($_GET['user']))&&(isset($_GET['stamp']))) {
	$user = $_GET['user'];
	$stamp = $_GET['stamp'];	
	
	$error_msg = '';
	if(isset($_POST['submit'])) {
		
		if(!empty($_POST['pwd'])) {
			$password = trim($_POST['pwd']);
			
			if(!empty($_POST['pwd_confirm'])) {
				$pwd_confirm = trim($_POST['pwd_confirm']);
				
				if($password != $pwd_confirm) {
					$error_msg = 1;
				} 
				
			} else {
				$error_msg = 2;
			}
			
		} else {
			$error_msg = 3;
		}
													
		if($error_msg == '') {
			$password = md5($password);		
			$decode_time = base64_decode($stamp);
			
			$query = "UPDATE members SET password='$password' WHERE username='$user' AND timestamp=$decode_time";
			$result = mysql_query($query);
			$number_rows = mysql_affected_rows();
			if($number_rows == 1) {
				$error_msg = 4;
			} else {
				$error_msg = 5;
			}
		}
		
	}	
	
} else {
	header("Location: http://www.mvland.com/index");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 會員登入</title>
<meta name="keywords" content="音樂盒" />
<meta name="description" content="" />

<script type="text/javascript">
function form_validator(form) {
	var pwd = form.pwd;
	if(pwd.value.length < 4) {
		alert("密碼至少4個字元");
		pwd.focus();
		return false;
	}
	return true;
}

window.onload = function() {
  document.getElementById('password_field').focus();
}
</script>

<? include("includes/header.php"); ?>
			
<div id="content">

<?
if((!empty($error_msg)) && ($error_msg !=4) && ($error_msg !=5)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "新密碼兩次輸入不一樣, 請重新輸入";
		break;
	case 2:
		echo "請輸入新密碼確認";
		break;		
	case 3:
		echo "請輸入新密碼";
		break;	
	}	
?> </div> <?
}
?>

<table style="width: 155%;"><tr><td>
<?
if ($error_msg == 4) { 
?>	<h1 style="font-size: 16px; padding-bottom: 10px;"><strong><?=$user?>, 您的密碼已經重新設定完成, 請到<a href="http://www.mvland.com/login">登錄首頁</a>進行登錄, 謝謝.</strong></h1> <?
} 
elseif ($error_msg == 5) {
?> <h1 style="font-size: 16px; padding-bottom: 10px;"><strong>抱歉,您的密碼重新設定程序發生錯誤, 請到<a href="http://www.mvland.com/help">幫助首頁</a>查詢, 謝謝.</strong></h1> <?
}
?>
</td></tr></table>

<?
if (($error_msg != 4) && ($error_msg != 5)) {
?>

<div style="margin-bottom: 30px; margin-top: 20px; font-size: 16px;"><strong><?=$user?> 的密碼重新設定</strong></div>

<form id="signupForm" method="post" action="http://www.mvland.com/password_reset?user=<?=$user?>&stamp=<?=$stamp?>" onsubmit="return form_validator(this)">
<table>
	<tr>
		<td>
		<fieldset class="form_red">
		<legend>Password Reset</legend>
			<div>
			<table border="0" width="100%">
				<tr>
					<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd'])) || ($error_msg==1)) { ?> <span class="error_text">新密碼: </span> <? } else { ?>新密碼: <? } ?></td>
					<td class="form_field"><input type="password" name="pwd" id="password_field" /></td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd_confirm'])) || ($error_msg==1)) { ?> <span class="error_text">新密碼確認: </span> <? } else { ?>新密碼確認: <? } ?></td>
					<td class="form_field"><input type="password" name="pwd_confirm" /></td>
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