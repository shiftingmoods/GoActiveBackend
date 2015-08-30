<?php session_start(); ?>
<?php require_once("../../models/user/index.php");
$user=new user();
?>
<?php	require_once("../connection/connect.php");
$cnct=new cnct_class();
$cnct->cnct();
?>
<?php
$id=$_SESSION['control_p_login_id'];
if($user->logout($id))
{
	
	if(session_destroy())
	{	
		header('Location:../../index/index.php');
	}
	else
	{
		header('Location:../../index/index.php?note=Please Try Again');
	}
}
else
{
	header('Location:../../index/index.php?note=Please Try Again');
}
?>