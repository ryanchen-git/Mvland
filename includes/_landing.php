<?php

session_start();

if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	DEFINE ('DB_HOST', 'localhost');
	DEFINE ('DB_USER', 'root');
	DEFINE ('DB_PASSWORD', '');
	DEFINE ('DB_NAME', 'mvland');
} else {
	DEFINE ('DB_HOST', 'localhost');
	DEFINE ('DB_USER', 'mvlandco_rckc26');
	DEFINE ('DB_PASSWORD', 'rc2600');
	DEFINE ('DB_NAME', 'mvlandco_music');
}

$link = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL; ' . mysql_connect());
mysql_query("SET NAMES 'utf8'");

@mysql_select_db(DB_NAME) OR die ('Could not select the database: ' . mysql_error());

/*
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', 'rc2600');
DEFINE ('DB_NAME', 'music');
*/

?>