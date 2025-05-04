<?php
require_once('../../models/connection/mysql_connect.php');
require_once('../../models/power/power.php');
require_once('../../public/constants.php');

$power = new power();
$connect = new db_connection();

class cnct_class
{
	function cnct()
	{
		global $connect;
		$C= 'ConstantsControl_p_admin';
		$C = new ReflectionClass($C);
		if (!isset($mod))
		{
			$mod = 'dev';
		}
		$mod = $C->getConstant('CATEGORY');
		if ($mod == 'prod')
		{
			$db = $connect->db_connect($C->getConstant('HOST_prod'),$C->getConstant('ROOT_prod'),$C->getConstant('PASS_prod'),$C->getConstant('DB_prod'));
		}
		else
		{
			$db = $connect->db_connect($C->getConstant('HOST_dev'),$C->getConstant('ROOT_dev'),$C->getConstant('PASS_dev'),$C->getConstant('DB_dev'));
		}
		$sql="SET time_zone = '".$C->getConstant('TIMEZONE')."'";
		mysqli_query($db,$sql);
		return $db;
	}
	
	function serverCnct_createDB()
	{
		global $power, $connect;
		$C= 'ConstantsControl_p_admin';
		$C = new ReflectionClass($C);
		if (!isset($mod))
		{
			$mod = 'dev';
		}
		$mod = $C->getConstant('CATEGORY');
		if ($mod == 'prod')
		{
			$host=$C->getConstant('HOST_prod');
			$root=$C->getConstant('ROOT_prod');
			$pass=$C->getConstant('PASS_prod');
			$db=$C->getConstant('DB_prod');
		}
		else
		{
			$host=$C->getConstant('HOST_dev');
			$root=$C->getConstant('ROOT_dev');
			$pass=$C->getConstant('PASS_dev');
			$db=$C->getConstant('DB_dev');
		}

		$serv_con = $connect->server_connect($host,$root,$pass);
		$data['db']=$db;
		$data['con']=$serv_con;
		$res=$power->create_db($data);
		if($res)
		{
			$cox = $this->cnct();
			return $cox;
		}
		return false;
	}
}
?>