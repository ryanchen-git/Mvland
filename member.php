<?
include("includes/_landing.php");

if(isset($_GET['user'])) {
	$user = $_GET['user'];
} else {
	header("Location: http://www.mvland.com/");
}

$query = "SELECT username FROM members WHERE username = '$user'";
$result = mysql_query($query);
if (mysql_num_rows($result)==0) {
	header("Location: http://www.mvland.com/");
}

$query = "SELECT m.*, c.country AS 'country' FROM members m, country c 
		  WHERE m.username = '$user' AND m.country=c.abbrv AND m.active=1";
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
<title>Mvland - 音樂, MV, 歌詞</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂" />
<meta name="description" content="Mvland" />

<? include("includes/header.php"); ?>

<div id="sidebar">

	<h1>關於<?=$user?></h1>
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
			echo "<br />關於我:<br /> $about_me<br />";
		}				
?>

<? include("includes/singer_table.php"); ?>

</div>

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

	/****************************************************************/
	/* calculate the number of entry */
	$query = "SELECT s.* 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE m.username='$user' AND s.id=p.songs AND m.id=p.members AND s.active=1";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
		$num_rows = mysql_num_rows($result);
	}	
	/****************************************************************/

	/****************************************************************/
	/* show the page number */
	$display = 10;	//number of entry show on each page
	
	if(isset($_GET['np'])) {
		$num_pages = $_GET['np'];
	} else {
		$num_records = $num_rows;
		
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

	$query = "SELECT s.* 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE m.username='$user' AND s.id=p.songs AND m.id=p.members AND s.active=1 
			  ORDER BY $order_by DESC
			  LIMIT $start, $display";
	$result = mysql_query($query);
	?> <h1><?=$user?> 提供的MV: </h1>
		
<?	
	if ($result && mysql_num_rows($result)) {
?>
	<? if(!isset($_GET['sort']) || (isset($_GET['sort']) && $order_by == "date")) { echo "提供時間"; } else { ?> <a href="http://www.mvland.com/member/user=<?=$user?>?sort=sort_date">提供時間</a> <? } ?> | 
	<? if(isset($_GET['sort']) && $order_by == "views") { echo "瀏覽人數"; } else { ?> <a href="http://www.mvland.com/member/user=<?=$user?>?sort=sort_views">瀏覽人數</a> <? } ?> |
	<? if(isset($_GET['sort']) && $order_by == "comments") { echo "留言次數"; } else { ?> <a href="http://www.mvland.com/member/user=<?=$user?>?sort=sort_comments">留言次數</a> <? } ?> |
	<? if(isset($_GET['sort']) && $order_by == "favorites") { echo "最愛數量"; } else { ?> <a href="http://www.mvland.com/member/user=<?=$user?>?sort=sort_favorites">最愛數量</a> <? } ?>
	
	<div style="padding-top: 10px;"></div>

 	<div style="margin-bottom: 20px; border-bottom: 2px solid #FF9900; font-weight: bold;">總共<?=$num_rows?>筆MV資料</div> <?	
		while($row = mysql_fetch_array($result)) {
			$song_id = $row['id'];		
			$song_name = $row['name'];
			$singer = $row['singer'];
			$type = $row['type'];
			$mv_url = $row['mv_url'];
			$lyric = $row['lyric'];
			$url = $row['unique_id'];
			$comments = $row['comments'];
			$favorites = $row['favorites'];			
			$views = $row['views'];
			$date =  $row['date'];
?> 
			<div style="font-size: 16px; margin-bottom: 5px;"><strong><a href="http://www.mvland.com/watch/mv=<?=$url?>"><?=$song_name?></a></strong> - 
<?
			if($type=='male') { ?> <a href="http://www.mvland.com/male/singer=<?=$singer?>"><?=$singer?></a> <? }		
			if($type=='female') { ?> <a href="http://www.mvland.com/female/singer=<?=$singer?>"><?=$singer?></a> <? }
			if($type=='group') { ?> <a href="http://www.mvland.com/group/singer=<?=$singer?>"><?=$singer?></a> <? }				
?>  					
			</div>
			<div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #669966;">瀏覽: <?=$views?> | 留言: <?=$comments?> | 最愛: <?=$favorites?> | 提供時間: <?=$date?></div>
<?		
		}
	} else {
?>
	您尚未提供任何MV. <a href="http://www.mvland.com/provide"><strong>現在就提供</strong></a>!
<? 
	}
	
	/****************************************************************/
	/* show the page number continued*/	
	if($num_pages > 1) {
		$current_page = ($start/$display) + 1;
		
		if($current_page!=1) {
			if($sort=='') {
?> 				<a href="http://www.mvland.com/member/user=<?=$user?>?s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			} else {		
?>				<a href="http://www.mvland.com/member/user=<?=$user?>?sort=<?=$sort?>&s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			}
		}
		
		if($current_page < 10) {
			if($num_pages > 10) {
				$page_display = $current_page + 5;
			}
			else {
				$page_display = $num_pages;
			}
			for($i=1; $i<=$page_display; $i++) {
				if($i!=$current_page) {
					if($sort=='') {
	?>					<a href="http://www.mvland.com/member/user=<?=$user?>?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
					} else {		
	?>					<a href="http://www.mvland.com/member/user=<?=$user?>?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
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
	?>					<a href="http://www.mvland.com/member/user=<?=$user?>?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
					} else {		
	?>					<a href="http://www.mvland.com/member/user=<?=$user?>?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
					}
				} else {
					echo $i;
				}
			}
		}
		else {
			for($i=$current_page-5; $i<$current_page; $i++) {
				if($sort=='') {
?>					<a href="http://www.mvland.com/member/user=<?=$user?>?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
				} else {		
?>					<a href="http://www.mvland.com/member/user=<?=$user?>?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				}
			}
			echo $i;
			for($i=$current_page+1; $i<=$current_page+5; $i++) {
				if($sort=='') {
?>					<a href="http://www.mvland.com/member/user=<?=$user?>?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?	
				} else {		
?>					<a href="http://www.mvland.com/member/user=<?=$user?>?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				}
			}			
		}		
		
		if($current_page!=$num_pages) {
			if($sort=='') {
?> 				<a href="http://www.mvland.com/member/user=<?=$user?>?s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a> <?
			} else {				
?>				<a href="http://www.mvland.com/member/user=<?=$user?>?sort=<?=$sort?>&s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a> <?
			}
		}
	}	
	/****************************************************************/		

include("includes/footer.php")
?>