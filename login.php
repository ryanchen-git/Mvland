<?
include("includes/_landing.php");

$error_msg = '';

if(isset($_GET['msg'])) {
	$msg = $_GET['msg'];
	switch($msg) {
	case 'password': 
		$error_msg = 1;
		break;
	case 'account': 
		$error_msg = 2;
		break;
	case 'mismatch': 
		$error_msg = 3;
		break;	
	}	
}

if(isset($_POST['submit'])) {

	if(!empty($_POST['username'])) {
		$username = trim($_POST['username']);
		$_SESSION['username'] = $username;
	
		if(!empty($_POST['pwd'])) {
			$password = trim($_POST['pwd']);			
		} else {
			header("Location: http://www.mvland.com/login?msg=password");	//password field empty
		}		
		
	} else {
		header("Location: http://www.mvland.com/login?msg=account");	//account field empty
	}
	
	if($error_msg == '') {
		$password = md5($password);
		$query = "SELECT * FROM members WHERE username='$username' AND password='$password' AND active=1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
				$user_id = $row['id'];		
				$user_name = $row['username'];
				$this_login = $row['this_login'];
				$last_login = $row['last_login'];
				$_SESSION['user_id'] = $user_id;
				$_SESSION['user_name'] = $user_name;
				
				$ip = $_SERVER['REMOTE_ADDR'];
				$browser = $_SERVER['HTTP_USER_AGENT'];
				$query = "UPDATE members SET this_login=NOW(), last_login='$this_login', login_ip='$ip', login_count=(login_count+1) WHERE id=$user_id";
				$result = mysql_query($query);
				
				$query = "INSERT INTO login_log (user_id, login_time, login_ip, browser) VALUES ('$user_id', NOW(), '$ip', '$browser')";
				$result = mysql_query($query);
				
				if(isset($_GET['next'])) {
					$next_page = $_GET['next'];
					if(($next_page != 'index') && ($next_page != 'provide') && ($next_page != 'account')) {
						$song_url = $next_page;
						//echo $song_url;
							header("Location: http://www.mvland.com/watch/mv=$song_url");		
					} else {
						switch($next_page) {
						case 'index':
							header("Location: http://www.mvland.com/");		
							break;
						case 'provide':
							header("Location: http://www.mvland.com/provide");		
							break;
						case 'account':
							header("Location: http://www.mvland.com/my_account");		
							break;
						}
					}
				} else {
					header("Location: http://www.mvland.com/");		
				}
		} else {
			if(isset($_GET['next'])) {
				$next_page = $_GET['next'];
				if(($next_page != 'index') && ($next_page != 'provide') && ($next_page != 'account')) {
					$song_url = $next_page;
						header("Location: http://www.mvland.com/login?next=account&msg=mismatch");		
				} else {
					switch($next_page) {
					case 'index':
						header("Location: http://www.mvland.com/login?next=index&msg=mismatch");		
						break;
					case 'provide':
						header("Location: http://www.mvland.com/login?next=provide&msg=mismatch");		
						break;
					case 'account':
						header("Location: http://www.mvland.com/login?next=account&msg=mismatch");		
						break;
					}
				}
			} else {
				header("Location: http://www.mvland.com/login??msg=mismatch");		//account and password doesn't match
			}
		}
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 會員登入</title>
<meta name="keywords" content="Mvland, 會員登入" />
<meta name="description" content="Mvland會員登入" />

<script type="text/javascript">
window.onload = function() {
	document.getElementById('user_field').focus();
}
</script>

<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>Mvland是甚麼?</h1>
	<p class="first">Mvland是一個讓大家欣賞及分享音樂錄影帶(MV)的地方, MV就是Music Video的簡稱. Mvland所有的MV都是由網友提供, 但是Mvland本身並不支援讓網友上傳MV的功能, 這裡所有的MV都是藉由YouTube Embded的功能將YouTube裡的Music Videos貼到Mvland上來與大家分享.</p>

	<h1>加入會員後你可以...</h1>
	<ul>
		<li>提供MV</li>
		<li>在任何MV中發表意見</li>
		<li>轉寄MV給朋友分享</li>		
		<li>把喜歡的MV存到最愛裡</li>
		<li>舉報有問題的MV</li>		
	</ul>
</div>

<div id="content">

<?
if(!empty($error_msg)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "請輸入密碼";
		break;
	case 2:
		echo "請輸入帳號";
		break;
	case 3:
		echo "輸入錯誤的帳號或密碼, 請再試一次";
		break;		
	}
?> </div> <?
}
?>

<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>會員登入</strong></h1>
<div style="margin-bottom: 10px;">還不是會員嗎? <a href="http://www.mvland.com/signup">我要註冊</a>! (完全免費又容易)</div>

<form id="signupForm" method="post" action="
<?
	if(isset($_GET['next'])) {
		$next_page = $_GET['next'];
		if(($next_page != 'index') && ($next_page != 'provide') && ($next_page != 'account')) {
			$song_url = $next_page;
				echo "http://www.mvland.com/login?next=$song_url";		
		} else {
			switch($next_page) {
			case 'index':
				echo "http://www.mvland.com/login?next=index";		
				break;
			case 'provide':
				echo "http://www.mvland.com/login?next=provide";	
				break;	
			case 'account':
				echo "http://www.mvland.com/login?next=account";	
				break;							
			} 
		}
	} else {
			echo "http://www.mvland.com/login";
	}
?>
">
<table>

	<tr>
		<td>
		<fieldset class="form_blue">
		<legend>Member Login</legend>
		<div>
			<table border="0" width="100%">
				<tr>
					<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['username'])) || $error_msg == 2 || $error_msg == 3) { ?> 帳號: <? } else { ?>帳號: <? } ?></td>
					<td class="form_field"><input type="text" name="username" id="user_field" <? if(isset($_POST['username'])) { $username = $_POST['username']; echo "value=\"$username\""; } elseif(isset($_SESSION['username'])) { $username = $_SESSION['username']; echo "value=\"$username\""; }?> /></td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd'])) || $error_msg ==1 || $error_msg == 3) { ?> 密碼: <? } else { ?>密碼: <? } ?></td>
					<td class="form_field"><input type="password" name="pwd" /></td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td align="left" colspan="2" style="padding-left: 85px;">
						<input type="submit" value="送出" style="width: 100px; height: 30px;"/>
					</td>
				</tr>						
			</table>
			<input type="hidden" name="submit" />
		
			<div style="padding-left: 90px;"><a href="http://www.mvland.com/forgot_username">忘記帳號</a> | <a href="http://www.mvland.com/forgot_password">忘記密碼</a> | <a href="http://www.mvland.com/signup">會員註冊</a></div>
		
		</div>
		</fieldset>
		</td>
	</tr>

</table>
</form>
			
<? 
include("includes/footer.php")
?>
