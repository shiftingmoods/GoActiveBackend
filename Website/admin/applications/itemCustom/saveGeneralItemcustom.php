<?php	session_start(); ?>
<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index(); ?>
<?php	require_once("../../models/index/simpleImage.php");
$image=new simpleImage();
$cnct=new cnct_class();
$cnct->cnct();
//$index->show($_POST);
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
$data = array();
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
						$data['image_id']=$index->addGeneralItem($dataImage,'image');
					}
				}
			}
		}
	}
	$itemExist=$index->checkGeneralItemIfExist($id='',$data,$table);
	if($itemExist)
	{
		$tablesData[$table] = "exists";
		$existData[$table]=$itemExist;
	}
	else
	{
		$tablesData[$table] = $data;
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
	echo 'No Session'; die();
}
//$index->show($_SESSION);
	$post=$tablesData;
	$added=array();
	$min=0;
	$max=0;//maximum number of parent forign keys in table
	foreach($seq as $table1=>$parents1)//fill the rest
	{
		if(count($parents1)>$max){ $max=count($parents1); }
	}
	if(isset($existData))
	{
		foreach($existData as $tbl=>$out)
		{
			echo $index->toView($tbl).' '.$out.' Already Exists<br/>';
		}
		echo 'Please Try Again';
		die();
	}
	foreach($seq as $table=>$parents)//fill the rest
	{	
		$thisId=$table.'_id';
		$added[$table]=$index->addGeneralItem($post[$table],$table);
		if(!$added[$table]){ $error=true; }
		foreach($seq as $table2=>$parents2)//add ids
		{ 
			switch(count($parents2))
			{
				case 0:
				break;
				case 1:
				if($parents2[0]==$thisId)
				{
					$post[$table2][$table.'_id']=$added[$table];
				}
				break;
				default :
				foreach($parents2 as $ind=>$thisParent)
				{
					if($thisParent==$thisId)
					{
						$post[$table2][$thisParent];
					}
				}
				
			}
		}
	}
	if(!isset($error))
	{
		unset($tablesData);
		//unset($_SESSION['postData']);
		echo 'Successfully Added';
		$seqInd=array_keys($seq);
		$mainTable=$seqInd[0];
		if(file_exists('../../index/edit'.ucfirst($mainTable).'_language.php'))
		{
			echo('<script language="javascript" >if(confirm("You Must Add In Different Languages \n Navigate Now?")) { parent.location="../../index/edit'.ucfirst($mainTable).'_language.php?id='.$added[$mainTable].'"; }</script>');
		}
	}
	else
	{
		foreach(array_reverse($added) as $Dtable=>$Did)
		{
			$index->deleteGeneralItems(array(0=>$Did),$Dtable);
		}
		echo $error .' Please Try Again';
	}
?>
</p>
</body>
</html>