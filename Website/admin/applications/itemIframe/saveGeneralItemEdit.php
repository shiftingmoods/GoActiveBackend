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
//$index->show($_FILES);
?>
<html>
<body STYLE="background-color:transparent;color:red;">
<p style="vertical-align:middle;border:1px solid gray;">
<?php
	$data = array();
	$note='<span style="font-size:13px;color:lightred!important" >';
	if(isset($_POST))
	{
		foreach($_POST as $key=>$post)
		{
			if($key!='table' && $key!='item')
			{
//**************************** save schedule as array ********************************
				if(strpos($key,'schedule_')!==false)								//
				{																	//
					$setSchedule=true;												//
					$schedule[substr($key,9)]=$post;								//
				}																	//
				else																//
//**************************** save schedule as array ********************************
				$data[$key]=$post;
			}
		}
	}
//**************************** save schedule as array ********************************
	if(isset($setSchedule))															//
	{																				//
		$data['schedule']=serialize($schedule);										//
	}																				//
//**************************** save schedule as array ********************************
	//$index->show($schedule);
	$id=$_POST['item'];
	$itemExist=$index->checkGeneralItemIfExist($id,$data,$table);
	if($itemExist)
	{
		$note=$index->toView($Table).' '.$itemExist.' Exists';
	}
	else
	{
//******************************************* IN CASE THERE IS IMAGE *****************************************
		if(isset($_FILES))
		{
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
							$item=$index->getGeneralItemById($id,$table);
							$idImage=$item[$id]['image_id'];
							$imgItem=$index->getGeneralItemById($idImage,'image');
							$oldImage=$imgItem[$idImage]['name'];
							if($idImage!='0')
							{
								$index->editGeneralItem($idImage,$dataImage,'image');
								$image->deleteImageFile($oldImage);
							}
							else
							{
								$data['image_id']=$index->addGeneralItem($dataImage,'image');
							}
						}
					}
				}
			}
		}
		
//******************************************* END IN CASE THERE IS IMAGE *****************************************
		if(isset($_GET['newLang']))
		{
			$itemEdited =$index->editGeneralItemNewLang($id,$data,$table);
			if($itemEdited)
			{
				$note=$index->toView($Table).' New Language Added';
			}
			else
			{die(mysql_error());
				if(strpos(mysql_error(),'Duplicate entry')=== false)
				{
					$note=$index->toView($Table).' New Language Not Added,Please Try Again';
				}
				else
				{ 
					$note=$index->toView($Table).' Language Exists,Try Edit Later';
				}
			}
		}
		else
		{
			if($index->hasLanguage($table))
			{
				$itemEdited =$index->editGeneralItemByIdAndLangId($id,$data,$table);
			}
			else
			{
				$itemEdited =$index->editGeneralItem($id,$data,$table);
			}		
			if($itemEdited)
			{
				$note=$index->toView($Table).' Edited';
			}
			else
			{
				$note=$index->toView($Table).' Not Edited,Please Try Again';
			}
		}
	}
	$note=$note.'</span>';
	echo($note);
?>
</p>
</body>
</html>