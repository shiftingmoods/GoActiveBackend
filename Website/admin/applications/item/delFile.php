<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index(); ?>
<?php	require_once("../../models/index/simpleImage.php");
$image=new simpleImage();
$cnct=new cnct_class();
$cnct->cnct();
$table=$_GET['table'];
$Table=$index->capitalize($table);
$id=$_GET['id'];
$file=$_GET['file'];
?>
<?php
	if($index->deleteFile($table.'/'.$id.'/'.$file))
	{	
		header('Location: ../../index/manageFiles.php?table='.$table.'&id='.$id.'&note='.$index->toView($Table).' File Deleted');
	}
	else
	{
		header('Location: ../../index/manageFiles.php?table='.$table.'&id='.$id.'&note='.$index->toView($Table).' File Not Deleted,Please Try Again');
	}
?>