<?php
require_once('../models/connection/mysql_connect.php');
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
			$cnx = $connect->db_connect($C->getConstant('HOST_prod'),$C->getConstant('ROOT_prod'),$C->getConstant('PASS_prod'),$C->getConstant('DB_prod'));
		}
		else
		{
			$cnx = $connect->db_connect($C->getConstant('HOST_dev'),$C->getConstant('ROOT_dev'),$C->getConstant('PASS_dev'),$C->getConstant('DB_dev'));
		}
		$sql="SET time_zone = '".$C->getConstant('TIMEZONE')."'";
		mysqli_query($cnx,$sql);
		return $cnx;
	}
}
?>