<?php
	require_once("../connection/connect.php");
	require_once("../../models/index/index.php");
	
	$cnct=new cnct_class();
	$cnx=$cnct->cnct();
	$index_data['cnx']=$cnx;
	$index=new index($index_data); 
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