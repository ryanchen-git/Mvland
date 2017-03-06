<?
include("includes/_landing.php");

if(!isset($_GET['url'])) {
	header("Location: http://www.mvland.com/");
} else {
	$url = $_GET['url'];
	
	if(!isset($_SESSION['mv'])) { //session not set, add 1
		$_SESSION['mv'] = $url;
		$query = "UPDATE songs SET views=(views+1) WHERE unique_id='$url' AND active=1";
		$result = mysql_query($query);
	} else {
		if($_SESSION['mv'] != $url) { //session set but not in the current page, add 1
			$_SESSION['mv'] = $url;
			$query = "UPDATE songs SET views=(views+1) WHERE unique_id='$url' AND active=1";
			$result = mysql_query($query);	
		} else {
			//echo 'session set but equal to current page, add 0';
		}
	}
	
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE s.unique_id='$url' AND s.active=1
			  AND s.id=p.songs AND m.id=p.members";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
			$song_id = $row['id'];
			$song_name = $row['name'];
			$singer = $row['singer'];
			$type = $row['type'];									
			$mv_url = $row['mv_url'];
			$lyric = $row['lyric'];
			$description = $row['description'];
			$user = $row['username'];
			$comments = $row['comments'];			
			$favorites = $row['favorites'];						
			$views = $row['views'];			
			$date =  $row['date'];	
	} else {
		header("Location: http://www.mvland.com/");
	}
}
	
if(isset($_GET['add'])) {
	$add = $_GET['add'];
	switch($add) {
	case 'comments': 
		$error_msg = 1;
		break;
	case 'favorites': 
		$error_msg = 2;
		break;
	case 'favorites_dup': 
		$error_msg = 3;
		break;	
	case 'flags': 
		$error_msg = 4;
		break;					
	}	
}

if(isset($_POST['add_comments']) && isset($_SESSION['user_id'])) {
	$message = $_POST['message'];
	$user_id = $_SESSION['user_id'];
	$user_name = $_SESSION['user_name'];
			
	$query = "INSERT INTO messages (message, date) VALUE ('$message', NOW())";
	$result = mysql_query($query);
	if($result) {
		$query2 = "SELECT id FROM messages WHERE message = '$message'";
		$result2 = mysql_query($query2);
		if($result2 && mysql_num_rows($result2)) {
			$row = mysql_fetch_array($result2);
				$message_id = $row['id'];
		}
		$query3 = "INSERT INTO members2messages_mapping (members, messages) VALUE ('$user_id', '$message_id')";		//update members2messages_mapping table
		$result3 = mysql_query($query3);
		if($result3) {
			$query4 = "INSERT INTO songs2messages_mapping (songs, messages) VALUE ('$song_id', '$message_id')";		//update songs2messages_mapping table
			$result4 = mysql_query($query4);
			if($result4) {
				$query5 = "UPDATE songs SET comments=(comments+1) WHERE unique_id='$url' AND active=1";		//update comments column in songs table
				$result5 = mysql_query($query5);
				if($result5) {
					header("Location: http://www.mvland.com/watch/mv=$url?add=comments");
				}				
			}
		}
	}
}	

if (isset($_POST['add_favorites']) && isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
	$query = "SELECT * FROM members2favorites_mapping WHERE members='$user_id' AND songs=$song_id AND active=1";
	$result = mysql_query($query);
	if($result && mysql_num_rows($result)) {
		header("Location: http://www.mvland.com/watch/mv=$url&add=favorites_dup");
	} else {
		$query = "INSERT INTO members2favorites_mapping (members, songs, date) VALUE ('$user_id', '$song_id', NOW())";
		$result = mysql_query($query);
		if($result) {
			$query2 = "UPDATE songs SET favorites=(favorites+1) WHERE id=$song_id AND active=1";
			$result2 = mysql_query($query2);
			if($result2) {
				header("Location: http://www.mvland.com/watch/mv=$url&add=favorites");
			}
		}
	}
}

if (isset($_POST['report_flag']) && isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
	$flag_option = $_POST['flag_option'];
	$query = "INSERT INTO flags (members, songs, flags, date) VALUE ('$user_id', '$song_id', '$flag_option', NOW())";
	$result = mysql_query($query);
	if($result) {
		header("Location: http://www.mvland.com/watch/mv=$url&add=flags");
	}
}

$song_url = $url; //Important!! Used for the login redirect, DO NOT DELETE!
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - <?=$singer?> - <?=$song_name?> MV</title>
<meta name="keywords" content="<?=$singer?>, <?=$song_name?>, MV, 音樂錄影帶, 歌詞, 歌手" />
<meta name="description" content="<?=$singer?><?=$song_name?>音樂錄影帶MV及歌詞" />

<script language="javascript" type="text/javascript" src="http://www.mvland.com/common/mv_function.js"></script>

<? include("includes/header.php"); ?>
<div id="sidebar">

<h1><?=$song_name?>歌詞</h1>
<p><?=$lyric?></p>

<?
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE singer='$singer' AND unique_id!='$url' AND s.id=p.songs AND m.id=p.members AND s.active=1 
			  ORDER BY date DESC LIMIT 5";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {

		if($type=='male') { ?><h1>其他<a href="http://www.mvland.com/male/singer=<?=$singer?>"><?=$singer?></a>的MV</h1> <? }		
		if($type=='female') { ?><h1>其他<a href="http://www.mvland.com/female/singer=<?=$singer?>"><?=$singer?></a>的MV</h1> <? }
		if($type=='group') { ?><h1>其他<a href="http://www.mvland.com/group/singer=<?=$singer?>"><?=$singer?></a>的MV</h1> <? }			

		while($row = mysql_fetch_array($result)) {
			$name = $row['name'];
			$type = $row['type'];
			$singer_side = $row['singer'];
			$url_side = $row['unique_id'];
			$user_side = $row['username'];
?> 
		<div style="margin-left: 10px;">
			<a href="http://www.mvland.com/watch/mv=<?=$url_side?>"><?=$name?></a> (<a href="http://www.mvland.com/member/user=<?=$user_side?>"><?=$user_side?></a>)
		</div>
<?		
		}
	}
	
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE singer='$singer' AND unique_id!='$url' AND s.id=p.songs AND m.id=p.members AND s.active=1";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
		$num_rows = mysql_num_rows($result);	
	
		if($num_rows > 5) {
		?> 	<div style="margin-left: 10px; padding-bottom: 10px;"> <?
			if($type=='male') { ?> <a href="http://www.mvland.com/male/singer=<?=$singer_side?>">更多<?=$singer_side?>的MV...</a> <? }		
			if($type=='female') { ?> <a href="http://www.mvland.com/female/singer=<?=$singer_side?>">更多<?=$singer_side?>的MV...</a> <? }
			if($type=='group') { ?> <a href="http://www.mvland.com/group/singer=<?=$singer_side?>">更多<?=$singer_side?>的MV...</a> <? }			
		?>  </div> <?
		} else {
		?> 	<div style="margin-left: 10px; padding-bottom: 10px;"></div> <?
		}
	}	

	$query = "SELECT s.* 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE m.username='$user' AND unique_id!='$url' AND s.id=p.songs AND m.id=p.members AND s.active=1 
			  ORDER BY date DESC LIMIT 5";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
	?> <h1>其他<a href="http://www.mvland.com/member/user=<?=$user?>"><?=$user?></a>提供的MV</h1> <?	
		while($row = mysql_fetch_array($result)) {
			$name = $row['name'];
			$type = $row['type'];
			$singer_side = $row['singer'];
			$url_side = $row['unique_id'];
	?> 
		<div style="margin-left: 10px;">

		<a href="http://www.mvland.com/watch/mv=<?=$url_side?>"><?=$name?></a> - 
		<?
			if($type=='male') { ?> <a href="http://www.mvland.com/male/singer=<?=$singer_side?>"><?=$singer_side?></a> <? }		
			if($type=='female') { ?> <a href="http://www.mvland.com/female/singer=<?=$singer_side?>"><?=$singer_side?></a> <? }
			if($type=='group') { ?> <a href="http://www.mvland.com/group/singer=<?=$singer_side?>"><?=$singer_side?></a> <? }			
		?>
		</div>
	<?		
		}
	}
	
	$query = "SELECT s.* 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE m.username='$user' AND unique_id!='$url' AND s.id=p.songs AND m.id=p.members AND s.active=1";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
		$num_rows = mysql_num_rows($result);
		if($num_rows > 5) {
		?> <div style="margin-left: 10px; padding-bottom: 10px;"><a href="http://www.mvland.com/member/user=<?=$user?>">更多...</a></div> <?
		} else {
		?> <div style="padding-bottom: 10px;"></div> <?
		}
	}
?>

</div>
<div id="content">

<?
if(!empty($error_msg)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "您的意見已經張貼,謝謝";
		break;			
	case 2: 
		echo "此MV已經存入你的最愛";
		break;
	case 3: 
		echo "你的最愛已經有這個MV囉!";
		break;
	case 4: 
		echo "您的舉報已經送出,我們會儘快處理,謝謝!";
		break;		
	}	
?> </div> <?
}
?>
		<h1><?=$singer?> - <?=$song_name?></h1>
		<strong>提供著:</strong> <a href="http://www.mvland.com/member/user=<?=$user?>"><?=$user?></a> | <strong>提供時間:</strong> <?=$date?><br /><br />
		<?=$mv_url?><br /><br />
		<table border="0" width="85%" style="border-left: 1px solid #679C32; border-right: 1px solid #679C32; border-bottom: 1px dotted #679C32; border-top: 1px solid #679C32;">
			<tr>
				<td align="center" style="border-right: 1px solid #679C32; border-left: 1px solid #679C32;" width="140">
				<?
				if(isset($_SESSION['user_id'])) { ?>
					<div id="save_favorite">
						<span id="favorite_link"><a href="Javascript: show_favorite();">存到最愛</a></span>
					</div> <?	
				} else { ?>	
					<a href="Javascript: login_action()">存到最愛</a> <?
				} ?>
				</td>
				<td align="center" style="border-right: 1px solid #679C32; border-left: 1px solid #679C32;" width="140">
				<?
				if(isset($_SESSION['user_id'])) {
					if(($comments != 0) || ($description != '')) { ?>
						<div id="insert_comment">
							<span id="comment_link"><a href="Javascript: show_comment();">發表意見</a></span>
						</div> <?	
					} else { ?> 		
						<a href="http://www.mvland.com/watch/mv=<?=$url?>#comment">發表意見</a> <?
					}
				} else { ?>	
					<a href="Javascript: post_comment()">發表意見</a> <?
				} ?>
				</td>
				<td align="center" style="border-left: 1px solid #679C32; border-right: 1px solid #679C32;" width="140">
				<? 
				if(isset($_SESSION['user_id'])) { ?>
					<div id="share_mv">
						<span id="share_link"><a href="Javascript: share_mv();">轉寄分享</a></span>
					</div> <?	
				} else { ?>	
					<a href="Javascript: login_action()">轉寄分享</a> <?
				} ?>
				</td>				
				<td align="center" style="border-left: 1px solid #679C32; border-right: 1px solid #679C32;" width="140">
				<?
				if(isset($_SESSION['user_id'])) { ?>
					<div id="report_flag">
						<span id="flag_link"><a href="Javascript: show_flag();">舉報問題</a></span>
					</div> <?	
				} else { ?>	
					<a href="Javascript: login_action()">舉報問題</a> <?
				} ?>
				</td>
			</tr>
		</table>
		<div style="padding-top: 5px;"></div>
		<table border="0" width="85%" style="border-left: 1px solid #679C32; border-right: 1px solid #679C32; border-bottom: 1px solid #679C32; border-top: 1px dotted #679C32;">
			<tr>
				<td align="center" style="border-right: 1px solid #679C32; border-left: 1px solid #679C32;" width="140">瀏覽人數: <?=$views?></td>
				<td align="center" style="border-left: 1px solid #679C32; border-right: 1px solid #679C32;" width="140">意見發表: <?=$comments?></td>	
				<td align="center" style="border-left: 1px solid #679C32; border-right: 1px solid #679C32;" width="140">最愛存入: <?=$favorites?></td>																	
			</tr>
		</table>
		
		
		<div id="insert_fav_field"></div>				
		<div id="insert_msg_field"></div>
		<div id="insert_flg_field"></div>		

<br />

<!-- Facebook plugin BEGIN -->
<iframe src="http://www.facebook.com/plugins/like.php?href=http://<? echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:450px; height: 35px;"></iframe>
<!-- Facebook plugin END -->
     
<!-- AddThis Button BEGIN -->
<a href="http://www.addthis.com/bookmark.php" onclick="addthis_url = location.href; addthis_title = document.title; return addthis_click(this);" target="_blank"><img src="http://s9.addthis.com/button1-share.gif" width="125" height="16" border="0" alt="將此MV收入您的網路書籤" /></a> <script type="text/javascript">var addthis_pub = 'ryanchen26';</script><script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script>  
<!-- AddThis Button END -->

<?
		//if($description!='') {
?>			
<!--			<br />
			
			<strong>描述:</strong>
			<table border="0" width="85%" style="border: 1px solid #0099FF; margin-top: 10px;">
				<tr>
					<td style="padding: 5px;"><? //$description ?></td>
				</tr>
			</table>
-->
<?
		//} 
?>

<?
$query = "SELECT DISTINCT m.id, b.username, m.message, m.date
		  FROM members b, messages m, members2messages_mapping p, songs2messages_mapping s 
		  WHERE p.members=b.id AND p.messages=m.id AND s.songs=$song_id AND s.messages=m.id
		  AND b.active=1 AND m.active=1
		  ORDER BY m.date desc";
$result = mysql_query($query);
if ($result && mysql_num_rows($result)) {
?> 		<br /><br /> 
		<strong>意見與回復:</strong>
		<table border="0" width="85%" style="border-top: 1px solid #FFCC00; border-top: 1px solid #FFCC00; border-left: 1px solid #FFCC00; border-right: 1px solid #FFCC00; margin-top: 10px;">
<?
	while($row = mysql_fetch_array($result)) {
		$message_id = $row['id'];
		$message = $row['message'];
		$visitor_name = $row['username'];
		$date = $row['date'];
		$date = substr($date, 0,16);
?> 
<? 		if($user == $user_name) { ?> <form action="http://www.mvland.com/comment_remove" method="post" onsubmit="return (confirm('確定要刪除嗎?'));"> <? } ?>
			<tr>
				<td style="padding-left: 5px; background: #FFFF99;"><a href="http://www.mvland.com/member/user=<?=$visitor_name?>"><?=$visitor_name?></a> (<?=$date?>) <? if($user == $user_name) { ?> 
				</td>
				<td align="right" style="background: #FFFF99;"><input type="hidden" name="message_id" value="<?=$message_id?>" /><input type="hidden" name="song_url" value="<?=$url?>" /><input type="submit" value="刪除" style="width: 50px; height: 20px; font-size: 12px; padding-top: 1px;" /></span><? } ?></td>
			</tr>
			<tr>
				<td colspan="2" style="border-bottom: 1px solid #FFCC00;"><div style="padding: 5px 10px;"><?=$message?></div><div style="padding-top: 15px;"></div></td>
			</tr>
<? 		if($user == $user_name) { ?> </form> <? } ?>			
<?
	}
?> 		</table> <?
}

if (isset($_SESSION['user_id'])) {
?>
<script type="text/javascript">
function validate(form) {
	var comment = form.message;
	if(comment.value == '' || comment.value == null) {
		alert("請輸入意見!");
		comment.focus();
		return false;
	} 
	if(comment.value.length > 500) {
		alert("您的意見必須少於500字以內");
		comment.focus();
		return false;
	}
	return true;
}
</script>
<br /><br />
	<a name="comment"></a>
	<strong>發表意見:</strong>
	
	<form action="http://www.mvland.com/watch/mv=<?=$url?>" method="post" id="message_form" onsubmit="return (validate(this))">
	<table border="0" width="100%">
		<tr>
			<td><textarea name="message" id="message_field" style="width: 85%; height: 90px; border: 1px solid #999999;" /></textarea></td>
		</tr>
		<tr><td height="5"></td></tr>	
		<tr>
			<td><input type="submit" value="送出" style="width: 100px; height: 30px;" /></td>
		</tr>
	</table>
	<input type="hidden" name="add_comments" />
	<div style="display: none">
		<input type="text" id="get_unique_id" value="<?=$url?>" />
	</div>
	</form>
	<br />
<?
} else {
?>	
	<script type="text/javascript">
	function login_action() {
		alert("請先登入後再執行這個動作喔!");
	}
	function post_comment() {
		alert("請先登入後再發表意見喔!");
	}	
	</script>
	<div style="margin: 20px 0;">
		<strong><a href="Javascript: post_comment()">我要發表意見</a></strong>
	</div>	
<?
}
?>
	<div style="clear: both;"></div>
	<div style="margin-top: 20px; text-align: center;">	
		<script type="text/javascript"><!--
		google_ad_client = "pub-5350348298322302";
		/* 728x90, 已建立 2008/6/18 */
		google_ad_slot = "3647045908";
		google_ad_width = 728;
		google_ad_height = 90;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>	
	</div>	
<?
include("includes/footer.php")
?>