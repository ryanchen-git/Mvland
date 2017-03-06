<? include("includes/_landing.php"); 

if(isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
	$query = "SELECT * FROM members WHERE id=$id AND active=1";
	$result = mysql_query($query);
	if($result && mysql_num_rows($result)) {
		while($row = mysql_fetch_array($result)) {
			$email_edit = $row['email'];
			$gender_edit = $row['gender'];
			$birth_month_edit = $row['birth_month'];
			$birth_day_edit = $row['birth_day'];
			$birth_year_edit = $row['birth_year'];
			$age_edit = $row['age'];
			$city_edit = $row['city'];
			$country_edit = $row['country'];
			$name_edit = $row['name'];
			$occupations_edit = $row['occupations'];
			$schools_edit = $row['schools'];
			$interests_edit = $row['interests'];	
			$about_edit = $row['about'];								
		}
	}
} else {
	header("Location: http://www.mvland.com/");
}

$error_msg = '';
if(isset($_POST['submit'])) {

	if(!empty($_POST['email'])) {
		$email = trim($_POST['email']);
		
		if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', "$email")) {
		
			if(!empty($_POST['gender'])) {
				$gender = $_POST['gender'];
				$age = $_POST['age'];
				
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
																					
								} else {
									$error_msg = 1;
								}
								
							} else {
								$error_msg = 2;
							}	
						
						} else {
							$error_msg = 3;
						}																					
					
					} else {
						$error_msg = 4;
					}																					
					
				} else {
					$error_msg = 5;
				}																					
										
			} else {
				$error_msg = 6;
			}
			
		} else {
			$error_msg = 7;
		}					
		
	} else {
		$error_msg = 8;
	}
	
	$name = $_POST['name'];
	if(strlen($name)>30) {
		$error_msg = 9;
	}
	$name = htmlspecialchars($name);
	
	$occupations = $_POST['occupations'];
	if(strlen($occupations)>30) {
		$error_msg = 10;
	}
	$occupations = htmlspecialchars($occupations);
	
	$schools = $_POST['schools'];
	if(strlen($schools)>30) {
		$error_msg = 11;
	}	
	$schools = htmlspecialchars($schools);
	
	$interests = $_POST['interests'];
	if(strlen($interests)>50) {
		$error_msg = 12;
	}	
	$interests = htmlspecialchars($interests);
		
	$about_me = $_POST['about_me'];	
	if(strlen($interests)>2500) {
		$error_msg = 13;
	}	
	$about_me = htmlspecialchars($about_me);			
							
	if($error_msg == '') {		
		$query = "UPDATE members SET email='$email', gender='$gender', birth_month='$birthday_month', birth_day='$birthday_day', birth_year='$birthday_year', age='$age', city='$city', country='$country', name='$name', occupations='$occupations', schools='$schools', interests='$interests', about='$about_me' WHERE id='$id' AND active=1";
				  
		$result = mysql_query($query);
		if ($result) {
			header("Location: http://www.mvland.com/my_account?account_edit=act_success");
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
<meta name="description" content="Mvland我的帳號修改我的個人資料" />

<script type="text/javascript" src="script/tooltip.js"></script>

<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>隱私聲明</h1>
	
	<p class="first">
	以下您的資料將不會被公開: <br /><br />
	<strong>Email</strong><br />
	<strong>生日</strong><br />
	<strong>年齡(可選擇公開或不公開)</strong><br />
	<strong>城市</strong><br /><br />
	其餘欄位的資料將會被公開. 如果您不希望其他任何欄位被公開的話, 請不要填寫該欄位(必填欄位除外).
	</p>
</div>

<div id="content">

<?
if(!empty($error_msg)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "請選擇國家";
		break;
	case 2:
		echo "請選擇城市";
		break;
	case 3:
		echo "您輸入的生日年分無效, 請再試一次 (例: 1981)";
		break;		
	case 4:
		echo "請輸入生日年份";
		break;	
	case 5:
		echo "請輸入生日";
		break;	
	case 6:
		echo "請選擇性別";
		break;
	case 7:
		echo "您輸入的Email不正確, 請再試一次";
		break;	
	case 8:
		echo "請輸入Email";
		break;			
	case 9:
		echo "您輸入的姓名長度過長";
		break;	
	case 10:
		echo "您輸入的職業長度過長";
		break;	
	case 11:
		echo "您輸入的學校長度過長";
		break;	
	case 12:
		echo "您輸入的興趣長度過長";
		break;	
	case 13:
		echo "您輸入的關於我長度過長";
		break;	
	}	
?> </div> <?
}
?>

<div style="margin-bottom: 20px; margin-top: 20px; font-size: 16px;"><a href="http://www.mvland.com/my_account"><strong>我的帳號</strong></a> | <strong>修改我的個人資料</strong></div>

<div style="margin-bottom: 10px;">打<span style="color: red;"> * </span>為必填欄位</div>

<form id="signupForm" method="post" action="http://www.mvland.com/my_profile">
<table>

	<tr>
		<td>
		<fieldset class="form_profile">
		<legend>Your Information</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if($error_msg==9) { ?> <span class="error_text">姓名: </span> <? } else { ?>姓名: <? } ?></td>
				<td class="form_field"><input type="text" name="name" style="width: 180px;" <? if(isset($_POST['submit'])) { $name = $_POST['name']; $name = htmlspecialchars($name); echo "value=\"$name\""; } else { echo "value=\"$name_edit\"";} ?> /></td>
			</tr>
			<tr><td height="10"></td></tr>					
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['email']))) { ?> <span class="error_text">* Email: </span> <? } elseif($error_msg==8) { ?> <span class="error_text">Email: </span> <? } else { ?><span style="color: red;">*</span> Email: <? } ?></td>
				<td class="form_field"><input type="text" name="email" style="width: 180px;" <? if(isset($_POST['submit'])) { $email = $_POST['email']; echo "value=\"$email\""; } else { echo "value=\"$email_edit\"";} ?> /></td>
			</tr>
			<tr><td height="10"></td></tr>			
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['gender']))) { ?> <span class="error_text">* 性別: </span> <? } else { ?><span style="color: red;">*</span> 性別: <? } ?></td>
				<td class="form_field">
					<input type="radio" name="gender" value="male" <? if(isset($_POST['gender'])) { $gender = $_POST['gender']; if($gender == 'male') echo " checked"; } else { if($gender_edit == 'male') echo " checked"; } ?> /> 男
					&nbsp;&nbsp;
					<input type="radio" name="gender" value="female"<? if(isset($_POST['gender'])) { $gender = $_POST['gender']; if($gender == 'female') echo " checked"; } else { if($gender_edit == 'female') echo " checked"; } ?> /> 女
				</td>
			</tr>			
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if(((isset($_POST['submit']))&&(empty($_POST['birthday_month']))) || ((isset($_POST['submit']))&&($_POST['birthday_month'] == '---')) || ((isset($_POST['submit']))&&($_POST['birthday_day'] == '---')) || ((isset($_POST['submit']))&&(empty($_POST['birthday_year'])))) { ?> <span class="error_text">* 生日: </span> <? } elseif($error_msg==12 || $error_msg==13 || $error_msg==14) { ?> <span class="error_text">生日: </span> <? } else { ?><span style="color: red;">*</span> 生日: <? } ?></td>
				<td class="form_field">
					<select name="birthday_month" style="width: 45px;">
						<option value="---"<? if(isset($_POST['submit']) && $_POST['birthday_month']=="---") { echo " selected"; } ?>>----</option>
						<option value="1"<? if(isset($_POST['submit']) && $_POST['birthday_month']==1) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==1)) { echo " selected"; } ?>>1</option>
						<option value="2"<? if(isset($_POST['submit']) && $_POST['birthday_month']==2) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==2)) { echo " selected"; } ?>>2</option>
						<option value="3"<? if(isset($_POST['submit']) && $_POST['birthday_month']==3) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==3)) { echo " selected"; } ?>>3</option>
						<option value="4"<? if(isset($_POST['submit']) && $_POST['birthday_month']==4) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==4)) { echo " selected"; } ?>>4</option>
						<option value="5"<? if(isset($_POST['submit']) && $_POST['birthday_month']==5) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==5)) { echo " selected"; } ?>>5</option>
						<option value="6"<? if(isset($_POST['submit']) && $_POST['birthday_month']==6) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==6)) { echo " selected"; } ?>>6</option>
						<option value="7"<? if(isset($_POST['submit']) && $_POST['birthday_month']==7) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==7)) { echo " selected"; } ?>>7</option>
						<option value="8"<? if(isset($_POST['submit']) && $_POST['birthday_month']==8) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==8)) { echo " selected"; } ?>>8</option>
						<option value="9"<? if(isset($_POST['submit']) && $_POST['birthday_month']==9) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==9)) { echo " selected"; } ?>>9</option>
						<option value="10"<? if(isset($_POST['submit']) && $_POST['birthday_month']==10) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==10)) { echo " selected"; } ?>>10</option>
						<option value="11"<? if(isset($_POST['submit']) && $_POST['birthday_month']==11) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==11)) { echo " selected"; } ?>>11</option>
						<option value="12"<? if(isset($_POST['submit']) && $_POST['birthday_month']==12) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_month_edit==12)) { echo " selected"; } ?>>12</option>
					</select> 月
					<select name="birthday_day" style="width: 45px;">
						<option value="---"<? if(isset($_POST['submit']) && $_POST['birthday_day']=="---") { echo " selected"; } ?>>---</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==1) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==1)) { echo " selected"; } ?>>1</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==2) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==2)) { echo " selected"; } ?>>2</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==3) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==3)) { echo " selected"; } ?>>3</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==4) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==4)) { echo " selected"; } ?>>4</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==5) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==5)) { echo " selected"; } ?>>5</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==6) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==6)) { echo " selected"; } ?>>6</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==7) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==7)) { echo " selected"; } ?>>7</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==8) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==8)) { echo " selected"; } ?>>8</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==9) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==9)) { echo " selected"; } ?>>9</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==10) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==10)) { echo " selected"; } ?>>10</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==11) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==11)) { echo " selected"; } ?>>11</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==12) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==12)) { echo " selected"; } ?>>12</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==13) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==13)) { echo " selected"; } ?>>13</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==14) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==14)) { echo " selected"; } ?>>14</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==15) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==15)) { echo " selected"; } ?>>15</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==16) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==16)) { echo " selected"; } ?>>16</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==17) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==17)) { echo " selected"; } ?>>17</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==18) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==18)) { echo " selected"; } ?>>18</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==19) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==19)) { echo " selected"; } ?>>19</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==20) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==20)) { echo " selected"; } ?>>20</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==21) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==21)) { echo " selected"; } ?>>21</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==22) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==22)) { echo " selected"; } ?>>22</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==23) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==23)) { echo " selected"; } ?>>23</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==24) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==24)) { echo " selected"; } ?>>24</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==25) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==25)) { echo " selected"; } ?>>25</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==26) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==26)) { echo " selected"; } ?>>26</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==27) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==27)) { echo " selected"; } ?>>27</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==28) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==28)) { echo " selected"; } ?>>28</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==29) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==29)) { echo " selected"; } ?>>29</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==30) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==30)) { echo " selected"; } ?>>30</option>
						<option<? if(isset($_POST['submit']) && $_POST['birthday_day']==31) { echo " selected"; } if((!isset($_POST['submit'])) && ($birth_day_edit==31)) { echo " selected"; } ?>>31</option>
					</select> 日
					<input type="text" name="birthday_year" style="width: 50px; height: 17px;" maxlength="4" <? if(isset($_POST['submit'])) { $birthday_year = $_POST['birthday_year']; echo "value=\"$birthday_year\""; } else { echo "value=\"$birth_year_edit\""; } ?> /> 年
				</td>
			</tr>
			<tr><td height="10"></td></tr>			
			<tr>
				<td align="right" width="80" valign="top">年齡:</td>
				<td class="form_field">
					<input type="radio" name="age" value="1" <? if(isset($_POST['age'])) { $age = $_POST['age']; if($age == 1) echo " checked"; } else { if($age_edit == 1) echo " checked"; } ?> /> 公開我的年齡
					<br />
					<input type="radio" name="age" value="0"<? if(isset($_POST['age'])) { $age = $_POST['age']; if($age == 0) echo " checked"; } else { if($age_edit == 0) echo " checked"; } ?> /> 不公開我的年齡
				</td>
			</tr>						
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if($error_msg==10) { ?> <span class="error_text">職業: </span> <? } else { ?>職業: <? } ?></td>
				<td class="form_field"><input type="text" name="occupations" style="width: 180px;" <? if(isset($_POST['submit'])) { $occupations = $_POST['occupations']; $occupations = htmlspecialchars($occupations); echo "value=\"$occupations\""; } else { echo "value=\"$occupations_edit\"";} ?> /></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if($error_msg==11) { ?> <span class="error_text">學校: </span> <? } else { ?>學校: <? } ?></td>
				<td class="form_field"><input type="text" name="schools" style="width: 180px;" <? if(isset($_POST['submit'])) { $schools = $_POST['schools']; $schools = htmlspecialchars($schools); echo "value=\"$schools\""; } else { echo "value=\"$schools_edit\"";} ?> /></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if($error_msg==12) { ?> <span class="error_text">興趣: </span> <? } else { ?>興趣: <? } ?></td>
				<td class="form_field"><input type="text" name="interests" style="width: 180px;" <? if(isset($_POST['submit'])) { $interests = $_POST['interests']; $interests = htmlspecialchars($interests); echo "value=\"$interests\""; } else { echo "value=\"$interests_edit\"";} ?> /></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80" valign="top"><? if($error_msg==13) { ?> <span class="error_text">關於我: </span> <? } else { ?>關於我: <? } ?></td>
				<td class="form_field"><textarea name="about_me" style="width: 80%; height: 80px;" /><? if(isset($_POST['submit'])) { $about_me = stripslashes($_POST['about_me']); $about_me = htmlspecialchars($about_me); echo $about_me; } else { echo stripslashes($about_edit); } ?></textarea></td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if(((isset($_POST['submit']))&&(empty($_POST['city']))) || ((isset($_POST['submit']))&&($_POST['city'] == '---'))) { ?> <span class="error_text">城市: </span> <? } elseif($error_msg==15) { ?> <span class="error_text">* 城市: </span> <? } else { ?><span style="color: red;">*</span> 城市: <? } ?></td>
				<td class="form_field">
					<select name="city" style="width: 85px;">
						<option value="---"<? if(isset($_POST['submit']) && $_POST['city']=="---") { echo " selected"; } ?>>---</option>
						<option value=3<? if(isset($_POST['submit']) && $_POST['city']==3) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==3)) { echo " selected"; } ?>>台北市</option>
						<option value=4<? if(isset($_POST['submit']) && $_POST['city']==4) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==4)) { echo " selected"; } ?>>台北縣</option>
						<option value=1<? if(isset($_POST['submit']) && $_POST['city']==1) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==1)) { echo " selected"; } ?>>宜蘭縣</option>
						<option value=7<? if(isset($_POST['submit']) && $_POST['city']==7) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==7)) { echo " selected"; } ?>>新竹市</option>
						<option value=6<? if(isset($_POST['submit']) && $_POST['city']==6) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==6)) { echo " selected"; } ?>>新竹縣</option>
						<option value=5<? if(isset($_POST['submit']) && $_POST['city']==5) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==5)) { echo " selected"; } ?>>桃園縣</option>
						<option value=2<? if(isset($_POST['submit']) && $_POST['city']==2) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==2)) { echo " selected"; } ?>>基隆市</option>
						<option value=9<? if(isset($_POST['submit']) && $_POST['city']==9) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==9)) { echo " selected"; } ?>>台中縣</option>
						<option value=10<? if(isset($_POST['submit']) && $_POST['city']==10) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==10)) { echo " selected"; } ?>>台中市</option>
						<option value=8<? if(isset($_POST['submit']) && $_POST['city']==8) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==8)) { echo " selected"; } ?>>苗栗縣</option>
						<option value=11<? if(isset($_POST['submit']) && $_POST['city']==11) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==11)) { echo " selected"; } ?>>南投縣</option>
						<option value=12<? if(isset($_POST['submit']) && $_POST['city']==12) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==12)) { echo " selected"; } ?>>彰化縣</option>
						<option value=13<? if(isset($_POST['submit']) && $_POST['city']==13) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==13)) { echo " selected"; } ?>>雲林縣</option>
						<option value=15<? if(isset($_POST['submit']) && $_POST['city']==15) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==15)) { echo " selected"; } ?>>嘉義市</option>
						<option value=14<? if(isset($_POST['submit']) && $_POST['city']==14) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==14)) { echo " selected"; } ?>>嘉義縣</option>
						<option value=17<? if(isset($_POST['submit']) && $_POST['city']==17) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==17)) { echo " selected"; } ?>>台南市</option>
						<option value=16<? if(isset($_POST['submit']) && $_POST['city']==16) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==16)) { echo " selected"; } ?>>台南縣</option>
						<option value=19<? if(isset($_POST['submit']) && $_POST['city']==19) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==19)) { echo " selected"; } ?>>高雄市</option>
						<option value=18<? if(isset($_POST['submit']) && $_POST['city']==18) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==18)) { echo " selected"; } ?>>高雄縣</option>
						<option value=25<? if(isset($_POST['submit']) && $_POST['city']==25) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==25)) { echo " selected"; } ?>>連江縣</option>
						<option value=20<? if(isset($_POST['submit']) && $_POST['city']==20) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==20)) { echo " selected"; } ?>>屏東縣</option>
						<option value=21<? if(isset($_POST['submit']) && $_POST['city']==21) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==21)) { echo " selected"; } ?>>台東縣</option>
						<option value=22<? if(isset($_POST['submit']) && $_POST['city']==22) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==22)) { echo " selected"; } ?>>花蓮縣</option>
						<option value=23<? if(isset($_POST['submit']) && $_POST['city']==23) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==23)) { echo " selected"; } ?>>澎湖縣</option>
						<option value=24<? if(isset($_POST['submit']) && $_POST['city']==24) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==24)) { echo " selected"; } ?>>金門縣</option>
						<option value=99<? if(isset($_POST['submit']) && $_POST['city']==99) { echo " selected"; } if((!isset($_POST['submit'])) && ($city_edit==99)) { echo " selected"; } ?>>國外地區</option>					
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="right" width="80"><? if(((isset($_POST['submit']))&&(empty($_POST['country']))) || ((isset($_POST['submit']))&&($_POST['country'] == '---'))) { ?> <span class="error_text">* 國家: </span> <? } elseif($error_msg==2) { ?> <span class="error_text">* 國家: </span> <? } else { ?><span style="color: red;">*</span> 國家: <? } ?></td>
				<td class="form_field">
					<select name="country" style="width: 240px; font-size: 10px;">
						<option value="---"<? if(isset($_POST['submit']) && $_POST['country']=="---") { echo " selected"; } ?>>---</option>
						<option value="AF"<? if(isset($_POST['submit']) && $_POST['country']=="AF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AF')) { echo " selected"; } ?>>Afghanistan</option>
						<option value="AL"<? if(isset($_POST['submit']) && $_POST['country']=="AL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AL')) { echo " selected"; } ?>>Albania</option>
						<option value="DZ"<? if(isset($_POST['submit']) && $_POST['country']=="DZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='DZ')) { echo " selected"; } ?>>Algeria</option>
						<option value="AS"<? if(isset($_POST['submit']) && $_POST['country']=="AS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AS')) { echo " selected"; } ?>>American Samoa</option>
						<option value="AD"<? if(isset($_POST['submit']) && $_POST['country']=="AD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AD')) { echo " selected"; } ?>>Andorra</option>
						<option value="AO"<? if(isset($_POST['submit']) && $_POST['country']=="AO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AO')) { echo " selected"; } ?>>Angola</option>
						<option value="AI"<? if(isset($_POST['submit']) && $_POST['country']=="AI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AI')) { echo " selected"; } ?>>Anguilla</option>
						<option value="AG"<? if(isset($_POST['submit']) && $_POST['country']=="AG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AG')) { echo " selected"; } ?>>Antigua and Barbuda</option>
						<option value="AR"<? if(isset($_POST['submit']) && $_POST['country']=="AR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AR')) { echo " selected"; } ?>>Argentina</option>
						<option value="AM"<? if(isset($_POST['submit']) && $_POST['country']=="AM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AM')) { echo " selected"; } ?>>Armenia</option>
						<option value="AW"<? if(isset($_POST['submit']) && $_POST['country']=="AW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AW')) { echo " selected"; } ?>>Aruba</option>
						<option value="AU"<? if(isset($_POST['submit']) && $_POST['country']=="AU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AU')) { echo " selected"; } ?>>Australia</option>
						<option value="AT"<? if(isset($_POST['submit']) && $_POST['country']=="AT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AT')) { echo " selected"; } ?>>Austria</option>
						<option value="AZ"<? if(isset($_POST['submit']) && $_POST['country']=="AZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AZ')) { echo " selected"; } ?>>Azerbaijan</option>
						<option value="BS"<? if(isset($_POST['submit']) && $_POST['country']=="BS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BS')) { echo " selected"; } ?>>Bahamas</option>
						<option value="BH"<? if(isset($_POST['submit']) && $_POST['country']=="BH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BH')) { echo " selected"; } ?>>Bahrain</option>
						<option value="BD"<? if(isset($_POST['submit']) && $_POST['country']=="BD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BD')) { echo " selected"; } ?>>Bangladesh</option>
						<option value="BB"<? if(isset($_POST['submit']) && $_POST['country']=="BB") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BB')) { echo " selected"; } ?>>Barbados</option>
						<option value="BY"<? if(isset($_POST['submit']) && $_POST['country']=="BY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BY')) { echo " selected"; } ?>>Belarus</option>
						<option value="BE"<? if(isset($_POST['submit']) && $_POST['country']=="BE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BE')) { echo " selected"; } ?>>Belgium</option>
						<option value="BZ"<? if(isset($_POST['submit']) && $_POST['country']=="BZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BZ')) { echo " selected"; } ?>>Belize</option>
						<option value="BJ"<? if(isset($_POST['submit']) && $_POST['country']=="BJ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BJ')) { echo " selected"; } ?>>Benin</option>
						<option value="BM"<? if(isset($_POST['submit']) && $_POST['country']=="BM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BM')) { echo " selected"; } ?>>Bermuda</option>
						<option value="BT"<? if(isset($_POST['submit']) && $_POST['country']=="BT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BT')) { echo " selected"; } ?>>Bhutan</option>
						<option value="BO"<? if(isset($_POST['submit']) && $_POST['country']=="BO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BO')) { echo " selected"; } ?>>Bolivia</option>
						<option value="BA"<? if(isset($_POST['submit']) && $_POST['country']=="BA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BA')) { echo " selected"; } ?>>Bosnia and Herzegovina</option>
						<option value="BW"<? if(isset($_POST['submit']) && $_POST['country']=="BW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BW')) { echo " selected"; } ?>>Botswana</option>
						<option value="BV"<? if(isset($_POST['submit']) && $_POST['country']=="BV") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BV')) { echo " selected"; } ?>>Bouvet Island</option>
						<option value="BR"<? if(isset($_POST['submit']) && $_POST['country']=="BR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BR')) { echo " selected"; } ?>>Brazil</option>
						<option value="IO"<? if(isset($_POST['submit']) && $_POST['country']=="IO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IO')) { echo " selected"; } ?>>British Indian Ocean Territory</option>
						<option value="VG"<? if(isset($_POST['submit']) && $_POST['country']=="VG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VG')) { echo " selected"; } ?>>British Virgin Islands</option>
						<option value="BN"<? if(isset($_POST['submit']) && $_POST['country']=="BN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BN')) { echo " selected"; } ?>>Brunei</option>
						<option value="BG"<? if(isset($_POST['submit']) && $_POST['country']=="BG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BG')) { echo " selected"; } ?>>Bulgaria</option>
						<option value="BF"<? if(isset($_POST['submit']) && $_POST['country']=="BF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BF')) { echo " selected"; } ?>>Burkina Faso</option>
						<option value="BI"<? if(isset($_POST['submit']) && $_POST['country']=="BI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='BI')) { echo " selected"; } ?>>Burundi</option>
						<option value="KH"<? if(isset($_POST['submit']) && $_POST['country']=="KH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KH')) { echo " selected"; } ?>>Cambodia</option>
						<option value="CM"<? if(isset($_POST['submit']) && $_POST['country']=="CM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CM')) { echo " selected"; } ?>>Cameroon</option>
						<option value="CA"<? if(isset($_POST['submit']) && $_POST['country']=="CA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CA')) { echo " selected"; } ?>>Canada</option>
						<option value="CV"<? if(isset($_POST['submit']) && $_POST['country']=="CV") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CV')) { echo " selected"; } ?>>Cape Verde</option>
						<option value="KY"<? if(isset($_POST['submit']) && $_POST['country']=="KY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KY')) { echo " selected"; } ?>>Cayman Islands</option>
						<option value="CF"<? if(isset($_POST['submit']) && $_POST['country']=="CF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CF')) { echo " selected"; } ?>>Central African Republic</option>
						<option value="TD"<? if(isset($_POST['submit']) && $_POST['country']=="TD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TD')) { echo " selected"; } ?>>Chad</option>
						<option value="CL"<? if(isset($_POST['submit']) && $_POST['country']=="CL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CL')) { echo " selected"; } ?>>Chile</option>
						<option value="CN"<? if(isset($_POST['submit']) && $_POST['country']=="CN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CN')) { echo " selected"; } ?>>China</option>
						<option value="CX"<? if(isset($_POST['submit']) && $_POST['country']=="CX") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CX')) { echo " selected"; } ?>>Christmas Island</option>
						<option value="CC"<? if(isset($_POST['submit']) && $_POST['country']=="CC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CC')) { echo " selected"; } ?>>Cocos (Keeling) Islands</option>
						<option value="CO"<? if(isset($_POST['submit']) && $_POST['country']=="CO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CO')) { echo " selected"; } ?>>Colombia</option>
						<option value="KM"<? if(isset($_POST['submit']) && $_POST['country']=="KM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KM')) { echo " selected"; } ?>>Comoros</option>
						<option value="CG"<? if(isset($_POST['submit']) && $_POST['country']=="CG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CG')) { echo " selected"; } ?>>Congo</option>
						<option value="CD"<? if(isset($_POST['submit']) && $_POST['country']=="CD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CD')) { echo " selected"; } ?>>Congo - Democratic Republic of</option>
						<option value="CK"<? if(isset($_POST['submit']) && $_POST['country']=="CK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CK')) { echo " selected"; } ?>>Cook Islands</option>
						<option value="CR"<? if(isset($_POST['submit']) && $_POST['country']=="CR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CR')) { echo " selected"; } ?>>Costa Rica</option>
						<option value="CI"<? if(isset($_POST['submit']) && $_POST['country']=="CI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CI')) { echo " selected"; } ?>>Cote d'Ivoire</option>
						<option value="HR"<? if(isset($_POST['submit']) && $_POST['country']=="HR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='HR')) { echo " selected"; } ?>>Croatia</option>
						<option value="CU"<? if(isset($_POST['submit']) && $_POST['country']=="CU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CU')) { echo " selected"; } ?>>Cuba</option>
						<option value="CY"<? if(isset($_POST['submit']) && $_POST['country']=="CY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CY')) { echo " selected"; } ?>>Cyprus</option>
						<option value="CZ"<? if(isset($_POST['submit']) && $_POST['country']=="CZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CZ')) { echo " selected"; } ?>>Czech Republic</option>
						<option value="DK"<? if(isset($_POST['submit']) && $_POST['country']=="DK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='DK')) { echo " selected"; } ?>>Denmark</option>
						<option value="DJ"<? if(isset($_POST['submit']) && $_POST['country']=="DJ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='DJ')) { echo " selected"; } ?>>Djibouti</option>
						<option value="DM"<? if(isset($_POST['submit']) && $_POST['country']=="DM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='DM')) { echo " selected"; } ?>>Dominica</option>
						<option value="DO"<? if(isset($_POST['submit']) && $_POST['country']=="DO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='DO')) { echo " selected"; } ?>>Dominican Republic</option>
						<option value="TP"<? if(isset($_POST['submit']) && $_POST['country']=="TP") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TP')) { echo " selected"; } ?>>East Timor</option>
						<option value="EC"<? if(isset($_POST['submit']) && $_POST['country']=="EC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='EC')) { echo " selected"; } ?>>Ecuador</option>
						<option value="EG"<? if(isset($_POST['submit']) && $_POST['country']=="EG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='EG')) { echo " selected"; } ?>>Egypt</option>
						<option value="SV"<? if(isset($_POST['submit']) && $_POST['country']=="SV") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SV')) { echo " selected"; } ?>>El Salvador</option>
						<option value="GQ"<? if(isset($_POST['submit']) && $_POST['country']=="GQ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GQ')) { echo " selected"; } ?>>Equitorial Guinea</option>
						<option value="ER"<? if(isset($_POST['submit']) && $_POST['country']=="ER") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ER')) { echo " selected"; } ?>>Eritrea</option>
						<option value="EE"<? if(isset($_POST['submit']) && $_POST['country']=="EE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='EE')) { echo " selected"; } ?>>Estonia</option>
						<option value="ET"<? if(isset($_POST['submit']) && $_POST['country']=="ET") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ET')) { echo " selected"; } ?>>Ethiopia</option>
						<option value="FK"<? if(isset($_POST['submit']) && $_POST['country']=="FK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='FK')) { echo " selected"; } ?>>Falkland Islands (Islas Malvinas)</option>
						<option value="FO"<? if(isset($_POST['submit']) && $_POST['country']=="FO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='FO')) { echo " selected"; } ?>>Faroe Islands</option>
						<option value="FJ"<? if(isset($_POST['submit']) && $_POST['country']=="FJ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='FJ')) { echo " selected"; } ?>>Fiji</option>
						<option value="FI"<? if(isset($_POST['submit']) && $_POST['country']=="FI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='FI')) { echo " selected"; } ?>>Finland</option>
						<option value="FR"<? if(isset($_POST['submit']) && $_POST['country']=="FR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='FR')) { echo " selected"; } ?>>France</option>
						<option value="GF"<? if(isset($_POST['submit']) && $_POST['country']=="GF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GF')) { echo " selected"; } ?>>French Guyana</option>
						<option value="PF"<? if(isset($_POST['submit']) && $_POST['country']=="PF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PF')) { echo " selected"; } ?>>French Polynesia</option>
						<option value="TF"<? if(isset($_POST['submit']) && $_POST['country']=="TF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TF')) { echo " selected"; } ?>>French Southern and Antarctic Lands</option>
						<option value="GA"<? if(isset($_POST['submit']) && $_POST['country']=="GA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GA')) { echo " selected"; } ?>>Gabon</option>
						<option value="GM"<? if(isset($_POST['submit']) && $_POST['country']=="GM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GM')) { echo " selected"; } ?>>Gambia</option>
						<option value="GZ"<? if(isset($_POST['submit']) && $_POST['country']=="GZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GZ')) { echo " selected"; } ?>>Gaza Strip</option>
						<option value="GE"<? if(isset($_POST['submit']) && $_POST['country']=="GE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GE')) { echo " selected"; } ?>>Georgia</option>
						<option value="DE"<? if(isset($_POST['submit']) && $_POST['country']=="DE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='DE')) { echo " selected"; } ?>>Germany</option>
						<option value="GH"<? if(isset($_POST['submit']) && $_POST['country']=="GH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GH')) { echo " selected"; } ?>>Ghana</option>
						<option value="GI"<? if(isset($_POST['submit']) && $_POST['country']=="GI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GI')) { echo " selected"; } ?>>Gibraltar</option>
						<option value="GR"<? if(isset($_POST['submit']) && $_POST['country']=="GR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GR')) { echo " selected"; } ?>>Greece</option>
						<option value="GL"<? if(isset($_POST['submit']) && $_POST['country']=="GL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GL')) { echo " selected"; } ?>>Greenland</option>
						<option value="GD"<? if(isset($_POST['submit']) && $_POST['country']=="GD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GD')) { echo " selected"; } ?>>Grenada</option>
						<option value="GP"<? if(isset($_POST['submit']) && $_POST['country']=="GP") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GP')) { echo " selected"; } ?>>Guadeloupe</option>
						<option value="GU"<? if(isset($_POST['submit']) && $_POST['country']=="GU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GU')) { echo " selected"; } ?>>Guam</option>
						<option value="GT"<? if(isset($_POST['submit']) && $_POST['country']=="GT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GT')) { echo " selected"; } ?>>Guatemala</option>
						<option value="GN"<? if(isset($_POST['submit']) && $_POST['country']=="GN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GN')) { echo " selected"; } ?>>Guinea</option>
						<option value="GW"<? if(isset($_POST['submit']) && $_POST['country']=="GW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GW')) { echo " selected"; } ?>>Guinea-Bissau</option>
						<option value="GY"<? if(isset($_POST['submit']) && $_POST['country']=="GY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GY')) { echo " selected"; } ?>>Guyana</option>
						<option value="HT"<? if(isset($_POST['submit']) && $_POST['country']=="HT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='HT')) { echo " selected"; } ?>>Haiti</option>
						<option value="HM"<? if(isset($_POST['submit']) && $_POST['country']=="HM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='HM')) { echo " selected"; } ?>>Heard Island and McDonald Islands</option>
						<option value="VA"<? if(isset($_POST['submit']) && $_POST['country']=="VA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VA')) { echo " selected"; } ?>>Holy See (Vatican City)</option>
						<option value="HN"<? if(isset($_POST['submit']) && $_POST['country']=="HN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='HN')) { echo " selected"; } ?>>Honduras</option>
						<option value="HK"<? if(isset($_POST['submit']) && $_POST['country']=="HK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='HK')) { echo " selected"; } ?>>Hong Kong</option>
						<option value="HU"<? if(isset($_POST['submit']) && $_POST['country']=="HU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='HU')) { echo " selected"; } ?>>Hungary</option>
						<option value="IS"<? if(isset($_POST['submit']) && $_POST['country']=="IS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IS')) { echo " selected"; } ?>>Iceland</option>
						<option value="IN"<? if(isset($_POST['submit']) && $_POST['country']=="IN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IN')) { echo " selected"; } ?>>India</option>
						<option value="ID"<? if(isset($_POST['submit']) && $_POST['country']=="ID") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ID')) { echo " selected"; } ?>>Indonesia</option>
						<option value="IR"<? if(isset($_POST['submit']) && $_POST['country']=="IR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IR')) { echo " selected"; } ?>>Iran</option>
						<option value="IQ"<? if(isset($_POST['submit']) && $_POST['country']=="IQ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IQ')) { echo " selected"; } ?>>Iraq</option>
						<option value="IE"<? if(isset($_POST['submit']) && $_POST['country']=="IE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IE')) { echo " selected"; } ?>>Ireland</option>
						<option value="IL"<? if(isset($_POST['submit']) && $_POST['country']=="IL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IL')) { echo " selected"; } ?>>Israel</option>
						<option value="IT"<? if(isset($_POST['submit']) && $_POST['country']=="IT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='IT')) { echo " selected"; } ?>>Italy</option>
						<option value="JM"<? if(isset($_POST['submit']) && $_POST['country']=="JM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='JM')) { echo " selected"; } ?>>Jamaica</option>
						<option value="JP"<? if(isset($_POST['submit']) && $_POST['country']=="JP") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='JP')) { echo " selected"; } ?>>Japan</option>
						<option value="JO"<? if(isset($_POST['submit']) && $_POST['country']=="JO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='JO')) { echo " selected"; } ?>>Jordan</option>
						<option value="KZ"<? if(isset($_POST['submit']) && $_POST['country']=="KZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KZ')) { echo " selected"; } ?>>Kazakhstan</option>
						<option value="KE"<? if(isset($_POST['submit']) && $_POST['country']=="KE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KE')) { echo " selected"; } ?>>Kenya</option>
						<option value="KI"<? if(isset($_POST['submit']) && $_POST['country']=="KI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KI')) { echo " selected"; } ?>>Kiribati</option>
						<option value="KW"<? if(isset($_POST['submit']) && $_POST['country']=="KW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KW')) { echo " selected"; } ?>>Kuwait</option>
						<option value="KG"<? if(isset($_POST['submit']) && $_POST['country']=="KG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KG')) { echo " selected"; } ?>>Kyrgyzstan</option>
						<option value="LA"<? if(isset($_POST['submit']) && $_POST['country']=="LA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LA')) { echo " selected"; } ?>>Laos</option>
						<option value="LV"<? if(isset($_POST['submit']) && $_POST['country']=="LV") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LV')) { echo " selected"; } ?>>Latvia</option>
						<option value="LB"<? if(isset($_POST['submit']) && $_POST['country']=="LB") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LB')) { echo " selected"; } ?>>Lebanon</option>
						<option value="LS"<? if(isset($_POST['submit']) && $_POST['country']=="LS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LS')) { echo " selected"; } ?>>Lesotho</option>
						<option value="LR"<? if(isset($_POST['submit']) && $_POST['country']=="LR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LR')) { echo " selected"; } ?>>Liberia</option>
						<option value="LY"<? if(isset($_POST['submit']) && $_POST['country']=="LY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LY')) { echo " selected"; } ?>>Libya</option>
						<option value="LI"<? if(isset($_POST['submit']) && $_POST['country']=="LI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LI')) { echo " selected"; } ?>>Liechtenstein</option>
						<option value="LT"<? if(isset($_POST['submit']) && $_POST['country']=="LT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LT')) { echo " selected"; } ?>>Lithuania</option>
						<option value="LU"<? if(isset($_POST['submit']) && $_POST['country']=="LU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LU')) { echo " selected"; } ?>>Luxembourg</option>
						<option value="MO"<? if(isset($_POST['submit']) && $_POST['country']=="MO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MO')) { echo " selected"; } ?>>Macau</option>
						<option value="MK"<? if(isset($_POST['submit']) && $_POST['country']=="MK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MK')) { echo " selected"; } ?>>Macedonia - The Former Yugoslav Republic of</option>
						<option value="MG"<? if(isset($_POST['submit']) && $_POST['country']=="MG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MG')) { echo " selected"; } ?>>Madagascar</option>
						<option value="MW"<? if(isset($_POST['submit']) && $_POST['country']=="MW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MW')) { echo " selected"; } ?>>Malawi</option>
						<option value="MY"<? if(isset($_POST['submit']) && $_POST['country']=="MY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MY')) { echo " selected"; } ?>>Malaysia</option>
						<option value="MV"<? if(isset($_POST['submit']) && $_POST['country']=="MV") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MV')) { echo " selected"; } ?>>Maldives</option>
						<option value="ML"<? if(isset($_POST['submit']) && $_POST['country']=="ML") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ML')) { echo " selected"; } ?>>Mali</option>
						<option value="MT"<? if(isset($_POST['submit']) && $_POST['country']=="MT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MT')) { echo " selected"; } ?>>Malta</option>
						<option value="MH"<? if(isset($_POST['submit']) && $_POST['country']=="MH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MH')) { echo " selected"; } ?>>Marshall Islands</option>
						<option value="MQ"<? if(isset($_POST['submit']) && $_POST['country']=="MQ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MQ')) { echo " selected"; } ?>>Martinique</option>
						<option value="MR"<? if(isset($_POST['submit']) && $_POST['country']=="MR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MR')) { echo " selected"; } ?>>Mauritania</option>
						<option value="MU"<? if(isset($_POST['submit']) && $_POST['country']=="MU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MU')) { echo " selected"; } ?>>Mauritius</option>
						<option value="YT"<? if(isset($_POST['submit']) && $_POST['country']=="YT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='YT')) { echo " selected"; } ?>>Mayotte</option>
						<option value="MX"<? if(isset($_POST['submit']) && $_POST['country']=="MX") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MX')) { echo " selected"; } ?>>Mexico</option>
						<option value="FM"<? if(isset($_POST['submit']) && $_POST['country']=="FM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='FM')) { echo " selected"; } ?>>Micronesia - Federated States of</option>
						<option value="MD"<? if(isset($_POST['submit']) && $_POST['country']=="MD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MD')) { echo " selected"; } ?>>Moldova</option>
						<option value="MC"<? if(isset($_POST['submit']) && $_POST['country']=="MC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MC')) { echo " selected"; } ?>>Monaco</option>
						<option value="MN"<? if(isset($_POST['submit']) && $_POST['country']=="MN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MN')) { echo " selected"; } ?>>Mongolia</option>
						<option value="MS"<? if(isset($_POST['submit']) && $_POST['country']=="MS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MS')) { echo " selected"; } ?>>Montserrat</option>
						<option value="MA"<? if(isset($_POST['submit']) && $_POST['country']=="MA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MA')) { echo " selected"; } ?>>Morocco</option>
						<option value="MZ"<? if(isset($_POST['submit']) && $_POST['country']=="MZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MZ')) { echo " selected"; } ?>>Mozambique</option>
						<option value="MM"<? if(isset($_POST['submit']) && $_POST['country']=="MM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MM')) { echo " selected"; } ?>>Myanmar</option>
						<option value="NA"<? if(isset($_POST['submit']) && $_POST['country']=="NA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NA')) { echo " selected"; } ?>>Namibia</option>
						<option value="NR"<? if(isset($_POST['submit']) && $_POST['country']=="NR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NR')) { echo " selected"; } ?>>Nauru</option>
						<option value="NP"<? if(isset($_POST['submit']) && $_POST['country']=="NP") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NP')) { echo " selected"; } ?>>Nepal</option>
						<option value="NL"<? if(isset($_POST['submit']) && $_POST['country']=="NL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NL')) { echo " selected"; } ?>>Netherlands</option>
						<option value="AN"<? if(isset($_POST['submit']) && $_POST['country']=="AN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AN')) { echo " selected"; } ?>>Netherlands Antilles</option>
						<option value="NC"<? if(isset($_POST['submit']) && $_POST['country']=="NC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NC')) { echo " selected"; } ?>>New Caledonia</option>
						<option value="NZ"<? if(isset($_POST['submit']) && $_POST['country']=="NZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NZ')) { echo " selected"; } ?>>New Zealand</option>
						<option value="NI"<? if(isset($_POST['submit']) && $_POST['country']=="NI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NI')) { echo " selected"; } ?>>Nicaragua</option>
						<option value="NE"<? if(isset($_POST['submit']) && $_POST['country']=="NE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NE')) { echo " selected"; } ?>>Niger</option>
						<option value="NG"<? if(isset($_POST['submit']) && $_POST['country']=="NG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NG')) { echo " selected"; } ?>>Nigeria</option>
						<option value="NU"<? if(isset($_POST['submit']) && $_POST['country']=="NU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NU')) { echo " selected"; } ?>>Niue</option>
						<option value="NF"<? if(isset($_POST['submit']) && $_POST['country']=="NF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NF')) { echo " selected"; } ?>>Norfolk Island</option>
						<option value="MP"<? if(isset($_POST['submit']) && $_POST['country']=="MP") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='MP')) { echo " selected"; } ?>>Northern Mariana Islands</option>
						<option value="KP"<? if(isset($_POST['submit']) && $_POST['country']=="KP") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KP')) { echo " selected"; } ?>>North Korea</option>
						<option value="NO"<? if(isset($_POST['submit']) && $_POST['country']=="NO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='NO')) { echo " selected"; } ?>>Norway</option>
						<option value="OM"<? if(isset($_POST['submit']) && $_POST['country']=="OM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='OM')) { echo " selected"; } ?>>Oman</option>
						<option value="PK"<? if(isset($_POST['submit']) && $_POST['country']=="PK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PK')) { echo " selected"; } ?>>Pakistan</option>
						<option value="PW"<? if(isset($_POST['submit']) && $_POST['country']=="PW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PW')) { echo " selected"; } ?>>Palau</option>
						<option value="PA"<? if(isset($_POST['submit']) && $_POST['country']=="PA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PA')) { echo " selected"; } ?>>Panama</option>
						<option value="PG"<? if(isset($_POST['submit']) && $_POST['country']=="PG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PG')) { echo " selected"; } ?>>Papua New Guinea</option>
						<option value="PY"<? if(isset($_POST['submit']) && $_POST['country']=="PY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PY')) { echo " selected"; } ?>>Paraguay</option>
						<option value="PE"<? if(isset($_POST['submit']) && $_POST['country']=="PE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PE')) { echo " selected"; } ?>>Peru</option>
						<option value="PH"<? if(isset($_POST['submit']) && $_POST['country']=="PH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PH')) { echo " selected"; } ?>>Philippines</option>
						<option value="PN"<? if(isset($_POST['submit']) && $_POST['country']=="PN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PN')) { echo " selected"; } ?>>Pitcairn Islands</option>
						<option value="PL"<? if(isset($_POST['submit']) && $_POST['country']=="PL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PL')) { echo " selected"; } ?>>Poland</option>
						<option value="PT"<? if(isset($_POST['submit']) && $_POST['country']=="PT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PT')) { echo " selected"; } ?>>Portugal</option>
						<option value="PR"<? if(isset($_POST['submit']) && $_POST['country']=="PR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PR')) { echo " selected"; } ?>>Puerto Rico</option>
						<option value="QA"<? if(isset($_POST['submit']) && $_POST['country']=="QA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='QA')) { echo " selected"; } ?>>Qatar</option>
						<option value="RE"<? if(isset($_POST['submit']) && $_POST['country']=="RE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='RE')) { echo " selected"; } ?>>Reunion</option>
						<option value="RO"<? if(isset($_POST['submit']) && $_POST['country']=="RO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='RO')) { echo " selected"; } ?>>Romania</option>
						<option value="RU"<? if(isset($_POST['submit']) && $_POST['country']=="RU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='RU')) { echo " selected"; } ?>>Russia</option>
						<option value="RW"<? if(isset($_POST['submit']) && $_POST['country']=="RW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='RW')) { echo " selected"; } ?>>Rwanda</option>
						<option value="KN"<? if(isset($_POST['submit']) && $_POST['country']=="KN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KN')) { echo " selected"; } ?>>Saint Kitts and Nevis</option>
						<option value="LC"<? if(isset($_POST['submit']) && $_POST['country']=="LC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LC')) { echo " selected"; } ?>>Saint Lucia</option>
						<option value="VC"<? if(isset($_POST['submit']) && $_POST['country']=="VC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VC')) { echo " selected"; } ?>>Saint Vincent and the Grenadines</option>
						<option value="WS"<? if(isset($_POST['submit']) && $_POST['country']=="WS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='WS')) { echo " selected"; } ?>>Samoa</option>
						<option value="SM"<? if(isset($_POST['submit']) && $_POST['country']=="SM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SM')) { echo " selected"; } ?>>San Marino</option>
						<option value="ST"<? if(isset($_POST['submit']) && $_POST['country']=="ST") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ST')) { echo " selected"; } ?>>Sao Tome and Principe</option>
						<option value="SA"<? if(isset($_POST['submit']) && $_POST['country']=="SA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SA')) { echo " selected"; } ?>>Saudi Arabia</option>
						<option value="SN"<? if(isset($_POST['submit']) && $_POST['country']=="SN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SN')) { echo " selected"; } ?>>Senegal</option>
						<option value="CS"<? if(isset($_POST['submit']) && $_POST['country']=="CS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CS')) { echo " selected"; } ?>>Serbia and Montenegro</option>
						<option value="SC"<? if(isset($_POST['submit']) && $_POST['country']=="SC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SC')) { echo " selected"; } ?>>Seychelles</option>
						<option value="SL"<? if(isset($_POST['submit']) && $_POST['country']=="SL") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SL')) { echo " selected"; } ?>>Sierra Leone</option>
						<option value="SG"<? if(isset($_POST['submit']) && $_POST['country']=="SG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SG')) { echo " selected"; } ?>>Singapore</option>
						<option value="SK"<? if(isset($_POST['submit']) && $_POST['country']=="SK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SK')) { echo " selected"; } ?>>Slovakia</option>
						<option value="SI"<? if(isset($_POST['submit']) && $_POST['country']=="SI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SI')) { echo " selected"; } ?>>Slovenia</option>
						<option value="SB"<? if(isset($_POST['submit']) && $_POST['country']=="SB") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SB')) { echo " selected"; } ?>>Solomon Islands</option>
						<option value="SO"<? if(isset($_POST['submit']) && $_POST['country']=="SO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SO')) { echo " selected"; } ?>>Somalia</option>
						<option value="ZA"<? if(isset($_POST['submit']) && $_POST['country']=="ZA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ZA')) { echo " selected"; } ?>>South Africa</option>
						<option value="GS"<? if(isset($_POST['submit']) && $_POST['country']=="GS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GS')) { echo " selected"; } ?>>South Georgia and the South Sandwich Islands</option>
						<option value="KR"<? if(isset($_POST['submit']) && $_POST['country']=="KR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='KR')) { echo " selected"; } ?>>South Korea</option>
						<option value="ES"<? if(isset($_POST['submit']) && $_POST['country']=="ES") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ES')) { echo " selected"; } ?>>Spain</option>
						<option value="LK"<? if(isset($_POST['submit']) && $_POST['country']=="LK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='LK')) { echo " selected"; } ?>>Sri Lanka</option>
						<option value="SH"<? if(isset($_POST['submit']) && $_POST['country']=="SH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SH')) { echo " selected"; } ?>>St. Helena</option>
						<option value="PM"<? if(isset($_POST['submit']) && $_POST['country']=="PM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PM')) { echo " selected"; } ?>>St. Pierre and Miquelon</option>
						<option value="SD"<? if(isset($_POST['submit']) && $_POST['country']=="SD") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SD')) { echo " selected"; } ?>>Sudan</option>
						<option value="SR"<? if(isset($_POST['submit']) && $_POST['country']=="SR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SR')) { echo " selected"; } ?>>Suriname</option>
						<option value="SJ"<? if(isset($_POST['submit']) && $_POST['country']=="SJ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SJ')) { echo " selected"; } ?>>Svalbard</option>
						<option value="SZ"<? if(isset($_POST['submit']) && $_POST['country']=="SZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SZ')) { echo " selected"; } ?>>Swaziland</option>
						<option value="SE"<? if(isset($_POST['submit']) && $_POST['country']=="SE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SE')) { echo " selected"; } ?>>Sweden</option>
						<option value="CH"<? if(isset($_POST['submit']) && $_POST['country']=="CH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='CH')) { echo " selected"; } ?>>Switzerland</option>
						<option value="SY"<? if(isset($_POST['submit']) && $_POST['country']=="SY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='SY')) { echo " selected"; } ?>>Syria</option>
						<option value="TW"<? if(isset($_POST['submit']) && $_POST['country']=="TW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TW')) { echo " selected"; } ?>>Taiwan</option>
						<option value="TJ"<? if(isset($_POST['submit']) && $_POST['country']=="TJ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TJ')) { echo " selected"; } ?>>Tajikistan</option>
						<option value="TZ"<? if(isset($_POST['submit']) && $_POST['country']=="TZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TZ')) { echo " selected"; } ?>>Tanzania</option>
						<option value="TH"<? if(isset($_POST['submit']) && $_POST['country']=="TH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TH')) { echo " selected"; } ?>>Thailand</option>
						<option value="TG"<? if(isset($_POST['submit']) && $_POST['country']=="TG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TG')) { echo " selected"; } ?>>Togo</option>
						<option value="TK"<? if(isset($_POST['submit']) && $_POST['country']=="TK") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TK')) { echo " selected"; } ?>>Tokelau</option>
						<option value="TO"<? if(isset($_POST['submit']) && $_POST['country']=="TO") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TO')) { echo " selected"; } ?>>Tonga</option>
						<option value="TT"<? if(isset($_POST['submit']) && $_POST['country']=="TT") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TT')) { echo " selected"; } ?>>Trinidad and Tobago</option>
						<option value="TN"<? if(isset($_POST['submit']) && $_POST['country']=="TN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TN')) { echo " selected"; } ?>>Tunisia</option>
						<option value="TR"<? if(isset($_POST['submit']) && $_POST['country']=="TR") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TR')) { echo " selected"; } ?>>Turkey</option>
						<option value="TM"<? if(isset($_POST['submit']) && $_POST['country']=="TM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TM')) { echo " selected"; } ?>>Turkmenistan</option>
						<option value="TC"<? if(isset($_POST['submit']) && $_POST['country']=="TC") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TC')) { echo " selected"; } ?>>Turks and Caicos Islands</option>
						<option value="TV"<? if(isset($_POST['submit']) && $_POST['country']=="TV") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='TV')) { echo " selected"; } ?>>Tuvalu</option>
						<option value="UG"<? if(isset($_POST['submit']) && $_POST['country']=="UG") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='UG')) { echo " selected"; } ?>>Uganda</option>
						<option value="UA"<? if(isset($_POST['submit']) && $_POST['country']=="UA") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='UA')) { echo " selected"; } ?>>Ukraine</option>
						<option value="AE"<? if(isset($_POST['submit']) && $_POST['country']=="AE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='AE')) { echo " selected"; } ?>>United Arab Emirates</option>
						<option value="GB"<? if(isset($_POST['submit']) && $_POST['country']=="GB") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='GB')) { echo " selected"; } ?>>United Kingdom</option>
						<option value="US"<? if(isset($_POST['submit']) && $_POST['country']=="US") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='US')) { echo " selected"; } ?>>United States</option>
						<option value="VI"<? if(isset($_POST['submit']) && $_POST['country']=="VI") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VI')) { echo " selected"; } ?>>United States Virgin Islands</option>
						<option value="UY"<? if(isset($_POST['submit']) && $_POST['country']=="UY") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='UY')) { echo " selected"; } ?>>Uruguay</option>
						<option value="UZ"<? if(isset($_POST['submit']) && $_POST['country']=="UZ") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='UZ')) { echo " selected"; } ?>>Uzbekistan</option>
						<option value="VU"<? if(isset($_POST['submit']) && $_POST['country']=="VU") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VU')) { echo " selected"; } ?>>Vanuatu</option>
						<option value="VE"<? if(isset($_POST['submit']) && $_POST['country']=="VE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VE')) { echo " selected"; } ?>>Venezuela</option>
						<option value="VN"<? if(isset($_POST['submit']) && $_POST['country']=="VN") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='VN')) { echo " selected"; } ?>>Vietnam</option>
						<option value="WF"<? if(isset($_POST['submit']) && $_POST['country']=="WF") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='WF')) { echo " selected"; } ?>>Wallis and Futuna</option>
						<option value="PS"<? if(isset($_POST['submit']) && $_POST['country']=="PS") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='PS')) { echo " selected"; } ?>>West Bank</option>
						<option value="EH"<? if(isset($_POST['submit']) && $_POST['country']=="EH") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='EH')) { echo " selected"; } ?>>Western Sahara</option>
						<option value="YE"<? if(isset($_POST['submit']) && $_POST['country']=="YE") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='YE')) { echo " selected"; } ?>>Yemen</option>
						<option value="ZM"<? if(isset($_POST['submit']) && $_POST['country']=="ZM") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ZM')) { echo " selected"; } ?>>Zambia</option>
						<option value="ZW"<? if(isset($_POST['submit']) && $_POST['country']=="ZW") { echo " selected"; } if((!isset($_POST['submit'])) && ($country_edit=='ZW')) { echo " selected"; } ?>>Zimbabwe</option>
					</select>
				</td>
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