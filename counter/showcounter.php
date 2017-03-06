
<?php
$file_count = fopen('counter/count.db', 'rb');
	$data = '';
	while (!feof($file_count)) $data .= fread($file_count, 4096);
	fclose($file_count);
	list($today, $yesterday, $total, $date, $days) = split("%", $data);
	//echo $today;
	//echo $yesterday;
	echo $total;
	//echo ceil($total/$days);
?>