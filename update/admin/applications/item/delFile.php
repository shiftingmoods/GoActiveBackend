<?php	
	session_start();
	require_once("../connection/connect.php");
	require_once("../../models/index/index.php");
	require_once("../../models/index/simpleImage.php");
	
	$cnct=new cnct_class();
	$cnx=$cnct->cnct();
	$index_data['cnx']=$cnx;
	$index=new index($index_data); 
	$image=new simpleImage($index_data);
	
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