<?php session_start(); ?>
<?php require_once("../../models/user/index.php"); ?>
<?php require_once("../../models/index/index.php");
	$user=new user();$index=new index();?>
<?php require_once("../../public/configuration.php");
	$cnct=new cnct_class();$cnct->cnct();?>
<?php
if($info=$user->login($_POST))
{
		$_SESSION['XC']['id']=$info['id'];
		$_SESSION['XC']['username']=$info['username'];
		header('Location:../../index/index.php');
}
else
{
	if(isset($_SESSION['XC'])) unset($_SESSION['XC']);
	header('Location:../../index/index.php?note=Bad Username/Password');
}
?>