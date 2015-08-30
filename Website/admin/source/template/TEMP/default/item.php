//************************************* check if the user control_p_group allow him to enter this page *******
$path_parts = pathinfo(__FILE__);
$page=$path_parts['filename'];
$data['control_p_privilege']=$page;
$data['control_p_group_id']=$_SESSION['control_p_group_id'];
if(!$index->isAllowed($data))
{
if(strpos($_SERVER["HTTP_REFERER"],'?')===false) $char='?' ; else $char='&'; header('Location:'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition');
}

//************************************* check if the user control_p_group allow him to enter this page *******
//********************************** General Constants  ********************************************
$table=$page;
$Table=$index->capitalize($table);
//********************************** End Of General Constants  ********************************************
//******************************************************** sub menu *****************************
$Mdata['page']=$page;
$menu=$index->getMenuList($Mdata);
?>
<div id="header" >

				<ul id="menu" align="left" style="padding-top:6px">
<?php
	foreach($menu['menu_pages'] as $menu_id=>$mnu)
	{
		$display_name=$menu['menu_display_names'][$menu_id];
		//var_dump($menu['menu_pages']); die();
?>
		
<?php $res=$index->isAllowed_2($_SESSION['control_p_group_id'],$mnu); if($res) { ?><li ><span ><a href="<?php echo $mnu;?>.php"><?php if($display_name=="")echo $index->toView($mnu); else { echo $display_name; }?> >></a></span></li><?php } ?>
		
<?php
	}
?>
		</ul>
		

</div>
<?php
//******************************************************** sub menu *****************************
//******************************************************** Filters Sort Search********************
$filterData['filterBy']='';
$filterData['keyword']='';
$filterData['order']='';
$filterData['orderBy']='';
$filterData['perPage']=5;
if(isset($_GET))
{
	foreach($_GET as $key=>$value)
	{
		$filterData[$key]=$value;
	}
}
//******************************************** filter of language ******************************
if($index->hasLanguage($table))
{
$filter1['filterBy']='language_id';
$filter1['keyword']=$lang;
$filter1['exact']=true;
$filterData['multiFilterBy'][]=$filter1;
}

if(isset($_GET['filterBy']))
{
	$filter2['filterBy']=$_GET['filterBy'];
	$filter2['keyword']=$_GET['keyword'];
	$filter2['exact']=false;
	$filterData['multiFilterBy'][]=$filter2;
}
//******************************************** filter of language ******************************
//$index->show($filterData['multiFilterBy']);
//******************************************************** Filters ********************
//**********************************************************paging code*******************

if(isset($_GET['page']))
{
	$curPage=$_GET['page'];
	$from=$curPage*$filterData['perPage'];
	$LIMIT=" LIMIT ".$from.", ".$filterData['perPage']." ";
	$next=$curPage+1;
	$prev=$curPage-1;
	if($prev<0){$prev='0';}
}
else
{
	$curPage='0';
	$LIMIT=" LIMIT 0, ".$filterData['perPage']." ";
	$next='1';
	$prev='0';

}
$filterData['limit']=$LIMIT;

//**********************************************************paging end*******************

$tableColumns=$index->getViewColumns($table);
$column=$index->getGeneralColums($table);
$keys=$column['keys'];
$filterKeys=$column['filterKeys'];
$filterData['filterKeys']=$filterKeys;
$items=$index->getAllGeneralItemsWithJoins($filterData,$table);
$filterData['limit']='';
$all=count($index->getAllGeneralItemsWithJoins($filterData,$table));
$all=ceil($all/$filterData['perPage']);

$PRI=$column['primaryKeys'];
$PRI=$PRI[0];
?>
<link href="../public/css/style.css" rel="stylesheet" type="text/css">
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="../public/css/style.ie7.css"/><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="../public/css/style.ie7.css"/><![endif]-->
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="../public/css/style.ie7.css"/><![endif]-->
<!----------------------------------  paging ------------------------------------------------->		
<script language="javascript" src="../public/js/paging.js" type="text/javascript"></script>
<!----------------------------------  paging end------------------------------------------------->
<div >
<ul id="menu3" >
				<li ><span style="width:150px"><input type="button" style="width:<?php echo(strlen('Add New'.$table)*9); ?>px" onClick="window.location='add<?php echo $Table; ?>.php'" value="Add New<?php echo ' '.$index->toView($table); ?>" ></span></li>
</ul>
<table  id="menu3" style="width:100%" >
	<tr>
		<td>
			<!------------------------------------------- paging ---------------------------------->
			<form name="pagingForm" method="get" action="<?php echo $table ?>.php" >
			<!------------------------------------------- Filter ---------------------------------->
			<label><input type="button" value="Search" style="width:60px" onclick="pageDir('')" > </label><input type="text" name="keyword" value="<?php echo $filterData['keyword']; ?>">
			<label>In</label>
			<select name="filterBy" onchange="pageDir('')">
			<option value="all">All Fields</option>
<?php
						foreach($filterKeys as $keyId=>$keyValue)
						{
							
							
							$filter[$keyId]=$keyValue;
							if($keyId=='id')
							{
?>							
								<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['filterBy']) echo " selected"; ?> ><?php echo $index->capitalize($keyId); ?></option>
<?php
							}
							else
							{
?>
								<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['filterBy']) echo " selected"; ?> ><?php echo $index->toView($keyId); ?></option>
<?php
							}
						}
?>
			</select>
<label>Order By: </label>
			<select name="orderBy" onchange="pageDir('')">
<?php
						foreach($filterKeys as $keyId=>$keyValue)
						{
							if($keyId=='id')
							{
?>							
								<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['orderBy']) echo " selected"; ?> ><?php echo $index->capitalize($keyId); ?></option>
<?php
							}
							else
							{
?>
								<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['orderBy']) echo " selected"; ?> ><?php echo $index->toView($keyId); ?></option>
<?php
							}
						}
?>
			</select>
			<label>Order</label>
			<select name="order" onchange="pageDir('')" >
				<option value="ASC"<?php if($filterData['order']=='ASC') echo " selected"; ?> >Asc</option>
				<option value="DESC"<?php if($filterData['order']=='DESC') echo " selected"; ?> >Desc</option>
			</select>
			<label>Show</label>
			<select  style="width:40px" name="perPage" onchange="pageDir('')" >
<?php
				for($j=1 ;$j<30 ;$j++)
				{
?>
				<option value="<?php echo $j; ?>"<?php if($filterData['perPage']==$j) echo " selected"; ?> ><?php echo $j; ?></option>
<?php
				}
?>		
			</select>
			<!------------------------------------------- End filter ---------------------------------->
			<input name="page" type="hidden" value="<?php echo $curPage; ?>" >
			<input type="hidden" name="all" value="<?php echo $all; ?>" >
			</form>
					</td>
	</tr>
	<tr>
	<td>
	</td>
	</tr>

			<!------------------------------------------- paging end ---------------------------------->


	<tr>
		<td  >
			<form method="post" name="form" action="../applications/item/deleteGeneralItems.php" onsubmit="return confirm('Are you sure you want to delete all selected')" >
			<table  border="1px" style="width:100%" >
				<tr>
<?php
						foreach(array_slice($filterKeys, 0, $tableColumns) as $keyId=>$keyValue)//this tr contains the table headers of the constant defined above
						{
							if($keyId!=$PRI)
							{
?>						
								<th><a href="#NULL" onclick="order('<?php echo $keyId; ?>')" ><?php if ($filterData['orderBy']==$keyId){ if($filterData['order']=='DESC') echo'<img width="30px" src="../public/design-images/desc.gif" > '; elseif($filterData['order']=='ASC') echo '<img width="30px" src="../public/design-images/asc.gif" > '; } echo $index->toView($keyId);  ?></a></th>
<?php
							}
							else
							{
?>
								<th ><span style="float:left"><?php if($_SESSION['control_p_group_id']<3) echo '<input type="submit" value="Delete Selected" style="width:102px" ><input type="checkbox" style="width:30px" name="checkAll" onChange="checkAllItems()" /><label> &nbsp All</label></span>';  ?>
								<input type="hidden" value="<?php echo $table; ?>" name="table" >
								</th>
<?php
							}
						}
?>
				</tr>
<?php
					foreach($items as $id=>$value)
					{
?>
				<tr>
<?php
						foreach(array_slice($filterKeys, 0, $tableColumns) as $keyId=>$keyValue)// fill the table with the same loop  that filled the header of the table
						{
							switch($keyId)
							{
								case $PRI://this colums conains a checkbox to multi select the table rows in order to multiple delete

?>
									<td><span style="float:left"><input type="button" style="width:50px" value="Edit" onclick="window.location='edit<?php echo $Table; ?>.php?id=<?php echo $value[$keyId]; ?>'" ><input type="button" style="width:50px" value="View" onclick="window.open('viewItem.php?id=<?php echo $value[$keyId]; ?>&table=<?php echo $table; ?>')" ><?php if($_SESSION['control_p_group_id']<3) echo'<input type="checkbox" style="width:30px" name="item'.$value[$keyId].'" value="'.$value[$keyId].'" />'; ?><?php echo 'Id: '.$value[$keyId]; ?></span><?php if($index->hasFiles($table)) { ?><span  ><input style="width:100px;float:right" type="button" value="<?php echo $Table.' '; ?>Files" onclick="window.location='manageFiles.php?table=<?php echo $table; ?>&id=<?php echo $value[$keyId]; ?>'" ></span><?php } ?></td>
<?php
									break;
								case 'image_id' :
							
?>							
									<td><?php if(file_exists('../../public/images/'.$index->showValue($value[$keyId],$keyId))) { ?><a target="_blank" href="../../public/images/<?php echo $index->showValue($value[$keyId],$keyId); ?>" ><img style="width:50px;height:50px;" src="../../public/images/thumbs/<?php echo $index->showValue($value[$keyId],$keyId); ?>" ></a><?php }else { echo 'No Image'; } ?></td>
<?php
									break;
								default :
									if(strpos($keyId,'_code')===false)
									{
?>
										<td><?php if(!$for=$index->isForien($keyId)) { echo $index->showValue($value[$keyId],$keyId); } else {echo '<a href="'.$for['table'].'.php?filterBy='.$keyId.'&keyword='.$index->showValue($value[$keyId],$keyId).'">'.$index->showValue($value[$keyId],$keyId).'</a>';} ?></td>
<?php
									}
									else
									{
?>										
										<td><?php if(!$for=$index->isForien($keyId)) { echo htmlentities($index->showValue($value[$keyId],$keyId)); } else {echo '<a href="'.$for['table'].'.php?filterBy='.$keyId.'&keyword='.$index->showValue($value[$keyId],$keyId).'">'.$index->showValue($value[$keyId],$keyId).'</a>';} ?></td>	
										
<?php
									}
									break;
							
							}
						}
?>
				</tr>
<?php
					}
?>
			</table>
			</form>

		</td>
	</tr>
	<tr>
	<td>

	</td>
	</tr>
</table>
</div>


<?php if($curPage>0) echo ('<input style="float:left;height:30px" type="button" value="prev" onclick="pageDir(\'prev\')">'); ?>
<?php if($curPage+1<$all) echo ('<input style="float:right;height:30px" type="button" value="next" onclick="pageDir(\'next\')">'); ?>
<?php require_once('../public/layouts/theme_1/_footer.html');