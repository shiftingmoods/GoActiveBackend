<?php session_start(); ?>
<?php require_once("../../models/user/index.php"); ?>
<?php require_once("../../models/index/index.php");
$user=new user();
$index=new index();
?>
<?php require_once("../connection/connect.php");
$cnct=new cnct_class();
$cnct->cnct();
?>
<?php
$ip=$_SERVER['REMOTE_ADDR'];
$user_agent=$_SERVER['HTTP_USER_AGENT'];
//$index->show($_POST);
if($info=$user->login($_POST))
{
	$info['ip']=$ip;
	$info['user_agent']=$user_agent;
	$info['online']='Yes';
	$info['start_date']=date("F j, Y, g:i a");
	$info['end_date']='PENDING';
	//var_dump($info); die();
	if($id=$index->addGeneralItem($info,$table='control_p_login'))
	{	
		if(isset($info['username']))	
		{
			$_SESSION['username']=$info['username'];	
		}	
		else	
		{
			$_SESSION['email']=$info['email'];	
		}
			$_SESSION['control_p_group_id']=$info['control_p_group_id'];	
			$_SESSION['control_p_login_id']=$id;	
			$_SESSION['id']=$info['control_p_admin'];	
			$_SESSION['lang']='1';			
			?>
			<script language="javascript" >
			window.location='../../index/index.php';
			</script>
			<?php
			//$index->show($_SESSION);
			die('Please Enable Javascript To Continue1');
			//header('Location:../../index/index.php');
	}
	else
	{	
	?>
		<script language="javascript" >
		window.location='../../index/index.php?note=Login Info Was Not Stored';
		</script>
	<?php
		die('Please Enable Javascript To Continue2');
		//header('Location:../../index/index.php?note=Login Info Was Not Stored');
	}
}
else
{
	?>
		<script language="javascript" >
		window.location='../../index/login.php?note=Bad Username/Password';
		</script>
	<?php
		die('Please Enable Javascript To Continue3');
	//header('Location:../../index/login.php?note=Bad Username/Password');
}
?>
