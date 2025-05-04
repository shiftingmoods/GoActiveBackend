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
	
	$table=$_POST['table'];
	$Table=$index->capitalize($table);
	$id=$_POST['id'];
	$keep_parent='';
if(isset($_POST['keep_parent']))// keep the parent table selected while adding childs
{
	$keep_parent='&'.$_POST['keep_parent'].'='.$_POST[$_POST['keep_parent']];
}
?>
<?php
	//$index->show($_FILES);
	$itemAdded=0;
	$done=true;
	if(isset($_FILES))
	{
		foreach($_FILES as $key=>$post)
		{
			if($post['error']=='0')
			{
				$post['table']=$table;
				$post['id']=$id;
				//$index->show($post);
				$res=$index->uploadFile($post);
				if($res)
				{
					$done=false;
				}// $name,$status 
			}
		}
	}
	
	if(!$done)
	{	
		header('Location: ../../index/manageFiles.php?table='.$table.'&id='.$id.'&note='.$index->toView($Table).' Files Uploaded'.$keep_parent);
	}
	else
	{
		header('Location: ../../index/manageFiles.php?table='.$table.'&id='.$id.'&note='.$index->toView($Table).' Files Not Uploaded,Please Try Again'.$keep_parent);
	}
?>