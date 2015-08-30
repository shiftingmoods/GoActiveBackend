<?php 
class db_connection
{
	function db_connect($dbserver,$dbuser,$dbpass,$dbname)
	{
		$db = mysql_connect ($dbserver, $dbuser, $dbpass) or die ("unable to connect to database");
		mysql_select_db($dbname,$db)or die("Unable to connect to the server") ;
		mysql_query('SET CHARACTER SET utf8');
		mysql_set_charset('utf8',$db);
		return $db;
	}
	function server_connect($dbserver,$dbuser,$dbpass)
	{
		$db = mysql_connect ($dbserver, $dbuser, $dbpass) or die ("unable to connect to server");
		return $db;
	}
}
?>