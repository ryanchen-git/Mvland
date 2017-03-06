<?
include("includes/_landing.php");

if(!isset($_SESSION['user_id'])) {
	header("Location: http://www.mvland.com/login");
}

$song_name = $_SESSION['song_name'];
$singer = $_SESSION['singer'];
$singer_group = $_SESSION['singer_group'];
$mv_url = $_SESSION['mv_url'];
$song_lyric = $_SESSION['song_lyric'];
$song_desc = $_SESSION['song_desc'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 我的帳號</title>
<meta name="keywords" content="Mvland, 我的帳號" />
<meta name="description" content="Mvland我的帳號檢視我提供的MV" />

<style type="text/css">
<!--

.song_table {
  border-collapse: collapse;
  width: 155%;
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

-->
</style>

<?
include("includes/header.php");
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>

	<div id="content">
<? 
	if(isset($_GET['edit'])) { 
		$edit = $_GET['edit'];
		if($edit == 'success') { ?>
			<div class="error_msg">MV資料已更新完成</div>
<? 		}
		elseif($edit == 'remove') { ?>
			<div class="error_msg">MV已移除</div>
<?  	}
	} 

	if(isset($_GET['provide'])) {
?>		<div class="error_msg">MV已成功登錄</div> <?
	}
?>
	<div style="margin-bottom: 30px; margin-top: 20px; font-size: 16px;"><a href="http://www.mvland.com/my_account"><strong>我的帳號</strong></a> | <strong>檢視我提供的MV</strong></div>
<?
	$query = "SELECT s.*, m.username 
			  FROM songs s, members m, members2songs_mapping p 
			  WHERE m.id=$user_id AND s.id=p.songs AND m.id=p.members AND s.active=1 
			  ORDER BY s.date DESC";
	$result = mysql_query($query);
	if ($result && mysql_num_rows($result)) {
		$num_rows = mysql_num_rows($result);
?> 
	<div style="padding-bottom: 10px;"><strong>您共提供了<?=$num_rows?>支MV</strong></div>
	<table cellspacing="0" class="song_table">
	
	<thead>
	<tr>
	<th><strong>編輯</strong></th>	
	<th><strong>歌名</strong></th>
	<th><strong>歌手</strong></th>
	<th><strong>瀏覽人數</strong></th>	
	<th><strong>提供時間</strong></th>
	<th><strong>移除</strong></th>	
	</tr>
	</thead>
	
	<tbody>
		<?
		while($row = mysql_fetch_array($result)) {
			$song_id = $row['id'];
			$name = $row['name'];
			$singer = $row['singer'];
			$mv_url = $row['mv_url'];
			$url = $row['unique_id'];
			$date = $row['date'];
			$modify_date = $row['modify_date'];
			$date = substr($date, 0,16);
			$modify_date = substr($modify_date, 0,10);
			$views = $row['views'];
		?> 
			<tr>
				<td style="width: 50px;"><a href="http://www.mvland.com/provide?song=<?=$url?>">編輯</a></td>			
				<td style="width: 140px;"><a href="http://www.mvland.com/watch/mv=<?=$url?>"><?=$name?></a></td>
				<td style="width: 100px;"><?=$singer?></td>
				<td style="width: 60px;"><?=$views?></a></td>				
				<td style="width: 145px;"><?=$date?></a></td>
				<!--<td width="105"><? //if($modify_date == '0000-00-00') { echo "無更新紀錄"; } else { echo $modify_date; } ?></a></td>-->
				<td style="width: 10px;"><form action="http://www.mvland.com/song_remove" method="post" onsubmit="return (confirm('確定要移除嗎?'));"><input type="hidden" name="song_id" value="<?=$song_id?>" /><input type="hidden" name="song_url" value="<?=$url?>" /><input type="submit" value="移除" style="width: 50px; height: 20px; font-size: 12px; padding-top: 1px;" /></form></td>								
			</tr>
		<?		
		} ?>
	</tbody>
	</table>
<?
	} else {
?>
	您尚未提供任何MV. <a href="http://www.mvland.com/provide"><strong>現在就提供</strong></a>!
<? 
	}
?>
<div style="padding-top: 30px;"></div>			
<?
include("includes/footer.php")
?>