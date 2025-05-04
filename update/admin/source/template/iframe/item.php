//************************************* check if the user control_p_group allow him to enter this page *******
$path_parts = pathinfo(__FILE__);
$page=$path_parts['filename'];
$data['control_p_privilege']=$page;
$data['control_p_group_id']=$_SESSION['control_p_group_id'];
if(!$index->isAllowed($data))
{
if(strpos($_SERVER["HTTP_REFERER"],'?')===false) $char='?' ; else $char='&'; header('Location:'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition');
}
$data['control_p_privilege']='delete_'.$page;
if($index->isAllowed($data))
{
	$canDelete=true;
}
else
{
	$canDelete=false;
}
//************************************* check if the user control_p_group allow him to enter this page *******
//********************************** General Constants  ********************************************
$table=$page;
$Table=$index->capitalize($table);
//********************************** End Of General Constants  ********************************************
//******************************************************** sub menu *****************************
$Mdata['page']=$page;
$menu=$index->getMenuList($Mdata);
$index_Mdata['page']="index";
$common_menu=$index->getMenuList($index_Mdata);
//************************ set lang variable *************************
if(isset($_SESSION['lang']))
{
	$lang=$_SESSION['lang'];
	$index->setVar($lang,'lang');
}
// echo($index->getVar('lang'));
//************************ set lang variable *************************
?>
	<!-----------------------Page Body------------------------>
<?php
//******************************************************** sub menu *****************************
$tableColumns=$index->getViewColumns($table);
$column=$index->getGeneralColums($table);
$keys=$column['keys'];
$filterKeys=$column['filterKeys'];
$PRI=$column['primaryKeys'];
$PRI=$PRI[0];
$tableLang=false;
if($index->checkTableIfExist($table.'_language'))
{
	$tableLang=$table.'_language';
}
//******************************************************** Filters Sort Search********************
$filterData['filterBy']='';
$filterData['keyword']='';
$filterData['order']='DESC';
$filterData['orderBy']=$PRI;
$filterData['perPage']=29;
if(isset($_GET))
{
	foreach($_GET as $key=>$value)
	{
		$filterData[$key]=$value;
	}
}
//$index->show($_SESSION);
//******************************************** filter of language ******************************
/*
no more editing and seeing languages in editItem page
if($index->hasLanguage($table))
{
$filter1['filterBy']='language_id';
$filter1['keyword']=1;
$filter1['exact']=true;
$filterData['multiFilterBy'][]=$filter1;
}
*/
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
$items=$index->getAllGeneralItemsWithJoins($filterData,$table);
$filterData['limit']='';
$all=count($index->getAllGeneralItemsWithJoins($filterData,$table));
$all=ceil($all/$filterData['perPage']);
//$index->show($column);
?>
	<div id="body">

		<div id="firstColumn" >
		<div >
			<input id="add_item_buttom" type="button" style="width:<?php echo(strlen('Add New'.$table)*9); ?>px" onClick="window.location='add<?php echo $Table; ?>.php'" value="Add New<?php echo ' '.$index->toView($table); ?>" >
		</div>
		<!---------------------------- Get Menu List In Common of all pages ---------------------------------->
		<table >
			<?php
				foreach($common_menu['menu_pages'] as $menu_id=>$mnu)
				{
					$display_name=$common_menu['menu_display_names'][$menu_id];
					$dot_position=strpos($mnu,".");
					$ext="";//to add .php if there was no extension in the string
					if($dot_position===false)
					{
						$mnu_no_param=$mnu;
						$ext=".php";
					}
					else
					{
						$mnu_no_param=substr($mnu,0,strpos($mnu,"."));
					}

			?>

			<?php
					$res=$index->isAllowed_2($_SESSION['control_p_group_id'],$mnu_no_param); if($res) { ?><tr style="font-weight:bold"><td><span ><a href="<?php echo $mnu.$ext;?>"><?php if($display_name=="")echo $index->toView($mnu); else { echo $display_name; }?></a></span></td></tr><?php } ?>
			<?php
				}
			?>
		<!---------------------------- Get Menu List In Common of all pages End ---------------------------------->
		<tr><td></td></tr>
		<tr><td></td></tr>
		<!---------------------------- Get Menu List Specific for this page ---------------------------------->
			<?php
				foreach($menu['menu_pages'] as $menu_id=>$mnu)
				{
					$display_name=$menu['menu_display_names'][$menu_id];
					$dot_position=strpos($mnu,".");
					$ext="";//to add .php if there was no extension in the string
					if($dot_position===false)
					{
						$mnu_no_param=$mnu;
						$ext=".php";
					}
					else
					{
						$mnu_no_param=substr($mnu,0,strpos($mnu,"."));
					}
					//var_dump($menu['menu_pages']); die();

			?>

			<?php
					$res=$index->isAllowed_2($_SESSION['control_p_group_id'],$mnu_no_param); if($res) { ?><tr ><td><span ><a href="<?php echo $mnu.$ext;?>"><?php if($display_name=="")echo $index->toView($mnu); else { echo $display_name; }?></a></span></td></tr><?php } ?>
			<?php
				}
			?>
		</table>
		<!---------------------------- Get Menu List Specific for this page End ---------------------------------->
		</div>
		<div id="secondColumn"  >
			<div id="item_name" >
				<label ><?php echo $index->toView($table); ?></label>
			</div>
			<table>
			<tr>
			<td>
			<table  class="main_table" width="100%" >
			<tr >
				<td>
					<!------------------------------------------- paging ---------------------------------->
					<form name="pagingForm" method="get" action="<?php echo $table ?>.php" >
					<!------------------------------------------- Filter ---------------------------------->
					<table class="filter_table" >
					<tr>
					<td>
					<label class="cb_header" onclick="pageDir('')" >Search</label><br />
					<div class="search">
					<input type="text" name="keyword" value="<?php echo $filterData['keyword']; ?>">
					</div>
					</td>
					<td>
					<label class="cb_header" >In</label><br />
					<div class="filter" >
					<select class="filter" name="filterBy" onchange="pageDir('')">
					<option value="all">All Fields</option>
		<?php
								if($tableLang)
								{
									$dispN=$index->getTableDisplayName($tableLang,'');
		?>
										<option value="<?php echo $dispN; ?>"<?php if($dispN==$filterData['filterBy']) echo " selected"; ?> ><?php echo $index->toView($dispN); ?></option>
		<?php
								}
								foreach($filterKeys as $keyId=>$keyValue)
								{


									$filter[$keyId]=$keyValue;
									switch ($keyId)
									{
									case 'id' :
		?>
										<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['filterBy']) echo " selected"; ?> ><?php echo $index->capitalize($keyId); ?></option>
		<?php
									break;
									default
		?>
										<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['filterBy']) echo " selected"; ?> ><?php echo $index->toView($keyId); ?></option>
		<?php
									}
								}
		?>
					</select>
					</div>
					</td>
					<td>
					<label class="cb_header" >Order By: </label><br />
					<div class="filter" >
					<select class="filter" name="orderBy" onchange="pageDir('')">
		<?php
								if($tableLang)
								{
		?>
										<option value="<?php echo $dispN; ?>"<?php if($dispN==$filterData['orderBy']) echo " selected"; ?> ><?php echo $index->toView($dispN); ?></option>
		<?php
								}
								foreach($filterKeys as $keyId=>$keyValue)
								{
									switch ($keyId)
									{
									case 'id' :
		?>
										<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['orderBy']) echo " selected"; ?> ><?php echo $index->capitalize($keyId); ?></option>
		<?php
									break;
									default
		?>

										<option value="<?php echo $keyId; ?>"<?php if($keyId==$filterData['orderBy']) echo " selected"; ?> ><?php echo $index->toView($keyId); ?></option>
		<?php
									}
								}
		?>
					</select>
					</div>
					</td>
					<td>
					<label class="cb_header" >Order</label><br />
					<div class="filter" >
					<select class="filter" name="order" onchange="pageDir('')" >
						<option value="ASC"<?php if($filterData['order']=='ASC') echo " selected"; ?> >Asc</option>
						<option value="DESC"<?php if($filterData['order']=='DESC') echo " selected"; ?> >Desc</option>
					</select>
					</div>
					</td>
					<td>
					<label class="cb_header" >Show</label><br />
					<div class="filter" >
					<select style="width:40px" name="perPage" onchange="pageDir('')" >
		<?php
						for($j=1 ;$j<30 ;$j++)
						{
		?>
						<option value="<?php echo $j; ?>"<?php if($filterData['perPage']==$j) echo " selected"; ?> ><?php echo $j; ?></option>
		<?php
						}
		?>
					</select>
					</div>
					</td>
					</tr>
					</table>
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

<?php //die('_'.$canDelete.'_'); ?>
	<tr>
		<td  >
			<form method="post" name="form" action="<?php if($canDelete) echo '../applications/item/deleteGeneralItems.php'; else echo '#'; ?>" onsubmit="<?php if($canDelete) echo "return confirm('Are you sure you want to delete all selected')"; else echo "alert('Sorry, No Permission to Delete. Contact Super Admin For More Permission'); return false; "; ?>" >
			<table width="100%" >
				<tr class="table_top" >
<?php
						foreach(array_slice($filterKeys, 0, $tableColumns) as $keyId=>$keyValue)//this tr contains the table headers of the constant defined above
						{
							if($keyId!=$PRI)
							{
?>
								<th class="main_table_hr" ><a href="#NULL" onclick="order('<?php echo $keyId; ?>')" ><?php if ($filterData['orderBy']==$keyId){ if($filterData['order']=='DESC') echo'<img height="18px" src="../public/design-images/desc.png" > '; elseif($filterData['order']=='ASC') echo '<img height="18px" src="../public/design-images/asc.png" > '; } echo $index->toView($keyId);  ?></a></th>
<?php
							}
							else
							{
?>
								<th class="main_table_hr" ><?php if($_SESSION['control_p_group_id']<3) echo '<table ><tr><td><input type="submit" style="width:100px" value="Delete Selected"  ></td><td><input type="checkbox"  name="checkAll" onChange="checkAllItems()" /></td><td><label><small>(All)</small></label></td></tr></table>';  ?>
								<input type="hidden" value="<?php echo $table; ?>" name="table" >
								</th>
<?php
								if($tableLang)
								{
?>
								<th class="main_table_hr" ><a href="#NULL" onclick="order('<?php echo $dispN; ?>')" ><?php if ($filterData['orderBy']==$dispN){ if($filterData['order']=='DESC') echo'<img height="18px" src="../public/design-images/desc.png" > '; elseif($filterData['order']=='ASC') echo '<img height="18px" src="../public/design-images/asc.png" > '; } echo $index->toView($dispN);  ?></a></th>
<?php
								}
							}
						}
?>
				</tr>
<?php
					foreach($items as $id=>$value)
					{
?>

				<tr class="table_mid" >
<?php
//***************************************************************** fill the table cols from cols of main table **********************************
						foreach(array_slice($filterKeys, 0, $tableColumns) as $keyId=>$keyValue)// fill the table with the same loop  that filled the header of the table
						{
							switch($keyId)
							{
								case $PRI: //this colums conains a checkbox to multi select the table rows in order to multiple delete
?>
									<td class="PRI_td" >
									<table class="PRI_table" >
									<tr>
									<td>
									<img class="icon edit_icon" src="../public/design-images/edit_icon.png" onclick=" window.location='edit<?php echo $Table; ?>.php?id=<?php echo $value[$keyId]; ?>'; " >
									</td>
									<td>
									<img class="icon view_icon" src="../public/design-images/view_icon.png" onclick="window.open('viewItem.php?id=<?php echo $value[$keyId]; ?>&table=<?php echo $table; ?>')" >
									</td>
									<td >
									<?php if($index->hasFiles($table)) { ?><img class="icon files_icon" src="../public/design-images/files_icon.png" onclick="window.location='manageFiles.php?table=<?php echo $table; ?>&id=<?php echo $value[$keyId]; ?>'" ><?php } ?>
									</td>
									<td >
									<?php if($index->hasImages($table)) { ?><img class="icon img_icon" src="../public/design-images/img_icon.png" onclick="window.location='manageImages.php?table=<?php echo $table; ?>&id=<?php echo $value[$keyId]; ?>'" ><?php } ?>
									</td>
									<td >
									<?php if($index->checkTableIfExist($table.'_language')) { ?><img class="icon lang_icon" src="../public/design-images/lang_icon.png" onclick="window.location='<?php echo 'edit'.$Table.'_language'; ?>.php?id=<?php echo $value[$keyId]; ?>'" ><?php } ?>
									</td>
									</tr>
									</table>
									<span style="float:left;" ><?php if($_SESSION['control_p_group_id']<3) echo'<input type="checkbox" style="width:30px;" name="item'.$value[$keyId].'" value="'.$value[$keyId].'" />'; ?><?php echo 'Id: '.$value[$keyId]; ?>
									</span>

									</td>
									<?php	 //$index->show($items);
									if($tableLang)//add the display name of the table_language in the columns after the primarykey
									{
?>
										<td><?php echo $index->showValue2($value[$dispN],$dispN,$tableLang); ?></td>
<?php
									}
									?>

									<?php
									break;
								case 'image_id' :

?>
									<td><?php if(file_exists('../../public/images/'.$index->showValue($value[$keyId],$keyId))) { ?><a class="fancybox" target="_blank" href="../../public/images/<?php echo $index->showValue($value[$keyId],$keyId); ?>" ><img style="width:50px;height:50px;" src="../../public/images/thumbs/<?php echo $index->showValue($value[$keyId],$keyId); ?>" ></a><?php }else { echo 'No Image'; } ?></td>
<?php
								break;
								default :
									if(strpos($keyId,'_code')===false)
									{

?>
										<td><?php if(!$for=$index->isForien($keyId)) { echo $index->showValue2($value[$keyId],$keyId,$table); } else {echo '<a href="'.$for['table'].'.php?filterBy='.$keyId.'&keyword='.$index->showValue($value[$keyId],$keyId).'">'.$index->showValue($value[$keyId],$keyId).'</a>';} ?></td>
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
//***************************************************************** End fill the table cols from cols of main table **********************************
?>
				</tr>
<?php
					}
?>
			</table>
			</form>
		</td>
	</tr>
	<tr class="table_bottom" >
	<td>
	</td>
	</tr>
</table>
</td>
</tr>
<tr>
<td>
<?php if($curPage>0) echo ('<input style="float:left;height:30px" type="button" value="prev" onclick="pageDir(\'prev\')">'); ?>
<?php if($curPage+1<$all) echo ('<input style="float:right;height:30px" type="button" value="next" onclick="pageDir(\'next\')">'); ?>
</td>
</tr>
</table>

		</div>

	</div>
<script language="javascript">
	hoverStyle('edit_icon','edit_icon');
	hoverStyle('view_icon','view_icon');
	hoverStyle('lang_icon','lang_icon');
	hoverStyle('img_icon','img_icon');
	hoverStyle('files_icon','files_icon');
</script>
<?php require_once('../public/layouts/theme_1/_footer.html');
