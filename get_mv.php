<?php
include("includes/_landing.php");

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
}

?>