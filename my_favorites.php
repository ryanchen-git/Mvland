<?
include("includes/_landing.php");

if(!isset($_SESSION['user_id'])) {
	header("Location: http://www.mvland.com/login");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Mvland - 我的帳號</title>
<meta name="keywords" content="Mvland, 我的帳號" />
<meta name="description" content="Mvland我的帳號檢視我的最愛MV" />

<style type="text/css">
<!--

.song_table {
  border-collapse: collapse;
  width: 130%;
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
	if(isset($_GET['fav'])) { 
		$fav = $_GET['fav'];
		if($fav == 'remove') { ?>
			<div class="error_msg">MV已從我的最愛移除</div>
<? 		}
	} 
?>
	<div style="margin-bottom: 30px; margin-top: 20px; font-size: 16px;"><a href="http://www.mvland.com/my_account"><strong>我的帳號</strong></a> | <strong>檢視我的最愛MV</strong></div>
<?
	$query = "SELECT f.id AS favorite_id, f.date AS favorite_date, s.*, m.username
			  FROM members2favorites_mapping f, songs s, members m, members2songs_mapping p
			  WHERE f.members=$user_id AND f.songs=s.id AND f.active=1 AND s.id=p.songs AND p.members=m.id AND m.active=1
			  ORDER BY f.date desc";
	$result = mysql_query($query);
	if($result && mysql_num_rows($result)) {
		$num_rows = mysql_num_rows($result);
?> 
	<div style="padding-bottom: 10px;"><strong>您共存入<?=$num_rows?>支MV在我的最愛</strong></div>
	<table cellspacing="0" class="song_table">
	
	<thead>
	<tr>
	<th><strong>歌名</strong></th>
	<th><strong>歌手</strong></th>
	<th><strong>提供著</strong></th>	
	<th><strong>存入時間</strong></th>
	<th><strong>移除</strong></th>	
	</tr>
	</thead>
	
	<tbody>
		<?
		while($row = mysql_fetch_array($result)) {
			$fav_id = $row['favorite_id'];
			$name = $row['name'];
			$singer = $row['singer'];
			$mv_url = $row['mv_url'];
			$url = $row['unique_id'];
			$date = $row['favorite_date'];
			$date = substr($date, 0,16);
			$user = $row['username'];
		?> 
			<tr>
				<td width="120"><a href="http://www.mvland.com/watch/mv=<?=$url?>"><?=$name?></a></td>
				<td width="100"><?=$singer?></td>
				<td width="100"><a href="http://www.mvland.com/member/user=<?=$user?>"><?=$user?></a></td>				
				<td width="145"><?=$date?></a></td>
				<td width="48"><form action="http://www.mvland.com/favorites_remove" method="post" onsubmit="return (confirm('確定要移除嗎?'));"><input type="hidden" name="fav_id" value="<?=$fav_id?>" /><input type="hidden" name="song_url" value="<?=$url?>" /><input type="submit" value="移除" style="width: 50px; height: 20px; font-size: 12px; padding-top: 1px;" /></form></a></td>				
			</tr>
		<?		
		} ?>
	</tbody>
	</table>
<?
	} else {
?>
	<span style="font-size: 13px;">您尚未存入任何MV到我的最愛. 回<a href="http://www.mvland.com/my_account"><strong>我的帳號</strong></a>.</span>
<? 
	}
?>
<div style="padding-top: 30px;"></div>			
<?
include("includes/footer.php")
?>