<?php
require_once('../../models/connection/mysql_connect.php');
require_once('../../public/constants.php');
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
	function serverCnct_createDB()
	{
		//return true;
		$connect = new db_connection();
		$C= 'ConstantsControl_p_admin';
		$C = new ReflectionClass($C);
		$serv = $connect->server_connect($C->getConstant('HOST_prod'),$C->getConstant('ROOT_prod'),$C->getConstant('PASS_prod'));
		$sql='CREATE DATABASE `'.$C->getConstant('DB_prod').'` CHARACTER SET utf8 COLLATE utf8_unicode_ci';
		$result=mysql_query($sql);
		if($result)
		{
			$cnx = new mysqli($C->getConstant('HOST_prod'),$C->getConstant('ROOT_prod'), $C->getConstant('PASS_prod'), $C->getConstant('DB_prod'));
			return $cnx;
		}
		return false;
	}
}
?>