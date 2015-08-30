<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index();
$cnct=new cnct_class();
$cnct->cnct();
//$index->show($_FILES);
?>
<?php
if(isset($_POST))
{
	if($index->isLanguageDefined(array('language_id'=>$_POST['language_id'],'id'=>$_POST['id']),$_POST['table']))
	{
		echo 'true';
	}
	else
	{
		echo'false';
	}
}
?>