<h1>歌手列表</h1>	
<div style="margin: 20px;">
    <div class="table_header">男歌手</div>
	<div class="table_content">
	<table cellspacing="0" class="all_singer_table">
		<tr>
		<?
			$query = "SELECT DISTINCT singer, type FROM songs WHERE type='male' AND active=1 ORDER BY views DESC";
			$result = mysql_query($query);
			if ($result && mysql_num_rows($result)) {
				$r = 1;
				while($row = mysql_fetch_array($result)) {
					$singer = $row['singer'];
					$type = $row['type'];
					if($r % 2) { // r is an odd number ?> 				
						<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td> <?	
						$r++;		
					} else { // r is an even number
						if($r != 300) { ?> 					
							<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td></tr><tr> <?	
							$r++;
						} else { ?>					
							<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td></tr> <?					
						}
					}
				}
			}
		?>
	</table>
	</div>

    <div class="table_header">女歌手</div>
	<div class="table_content">
	<table cellspacing="0" class="all_singer_table">
		<tr>
		<?
			$query = "SELECT DISTINCT singer, type FROM songs WHERE type='female' AND active=1 ORDER BY views DESC";
			$result = mysql_query($query);
			if ($result && mysql_num_rows($result)) {
				$r = 1;
				while($row = mysql_fetch_array($result)) {
					$singer = $row['singer'];
					$type = $row['type'];
					if($r % 2) { // r is an odd number ?> 				
						<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td> <?	
						$r++;		
					} else { // r is an even number
						if($r != 300) { ?> 					
							<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td></tr><tr> <?	
							$r++;
						} else { ?>					
							<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td></tr> <?					
						}
					}
				}
			}
		?>
	</table>
	</div>
	
    <div class="table_header">團體</div>    
	<div class="table_content">
	<table cellspacing="0" class="all_singer_table">
		<tr>
		<?
			$query = "SELECT DISTINCT singer, type FROM songs WHERE type='group' AND active=1 ORDER BY views DESC";
			$result = mysql_query($query);
			if ($result && mysql_num_rows($result)) {
				$r = 1;
				while($row = mysql_fetch_array($result)) {
					$singer = $row['singer'];
					$type = $row['type'];
					if($r % 2) { // r is an odd number ?> 				
						<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td> <?	
						$r++;		
					} else { // r is an even number
						if($r != 300) { ?> 					
							<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td></tr><tr> <?	
							$r++;
						} else { ?>					
							<td width="50%"><a href="http://www.mvland.com/<?=$type?>/singer=<?=$singer?>"><?=$singer?></a></td></tr> <?					
						}
					}
				}
			}
		?>
	</table>
	</div>	
</div>	