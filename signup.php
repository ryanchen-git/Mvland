<? include("includes/_landing.php"); ?>

<?
$error_msg = '';
if(isset($_POST['submit'])) {

	if(!empty($_POST['username'])) {
		$username = trim($_POST['username']);
		
		if(preg_match('/^[A-Za-z0-9]+$/', "$username")) {
		
			$query = "SELECT username FROM members WHERE username='$username'";
			$result = mysql_query($query);
			$num_rows = mysql_num_rows($result);
			if($num_rows==0) {
		
				if(!empty($_POST['pwd'])) {
					$password = trim($_POST['pwd']);
					
					if(!empty($_POST['pwd_confirm'])) {
						$pwd_confirm = trim($_POST['pwd_confirm']);
						
					if($password != $pwd_confirm) {
						$error_msg = 1;
					} else {
						
						if(!empty($_POST['email'])) {
							$email = trim($_POST['email']);
							
							if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', "$email")) {
							
								if(!empty($_POST['gender'])) {
									$gender = $_POST['gender'];
									
									if($_POST['birthday_month']!= '---' && $_POST['birthday_day']!= '---') {
										$birthday_month = $_POST['birthday_month'];
										$birthday_day = $_POST['birthday_day'];
										
										if(!empty($_POST['birthday_year'])) {
											$birthday_year = $_POST['birthday_year'];
											
											if(preg_match('/^[0-9]+$/', "$birthday_year") && strlen($birthday_year)==4) {
											
												if($_POST['city'] != '---') {
													$city = $_POST['city'];
										
													if($_POST['country'] != '---') {
														$country = $_POST['country'];
														
														if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code']))) {
															unset($_SESSION['security_code']);
															
															if($_POST['check_terms'] == true) {
															} else {
																 $error_msg = 16;
															}
															
														} else {
														  $error_msg = 11;
														}
													
													} else {
														$error_msg = 2;
													}
													
												} else {
													$error_msg = 15;
												}	
											
											} else {
												$error_msg = 14;
											}																					
										
										} else {
											$error_msg = 13;
										}																					
										
									} else {
										$error_msg = 12;
									}																					
															
								} else {
									$error_msg = 3;
								}
								
							} else {
								$error_msg = 8;
							}					
							
						} else {
							$error_msg = 4;
						}
						
					}
					} else {
						$error_msg = 5;
					}
					
				} else {
					$error_msg = 6;
				}
				
			} else {
				$error_msg = 10;
			}		
			
		} else {
			$error_msg = 9;
		}
	
	} else {
		$error_msg = 7;
	}	
	
	if($error_msg == '') {
	
		$password = md5($password);			
		$timestamp = time();
		
		$query = "INSERT INTO members (username, password, email, gender, birth_month, birth_day, birth_year, city, country, date, timestamp)
				  VALUES ('$username', '$password', '$email', '$gender', '$birthday_month', '$birthday_day', '$birthday_year', '$city', '$country', NOW(), '$timestamp')";
		$result = mysql_query($query);
		if ($result) {
			$query = "SELECT * FROM members WHERE username='$username' AND timestamp='$timestamp'";
			$result = mysql_query($query);
			if ($result && mysql_num_rows($result)) {
				while($row = mysql_fetch_array($result)) {
					$password = $row['password'];	
					$email = $row['email'];	
				}
				$timestamp_encode = base64_encode($timestamp);
				$url = "http://www.mvland.com/activate?user=$username&stamp=$timestamp_encode";
				$message ="<strong>會員 $username 您好:</strong><br /><br />請點選以下的連結來啟動您的帳號:<br /><br />";
				$message .="<a href=\"$url\">$url</a><br /><br />";
				$message .="若點選無效, 請將以上連結複製後貼到瀏覽器中.<br />";
				$message .="如果有任何問題請到我們的<a href=\"http://www.mvland.com/help\">幫助首頁</a>.<br /><br />Mvland客服中心上<br />";
				$message .="<a href=\"http://www.mvland.com/\">http://www.mvland.com/</a>";
				$to = "$email";
				$subject = 'Mvland帳號啟動連結'; 
				$headers = "Content-type: text/html\r\n";			
				$headers .= "From: \"Mvland\" <service@mvland.com>";
				mail($to, $subject, $message, $headers); 
			
				$error_msg = 17;
			}
		}
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 註冊</title>
<meta name="keywords" content="Mvland, 註冊, 會員, signup" />
<meta name="description" content="Mvland會員註冊" />

<script type="text/javascript" src="script/ajax.js"></script>
<script type="text/javascript" src="script/check_username.js"></script>
<script type="text/javascript" src="script/tooltip.js"></script>

<script type="text/javascript">
function form_validator(form) {
	var username = form.username;
	var pwd = form.pwd;
	if(username.value.length < 4 || username.value.length > 16) {
		alert("帳號必須4~16個字元");
		username.focus();
		return false;
	} 
	if(pwd.value.length < 6) {
		alert("密碼至少6個字元");
		pwd.focus();
		return false;
	}
	return true;
}

function CheckMemberID() {
	var username = document.getElementById("user_field").value;
	
	if(username == '') {
		alert("請輸入帳號");
		username.focus();
	} 	
	else if(username.length < 4 || username.length > 16) {
		alert("帳號必須4~16個字元");
		username.focus();
	}
	else {
		//window.open('http://www.mvland.com/check_account?username='+username,'CheckMemberID','width=350,height=170');
	}
}
</script>

<style>
.forgot_acc_pass {
	text-align: center; 
	padding: 10px 0 5px 0;
} 
.good { background: url(images/good.gif) no-repeat right; height: 18px; width: 27px; }
.bad { background: url(images/bad.gif) no-repeat right; height: 18px; width: 20px; }
</style>

<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>Mvland是甚麼?</h1>
	<p class="first">Mvland是一個讓大家欣賞及分享音樂錄影帶(MV)的地方, MV就是Music Video的簡稱. Mvland所有的MV都是由網友提供, 但是Mvland本身並不支援讓網友上傳MV的功能, 這裡所有的MV都是藉由YouTube Embded的功能將YouTube裡的音樂錄影帶貼到Mvland上來與大家分享.</p>
    
    <h1>會員登錄</h1>
    <form id="signupForm" method="post" action="http://www.mvland.com/login?next=index" style="margin-bottom: 2px;">
    <table>
        <tr>
            <td>
            <div style="border: 1px solid #999999; margin-left: 55px;">
                <div class="signup_form">
                    <table border="0" width="100%">
                        <tr>
                            <td align="right" width="50"><? if((isset($_POST['submit']))&&(empty($_POST['username']))) { ?> <span class="error_text">帳號: </span> <? } else { ?>帳號: <? } ?></td>
                            <td class="form_field"><input type="text" name="username" style="width: 120px;" <? if(isset($_POST['username'])) { $username = $_POST['username']; echo "value=\"$username\""; }?> /></td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        <tr>
                            <td align="right" width="50"><? if((isset($_POST['submit']))&&(empty($_POST['pwd']))) { ?> <span class="error_text">密碼: </span> <? } else { ?>密碼: <? } ?></td>
                            <td class="form_field"><input type="password" name="pwd" style="width: 120px;" /></td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        <tr>
                            <td align="left" colspan="2" style="padding-left: 55px;">
                                <input type="submit" value="登錄" style="width: 90px; height: 25px;"/>
                            </td>
                        </tr>						
                    </table>
                        <input type="hidden" name="submit" />
                        
                    <div class="forgot_acc_pass">
                        <a href="http://www.mvland.com/forgot_username">忘記帳號</a> | <a href="http://www.mvland.com/forgot_password">忘記密碼</a>
                    </div>
                </div> <!-- end signup form div -->
            </div>
            </td>
        </tr>
    </table>
    </form>
    
	<h1>加入會員後你可以...</h1>
	<ul>
		<li>提供MV</li>
		<li>在任何MV首頁中發表意見</li>
		<li>轉寄MV給朋友分享</li>		
		<li>把喜歡的MV存到最愛裡</li>
		<li>舉報有問題的MV</li>		
	</ul>    
</div>
			
<div id="content">

<?
if((!empty($error_msg)) && $error_msg != 17) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "密碼兩次輸入不一樣, 請重新輸入";
		break;
	case 2:
		echo "請選擇國家";
		break;
	case 3:
		echo "請選擇性別";
		break;		
	case 4:
		echo "請輸入Email";
		break;	
	case 5:
		echo "請輸入密碼確認";
		break;		
	case 6:
		echo "請輸入密碼";
		break;	
	case 7:
		echo "請輸入帳號";
		break;	
	case 8:
		echo "您輸入的Email不正確, 請再試一次";
		break;	
	case 9:
		echo "您輸入的帳號不是有效的帳號";
		break;		
	case 10:
		echo "您輸入的帳號已被使用, 請再試一次";
		break;											
	case 11:
		echo "您輸入的驗證碼錯誤, 請再試一次";
		break;
	case 12:
		echo "請輸入生日";
		break;	
	case 13:
		echo "請輸入生日年份";
		break;			
	case 14:
		echo "您輸入的生日年分無效, 請再試一次 (例: 1981)";
		break;	
	case 15:
		echo "請選擇城市";
		break;	
	case 16:
		echo "請同意接受會員條款";
		break;			
	}	
?> </div> <?
}

if ($error_msg == 17) {
?>	<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>您已成功完成Mvland的會員註冊, 請到您的Email信箱進行帳號啟動, 謝謝.</strong></h1> <?
} else {
?>

<h1 style="font-size: 16px; padding-bottom: 10px;"><strong>會員註冊</strong></h1>
<div style="margin-bottom: 10px;">非常簡單並且完全免費</span></div>

<form id="signupForm" method="post" action="http://www.mvland.com/signup" onsubmit="return form_validator(this)">
<table>

	<tr>
		<td>
		<fieldset class="form_red">
		<legend>Account Information</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['username']))) { ?> <span class="error_text">帳號: </span> <? } elseif($error_msg==9||$error_msg==10) { ?> <span class="error_text">帳號: </span> <? } else { ?>帳號: <? } ?></td>
				<td class="form_field"><input tabindex="1" type="text" name="username" id="user_field" style="width: 175px;" <? if(isset($_POST['submit'])) { $username = $_POST['username']; echo "value=\"$username\""; }?> /></td>
			</tr>
            <tr>
            	<td></td><td><span id="check_msg" style="font-size: 12px; padding-left: 5px; color: #CC6666;">帳號至少4個字元, 必須只能包含英文字或數字</span></td>
            </tr>
			<tr><td height="5"></td></tr>
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd']))) { ?> <span class="error_text">密碼: </span> <? } else { ?>密碼: <? } ?></td>
				<td class="form_field"><input tabindex="2" type="password" name="pwd" id="password" /></td>
			</tr>
			<tr>
				<td></td><td><span style="font-size: 12px; padding-left: 5px; color: #CC6666;">密碼至少6個字元</span></td>
			</tr>			
			<tr><td height="5"></td></tr>
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['pwd_confirm']))) { ?> <span class="error_text">密碼確認: </span> <? } else { ?>密碼確認: <? } ?></td>
				<td class="form_field"><input tabindex="3" type="password" name="pwd_confirm" /></td>
			</tr>			
		</table>
		</div>
		</fieldset>
		</td>
	</tr>

	<tr><td height="10"></td></tr>

	<tr>
		<td>
		<fieldset class="form_blue">
		<legend>Your Information</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['email']))) { ?> <span class="error_text">*</span> Emai: <? } elseif($error_msg==8) { ?> <span class="error_text">Email: </span> <? } else { ?>Email: <? } ?></td>
				<td class="form_field"><input tabindex="4" type="text" name="email" style="width: 180px;" <? if(isset($_POST['submit'])) { $email = $_POST['email']; echo "value=\"$email\""; }?> /></td>
			</tr>
			<tr><td height="10"></td></tr>			
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['gender']))) { ?> <span class="error_text">*</span> 性別: <? } else { ?>性別: <? } ?></td>
				<td class="form_field">
					<input tabindex="5" type="radio" name="gender" value="male" <? if(isset($_POST['gender'])) { $gender = $_POST['gender']; if($gender == 'male') echo " checked"; }?> /> 男
					&nbsp;&nbsp;
					<input tabindex="6" type="radio" name="gender" value="female"<? if(isset($_POST['gender'])) { $gender = $_POST['gender']; if($gender == 'female') echo " checked"; }?> /> 女
				</td>
			</tr>			
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if(((isset($_POST['submit']))&&(empty($_POST['birthday_month']))) || ((isset($_POST['submit']))&&($_POST['birthday_month'] == '---')) || ((isset($_POST['submit']))&&($_POST['birthday_day'] == '---')) || ((isset($_POST['submit']))&&(empty($_POST['birthday_year'])))) { ?> <span class="error_text">*</span> 生日: <? } elseif($error_msg==12 || $error_msg==13 || $error_msg==14) { ?> <span class="error_text">生日: </span> <? } else { ?>生日: <? } ?></td>
				<td class="form_field">
					<select tabindex="7" name="birthday_month" style="width: 45px;">
						<option value="---">----</option>
						<option value="1"<? if(isset($_POST['submit']) && $_POST['birthday_month']==1) { echo " selected"; }?>>1</option>
						<option value="2"<? if(isset($_POST['submit']) && $_POST['birthday_month']==2) { echo " selected"; }?>>2</option>
						<option value="3"<? if(isset($_POST['submit']) && $_POST['birthday_month']==3) { echo " selected"; }?>>3</option>
						<option value="4"<? if(isset($_POST['submit']) && $_POST['birthday_month']==4) { echo " selected"; }?>>4</option>
						<option value="5"<? if(isset($_POST['submit']) && $_POST['birthday_month']==5) { echo " selected"; }?>>5</option>
						<option value="6"<? if(isset($_POST['submit']) && $_POST['birthday_month']==6) { echo " selected"; }?>>6</option>
						<option value="7"<? if(isset($_POST['submit']) && $_POST['birthday_month']==7) { echo " selected"; }?>>7</option>
						<option value="8"<? if(isset($_POST['submit']) && $_POST['birthday_month']==8) { echo " selected"; }?>>8</option>
						<option value="9"<? if(isset($_POST['submit']) && $_POST['birthday_month']==9) { echo " selected"; }?>>9</option>
						<option value="10"<? if(isset($_POST['submit']) && $_POST['birthday_month']==10) { echo " selected"; }?>>10</option>
						<option value="11"<? if(isset($_POST['submit']) && $_POST['birthday_month']==11) { echo " selected"; }?>>11</option>
						<option value="12"<? if(isset($_POST['submit']) && $_POST['birthday_month']==12) { echo " selected"; }?>>12</option>
					</select> 月
					<select tabindex="8" name="birthday_day" style="width: 45px;">
						<option value="---">---</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==1) { echo " selected"; }?>>1</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==2) { echo " selected"; }?>>2</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==3) { echo " selected"; }?>>3</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==4) { echo " selected"; }?>>4</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==5) { echo " selected"; }?>>5</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==6) { echo " selected"; }?>>6</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==7) { echo " selected"; }?>>7</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==8) { echo " selected"; }?>>8</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==9) { echo " selected"; }?>>9</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==10) { echo " selected"; }?>>10</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==11) { echo " selected"; }?>>11</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==12) { echo " selected"; }?>>12</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==13) { echo " selected"; }?>>13</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==14) { echo " selected"; }?>>14</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==15) { echo " selected"; }?>>15</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==16) { echo " selected"; }?>>16</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==17) { echo " selected"; }?>>17</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==18) { echo " selected"; }?>>18</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==19) { echo " selected"; }?>>19</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==20) { echo " selected"; }?>>20</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==21) { echo " selected"; }?>>21</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==22) { echo " selected"; }?>>22</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==23) { echo " selected"; }?>>23</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==24) { echo " selected"; }?>>24</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==25) { echo " selected"; }?>>25</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==26) { echo " selected"; }?>>26</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==27) { echo " selected"; }?>>27</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==28) { echo " selected"; }?>>28</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==29) { echo " selected"; }?>>29</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==30) { echo " selected"; }?>>30</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==31) { echo " selected"; }?>>31</option>
					</select> 日
					<input tabindex="9" type="text" name="birthday_year" style="width: 50px; height: 17px;" maxlength="4" <? if(isset($_POST['submit'])) { $birthday_year = $_POST['birthday_year']; echo "value=\"$birthday_year\""; } ?> /> 年(西元)
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if(((isset($_POST['submit']))&&(empty($_POST['city']))) || ((isset($_POST['submit']))&&($_POST['city'] == '---'))) { ?> <span class="error_text">城市: </span> <? } elseif($error_msg==15) { ?> <span class="error_text">城市: </span> <? } else { ?>城市: <? } ?></td>
				<td class="form_field">
					<select tabindex="10" name="city" style="width: 85px;">
						<option value="---">---</option>
						<option value=3<? if(!isset($_POST['submit'])) { echo " selected"; } elseif(isset($_POST['submit']) && $_POST['city']==3) { echo " selected"; }?>>台北市</option>
						<option value=4<? if(isset($_POST['submit']) && $_POST['city']==4) { echo " selected"; }?>>台北縣</option>
						<option value=1<? if(isset($_POST['submit']) && $_POST['city']==1) { echo " selected"; }?>>宜蘭縣</option>
						<option value=7<? if(isset($_POST['submit']) && $_POST['city']==7) { echo " selected"; }?>>新竹市</option>
						<option value=6<? if(isset($_POST['submit']) && $_POST['city']==6) { echo " selected"; }?>>新竹縣</option>
						<option value=5<? if(isset($_POST['submit']) && $_POST['city']==5) { echo " selected"; }?>>桃園縣</option>
						<option value=2<? if(isset($_POST['submit']) && $_POST['city']==2) { echo " selected"; }?>>基隆市</option>
						<option value=9<? if(isset($_POST['submit']) && $_POST['city']==9) { echo " selected"; }?>>台中縣</option>
						<option value=10<? if(isset($_POST['submit']) && $_POST['city']==10) { echo " selected"; }?>>台中市</option>
						<option value=8<? if(isset($_POST['submit']) && $_POST['city']==8) { echo " selected"; }?>>苗栗縣</option>
						<option value=11<? if(isset($_POST['submit']) && $_POST['city']==11) { echo " selected"; }?>>南投縣</option>
						<option value=12<? if(isset($_POST['submit']) && $_POST['city']==12) { echo " selected"; }?>>彰化縣</option>
						<option value=13<? if(isset($_POST['submit']) && $_POST['city']==13) { echo " selected"; }?>>雲林縣</option>
						<option value=15<? if(isset($_POST['submit']) && $_POST['city']==15) { echo " selected"; }?>>嘉義市</option>
						<option value=14<? if(isset($_POST['submit']) && $_POST['city']==14) { echo " selected"; }?>>嘉義縣</option>
						<option value=17<? if(isset($_POST['submit']) && $_POST['city']==17) { echo " selected"; }?>>台南市</option>
						<option value=16<? if(isset($_POST['submit']) && $_POST['city']==16) { echo " selected"; }?>>台南縣</option>
						<option value=19<? if(isset($_POST['submit']) && $_POST['city']==19) { echo " selected"; }?>>高雄市</option>
						<option value=18<? if(isset($_POST['submit']) && $_POST['city']==18) { echo " selected"; }?>>高雄縣</option>
						<option value=25<? if(isset($_POST['submit']) && $_POST['city']==25) { echo " selected"; }?>>連江縣</option>
						<option value=20<? if(isset($_POST['submit']) && $_POST['city']==20) { echo " selected"; }?>>屏東縣</option>
						<option value=21<? if(isset($_POST['submit']) && $_POST['city']==21) { echo " selected"; }?>>台東縣</option>
						<option value=22<? if(isset($_POST['submit']) && $_POST['city']==22) { echo " selected"; }?>>花蓮縣</option>
						<option value=23<? if(isset($_POST['submit']) && $_POST['city']==23) { echo " selected"; }?>>澎湖縣</option>
						<option value=24<? if(isset($_POST['submit']) && $_POST['city']==24) { echo " selected"; }?>>金門縣</option>
						<option value=26<? if(isset($_POST['submit']) && $_POST['city']==26) { echo " selected"; }?>>國外地區</option>					
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if(((isset($_POST['submit']))&&(empty($_POST['country']))) || ((isset($_POST['submit']))&&($_POST['country'] == '---'))) { ?> <span class="error_text">國家: </span> <? } elseif($error_msg==2) { ?> <span class="error_text">國家: </span> <? } else { ?>國家: <? } ?></td>
				<td class="form_field">
					<select tabindex="11" name="country" style="width: 240px; font-size: 10px;">
						<option value="---">---</option>
						<option value="AF"<? if(isset($_POST['submit']) && $_POST['country']=="AF") { echo " selected"; }?>>Afghanistan</option>
						<option value="AL"<? if(isset($_POST['submit']) && $_POST['country']=="AL") { echo " selected"; }?>>Albania</option>
						<option value="DZ"<? if(isset($_POST['submit']) && $_POST['country']=="DZ") { echo " selected"; }?>>Algeria</option>
						<option value="AS"<? if(isset($_POST['submit']) && $_POST['country']=="AS") { echo " selected"; }?>>American Samoa</option>
						<option value="AD"<? if(isset($_POST['submit']) && $_POST['country']=="AD") { echo " selected"; }?>>Andorra</option>
						<option value="AO"<? if(isset($_POST['submit']) && $_POST['country']=="AO") { echo " selected"; }?>>Angola</option>
						<option value="AI"<? if(isset($_POST['submit']) && $_POST['country']=="AI") { echo " selected"; }?>>Anguilla</option>
						<option value="AG"<? if(isset($_POST['submit']) && $_POST['country']=="AG") { echo " selected"; }?>>Antigua and Barbuda</option>
						<option value="AR"<? if(isset($_POST['submit']) && $_POST['country']=="AR") { echo " selected"; }?>>Argentina</option>
						<option value="AM"<? if(isset($_POST['submit']) && $_POST['country']=="AM") { echo " selected"; }?>>Armenia</option>
						<option value="AW"<? if(isset($_POST['submit']) && $_POST['country']=="AW") { echo " selected"; }?>>Aruba</option>
						<option value="AU"<? if(isset($_POST['submit']) && $_POST['country']=="AU") { echo " selected"; }?>>Australia</option>
						<option value="AT"<? if(isset($_POST['submit']) && $_POST['country']=="AT") { echo " selected"; }?>>Austria</option>
						<option value="AZ"<? if(isset($_POST['submit']) && $_POST['country']=="AZ") { echo " selected"; }?>>Azerbaijan</option>
						<option value="BS"<? if(isset($_POST['submit']) && $_POST['country']=="BS") { echo " selected"; }?>>Bahamas</option>
						<option value="BH"<? if(isset($_POST['submit']) && $_POST['country']=="BH") { echo " selected"; }?>>Bahrain</option>
						<option value="BD"<? if(isset($_POST['submit']) && $_POST['country']=="BD") { echo " selected"; }?>>Bangladesh</option>
						<option value="BB"<? if(isset($_POST['submit']) && $_POST['country']=="BB") { echo " selected"; }?>>Barbados</option>
						<option value="BY"<? if(isset($_POST['submit']) && $_POST['country']=="BY") { echo " selected"; }?>>Belarus</option>
						<option value="BE"<? if(isset($_POST['submit']) && $_POST['country']=="BE") { echo " selected"; }?>>Belgium</option>
						<option value="BZ"<? if(isset($_POST['submit']) && $_POST['country']=="BZ") { echo " selected"; }?>>Belize</option>
						<option value="BJ"<? if(isset($_POST['submit']) && $_POST['country']=="BJ") { echo " selected"; }?>>Benin</option>
						<option value="BM"<? if(isset($_POST['submit']) && $_POST['country']=="BM") { echo " selected"; }?>>Bermuda</option>
						<option value="BT"<? if(isset($_POST['submit']) && $_POST['country']=="BT") { echo " selected"; }?>>Bhutan</option>
						<option value="BO"<? if(isset($_POST['submit']) && $_POST['country']=="BO") { echo " selected"; }?>>Bolivia</option>
						<option value="BA"<? if(isset($_POST['submit']) && $_POST['country']=="BA") { echo " selected"; }?>>Bosnia and Herzegovina</option>
						<option value="BW"<? if(isset($_POST['submit']) && $_POST['country']=="BW") { echo " selected"; }?>>Botswana</option>
						<option value="BV"<? if(isset($_POST['submit']) && $_POST['country']=="BV") { echo " selected"; }?>>Bouvet Island</option>
						<option value="BR"<? if(isset($_POST['submit']) && $_POST['country']=="BR") { echo " selected"; }?>>Brazil</option>
						<option value="IO"<? if(isset($_POST['submit']) && $_POST['country']=="IO") { echo " selected"; }?>>British Indian Ocean Territory</option>
						<option value="VG"<? if(isset($_POST['submit']) && $_POST['country']=="VG") { echo " selected"; }?>>British Virgin Islands</option>
						<option value="BN"<? if(isset($_POST['submit']) && $_POST['country']=="BN") { echo " selected"; }?>>Brunei</option>
						<option value="BG"<? if(isset($_POST['submit']) && $_POST['country']=="BG") { echo " selected"; }?>>Bulgaria</option>
						<option value="BF"<? if(isset($_POST['submit']) && $_POST['country']=="BF") { echo " selected"; }?>>Burkina Faso</option>
						<option value="BI"<? if(isset($_POST['submit']) && $_POST['country']=="BI") { echo " selected"; }?>>Burundi</option>
						<option value="KH"<? if(isset($_POST['submit']) && $_POST['country']=="KH") { echo " selected"; }?>>Cambodia</option>
						<option value="CM"<? if(isset($_POST['submit']) && $_POST['country']=="CM") { echo " selected"; }?>>Cameroon</option>
						<option value="CA"<? if(isset($_POST['submit']) && $_POST['country']=="CA") { echo " selected"; }?>>Canada</option>
						<option value="CV"<? if(isset($_POST['submit']) && $_POST['country']=="CV") { echo " selected"; }?>>Cape Verde</option>
						<option value="KY"<? if(isset($_POST['submit']) && $_POST['country']=="KY") { echo " selected"; }?>>Cayman Islands</option>
						<option value="CF"<? if(isset($_POST['submit']) && $_POST['country']=="CF") { echo " selected"; }?>>Central African Republic</option>
						<option value="TD"<? if(isset($_POST['submit']) && $_POST['country']=="TD") { echo " selected"; }?>>Chad</option>
						<option value="CL"<? if(isset($_POST['submit']) && $_POST['country']=="CL") { echo " selected"; }?>>Chile</option>
						<option value="CN"<? if(isset($_POST['submit']) && $_POST['country']=="CN") { echo " selected"; }?>>China</option>
						<option value="CX"<? if(isset($_POST['submit']) && $_POST['country']=="CX") { echo " selected"; }?>>Christmas Island</option>
						<option value="CC"<? if(isset($_POST['submit']) && $_POST['country']=="CC") { echo " selected"; }?>>Cocos (Keeling) Islands</option>
						<option value="CO"<? if(isset($_POST['submit']) && $_POST['country']=="CO") { echo " selected"; }?>>Colombia</option>
						<option value="KM"<? if(isset($_POST['submit']) && $_POST['country']=="KM") { echo " selected"; }?>>Comoros</option>
						<option value="CG"<? if(isset($_POST['submit']) && $_POST['country']=="CG") { echo " selected"; }?>>Congo</option>
						<option value="CD"<? if(isset($_POST['submit']) && $_POST['country']=="CD") { echo " selected"; }?>>Congo - Democratic Republic of</option>
						<option value="CK"<? if(isset($_POST['submit']) && $_POST['country']=="CK") { echo " selected"; }?>>Cook Islands</option>
						<option value="CR"<? if(isset($_POST['submit']) && $_POST['country']=="CR") { echo " selected"; }?>>Costa Rica</option>
						<option value="CI"<? if(isset($_POST['submit']) && $_POST['country']=="CI") { echo " selected"; }?>>Cote d'Ivoire</option>
						<option value="HR"<? if(isset($_POST['submit']) && $_POST['country']=="HR") { echo " selected"; }?>>Croatia</option>
						<option value="CU"<? if(isset($_POST['submit']) && $_POST['country']=="CU") { echo " selected"; }?>>Cuba</option>
						<option value="CY"<? if(isset($_POST['submit']) && $_POST['country']=="CY") { echo " selected"; }?>>Cyprus</option>
						<option value="CZ"<? if(isset($_POST['submit']) && $_POST['country']=="CZ") { echo " selected"; }?>>Czech Republic</option>
						<option value="DK"<? if(isset($_POST['submit']) && $_POST['country']=="DK") { echo " selected"; }?>>Denmark</option>
						<option value="DJ"<? if(isset($_POST['submit']) && $_POST['country']=="DJ") { echo " selected"; }?>>Djibouti</option>
						<option value="DM"<? if(isset($_POST['submit']) && $_POST['country']=="DM") { echo " selected"; }?>>Dominica</option>
						<option value="DO"<? if(isset($_POST['submit']) && $_POST['country']=="DO") { echo " selected"; }?>>Dominican Republic</option>
						<option value="TP"<? if(isset($_POST['submit']) && $_POST['country']=="TP") { echo " selected"; }?>>East Timor</option>
						<option value="EC"<? if(isset($_POST['submit']) && $_POST['country']=="EC") { echo " selected"; }?>>Ecuador</option>
						<option value="EG"<? if(isset($_POST['submit']) && $_POST['country']=="EG") { echo " selected"; }?>>Egypt</option>
						<option value="SV"<? if(isset($_POST['submit']) && $_POST['country']=="SV") { echo " selected"; }?>>El Salvador</option>
						<option value="GQ"<? if(isset($_POST['submit']) && $_POST['country']=="GQ") { echo " selected"; }?>>Equitorial Guinea</option>
						<option value="ER"<? if(isset($_POST['submit']) && $_POST['country']=="ER") { echo " selected"; }?>>Eritrea</option>
						<option value="EE"<? if(isset($_POST['submit']) && $_POST['country']=="EE") { echo " selected"; }?>>Estonia</option>
						<option value="ET"<? if(isset($_POST['submit']) && $_POST['country']=="ET") { echo " selected"; }?>>Ethiopia</option>
						<option value="FK"<? if(isset($_POST['submit']) && $_POST['country']=="FK") { echo " selected"; }?>>Falkland Islands (Islas Malvinas)</option>
						<option value="FO"<? if(isset($_POST['submit']) && $_POST['country']=="FO") { echo " selected"; }?>>Faroe Islands</option>
						<option value="FJ"<? if(isset($_POST['submit']) && $_POST['country']=="FJ") { echo " selected"; }?>>Fiji</option>
						<option value="FI"<? if(isset($_POST['submit']) && $_POST['country']=="FI") { echo " selected"; }?>>Finland</option>
						<option value="FR"<? if(isset($_POST['submit']) && $_POST['country']=="FR") { echo " selected"; }?>>France</option>
						<option value="GF"<? if(isset($_POST['submit']) && $_POST['country']=="GF") { echo " selected"; }?>>French Guyana</option>
						<option value="PF"<? if(isset($_POST['submit']) && $_POST['country']=="PF") { echo " selected"; }?>>French Polynesia</option>
						<option value="TF"<? if(isset($_POST['submit']) && $_POST['country']=="TF") { echo " selected"; }?>>French Southern and Antarctic Lands</option>
						<option value="GA"<? if(isset($_POST['submit']) && $_POST['country']=="GA") { echo " selected"; }?>>Gabon</option>
						<option value="GM"<? if(isset($_POST['submit']) && $_POST['country']=="GM") { echo " selected"; }?>>Gambia</option>
						<option value="GZ"<? if(isset($_POST['submit']) && $_POST['country']=="GZ") { echo " selected"; }?>>Gaza Strip</option>
						<option value="GE"<? if(isset($_POST['submit']) && $_POST['country']=="GE") { echo " selected"; }?>>Georgia</option>
						<option value="DE"<? if(isset($_POST['submit']) && $_POST['country']=="DE") { echo " selected"; }?>>Germany</option>
						<option value="GH"<? if(isset($_POST['submit']) && $_POST['country']=="GH") { echo " selected"; }?>>Ghana</option>
						<option value="GI"<? if(isset($_POST['submit']) && $_POST['country']=="GI") { echo " selected"; }?>>Gibraltar</option>
						<option value="GR"<? if(isset($_POST['submit']) && $_POST['country']=="GR") { echo " selected"; }?>>Greece</option>
						<option value="GL"<? if(isset($_POST['submit']) && $_POST['country']=="GL") { echo " selected"; }?>>Greenland</option>
						<option value="GD"<? if(isset($_POST['submit']) && $_POST['country']=="GD") { echo " selected"; }?>>Grenada</option>
						<option value="GP"<? if(isset($_POST['submit']) && $_POST['country']=="GP") { echo " selected"; }?>>Guadeloupe</option>
						<option value="GU"<? if(isset($_POST['submit']) && $_POST['country']=="GU") { echo " selected"; }?>>Guam</option>
						<option value="GT"<? if(isset($_POST['submit']) && $_POST['country']=="GT") { echo " selected"; }?>>Guatemala</option>
						<option value="GN"<? if(isset($_POST['submit']) && $_POST['country']=="GN") { echo " selected"; }?>>Guinea</option>
						<option value="GW"<? if(isset($_POST['submit']) && $_POST['country']=="GW") { echo " selected"; }?>>Guinea-Bissau</option>
						<option value="GY"<? if(isset($_POST['submit']) && $_POST['country']=="GY") { echo " selected"; }?>>Guyana</option>
						<option value="HT"<? if(isset($_POST['submit']) && $_POST['country']=="HT") { echo " selected"; }?>>Haiti</option>
						<option value="HM"<? if(isset($_POST['submit']) && $_POST['country']=="HM") { echo " selected"; }?>>Heard Island and McDonald Islands</option>
						<option value="VA"<? if(isset($_POST['submit']) && $_POST['country']=="VA") { echo " selected"; }?>>Holy See (Vatican City)</option>
						<option value="HN"<? if(isset($_POST['submit']) && $_POST['country']=="HN") { echo " selected"; }?>>Honduras</option>
						<option value="HK"<? if(isset($_POST['submit']) && $_POST['country']=="HK") { echo " selected"; }?>>Hong Kong</option>
						<option value="HU"<? if(isset($_POST['submit']) && $_POST['country']=="HU") { echo " selected"; }?>>Hungary</option>
						<option value="IS"<? if(isset($_POST['submit']) && $_POST['country']=="IS") { echo " selected"; }?>>Iceland</option>
						<option value="IN"<? if(isset($_POST['submit']) && $_POST['country']=="IN") { echo " selected"; }?>>India</option>
						<option value="ID"<? if(isset($_POST['submit']) && $_POST['country']=="ID") { echo " selected"; }?>>Indonesia</option>
						<option value="IR"<? if(isset($_POST['submit']) && $_POST['country']=="IR") { echo " selected"; }?>>Iran</option>
						<option value="IQ"<? if(isset($_POST['submit']) && $_POST['country']=="IQ") { echo " selected"; }?>>Iraq</option>
						<option value="IE"<? if(isset($_POST['submit']) && $_POST['country']=="IE") { echo " selected"; }?>>Ireland</option>
						<option value="IL"<? if(isset($_POST['submit']) && $_POST['country']=="IL") { echo " selected"; }?>>Israel</option>
						<option value="IT"<? if(isset($_POST['submit']) && $_POST['country']=="IT") { echo " selected"; }?>>Italy</option>
						<option value="JM"<? if(isset($_POST['submit']) && $_POST['country']=="JM") { echo " selected"; }?>>Jamaica</option>
						<option value="JP"<? if(isset($_POST['submit']) && $_POST['country']=="JP") { echo " selected"; }?>>Japan</option>
						<option value="JO"<? if(isset($_POST['submit']) && $_POST['country']=="JO") { echo " selected"; }?>>Jordan</option>
						<option value="KZ"<? if(isset($_POST['submit']) && $_POST['country']=="KZ") { echo " selected"; }?>>Kazakhstan</option>
						<option value="KE"<? if(isset($_POST['submit']) && $_POST['country']=="KE") { echo " selected"; }?>>Kenya</option>
						<option value="KI"<? if(isset($_POST['submit']) && $_POST['country']=="KI") { echo " selected"; }?>>Kiribati</option>
						<option value="KW"<? if(isset($_POST['submit']) && $_POST['country']=="KW") { echo " selected"; }?>>Kuwait</option>
						<option value="KG"<? if(isset($_POST['submit']) && $_POST['country']=="KG") { echo " selected"; }?>>Kyrgyzstan</option>
						<option value="LA"<? if(isset($_POST['submit']) && $_POST['country']=="LA") { echo " selected"; }?>>Laos</option>
						<option value="LV"<? if(isset($_POST['submit']) && $_POST['country']=="LV") { echo " selected"; }?>>Latvia</option>
						<option value="LB"<? if(isset($_POST['submit']) && $_POST['country']=="LB") { echo " selected"; }?>>Lebanon</option>
						<option value="LS"<? if(isset($_POST['submit']) && $_POST['country']=="LS") { echo " selected"; }?>>Lesotho</option>
						<option value="LR"<? if(isset($_POST['submit']) && $_POST['country']=="LR") { echo " selected"; }?>>Liberia</option>
						<option value="LY"<? if(isset($_POST['submit']) && $_POST['country']=="LY") { echo " selected"; }?>>Libya</option>
						<option value="LI"<? if(isset($_POST['submit']) && $_POST['country']=="LI") { echo " selected"; }?>>Liechtenstein</option>
						<option value="LT"<? if(isset($_POST['submit']) && $_POST['country']=="LT") { echo " selected"; }?>>Lithuania</option>
						<option value="LU"<? if(isset($_POST['submit']) && $_POST['country']=="LU") { echo " selected"; }?>>Luxembourg</option>
						<option value="MO"<? if(isset($_POST['submit']) && $_POST['country']=="MO") { echo " selected"; }?>>Macau</option>
						<option value="MK"<? if(isset($_POST['submit']) && $_POST['country']=="MK") { echo " selected"; }?>>Macedonia - The Former Yugoslav Republic of</option>
						<option value="MG"<? if(isset($_POST['submit']) && $_POST['country']=="MG") { echo " selected"; }?>>Madagascar</option>
						<option value="MW"<? if(isset($_POST['submit']) && $_POST['country']=="MW") { echo " selected"; }?>>Malawi</option>
						<option value="MY"<? if(isset($_POST['submit']) && $_POST['country']=="MY") { echo " selected"; }?>>Malaysia</option>
						<option value="MV"<? if(isset($_POST['submit']) && $_POST['country']=="MV") { echo " selected"; }?>>Maldives</option>
						<option value="ML"<? if(isset($_POST['submit']) && $_POST['country']=="ML") { echo " selected"; }?>>Mali</option>
						<option value="MT"<? if(isset($_POST['submit']) && $_POST['country']=="MT") { echo " selected"; }?>>Malta</option>
						<option value="MH"<? if(isset($_POST['submit']) && $_POST['country']=="MH") { echo " selected"; }?>>Marshall Islands</option>
						<option value="MQ"<? if(isset($_POST['submit']) && $_POST['country']=="MQ") { echo " selected"; }?>>Martinique</option>
						<option value="MR"<? if(isset($_POST['submit']) && $_POST['country']=="MR") { echo " selected"; }?>>Mauritania</option>
						<option value="MU"<? if(isset($_POST['submit']) && $_POST['country']=="MU") { echo " selected"; }?>>Mauritius</option>
						<option value="YT"<? if(isset($_POST['submit']) && $_POST['country']=="YT") { echo " selected"; }?>>Mayotte</option>
						<option value="MX"<? if(isset($_POST['submit']) && $_POST['country']=="MX") { echo " selected"; }?>>Mexico</option>
						<option value="FM"<? if(isset($_POST['submit']) && $_POST['country']=="FM") { echo " selected"; }?>>Micronesia - Federated States of</option>
						<option value="MD"<? if(isset($_POST['submit']) && $_POST['country']=="MD") { echo " selected"; }?>>Moldova</option>
						<option value="MC"<? if(isset($_POST['submit']) && $_POST['country']=="MC") { echo " selected"; }?>>Monaco</option>
						<option value="MN"<? if(isset($_POST['submit']) && $_POST['country']=="MN") { echo " selected"; }?>>Mongolia</option>
						<option value="MS"<? if(isset($_POST['submit']) && $_POST['country']=="MS") { echo " selected"; }?>>Montserrat</option>
						<option value="MA"<? if(isset($_POST['submit']) && $_POST['country']=="MA") { echo " selected"; }?>>Morocco</option>
						<option value="MZ"<? if(isset($_POST['submit']) && $_POST['country']=="MZ") { echo " selected"; }?>>Mozambique</option>
						<option value="MM"<? if(isset($_POST['submit']) && $_POST['country']=="MM") { echo " selected"; }?>>Myanmar</option>
						<option value="NA"<? if(isset($_POST['submit']) && $_POST['country']=="NA") { echo " selected"; }?>>Namibia</option>
						<option value="NR"<? if(isset($_POST['submit']) && $_POST['country']=="NR") { echo " selected"; }?>>Nauru</option>
						<option value="NP"<? if(isset($_POST['submit']) && $_POST['country']=="NP") { echo " selected"; }?>>Nepal</option>
						<option value="NL"<? if(isset($_POST['submit']) && $_POST['country']=="NL") { echo " selected"; }?>>Netherlands</option>
						<option value="AN"<? if(isset($_POST['submit']) && $_POST['country']=="AN") { echo " selected"; }?>>Netherlands Antilles</option>
						<option value="NC"<? if(isset($_POST['submit']) && $_POST['country']=="NC") { echo " selected"; }?>>New Caledonia</option>
						<option value="NZ"<? if(isset($_POST['submit']) && $_POST['country']=="NZ") { echo " selected"; }?>>New Zealand</option>
						<option value="NI"<? if(isset($_POST['submit']) && $_POST['country']=="NI") { echo " selected"; }?>>Nicaragua</option>
						<option value="NE"<? if(isset($_POST['submit']) && $_POST['country']=="NE") { echo " selected"; }?>>Niger</option>
						<option value="NG"<? if(isset($_POST['submit']) && $_POST['country']=="NG") { echo " selected"; }?>>Nigeria</option>
						<option value="NU"<? if(isset($_POST['submit']) && $_POST['country']=="NU") { echo " selected"; }?>>Niue</option>
						<option value="NF"<? if(isset($_POST['submit']) && $_POST['country']=="NF") { echo " selected"; }?>>Norfolk Island</option>
						<option value="MP"<? if(isset($_POST['submit']) && $_POST['country']=="MP") { echo " selected"; }?>>Northern Mariana Islands</option>
						<option value="KP"<? if(isset($_POST['submit']) && $_POST['country']=="KP") { echo " selected"; }?>>North Korea</option>
						<option value="NO"<? if(isset($_POST['submit']) && $_POST['country']=="NO") { echo " selected"; }?>>Norway</option>
						<option value="OM"<? if(isset($_POST['submit']) && $_POST['country']=="OM") { echo " selected"; }?>>Oman</option>
						<option value="PK"<? if(isset($_POST['submit']) && $_POST['country']=="PK") { echo " selected"; }?>>Pakistan</option>
						<option value="PW"<? if(isset($_POST['submit']) && $_POST['country']=="PW") { echo " selected"; }?>>Palau</option>
						<option value="PA"<? if(isset($_POST['submit']) && $_POST['country']=="PA") { echo " selected"; }?>>Panama</option>
						<option value="PG"<? if(isset($_POST['submit']) && $_POST['country']=="PG") { echo " selected"; }?>>Papua New Guinea</option>
						<option value="PY"<? if(isset($_POST['submit']) && $_POST['country']=="PY") { echo " selected"; }?>>Paraguay</option>
						<option value="PE"<? if(isset($_POST['submit']) && $_POST['country']=="PE") { echo " selected"; }?>>Peru</option>
						<option value="PH"<? if(isset($_POST['submit']) && $_POST['country']=="PH") { echo " selected"; }?>>Philippines</option>
						<option value="PN"<? if(isset($_POST['submit']) && $_POST['country']=="PN") { echo " selected"; }?>>Pitcairn Islands</option>
						<option value="PL"<? if(isset($_POST['submit']) && $_POST['country']=="PL") { echo " selected"; }?>>Poland</option>
						<option value="PT"<? if(isset($_POST['submit']) && $_POST['country']=="PT") { echo " selected"; }?>>Portugal</option>
						<option value="PR"<? if(isset($_POST['submit']) && $_POST['country']=="PR") { echo " selected"; }?>>Puerto Rico</option>
						<option value="QA"<? if(isset($_POST['submit']) && $_POST['country']=="QA") { echo " selected"; }?>>Qatar</option>
						<option value="RE"<? if(isset($_POST['submit']) && $_POST['country']=="RE") { echo " selected"; }?>>Reunion</option>
						<option value="RO"<? if(isset($_POST['submit']) && $_POST['country']=="RO") { echo " selected"; }?>>Romania</option>
						<option value="RU"<? if(isset($_POST['submit']) && $_POST['country']=="RU") { echo " selected"; }?>>Russia</option>
						<option value="RW"<? if(isset($_POST['submit']) && $_POST['country']=="RW") { echo " selected"; }?>>Rwanda</option>
						<option value="KN"<? if(isset($_POST['submit']) && $_POST['country']=="KN") { echo " selected"; }?>>Saint Kitts and Nevis</option>
						<option value="LC"<? if(isset($_POST['submit']) && $_POST['country']=="LC") { echo " selected"; }?>>Saint Lucia</option>
						<option value="VC"<? if(isset($_POST['submit']) && $_POST['country']=="VC") { echo " selected"; }?>>Saint Vincent and the Grenadines</option>
						<option value="WS"<? if(isset($_POST['submit']) && $_POST['country']=="WS") { echo " selected"; }?>>Samoa</option>
						<option value="SM"<? if(isset($_POST['submit']) && $_POST['country']=="SM") { echo " selected"; }?>>San Marino</option>
						<option value="ST"<? if(isset($_POST['submit']) && $_POST['country']=="ST") { echo " selected"; }?>>Sao Tome and Principe</option>
						<option value="SA"<? if(isset($_POST['submit']) && $_POST['country']=="SA") { echo " selected"; }?>>Saudi Arabia</option>
						<option value="SN"<? if(isset($_POST['submit']) && $_POST['country']=="SN") { echo " selected"; }?>>Senegal</option>
						<option value="CS"<? if(isset($_POST['submit']) && $_POST['country']=="CS") { echo " selected"; }?>>Serbia and Montenegro</option>
						<option value="SC"<? if(isset($_POST['submit']) && $_POST['country']=="SC") { echo " selected"; }?>>Seychelles</option>
						<option value="SL"<? if(isset($_POST['submit']) && $_POST['country']=="SL") { echo " selected"; }?>>Sierra Leone</option>
						<option value="SG"<? if(isset($_POST['submit']) && $_POST['country']=="SG") { echo " selected"; }?>>Singapore</option>
						<option value="SK"<? if(isset($_POST['submit']) && $_POST['country']=="SK") { echo " selected"; }?>>Slovakia</option>
						<option value="SI"<? if(isset($_POST['submit']) && $_POST['country']=="SI") { echo " selected"; }?>>Slovenia</option>
						<option value="SB"<? if(isset($_POST['submit']) && $_POST['country']=="SB") { echo " selected"; }?>>Solomon Islands</option>
						<option value="SO"<? if(isset($_POST['submit']) && $_POST['country']=="SO") { echo " selected"; }?>>Somalia</option>
						<option value="ZA"<? if(isset($_POST['submit']) && $_POST['country']=="ZA") { echo " selected"; }?>>South Africa</option>
						<option value="GS"<? if(isset($_POST['submit']) && $_POST['country']=="GS") { echo " selected"; }?>>South Georgia and the South Sandwich Islands</option>
						<option value="KR"<? if(isset($_POST['submit']) && $_POST['country']=="KR") { echo " selected"; }?>>South Korea</option>
						<option value="ES"<? if(isset($_POST['submit']) && $_POST['country']=="ES") { echo " selected"; }?>>Spain</option>
						<option value="LK"<? if(isset($_POST['submit']) && $_POST['country']=="LK") { echo " selected"; }?>>Sri Lanka</option>
						<option value="SH"<? if(isset($_POST['submit']) && $_POST['country']=="SH") { echo " selected"; }?>>St. Helena</option>
						<option value="PM"<? if(isset($_POST['submit']) && $_POST['country']=="PM") { echo " selected"; }?>>St. Pierre and Miquelon</option>
						<option value="SD"<? if(isset($_POST['submit']) && $_POST['country']=="SD") { echo " selected"; }?>>Sudan</option>
						<option value="SR"<? if(isset($_POST['submit']) && $_POST['country']=="SR") { echo " selected"; }?>>Suriname</option>
						<option value="SJ"<? if(isset($_POST['submit']) && $_POST['country']=="SJ") { echo " selected"; }?>>Svalbard</option>
						<option value="SZ"<? if(isset($_POST['submit']) && $_POST['country']=="SZ") { echo " selected"; }?>>Swaziland</option>
						<option value="SE"<? if(isset($_POST['submit']) && $_POST['country']=="SE") { echo " selected"; }?>>Sweden</option>
						<option value="CH"<? if(isset($_POST['submit']) && $_POST['country']=="CH") { echo " selected"; }?>>Switzerland</option>
						<option value="SY"<? if(isset($_POST['submit']) && $_POST['country']=="SY") { echo " selected"; }?>>Syria</option>
						<option value="TW"<? if(!isset($_POST['submit'])) { echo " selected"; } elseif(isset($_POST['submit']) && $_POST['country']=="TW") { echo " selected"; }?>>Taiwan</option>
						<option value="TJ"<? if(isset($_POST['submit']) && $_POST['country']=="TJ") { echo " selected"; }?>>Tajikistan</option>
						<option value="TZ"<? if(isset($_POST['submit']) && $_POST['country']=="TZ") { echo " selected"; }?>>Tanzania</option>
						<option value="TH"<? if(isset($_POST['submit']) && $_POST['country']=="TH") { echo " selected"; }?>>Thailand</option>
						<option value="TG"<? if(isset($_POST['submit']) && $_POST['country']=="TG") { echo " selected"; }?>>Togo</option>
						<option value="TK"<? if(isset($_POST['submit']) && $_POST['country']=="TK") { echo " selected"; }?>>Tokelau</option>
						<option value="TO"<? if(isset($_POST['submit']) && $_POST['country']=="TO") { echo " selected"; }?>>Tonga</option>
						<option value="TT"<? if(isset($_POST['submit']) && $_POST['country']=="TT") { echo " selected"; }?>>Trinidad and Tobago</option>
						<option value="TN"<? if(isset($_POST['submit']) && $_POST['country']=="TN") { echo " selected"; }?>>Tunisia</option>
						<option value="TR"<? if(isset($_POST['submit']) && $_POST['country']=="TR") { echo " selected"; }?>>Turkey</option>
						<option value="TM"<? if(isset($_POST['submit']) && $_POST['country']=="TM") { echo " selected"; }?>>Turkmenistan</option>
						<option value="TC"<? if(isset($_POST['submit']) && $_POST['country']=="TC") { echo " selected"; }?>>Turks and Caicos Islands</option>
						<option value="TV"<? if(isset($_POST['submit']) && $_POST['country']=="TV") { echo " selected"; }?>>Tuvalu</option>
						<option value="UG"<? if(isset($_POST['submit']) && $_POST['country']=="UG") { echo " selected"; }?>>Uganda</option>
						<option value="UA"<? if(isset($_POST['submit']) && $_POST['country']=="UA") { echo " selected"; }?>>Ukraine</option>
						<option value="AE"<? if(isset($_POST['submit']) && $_POST['country']=="AE") { echo " selected"; }?>>United Arab Emirates</option>
						<option value="GB"<? if(isset($_POST['submit']) && $_POST['country']=="GB") { echo " selected"; }?>>United Kingdom</option>
						<option value="US"<? if(isset($_POST['submit']) && $_POST['country']=="US") { echo " selected"; }?>>United States</option>
						<option value="VI"<? if(isset($_POST['submit']) && $_POST['country']=="VI") { echo " selected"; }?>>United States Virgin Islands</option>
						<option value="UY"<? if(isset($_POST['submit']) && $_POST['country']=="UY") { echo " selected"; }?>>Uruguay</option>
						<option value="UZ"<? if(isset($_POST['submit']) && $_POST['country']=="UZ") { echo " selected"; }?>>Uzbekistan</option>
						<option value="VU"<? if(isset($_POST['submit']) && $_POST['country']=="VU") { echo " selected"; }?>>Vanuatu</option>
						<option value="VE"<? if(isset($_POST['submit']) && $_POST['country']=="VE") { echo " selected"; }?>>Venezuela</option>
						<option value="VN"<? if(isset($_POST['submit']) && $_POST['country']=="VN") { echo " selected"; }?>>Vietnam</option>
						<option value="WF"<? if(isset($_POST['submit']) && $_POST['country']=="WF") { echo " selected"; }?>>Wallis and Futuna</option>
						<option value="PS"<? if(isset($_POST['submit']) && $_POST['country']=="PS") { echo " selected"; }?>>West Bank</option>
						<option value="EH"<? if(isset($_POST['submit']) && $_POST['country']=="EH") { echo " selected"; }?>>Western Sahara</option>
						<option value="YE"<? if(isset($_POST['submit']) && $_POST['country']=="YZ") { echo " selected"; }?>>Yemen</option>
						<option value="ZM"<? if(isset($_POST['submit']) && $_POST['country']=="ZM") { echo " selected"; }?>>Zambia</option>
						<option value="ZW"<? if(isset($_POST['submit']) && $_POST['country']=="ZW") { echo " selected"; }?>>Zimbabwe</option>
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['security_code']))) { ?> <span class="error_text">*</span> 驗證碼確認: <? } else { ?>驗證碼確認: <? } ?></td>
				<td class="form_field"><input tabindex="12" type="text" id="security_code" name="security_code" style="width: 116px;" /></td>
			</tr>
			<tr>
				<td></td><td><span style="padding-left: 5px;">請輸入以下圖片中的認證碼</span></td>
			</tr>
			<tr>
				<td></td><td><span style="padding-left: 5px;"><img src="CaptchaSecurityImages.php" border="1" /></span></td>
			</tr>
		</table>
		</div>
		</fieldset>
		</td>
	</tr>
	
	<tr><td height="10"></td></tr>	
		
	<tr>
		<td>
		<fieldset class="form_yellow">
		<legend>Submit</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="center">
					<input type="checkbox" name="check_terms" /> 我已詳細閱讀並同意接受<a href="http://www.mvland.com/terms" target="_blank">服務條款</a><br /><br />
					<input tabindex="13" type="submit" value="送出" style="width: 100px; height: 30px;"/>
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