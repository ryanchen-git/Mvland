<?
include("includes/_landing.php");

//check for user session id
if(!isset($_SESSION['user_id'])) {
	if(isset($_GET['next'])) {
		$next_page = $_GET['next'];
		switch($next_page) {
		case 'provide':
			header("Location: http://www.mvland.com/login?next=provide");		
			break;					
		} 
	} else {
		header("Location: http://www.mvland.com/login");		
	}
} else {
	$user_id = $_SESSION['user_id'];
}

//check for GET song parameter
if(isset($_GET['song'])) {

	$song_url = $_GET['song'];

	//check for song id
	$query = "SELECT * FROM songs WHERE unique_id='$song_url'";
	$result = mysql_query($query);
	if($result && mysql_num_rows($result)) {
		$row = mysql_fetch_array($result);
			$song_id = $row['id'];
			$edit_song_name = $row['name'];
			$edit_singer = $row['singer'];
			$edit_song_type = $row['type'];		
			$edit_mv_url = $row['mv_url'];
			$edit_lyric = $row['lyric'];
			$edit_lyric = preg_replace("/<br \/>/", "", $edit_lyric);		
			$edit_song_desc = $row['description'];
	} else {
		header("Location: http://www.mvland.com/");
	}
	
	$query = "SELECT s.*, m.* FROM songs s, members2songs_mapping m WHERE s.id=m.songs AND s.active=1 AND members=$user_id AND songs=$song_id";
	$result = mysql_query($query);
		if($result && mysql_num_rows($result)) {
		} else {
			header("Location: http://www.mvland.com/");
		}	
}

	if(isset($_GET['edit'])) { 
		$edit = $_GET['edit'];
		if($edit == 'success') { 
			header("Location: http://www.mvland.com/my_videos?edit=success");
		}
	}

$error_msg = '';
if(isset($_POST['submit'])) {

	if(!empty($_POST['singer'])) {
		$singer = trim($_POST['singer']);
		if(preg_match('/^.*<a href=/', "$singer")) {
			$error_msg = 11;
		}
		$singer = htmlspecialchars($singer);
	
		if(!empty($_POST['song_name'])) {
			$song_name = trim($_POST['song_name']);
			if(preg_match('/^.*<a href=/', "$song_name")) {
				$error_msg = 11;
			}
			$song_name = htmlspecialchars($song_name);
					
			if(!empty($_POST['singer_group'])) {
				$singer_group = $_POST['singer_group'];
			
				if(!empty($_POST['mv_url'])) {
					$mv_url = trim($_POST['mv_url']);
					$mv_url = stripslashes($mv_url);				
					//if(preg_match('/<object width="425" height="344"><param name="movie" value="http:\/\/www.youtube.com\/v\/([A-Za-z0-9-_|&|=]){17,}"><\/param><param name="allowFullScreen" value="true"><\/param><embed src="http:\/\/www.youtube.com\/v\/([A-Za-z0-9-_|&|=]){17,}" type="application\/x-shockwave-flash" allowfullscreen="true" width="425" height="344"><\/embed><\/object>/', "$mv_url")) {
					if(!empty($mv_url)) {
				
						if(!empty($_POST['song_lyric'])) {
							$song_lyric = trim($_POST['song_lyric']);
							$song_lyric = htmlspecialchars($song_lyric);
								
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
	
	/*
	if(!empty($_POST['song_desc'])) {
		$song_desc = trim($_POST['song_desc']);	
		if(preg_match('/^.*<a href=/', "$song_desc")) {
			$error_msg = 11;
		}
		$song_desc = htmlspecialchars($song_desc);
	} else {
		$song_desc = '';
	}
	*/
	
	if(strlen($song_name)>50) {
		$error_msg = 7;
	}	
	if(strlen($singer)>50) {
		$error_msg = 8;
	}	
	if(strlen($song_lyric)>2500) {
		$error_msg = 9;
	}	
	/*if(strlen($song_desc)>2500) {
		$error_msg = 10;
	}*/		
		
	if($error_msg == '') {
		$song_lyric = nl2br($song_lyric);
		//$song_desc = nl2br($song_desc);
		$unique_id = uniqid();
		$combine1 = $singer . $song_name;
		$combine2 = $song_name . $singer;
		/*
		$_SESSION['song_name'] = $_POST['song_name'];
		$_SESSION['singer'] = $_POST['singer'];
		$_SESSION['singer_group'] = $_POST['singer_group'];
		$_SESSION['mv_url'] = $_POST['mv_url'];
		$_SESSION['song_lyric'] = $_POST['song_lyric'];		
		$_SESSION['song_desc'] = $_POST['song_desc'];		
		*/
		if(isset($_GET['song'])) {
			$query = "UPDATE songs SET name='$song_name', singer='$singer', type='$singer_group', mv_url='$mv_url', lyric='$song_lyric', combine1='$combine1', combine2='$combine2', modify_date=NOW() WHERE unique_id='$song_url'";
			$result = mysql_query($query);
			if ($result) {
				header("Location: http://www.mvland.com/provide?edit=success");
			}
		} else {
			$query = "INSERT INTO songs (name, singer, type, mv_url, lyric, combine1, combine2, unique_id, date)
					  VALUES ('$song_name', '$singer', '$singer_group', '$mv_url', '$song_lyric', '$combine1', '$combine2', '$unique_id', NOW())";
			$result = mysql_query($query);
			if ($result) {
				$query2 = "SELECT id FROM songs WHERE unique_id='$unique_id' AND active=1";
				$result2 = mysql_query($query2);
				if ($result2 && mysql_num_rows($result2)) {
					$row = mysql_fetch_array($result2);
						$song_id = $row['id'];
				}
				$query3 = "INSERT INTO members2songs_mapping (members, songs) VALUES ('$user_id', '$song_id')";
				$result3 = mysql_query($query3);
				if ($result3) {
					$query4 = "UPDATE members SET song_provide=(song_provide+1) WHERE id=$user_id AND active=1";
					$result4 = mysql_query($query4);
					if(result4) {
						header("Location: http://www.mvland.com/my_videos?provide=sucess");
					}
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - <? if(isset($_GET['song'])) { echo "MV歌曲編輯 - $edit_song_name - $edit_singer"; } else { echo "MV提供"; } ?></title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶" />
<meta name="description" content="" />
<script type="text/javascript">
window.onload = function() {
  document.getElementById('singer_field').focus();
}
</script>

<style>
.mv_sampleBox {
	margin: 5px 10px 5px 10px; padding: 5px 5px 10px 10px; border-bottom: 1px dashed #666666;
}
</style>
<? include("includes/header.php"); ?>

<div id="sidebar">
	<h1>上傳MV範例</h1>
	<div class="mv_sampleBox"><strong>歌手</strong>: 張惠妹</div>
	<div class="mv_sampleBox"><strong>歌名</strong>: 如果你也聽說</div>	
	<div class="mv_sampleBox"><strong>類別</strong>: 女歌手</div>		
	<div class="mv_sampleBox"><strong>MV 網址</strong>: 從<a href="http://www.youtube.com" target="_blank">YouTube</a>裡把內嵌程式碼貼過來. 詳細步驟請參考<a href="http://www.mvland.com/help#3a" target="_blank">我要怎麼提供MV?</a>.</div>
	<div class="mv_sampleBox"><strong>歌詞</strong>: 輸入或複製歌詞到這個欄位, 請用Enter鍵換行, 如:<br /><br />
		突然發現站了好久 不知道要往哪走<br />
		還不想回家的我 再多人陪只會更寂寞<br />
		許多話題關於我 就連我也有聽過<br />
		我的快樂要被認可 委屈卻沒有人訴說<br />
		夜把心洋蔥般剝落 拿掉防衛剩下什麼<br />
		為什麼脆弱時候 想你更多<br /><br />
		<strong>不想打歌詞?</strong> 歌詞一字一字打進去有點累人, 介紹大家一個不錯找到歌詞的地方, 那就是<a href="http://tw.knowledge.yahoo.com/" title="Yahoo奇摩知識" target="_blank">Yahoo奇摩知識</a>. 基本上在那裏沒有甚麼歌詞找不到的, 只要輸入歌名進去應該都可以找到. 找到後把歌詞複製再貼到歌詞欄位就可以了.
	</div>
	<!-- <div style="margin: 5px 10px 5px 10px; padding: 5px 5px 0px 10px;"><strong>描述</strong>: 這是一個可以讓你自由發揮的空間, 可以是你對這首歌曲, 歌詞或是MV的感想或評價.</div> -->
	<br /> 
</div>

<div id="content">

<?
if(!empty($error_msg)) {
?> <div class="error_msg"> <?
	switch($error_msg) {
	case 1: 
		echo "請輸入歌詞";
		break;
	case 2:
		echo "您輸入的MV網址不合規定";
		break;
	case 3:
		echo "請輸入MV網址";
		break;		
	case 4:
		echo "請選擇類別";
		break;	
	case 5:
		echo "請輸入歌名";
		break;
	case 6:
		echo "請輸入歌手";
		break;
	case 7:
		echo "您輸入的歌名長度過長";
		break;	
	case 8:
		echo "您輸入的歌手長度過長";
		break;			
	case 9:
		echo "您輸入的歌詞長度過長";
		break;			
	//case 10:
		//echo "您輸入的描述長度過長";
		//break;	
	case 11:
		echo "輸入的內容不能包含URL連結";
		break;							
	}
?> </div> <?
}
?>
<script type="text/javascript" src="script/tooltip.js"></script>

<?
if(isset($_GET['song'])) {
?> 	<div style="margin-bottom: 20px; margin-top: 20px; font-size: 16px;"><a href="http://www.mvland.com/my_account"><strong>我的帳號</strong></a> | <strong>MV歌曲編輯</strong></div> <?
} else { ?>
	<h1>上傳MV</h1>
	<div style="margin-bottom: 10px;">請輸入歌曲的資料</div>
<? 
} ?>

<form id="signupForm" method="post" action="<? if(isset($_GET['song'])) { echo "http://www.mvland.com/provide?song=$song_url"; } else { echo "http://www.mvland.com/provide"; } ?>">
<table border="0" width="100%">

	<tr>
		<td>
		<fieldset class="form_provide">
		<legend>Song Information</legend>
		<div>
		<table border="0" width="100%">
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['singer']))) { ?> <span class="error_text">*</span> <strong>歌手</strong>: <? } else { ?>歌手: <? } ?></td>
				<td class="form_field"><input type="text" name="singer" style="width: 150px;" id="singer_field" <? if(isset($_POST['singer'])) { $singer = stripslashes($_POST['singer']); $singer = htmlspecialchars($singer); echo "value=\"$singer\""; } elseif(isset($_GET['song'])) { echo "value=\"$edit_singer\""; } ?> /></td>
			</tr>
			
			<tr><td height="10"></td></tr>	
				
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['song_name']))) { ?> <span class="error_text">*</span> <strong>歌名</strong>: <? } else { ?>歌名: <? } ?></td>
				<td class="form_field"><input type="text" name="song_name" style="width: 150px;" id="song_field" <? if(isset($_POST['song_name'])) { $song_name = stripslashes($_POST['song_name']); $song_name = htmlspecialchars($song_name); echo "value=\"$song_name\""; } elseif(isset($_GET['song'])) { echo "value=\"$edit_song_name\""; } ?> /></td>
			</tr>
			
			<tr><td height="10"></td></tr>	
				
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['singer_group']))) { ?> <span class="error_text">*</span> <strong>類別</strong>: <? } else { ?>類別: <? } ?></td>
				<td class="form_field">
					<input type="radio" name="singer_group" value="male" <? if(isset($_POST['singer_group'])) { $singer_group = $_POST['singer_group']; if($singer_group == 'male') echo " checked"; } elseif(isset($_GET['song'])) { if($edit_song_type == 'male') echo "checked";} ?> /> 男歌手&nbsp;
					<input type="radio" name="singer_group" value="female" <? if(isset($_POST['singer_group'])) { $singer_group = $_POST['singer_group']; if($singer_group == 'female') echo " checked"; } elseif(isset($_GET['song'])) { if($edit_song_type == 'female') echo "checked";} ?> /> 女歌手&nbsp;
					<input type="radio" name="singer_group" value="group" <? if(isset($_POST['singer_group'])) { $singer_group = $_POST['singer_group']; if($singer_group == 'group') echo " checked"; } elseif(isset($_GET['song'])) { if($edit_song_type == 'group') echo "checked";} ?> /> 團體					
				</td>
			</tr>			
			
			<tr><td height="10"></td></tr>	
							
			<tr>
				<td align="right" width="80"><? if((isset($_POST['submit']))&&(empty($_POST['mv_url']))) { ?> <span class="error_text">*</span> <strong>MV 網址</strong>: <? } elseif($error_msg==2) { ?> <span class="error_text">* MV 網址: </span> <? } else { ?>MV 網址: <? } ?></td>
				<td class="form_field"><input type="text" name="mv_url" style="width: 330px;" <? if(isset($_POST['mv_url'])) { $mv_url = stripslashes($_POST['mv_url']); ?> value='<? echo $mv_url; ?>'<? } elseif(isset($_GET['song'])) { ?>value='<? echo stripslashes($edit_mv_url); ?>'<? } ?> /></td>
				<td valign="top"><img src="images/ctx_question_inactive.gif" onMouseover="fixedtooltip('目前只接受YouTube的MV內嵌程式碼', this, event, '150px')" onMouseout="delayhidetip()" /></td>
			</tr>
			
			<tr><td height="10"></td></tr>
			
			<tr>
				<td align="right" width="80" valign="top"><? if((isset($_POST['submit']))&&(empty($_POST['song_lyric']))) { ?> <span class="error_text">*</span> <strong>歌詞</strong>: <? } else { ?>歌詞: <? } ?></td>
				<td class="form_field"><textarea name="song_lyric" style="width: 97%; height: 250px;" /><? if(isset($_POST['song_lyric'])) { $song_lyric = stripslashes($_POST['song_lyric']); $song_lyric = htmlspecialchars($song_lyric); echo $song_lyric; } elseif(isset($_GET['song'])) { echo stripslashes($edit_lyric); } ?></textarea></td>
				<td valign="top"><img src="images/ctx_question_inactive.gif" onMouseover="fixedtooltip('請用Enter換行', this, event, '150px')" onMouseout="delayhidetip()" /></td>
			</tr>
			
			<tr><td height="10"></td></tr>
<!--			
			<tr>
				<td align="right" width="80" valign="top"><? //if($error_msg==10) { ?> <span class="error_text">描述: </span> <? //} else { ?>描述: <? //} ?></td>			
				<td class="form_field"><textarea name="song_desc" style="width: 97%; height: 100px;" /><? //if(isset($_POST['song_desc'])) { $song_desc = stripslashes($_POST['song_desc']); $song_desc = htmlspecialchars($song_desc); echo $song_desc; } elseif(isset($_GET['song'])) { echo stripslashes($edit_song_desc); } ?></textarea></td>
				<td valign="top"><img src="images/ctx_question_inactive.gif" onMouseover="fixedtooltip('這是一個可以讓你自由發揮的空間, 可以是你對這首歌曲, 歌詞或是MV的感想或評價. (此欄可不填)', this, event, '150px')" onMouseout="delayhidetip()" /></td>
			</tr>
		
			<tr><td height="10"></td></tr>
-->				
			<tr>
				<td align="left" colspan="2" style="padding-left: 85px;">
					<input type="submit" value="<? if(isset($_GET['song'])) { echo "儲存"; } else { echo "送出"; } ?>" style="width: 100px; height: 30px;"/>
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
include("includes/footer.php")
?>