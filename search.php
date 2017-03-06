<? include("includes/_landing.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 音樂, MV, 歌詞</title>
<meta name="keywords" content="Mvland, MV, Music Videos, 音樂錄影帶, 歌詞, lyrics, 音樂, 搜尋" />
<meta name="description" content="" />

<? include("includes/header.php"); ?>

<div id="sidebar">		
	<h1>搜尋小幫手</h1>
	<div style="padding:10px 20px;">你可以用下列的4種組合來搜尋Mvland的MV:<br />
		<img src="http://www.mvland.com/images/blue_arrow.gif" /> <strong>歌手名字</strong> (<a href="http://www.mvland.com/search?mv_search=張惠妹">張惠妹</a>)<br />
		<img src="http://www.mvland.com/images/blue_arrow.gif" /> <strong>歌曲名稱</strong> (<a href="http://www.mvland.com/search?mv_search=如果你也聽說">如果你也聽說</a>)<br />
		<img src="http://www.mvland.com/images/blue_arrow.gif" /> <strong>歌手名字+歌曲名稱</strong> (<a href="http://www.mvland.com/search?mv_search=張惠妹如果你也聽說">張惠妹如果你也聽說</a>)<br />
		<img src="http://www.mvland.com/images/blue_arrow.gif" /> <strong>歌曲名稱+歌手名字</strong> (<a href="http://www.mvland.com/search?mv_search=如果你也聽說張惠妹">如果你也聽說張惠妹</a>)<br />
	</div>
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

if(isset($_GET['mv_search'])) {
	$mv_search = trim($_GET['mv_search']);
	
	if($mv_search == '') {
		$mv_search = '';
?>	
		<h1>&ldquo;<?=$mv_search?>&rdquo; 的搜尋結果:</h1>
		<div style="margin-top: 5px; padding-top: 15px; border-top: 1px dashed #999999; font-size: 13px;">找不到任何MV資料喔!<br /><br />如果還是找不到你想看的MV, 那表示還沒有人提供, 趕快到<a href="http://www.youtube.com">YouTube</a>當第一個提供着吧!<br /><br /><strong><a href="http://www.mvland.com/help#3a">我要怎麼提供MV?</a></strong></div>
<?
	} else {
	
		/****************************************************************/
		/* calculate the number of entry */
		$query = "SELECT s.*, m.username 
				  FROM songs s, members m, members2songs_mapping p 
				  WHERE (s.name like '%$mv_search%' OR s.singer like '%$mv_search%') AND s.id=p.songs AND m.id=p.members AND s.active=1";
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
		
		if($mv_search == '') {
		?>	<h1>&ldquo;<?=$mv_search?>&rdquo; 的搜尋結果:</h1>
		<div style="margin-top: 5px; padding-top: 15px; border-top: 1px dashed #999999; font-size: 13px;">找不到任何MV資料喔!<br /><br />如果還是找不到你想看的MV, 那表示還沒有人提供, 趕快到<a href="http://www.youtube.com">YouTube</a>當第一個提供着吧!<br /><br /><strong><a href="http://www.mvland.com/help#3a">我要怎麼提供MV?</a></strong></div>
		<?
		} else {
		
			$query = "INSERT INTO search_kw (keywords, date) VALUES ('$mv_search', NOW())";
			$result = mysql_query($query);
	 
			$query = "SELECT s.*, m.username 
					  FROM songs s, members m, members2songs_mapping p 
					  WHERE (s.name LIKE '%$mv_search%' OR s.singer LIKE '%$mv_search%' OR s.combine1 LIKE '%$mv_search%' OR s.combine2 LIKE '%$mv_search%') 
					  AND s.id=p.songs AND m.id=p.members AND s.active=1 
					  ORDER BY $order_by DESC 
					  LIMIT $start, $display";
			$result = mysql_query($query);
			?> <h1>&ldquo;<?=$mv_search?>&rdquo; 的搜尋結果:</h1> 
			
	<? if(!isset($_GET['sort']) || (isset($_GET['sort']) && $order_by == "date")) { echo "提供時間"; } else { ?> <a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&sort=sort_date">提供時間</a> <? } ?> | 
	<? if(isset($_GET['sort']) && $order_by == "views") { echo "瀏覽人數"; } else { ?> <a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&sort=sort_views">瀏覽人數</a> <? } ?> |
	<? if(isset($_GET['sort']) && $order_by == "comments") { echo "留言次數"; } else { ?> <a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&sort=sort_comments">留言次數</a> <? } ?> |
	<? if(isset($_GET['sort']) && $order_by == "favorites") { echo "最愛數量"; } else { ?> <a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&sort=sort_favorites">最愛數量</a> <? } ?>
	
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
					<div style="font-size: 16px; margin-bottom: 5px;"><strong><a href="http://www.mvland.com/watch/mv=<?=$url?>"><?=$song_name?></a></strong> - 
		<?
					if($type=='male') { ?> <a href="http://www.mvland.com/male/singer=<?=$singer?>"><?=$singer?></a> <? }		
					if($type=='female') { ?> <a href="http://www.mvland.com/female/singer=<?=$singer?>"><?=$singer?></a> <? }
					if($type=='group') { ?> <a href="http://www.mvland.com/group/singer=<?=$singer?>"><?=$singer?></a> <? }				
		?>  		
					 <span style="font-size: 13px;">(<a href="http://www.mvland.com/member/user=<?=$user?>"><?=$user?></a>)</span></div>
			<div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #669966;">瀏覽: <?=$views?> | 留言: <?=$comments?> | 最愛: <?=$favorites?> | 提供時間: <?=$date?></div>
		<?		
				}
			} else {
		?>		
		<div style="margin-top: 5px; padding-top: 15px; border-top: 1px dashed #999999; font-size: 13px;">找不到任何MV資料喔!<br /><br />如果還是找不到你想看的MV, 那表示還沒有人提供, 趕快到<a href="http://www.youtube.com">YouTube</a>當第一個提供着吧!<br /><br /><strong><a href="http://www.mvland.com/help#3a">我要怎麼提供MV?</a></strong></div>
 		<?
			}
		}
		
		/****************************************************************/
		/* show the page number continued*/
		if($num_pages > 1) {
			$current_page = ($start/$display) + 1;
			
			if($current_page!=1) {
		?>			<a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&s=<?=$start-$display?>&np=<?=$num_pages?>">上一頁</a> <?
			}
			
			for($i=1; $i<=$num_pages; $i++) {
				if($i!=$current_page) {
		?>				<a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&s=<?=(($display*($i-1)))?>&np=<?=$num_pages?>"><?=$i?></a> <?
				} else {
					echo $i;
				}
			}
			
			if($current_page!=$num_pages) {
		?>			<a href="http://www.mvland.com/search?mv_search=<?=$mv_search?>&s=<?=($start+$display)?>&np=<?=$num_pages?>">下一頁</a> <?
			}
		}
		/****************************************************************/	
	}	
} 

include("includes/footer.php")
?>