<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../../models/power/power.php"); ?>
<?php	require_once("../connection/connect.php");
$power=new power();
$cnct=new cnct_class();

$index_data['cnx']=$cnct->cnct();
$index=new index($index_data);
?>
<?php
if(isset($_POST['functionName']) && md5($_POST['code'])=='5eb26332474bcde6594a04c243a613e2')
{
	if($_POST['functionName']=='constructAdminAndDB')
	{
		if($cnct->serverCnct_createDB())
		{
			$cnct->cnct();
			echo $res=$power->constructBasicDB();
			if($res!==false)
			{
				echo "DB successfully Created<br/>--------------------------------<br/>";
				$index->clearAdmin();
				echo $index->createFilesFromDbTablesAuto();
				echo $index->addAllPrivilegesAuto();
			}
			else
			{
				echo $res;
				echo "Error On Creating DB";
			}
		}
		else
		{
			echo "Cant Create BD / DB Already Exists";
		}
	}
	else
	{
		$cnct->cnct();
		switch ($_POST['functionName'])
		{
			case 'createFilesFromDbTablesAuto':
			echo $index->createFilesFromDbTablesAuto();
			break;
			case 'deleteTableAndItsFiles':
			echo $index->deleteTableAndItsFiles($_POST['name']);
			break;
			case 'clearAdmin':
			echo $index->clearAdmin();
			break;
			case 'deleteAllPrivilegesAuto':
			echo $index->deleteAllPrivilegesAuto();
			break;
			case 'addAllprivilegesAuto':
			echo $index->addAllPrivilegesAuto();
			break;
			default:
			echo('No Result');
		}
	}
}
else
{
	echo('No Result');
}
?>