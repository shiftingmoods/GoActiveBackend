<?php session_start(); ?>
<?php
/*	require_once("../../models/user/index.php");
		$user=new user();
?>
<?php	require_once("../connection/connect.php");
		$cnct=new cnct_class();
		$cnct->cnct();
*/
?>
<?php
	if(isset($_SESSION['XC']))
	{
		unset($_SESSION['XC']);
	}
	header('Location:../../index/index.php');
?>