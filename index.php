<? include("includes/_landing.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 音樂, MV, 歌詞</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂" />
<meta name="description" content="A Place for Music Videos" />
<meta name="verify-v1" content="KAYo3fjvKDJRRUnu7MfGm5RsRP4h3UKl9tubV39an6g=" />
<META name="y_key" content="07b0a70710b7d37c" >

<script type="text/javascript" src="script/ajax.js"></script>
<script type="text/javascript" src="script/random_mv.js"></script>

<?php include("includes/header.php"); ?>

<div id="sidebar">

	<h1>MV提供者排行</h1>
	<div style="margin: 20px;">
		<table cellspacing="0" class="chart_table">
			<tr>
				<th width="20%">名次</th>
				<th width="60%">提供者</th>
				<th width="20%">數量</th>
			</tr>
		<?
			$query = "SELECT username, song_provide FROM members WHERE active=1 AND song_provide>0 ORDER BY song_provide DESC LIMIT 5";
			$result = mysql_query($query);
			if ($result && mysql_num_rows($result)) {
				$i = 1;	
				while($row = mysql_fetch_array($result)) {
					$top_user = $row['username'];
					$top_song_num = $row['song_provide']; ?>
					<tr class="bg_change">
						<td width="20%"><?=$i?></td>
						<td width="60%"><a href="http://www.mvland.com/member/user=<?=$top_user?>"><?=$top_user?></a></td>
						<td width="20%"><?=$top_song_num?></td>
					</tr> <?			
					$i++;
				}
			}
		?>
		</table>
	</div>
    
	<?php include("includes/singer_table.php"); ?>    
	
	<? if(!isset($_SESSION['user_id'])) { ?>
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
				
				<div class="member_signup">還不是會員嗎?
					<div class="account_box">
						<a href="http://www.mvland.com/signup">免費加入會員</a>
					</div>
				</div>
			</div>
			</td>
		</tr>
	</table>
	</form>
	<? } ?>	

</div> <!--End sidebar div --> 

<div id="content">

<?
if(isset($_GET['sort'])) {
	$sort = $_GET['sort'];
	switch($sort) {
	case 'sort_date':
		$order_by = "date";
		break;	
	case 'sort_views':
		$order_by = "views";
		break;		
	case 'sort_comments':
		$order_by = "comments";
		break;
	case 'sort_favorites':
		$order_by = "favorites";
		break;
	}	
} else {
	$order_by = "date";
}

	//get total number of mv
	$query = "SELECT COUNT(*) FROM songs where active = 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$total_num_mv = $row[0];	

	if(isset($_GET['sort'])) {
		$sort = $_GET['sort'];
		switch($sort) {
		case 'sort_date':
			echo "<h1>最新提供MV</h1>";
			break;	
		case 'sort_views':
			echo "<h1>最多瀏覽MV</h1>";
			break;		
		case 'sort_comments':
			echo "<h1>最多留言MV</h1>";
			break;
		case 'sort_favorites':
			echo "<h1>最多最愛MV</h1>";
			break;
		}	
	} else {
		echo "<h1>最新提供MV</h1>";
	}
	
	if($_SERVER['REQUEST_URI'] == '/') {
?>	
        <div id="random_mv"> <?
			$query = "SELECT * FROM songs WHERE active=1 AND (mv_url LIKE '%<object width=\"425\"%' OR mv_url LIKE '%<object width=\"480\"%') AND mv_url NOT LIKE 'http%' ORDER BY RAND() LIMIT 1";
			$result = mysql_query($query);
			if($result && mysql_num_rows($result)) {
				$row = mysql_fetch_array($result); ?>
                <div class="title">
                    隨機MV: <a href="/<?=$row['type']?>/singer=<?=$row['singer']?>"><?=$row['singer']?></a> - <a href="/watch/mv=<?=$row['unique_id']?>"><?=$row['name']?></a></strong>
                    <span id="random_mv_link">
                        (<a href="" onclick="grabFile(); return false;">看下個隨機MV</a>)
                    </span> 
                </div>
                <?=$row['mv_url'];?> <?
			} ?>
        </div> 
		<?
	} ?>

	<div style="margin: 20px 0px; border-bottom: 2px solid #FF9900; font-weight: bold;">總共<?=$total_num_mv?>筆MV資料</div>	
<?	
	/****************************************************************/
	/* show the page number */
	$display = 10;	//number of entry show on each page
	
	if(isset($_GET['np'])) {
		$num_pages = $_GET['np'];
	} else {
		$query = "SELECT COUNT(*) FROM songs where active = 1";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$num_records = $row[0];
		
		if($num_records > $display) {
			$num_pages = ceil($num_records/$display);
		} else {
			$num_pages = 1;
		}
	}
	
	if(isset($_GET['s'])) {
		$start = $_GET['s'];
	} else {
		$start = 0;
	}
	/****************************************************************/	
	
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE s.id=p.songs AND m.id=p.members AND s.active=1 
			  ORDER BY $order_by DESC
			  LIMIT $start, $display";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
		while($row = mysql_fetch_array($result)) {
			$song_id = $row['id'];		
			$song_name = $row['name'];
			$singer = $row['singer'];
			$type = $row['type'];
			$mv_url = $row['mv_url'];
			$lyric = $row['lyric'];
			$url = $row['unique_id'];
			$user = $row['username'];
			$comments = $row['comments'];
			$favorites = $row['favorites'];			
			$views = $row['views'];
			$date =  $row['date'];
			$date = substr("$date", 0, 10);
				
?>
			<div style="font-size: 16px; margin-bottom: 5px;"><strong><a href="http://www.mvland.com/watch/mv=<?=$url?>"><?=$song_name?></a></strong> - <a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a>
			<span style="font-size: 13px;">(<a href="http://www.mvland.com/member/user=<?=$user?>"><?=$user?></a>)</span></div>
			<div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #669966;">瀏覽: <?=$views?> | 留言: <?=$comments?> | 最愛: <?=$favorites?> | 提供時間: <?=$date?></div>
<?				
		}
	}

	/****************************************************************/
	/* show the page number continued*/
	if($num_pages > 1) {
		$current_page = ($start/$display) + 1;
		
		if($current_page!=1) {
			if($sort=='') {
?> 				<a href="http://www.mvland.com/index?s=0&np=<?=$num_pages?>">最前</a>
				<a href="http://www.mvland.com/index?s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			} else {
?>				<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=0&np=<?=$num_pages?>">最前</a>
				<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			}
		}

		if($current_page < 10) {
			if($num_pages > 10) {
				$page_display = $current_page + 9;
			}
			else {
				$page_display = $num_pages;
			}
			for($i=1; $i<=$page_display; $i++) {
				if($i!=$current_page) {
					if($sort=='') {
	?>					<a href="http://www.mvland.com/index?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
					} else {		
	?>					<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
					}
				} else {
					echo $i;
				}
			}
		}
		else if($current_page > $num_pages - 8) {
			for($i=$current_page - 5; $i<=$num_pages; $i++) {
				if($i!=$current_page) {
					if($sort=='') {
	?>					<a href="http://www.mvland.com/index?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
					} else {		
	?>					<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
					}
				} else {
					echo $i;
				}
			}
		}
		else {
			for($i=$current_page-5; $i<$current_page; $i++) {
				if($sort=='') {
?>					<a href="http://www.mvland.com/index?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
				} else {		
?>					<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				}
			}
			echo $i;
			for($i=$current_page+1; $i<=$current_page+5; $i++) {
				if($sort=='') {
?>					<a href="http://www.mvland.com/index?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
				} else {		
?>					<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				}
			}			
		}
		
		if($current_page!=$num_pages) {
			if($sort=='') {
?>				<a href="http://www.mvland.com/index?s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a> 
				<a href="http://www.mvland.com/index?s=<?=$num_pages-1 . 0?>&np=<?=$num_pages?>">最後</a> <?
			} else {
?>				<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a>
				<a href="http://www.mvland.com/index?sort=<?=$sort?>&s=<?=$num_pages-1 . 0?>&np=<?=$num_pages?>">最後</a> <?
			}
		}
	}
	/****************************************************************/		

include("includes/footer.php")
?>