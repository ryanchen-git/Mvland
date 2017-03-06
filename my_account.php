<?
include("includes/_landing.php");

//check for user session id
if(!isset($_SESSION['user_id'])) {
	if(isset($_GET['next'])) {
		$next_page = $_GET['next'];
		switch($next_page) {
		case 'account':
			header("Location: http://www.mvland.com/login?next=account");		
			break;					
		} 
	} else {
		header("Location: http://www.mvland.com/login");		
	}
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$query = "SELECT m.*, c.country AS 'country' FROM members m, country c 
		  WHERE m.username = '$user_name' AND m.country=c.abbrv";
$result = mysql_query($query);
if ($result && mysql_num_rows($result)) {
	$row = mysql_fetch_array($result);
	$birth_year = $row['birth_year'];
	$birth_month = $row['birth_month'];
	$birth_day = $row['birth_day'];
	$gender = $row['gender'];
	$age_setting = $row['age'];
	$country = $row['country'];	
	$name = $row['name'];	
	$occupations = $row['occupations'];	
	$schools = $row['schools'];			
	$interests = $row['interests'];			
	$about_me = $row['about'];						
	$date = $row['date'];
	$date = substr($date, 0,10);
  	$last_login	= $row['this_login'];
	$last_login = substr($last_login, 0,10);
}

switch($gender) {
case 'male':
	$gender = "男";
	break;	
case 'female':
	$gender = "女";
	break;		
}	

$current_year = date('Y');
$current_month = date('n');
$current_day = date('j');
if($current_month == $birth_month) {
	if($current_day == $birth_day || $current_day > $birth_day) {
		$age = $current_year - $birth_year;
	} else {
		$age = $current_year - $birth_year - 1;
	}
}
elseif($current_month > $birth_month) {
	$age = $current_year - $birth_year;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 我的帳號</title>
<meta name="keywords" content="Mvland, 我的帳號" />
<meta name="description" content="Mvland我的帳號" />

<style type="text/css">
.song_table {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #666;
}

.song_table caption {
  font-size: 1.2em;
  font-weight: bold;
  margin: 1em 0;
}

.song_table thead {
  background: #ccc url(images/bar.gif) repeat-x left center;
}

.song_table th {
  font-weight: normal;
  text-align: left;
}

.song_table th, .song_table td {
  padding: 0.1em 1em;
  border: 1px dotted #CCCCCC;
}

.song_table tr:hover {
  background-color: #FFFF99;
}

.song_table thead tr:hover {
  background-color: transparent;
  color: inherit;
}

.my_account_box a { 
	display: block; 
	text-decoration: none; 
	border: 1px solid #000; 
	font-weight: bold; 
	font-size: 16px; 
	text-align: center; 
	padding-top: 10px; 
	width: 400px; 
	height: 30px; 
	margin-bottom: 20px; 
}
.my_account_box a:hover { 
	background-color: #FFCC00; 
	color: #000000; 
}  

</style>

<? include("includes/header.php"); ?>

<div id="sidebar">

<h1>我公開的資料 (<a href="http://www.mvland.com/my_profile">修改</a>)</h1>
		<p class="first">
<? 
		if($name != '') {
			echo "姓名: $name<br />";
		}
		echo "性別: $gender<br />";		
		if($age_setting == 1) { 
			echo "年齡: $age<br />";
		}
		if($occupations != '') {
			echo "職業: $occupations<br />";
		}	
		if($schools != '') {
			echo "學校: $schools<br />";
		}		
		if($interests != '') {
			echo "興趣: $interests<br />";
		}		
		echo "國家: $country<br />";
		echo "加入會員日期: $date<br />";
		echo "最近登錄日期: $last_login<br />";
		if($about_me != '') {
			echo "<br />關於我: $about_me<br />";
		}				
?>
</div>
			
<div id="content">

<? 
	if(isset($_GET['account_edit'])) { 
		$account_edit = $_GET['account_edit'];
		if($account_edit == 'act_success') { ?>
			<div class="error_msg">帳號已更新完成</div>
<? 		}
		if($account_edit == 'pwd_success') { ?>
			<div class="error_msg">密碼已更新完成</div>
<?
		}
	}
?>

	<h1>我的帳號 - <?=$user_name?></h1>

	<div style="font-size: 16px; margin-bottom: 10px;"><strong>MV: </strong></div>
	<div class="my_account_box">
		<a href="http://www.mvland.com/my_videos">檢視/編輯 我提供的MV</a>
	</div>
	<div class="my_account_box">
		<a href="http://www.mvland.com/my_favorites">檢視我的最愛MV</a>
	</div>		
	
	<div style="font-size: 16px; margin-bottom: 10px;"><strong>帳號:</strong></div> 
	<div class="my_account_box">
		<a href="http://www.mvland.com/my_profile">修改我的個人資料</a>
	</div>		
	
	<div class="my_account_box">
		<a href="http://www.mvland.com/my_password">更改我的密碼</a>
	</div>
		
<?
include("includes/footer.php")
?>