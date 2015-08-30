<?php	session_start(); ?>
<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index(); ?>
<?php	require_once("../../models/index/simpleImage.php");
$image=new simpleImage();
$cnct=new cnct_class();
$cnct->cnct();
//$index->show($_FILES);
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
foreach($_POST as $table=>$postTable)
{
$data=array();
$id=$postTable['item'];
unset($postTable['item']);
			foreach($postTable as $key=>$post)
			{
//**************************** save schedule as array ********************************
					if(strpos($key,'schedule_')!==false)							//
					{																//
						$setSchedule=true;											//
						$schedule[substr($key,9)]=$post;							//
					}																//
					elseif															//
//**************************** save schedule as array ********************************
//**************************** save table as array ********************************
					(strpos($key,'table_')!==false)									//
					{																//
						$setTable=true;												//
						$dataTable[substr($key,6)]=$post;							//
					}																//
					elseif															//
//**************************** save schedule as array ********************************
//**************************** save table as array ********************************
					(strpos($key,'list_')!==false)									//
					{																//
						$setList=true;												//
						$dataList[substr($key,5)]=$post;							//
					}																//
					else															//
//**************************** save schedule as array ********************************
					{$data[$key]=$post;}
			}
		
	
//**************************** save schedule as array ********************************
	if(isset($setSchedule))															//
	{																				//
		$data['schedule']=serialize($schedule);										//
	}																				//
//**************************** save schedule as array ********************************
	if(isset($_FILES[$table]))
	{
		$FILE=$_FILES[$table];
		foreach($FILE as $property=>$nam)
		{
			$keyN=array_keys($nam);
			$keyN=$keyN[0];
			$TempFILE[$keyN][$property]=$FILE[$property][$keyN];
		}
		$FILE=$TempFILE;
		if(array_key_exists('image_id',$FILE))
		{
			if($FILE['image_id']['tmp_name']!='')
			{
				$dataImage = array();
				$dataTmp = array();
				if($dataTmp['name']=$image->uploadImage($FILE,'image_id'))
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
	
	$itemExist=$index->checkGeneralItemIfExist($id,$data,$table);
	if($itemExist)
	{
		$tablesData[$table] = "exists";
		$existData[$table]=$itemExist;
	}
	else
	{
	
		$tablesData[$table] = $data;
		$ids[$table]=$id;
	}
	
}
}
//$index->show($_SESSION);
?>
<?php
if(isset($tablesData) && isset($_SESSION['postData']))
{
	//************************* general Variables **************************
	$seq=$_SESSION['postData']['seq'];
	//************************* general Variables **************************
	if(count($tablesData)!=count($seq))
	{
		unset($tablesData);
		echo'count'; die();
	}
}
else
{
	echo 'Already Edited, Reload The Page To Edit Again'; die();
}
//$index->show($_SESSION);
	$post=$tablesData;
	$added=array();
/*	
	foreach($tablesData as $table0=>$data0)//fill the rest
	{
		if(!is_array($data0))
		{
			if($data0=="exists")
			{
				echo $index->toView($table).' Already Exists<br/>';
				
			}
		}
	}
*/
	foreach($seq as $table=>$parents)//fill the rest
	{
		if($tablesData[$table]!="exists")
		{
			$added[$table]=$index->editGeneralItem($ids[$table],$post[$table],$table);
		}
		else
		{
			if(isset($existData[$table]))
			{
				echo $index->toView($table).' '.$existData[$table].' Already Exists<br/>';
			}
		}
	}
	if(mysql_error()=='')
	{
		unset($tablesData);
		//unset($_SESSION['postData']);
		echo 'Changes Saved';
	}
	else
	{
		//die(mysql_error());
		echo 'Please Try Again';
	}
?>
</p>
</body>
</html>