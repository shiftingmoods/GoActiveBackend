<?php
if(file_exists('../models/connection/mysql_connect.php'))
{
	require_once('../models/connection/mysql_connect.php');
}
elseif(file_exists('../../models/connection/mysql_connect.php'))
{
	require_once('../../models/connection/mysql_connect.php');
}
else
{
die('Missing Files, Please Try Again');
}
require_once('constants.php');
class cnct_class
{
	function cnct()
	{
		$connect = new db_connection();
		$C= 'ConstantsControl_p_admin';
		$C = new ReflectionClass($C);
		if (!isset($mod))
		{
			$mod = 'dev';
		}
		//$mod = 'production';
		$mod = $C->getConstant('CATEGORY');
		if ($mod == 'prod')
		{
			$db = $connect->db_connect($C->getConstant('HOST_prod'),$C->getConstant('ROOT_prod'),$C->getConstant('PASS_prod'),$C->getConstant('DB_prod'));
		}
		else
		{
			$db = $connect->db_connect($C->getConstant('HOST_dev'),$C->getConstant('ROOT_dev'),$C->getConstant('PASS_dev'),$C->getConstant('DB_dev'));
		}
	}
}
?>