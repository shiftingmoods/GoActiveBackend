<?php	require_once("../../models/index/index.php");
$index=new index(); ?>
<?php	require_once("../connection/connect.php");
$cnct=new cnct_class();
$cnct->cnct();
$table=$_POST['table'];
$Table=$index->capitalize($table);
?>
<?php
	$data = array();
	if(isset($_POST))
	{
		foreach($_POST as $key=>$post)
		{
			if($key!='table' && $key!='checkAll')
			{
				$data[$key]=$post;
			}
		}
	}
	//var_dump($data);die();
	$itemsDeleted = $index->deleteGeneralItems($data,$table);
	if($itemsDeleted)
	{
		header('Location: ../../index/'.$table.'.php?note=All Selected Was Deleted');
	}
	else
	{
		header('Location: ../../index/'.$table.'.php?note=Sorry, Please Try Again');
	}
?>