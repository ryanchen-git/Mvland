<? include("includes/_landing.php"); 

if(isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
} else {
	header("Location: http://www.mvland.com/");
}

$error_msg = '';
if(isset($_POST['submit'])) {
	
	if(!empty($_POST['old_pwd'])) {
		$old_password = trim($_POST['old_pwd']);
		$old_password = md5($old_password);
		
		$query = "SELECT * FROM members WHERE id=$id AND password='$old_password' AND active=1";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 1) {
				
			if(!empty($_POST['pwd'])) {
				$password = trim($_POST['pwd']);
				
				if(!empty($_POST['pwd_confirm'])) {
					$pwd_confirm = trim($_POST['pwd_confirm']);
					
					if($password != $pwd_confirm) {
						$error_msg = 1;
					} 
					
				} else {
					$error_msg = 5;
				}
				
			} else {
				$error_msg = 6;
			}
			
		} else {
			$error_msg = 3;
		}
			
	} else {
		$error_msg = 2;
	}
					
	if($error_msg == '') {
		$password = md5($password);
		$query = "UPDATE members SET password='$password' WHERE id=$id AND active=1";
		$result = mysql_query($query);
		if ($result) {
			header("Location: http://www.mvland.com/my_account?account_edit=pwd_success");
		}
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 我的帳號</title>
<meta name="keywords" content="Mvland, 我的帳號" />
<meta name="description" content="Mvland我的帳號修更改我的密碼" />

<script type="text/javascript">
function form_validator(form) {
	var pwd = form.pwd;
	if(pwd.value.length < 6) {
		alert("密碼必須至少6個字元");
		pwd.focus();
		return false;
	}
	return true;
}

window.onload = function() {
  document.getElementById('current_password').focus();
}
</script>

<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>請注意</h1>
	<p class="first">	
	密碼必須至少6個字元. 請選擇自己容易記住的密碼.
	</p>
</div>
			
<div id="content">

<?
if(!empty($error_msg)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "新密碼兩次輸入不一樣, 請重新輸入";
		break;
	case 2: 
		echo "請輸入現有密碼";
		break;
	case 3: 
		echo "您輸入的現有密碼不正確";
		break;				
	case 5:
		echo "請輸入新密碼確認";
		break;		
	case 6:
		echo "請輸入新密碼";
		break;	
	}	
?> </div> <?
}
?>

<div style="margin-bottom: 30px; margin-top: 20px; font-size: 16px;"><a href="http://www.mvland.com/my_account"><strong>我的帳號</strong></a> | <strong>更改我的密碼</strong></div>

<form id="signupForm" method="post" action="http://www.mvland.com/my_password" onsubmit="return form_validator(this)">
<table>

	<tr>
		<td>
		<fieldset class="form_red">
		<legend>Password Change</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd'])) || ($error_msg==2) || ($error_msg==3)) { ?> <span class="error_text">現有密碼: </span> <? } else { ?>現有密碼: <? } ?></td>
				<td class="form_field"><input type="password" name="old_pwd" id="current_password" /></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd'])) || ($error_msg==1)) { ?> <span class="error_text">新密碼: </span> <? } else { ?>新密碼: <? } ?></td>
				<td class="form_field"><input type="password" name="pwd" /></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd_confirm'])) || ($error_msg==1)) { ?> <span class="error_text">新密碼確認: </span> <? } else { ?>新密碼確認: <? } ?></td>
				<td class="form_field"><input type="password" name="pwd_confirm" /></td>
			</tr>			
			<tr><td height="10"></td></tr>
			<tr>
				<td align="left" colspan="2" style="padding-left: 85px;">
					<input type="submit" value="儲存" style="width: 100px; height: 30px;"/>
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