<? include("includes/_landing.php"); ?>
<?
	if(isset($_GET['singer'])) {
		$singer = $_GET['singer'];
		$display_singer = "- $singer";	//for browser title
	} else {
		header("Location: http://www.mvland.com");
	}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 男歌手 <?=$display_singer?></title>
<meta name="keywords" content="<?=$singer?>, 音樂錄影帶, MV, 男歌手" />
<meta name="description" content="<?=$singer?>的音樂錄影帶MV" />

<? include("includes/header.php"); ?> 

<div id="sidebar">
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

if(isset($_GET['singer'])) {
	$singer = $_GET['singer'];
	
	/****************************************************************/
	/* calculate the number of entry */
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE s.singer='$singer' AND s.id=p.songs AND m.id=p.members AND s.active=1";
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
	
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE s.singer='$singer' AND s.id=p.songs AND m.id=p.members AND s.active=1 
			  ORDER BY $order_by DESC 
			  LIMIT $start, $display";
	$result = mysql_query($query);
	?> <h1><?=$singer?>的MV:</h1>

<? if(!isset($_GET['sort']) || (isset($_GET['sort']) && $order_by == "date")) { echo "提供時間"; } else { ?> <a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=sort_date">提供時間</a> <? } ?> | 
<? if(isset($_GET['sort']) && $order_by == "views") { echo "瀏覽人數"; } else { ?> <a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=sort_views">瀏覽人數</a> <? } ?> |
<? if(isset($_GET['sort']) && $order_by == "comments") { echo "留言次數"; } else { ?> <a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=sort_comments">留言次數</a> <? } ?> |
<? if(isset($_GET['sort']) && $order_by == "favorites") { echo "最愛數量"; } else { ?> <a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=sort_favorites">最愛數量</a> <? } ?>

<div style="padding-top: 10px;"></div>
					
<?		
	if ($result && mysql_num_rows($result)) {
	?> <div style="margin-bottom: 20px; border-bottom: 2px solid #FF9900; font-weight: bold;">總共<?=$num_rows?>筆MV資料</div> <?
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
?> 
		<div style="font-size: 16px; margin-bottom: 5px;"><strong><a href="http://www.mvland.com/watch/mv=<?=$url?>"><?=$song_name?></a></strong> <span style="font-size: 13px;">(<a href="http://www.mvland.com/member/user=<?=$user?>"><?=$user?></a>)</span></div>
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
?>				<a href="http://www.mvland.com/male/singer=<?=$singer?>?s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			} else {		
?>				<a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=<?=$sort?>&s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			}
		}
		
		for($i=1; $i<=$num_pages; $i++) {
			if($i!=$current_page) {
				if($sort=='') {
?>					<a href="http://www.mvland.com/male/singer=<?=$singer?>?s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				} else {		
?>					<a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=<?=$sort?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				}
			} else {
				echo $i;
			}
		}
		
		if($current_page!=$num_pages) {
			if($sort=='') {
?>				<a href="http://www.mvland.com/male/singer=<?=$singer?>?s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a> <?
			} else {		
?>				<a href="http://www.mvland.com/male/singer=<?=$singer?>?sort=<?=$sort?>&s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a> <?
			}
		}
	?> <br /><br /> <?
	}	
	/****************************************************************/		
}

?>
	<a href="http://www.mvland.com/provide?next=provide"><strong>我要提供MV</strong></a>
<?
	
include("includes/footer.php")
?>