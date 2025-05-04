<?php 
class db_connection
{
	function db_connect($dbserver,$dbuser,$dbpass,$dbname)
	{
		$db = mysqli_connect ($dbserver, $dbuser, $dbpass, $dbname) or die ("unable to connect to database");
		//mysql_select_db($dbname,$db)or die("Unable to connect to the server") ;
		mysqli_query($db,'SET CHARACTER SET utf8');
		mysqli_set_charset($db,'utf8');
		return $db;
	}
	function server_connect($dbserver, $dbuser, $dbpass)
	{
		$con = new mysqli($dbserver, $dbuser, $dbpass);
	
		if ($con->connect_error) {
			die("Unable to connect to server: " . $con->connect_error);
		}
	
		return $con;
	}
}
?>