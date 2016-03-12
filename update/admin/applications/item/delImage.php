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
	$item_id=$_GET['item_id'];
	$file=$_GET['file'];
	$data[]=$id;
?>
<?php
	if($index->deleteGeneralItems($data,'image_to_'.$table))
	{	
		header('Location: ../../index/manageImages.php?table='.$table.'&id='.$item_id.'&note='.$index->toView($Table).' Image Deleted');
	}
	else
	{
		header('Location: ../../index/manageImages.php?table='.$table.'&id='.$item_id.'&note='.$index->toView($Table).' Image Not Deleted,Please Try Again');
	}
?>