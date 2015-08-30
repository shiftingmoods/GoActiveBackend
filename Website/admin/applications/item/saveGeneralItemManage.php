<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index();
$cnct=new cnct_class();
$cnct->cnct();
$table=$_POST['table'];
$Table=$index->capitalize($table);
$Ftables=explode('_',$table);
$PT1=$Ftables[0];
$PT2=$Ftables[1];
?>
<?php
	$data = array();
	if(!isset($_POST))
	{
		die('No Data Sent');
	}
	//var_dump($data);die();
	//var_dump($_POST);die();
	/*
	$ItemToEditTable=array_keys($data);
	$ItemToEditTable=$ItemToEditTable[0];
	$ItemToEditId=array_values($data);
	$ItemToEditId=$ItemToEditId[0];
	*/
	$filterData['keyword']=$_POST[$PT1.'_id'];
	$filterData['filterBy']=$PT1.'_id';
	$old=$index->getAllGeneralExactItemsWithJoins($filterData,$table);
	$cols=$index->getGeneralColums($table);
	$PRI=$cols['primaryKeys'];
	$PRI=$PRI[0];
	$oldData=array();
	foreach($old as $key3=>$val3)
	{
		$oldData[$key3]=$val3[$PRI];
	}
	if($index->deleteGeneralItems($oldData,$table))
	{
		$newData[$PT1.'_id']=$_POST[$PT1.'_id'];//setting the new data would be added to the table. this represents the selected item to be edited
		//var_dump($old);die();
				foreach($_POST[$PT2] as $key2=>$val)
				{
					$newData[$PT2.'_id']=$val;//setting the new data would be added to the table. this represents the checked sub-item that is assigned to be item edited
					//var_dump($newData);die();
					if(!$index->addGeneralItem($newData,$table))
					{
						$result=false;
						break;
					}
					else
					{
						$result=true;
					}
				}
				if($result)
				{
					header('Location: ../../index/'.$table.'.php?note='.$index->toView($Table).' Edited');
				}
				else
				{
					header('Location: ../../index/'.$table.'.php?note='.$index->toView($table).' Not Edited,Please Try Again');
				}
		
	}
	else
	{
		header('Location: ../../index/'.$table.'.php?note='.$index->toView($Table).' Not Edited,Please Try Again');
	}
	//var_dump($oldData);die();
	
	/*
	$itemExist=$index->checkGeneralItemIfExist($id,$data,$table);
	if($itemExist)
	{
		header('Location: ../../index/'.$table.'.php?note='.$index->toView($Table).' Name Exist');
		exit;
	}
	else
	{
		$itemEdited =$index->editGeneralItem($id,$data,$table);
		if($itemEdited)
		{
			header('Location: ../../index/'.$table.'.php?note='.$index->toView($Table).' Edited');
		}
		else
		{
			header('Location: ../../index/'.$table.'.php?note='.$index->toView($Table).' Not Edited,Please Try Again');
		}
	}
	*/
?>