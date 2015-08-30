<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index(); ?>
<?php	require_once("../../models/index/simpleImage.php");
$image=new simpleImage();
$cnct=new cnct_class();
$cnct->cnct();
if(!isset($_POST))
{
	die('No Data Sent');
}
$table=$_POST['table'];
$Table=$index->capitalize($table);
?>
<html>
<body STYLE="background-color:transparent;color:red;">
<p style="vertical-align:middle;border:1px solid gray;">
<?php

	//var_dump($_FILES);die();
	$data = array();
	$note = 'No Post';
	if(isset($_POST))
	{
		foreach($_POST as $key=>$post)
		{
			if($key!='table')
			{
//**************************** save schedule as array ********************************
				if(strpos($key,'schedule_')!==false)								//
				{																	//
					$setSchedule=true;												//
					$schedule[substr($key,9)]=$post;								//
				}																	//
				elseif																//
//**************************** save schedule as array ********************************
//**************************** save table as array ********************************
				(strpos($key,'table_')!==false)										//
				{																	//
					$setTable=true;													//
					$dataTable[substr($key,6)]=$post;								//
				}																	//
				elseif																//
//**************************** save schedule as array ********************************
//**************************** save table as array ********************************
				(strpos($key,'list_')!==false)										//
				{																	//
					$setList=true;													//
					$dataList[substr($key,5)]=$post;								//
				}																	//
				else																//
//**************************** save schedule as array ********************************
				{$data[$key]=$post;}
			}
		}
	}
//**************************** save schedule as array ********************************
	if(isset($setSchedule))															//
	{																				//
		$data['schedule']=serialize($schedule);										//
	}																				//
//**************************** save schedule as array ********************************

//**************************** save table as html ********************************
	
	if(isset($dataTable))															
	{
		$r=$dataTable['rows'];
		$c=$dataTable['cols'];
		
		$data['content_code']='<table cellspacing="0" cellpadding="0" >';

		$data['content_code']=$data['content_code'].'<tr>';
			for($i=0; $i<$c ;$i++)
			{
				$data['content_code']=$data['content_code'].'<th >';

				$data['content_code']=$data['content_code'].$dataTable['h_'.$i];
				$data['content_code']=$data['content_code'].'</th>';
			}
		$data['content_code']=$data['content_code'].'</tr>';
		
		for($j=0; $j<$r ;$j++)
		{
			$data['content_code']=$data['content_code'].'<tr>';
			for($i=0; $i<$c ;$i++)
			{
				$data['content_code']=$data['content_code'].'<td>';

				$data['content_code']=$data['content_code'].$dataTable[$j.'_'.$i];
				$data['content_code']=$data['content_code'].'</td>';
			}
			$data['content_code']=$data['content_code'].'</tr>';
		}
		$data['content_code']=$data['content_code'].'</table>';
	}
//**************************** save table as html ********************************
//**************************** save list as html ********************************
	
	if(isset($dataList))															
	{
		$r=$dataList['rows'];
		$data['content_code']='<ul>';
		for($j=0; $j<$r ;$j++)
		{
			$data['content_code']=$data['content_code'].'<li>';
			$data['content_code']=$data['content_code'].$dataList[$j];
			$data['content_code']=$data['content_code'].'</li>';
		}
		$data['content_code']=$data['content_code'].'</ul>';
	}
//**************************** save table as html ********************************
	//$index->show($data);die();
	if(isset($_FILES))
	{
		if($_FILES['file']['error'])
		{
			die('File Error');
		}
		if(array_key_exists('image_id',$_FILES))
		{
			if($_FILES['image_id']['tmp_name']!='')
			{
				$dataImage = array();
				$dataTmp = array();
				if($dataTmp['name']=$image->uploadImage($_FILES,'image_id'))
				{ 
					if($image->masterImage($dataTmp))
					{
						$dataImage['name']=$dataTmp['name'];
						$data['image_id']=$index->addGeneralItem($dataImage,'image');
					}
				}
			}
		}
//****************************************************************************************
		//$index->show($_FILES);
		if(array_key_exists('file',$_FILES))
		{
			
			if($_FILES['file']['tmp_name']!='')
			{	
				//************ verify file type ***********
				if(isset($_POST['control_p_file_type_id']))
				{
					$validType=false;
					$fileType=$index->getGeneralItemById($_POST['control_p_file_type_id'],'control_p_file_type');
					$fileType=$fileType[$_POST['control_p_file_type_id']];
					$extA=explode(' ',$fileType['extentions']);
					foreach($extA as $ind=>$ext)
					{
						if($ext==$_FILES['file']['type'])
						{
							$validType=true;
							break;
						}
					}
					$note1='';
					if(!$validType)
					{
						$note1.='Invalid File Type,Must Be ( '.str_replace(" "," or ",$fileType["extentions"]).' )<br/>';
					}
					if($fileType['max_file_size']<$_FILES['file']['size'])
					{
						$note1.='Invalid File Size (Must Be < '.$fileType["max_file_size"].'b)<br/>';
					}
					if($note1!=''){ echo $note1; die();}
				}
				//*****************************************
				$dataUploadFile=array();
				if(isset($data['control_p_folder_id']))
				{
					$dataUploadFile['folder']=$index->getFolderPath($data['control_p_folder_id']);
				}
				$dataUploadFile['_FILES']=$_FILES;
				$dataUploadFile['type']=$index->getColumnIndex('file',$table);
				//$dataFile = array();
				//$dataTmpFile = array();
				$fileName=$image->uploadFile($dataUploadFile,'file');
				if(!$fileName)
				{
					echo 'File Was Not Uploaded,Please Try Again'; die();
				}
				$data['path']=$fileName;
			}
		}
//****************************************************************************************
	}
	$itemExist=$index->checkGeneralItemIfExist($id='0',$data,$table);
	if($itemExist)
	{
		$note=$index->toView($Table).' Exist';
	}
	else
	{
		if($itemAdded = $index->addGeneralItem($data,$table))
		{	
			$note=$index->toView($Table).' Added';
		}
		else
		{
			$note=$index->toView($Table).' Not Added,Please Try Again';
		}
	}
	echo($note);
?>
</p>
</body>
</html>