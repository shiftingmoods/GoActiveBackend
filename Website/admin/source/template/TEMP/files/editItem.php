//************************************* check if the user control_p_group allow him to enter this page *******
$path_parts = pathinfo(__FILE__);
$page=$path_parts['filename'];
$data['control_p_privilege']=$page;
$data['control_p_group_id']=$_SESSION['control_p_group_id'];
if(!$index->isAllowed($data))
{
	if(isset($_SERVER["HTTP_REFERER"]))
	{
		$pathA=explode('/',$_SERVER["HTTP_REFERER"]);
		if(str_replace('.php','',$pathA[count($pathA)-1])!=$page)
		{
			if(strpos($_SERVER["HTTP_REFERER"],'?')===false) $char='?' ; else $char='&'; 
			ob_end_clean();
			header('Location:'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition');
		}
		else
		{
			ob_end_clean();
			header('Location:index.php?note=No Permition');
		}
	}
	else
	{
		ob_end_clean();
		header('Location:index.php?note=No Permition');
	}
}
//************************************* check if the user control_p_group allow him to enter this page *******
//******************************************* constants *****************************************
$table=strtolower(substr($page,4));
$Table=$index->capitalize($table);
//******************************************* constants end *************************************
//******************************************************** sub menu *****************************
$data['page']=$page;
$menu=$index->getMenuList($data);
?>
<div id="header" >

		<ul id="menu" align="left" style="padding-top:6px">
<?php
	foreach($menu['menu_pages'] as $menu_id=>$mnu)
	{
		$display_name=$menu['menu_display_names'][$menu_id];
		//var_dump($menu['menu_pages']); 
?>
		
<?php $res=$index->isAllowed_2($_SESSION['control_p_group_id'],$mnu); if($res) { ?><li ><span ><a href="<?php echo $mnu;?>.php"><?php if($display_name=="")echo $index->toView($mnu); else { echo $display_name; }?> >></a></span></li><?php } ?>
		
<?php
	}
?>
		</ul>
		
</div>
<?php
//******************************************************** sub menu *****************************

?>
<link href="../public/css/style.css" rel="stylesheet" type="text/css">
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="../public/css/style.ie7.css"/><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="../public/css/style.ie7.css"/><![endif]-->
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="../public/css/style.ie7.css"/><![endif]-->
<?php
if(isset($_GET['id']))
{
	if($_GET['id']!='0')
	{	
		$selectedItemId=$_GET['id'];
		if(!$index->checkIdIfExist($selectedItemId,$table))
		{
			header('Location:'.$table.'.php?note=Invalid '.$Table.' Id');
		}
		if($index->hasLanguage($table))
		{		
				$selectedItemIdA['id']=$selectedItemId;
				$selectedItemIdA['language_id']=$lang;
				$Aitm = $index->getGeneralItemById($selectedItemIdA,$table);
		}
		else
		{
				$Aitm = $index->getGeneralItemById($selectedItemId,$table);
		}	
		$itm = $Aitm[$selectedItemId];
		$cols = $index->getGeneralColums($table);
		$keys = $cols['keys'];
		
	}
}
else
{
	header('Location:'.$table.'.php?note=Invalid '.$Table.' Id');
}

?>
<html>
<head>
<title>Edit<?php echo ' '.$index->toView($table); ?></title>

<SCRIPT language=JavaScript>
	function reload()
	{
		var selectedItemId = document.form.item.value;
		
		
		window.location='?selectedItemId='+selectedItemId ;
	}	
	function changeEditMode()
	{
		if(document.form.language_id.value=="<?php echo $lang; ?>")
		{
			document.form.action='../applications/itemIframe/saveGeneralItemEdit.php?newLang=true';
			document.getElementById("saveNewLang").style.display="none";
			document.getElementById("saveChanges").style.display="block";
			document.getElementById("saveNewLangNote").style.display="none";
		}
		else if(document.form.language_id.value!=0)
		{
			document.form.action='../applications/itemIframe/saveGeneralItemEdit.php?newLang=true';
			document.getElementById("saveChanges").style.display="none";
			document.getElementById("saveNewLang").style.display="block";
			document.getElementById("saveNewLangNote").style.display="block";
		}
	}
	function init() {
		document.getElementById('form').onsubmit=function() {
		if(!check('form'))
		{ return false; }
		document.getElementById('form').target = 'upload_target'; //'upload_target' is the name of the iframe
		document.getElementById('upload_target').style.width="100%";
		document.getElementById('upload_target').style.height="40px";
	}
	}
	window.onload=init;
	</script>
<script language="javascript" src="../public/js/validate.js" type="text/javascript"></script>
<script language="javascript" src="../public/js/submitForm.js" type="text/javascript"></script>
<script language="javascript" src="../public/js/jquery.js" type="text/javascript"></script>
<script language="javascript" src="../public/js/general.js" type="text/javascript"></script>
<link type="text/css" href="../public/js/css/custom-theme/jquery-ui-1.8.9.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="../public/js/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../public/js/js/jquery-ui-1.8.9.custom.min.js"></script>
</head>
<body>
<center><h1>Edit<?php echo ' '.$index->toView($table); ?></h1>

</center>
<br><br><br>
<table>
<form method="post" name="form" id="form" action="../applications/itemFiles/saveGeneralItemEdit.php" enctype="multipart/form-data"  >
<tr>
<td >
</td>
<td>
<input type="hidden" value="<?php echo $selectedItemId;?>" name="item" >
</td>
</tr>
<?php 
if(isset($selectedItemId))
{
	if($selectedItemId!='0')
	{
		foreach($keys as $id=>$value)
		{
			$type=$index->getColType($id,$table);
			if($id=='language_id'){ $showLangButton=true; }
			if($id=='status'){$showStat=true;}else{$showStat=false;}
			if($id!='id' && $id!='status' && $id!='path')
			{
				if($value['Type']=='date')
				{
					$date=' alt="date" readonly ';
					if($id=='date_created')
					{
						$date=' readonly ';
					}
				}
				else
				{ 
					$date='';
				}
				if($index->isOptional($id,$table))
				{
					$optional='_optional';
					$star='';
				}
				else
				{
					$optional='';
					$star=' <font color="red" >*</font>';
				}
				$outer= $index->toView($id);
?>
				<tr>
				<td><label><?php echo $outer; ?>:<?php echo ' '.$star; ?></label></td>
				<td>
<?php			if(($foreignTable=$index->composeSelectBoxWithFilter($id,'')) && $id!='image_id')
				{
						if($pos=strrpos($id,'_id'))
						{
							$Ftable=substr($id, 0, $pos);
						}
						$cols=$index->getGeneralColums($Ftable);
						$PRI=$cols['primaryKeys'];
						$PRI=$PRI[0];
?>
					<select name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>"<?php if($Ftable=='language') echo (' onchange="changeEditMode()"'); ?> >
					<option value="" >Select</option>
<?php
					foreach($foreignTable as $id2=>$item)
					{
						if($itm[$id]==$item[$PRI])
						{
							echo('<option value="'.$item[$PRI].'" selected="selected" >'.$item[$index->getTableDisplayName($Ftable)].'</option>');
						}
						else
						{
							echo('<option value="'.$item[$PRI].'" >'.$item[$index->getTableDisplayName($Ftable)].'</option>');
						}
					}
?>
					</select><?php if($id=='language_id') echo'<span style="display:none;font-size:10px;color:red;" id="saveNewLangNote" >(You are adding a new language definition)</span>'; ?>
<?php
				}
				else
				{
					switch($id)
					{
						case 'password':
?>					
						<input type="password" name="<?php echo $id; ?>" id="<?php echo $outer.'_optional'; ?>" value="" >
<?php				
						break;
//*********************************************************  case when the user has a schedule ******************************
						case 'schedule':
						$dayz=array( "0" => 'Monday',"1" => 'Tuesday',"2" => 'Wednesday',"3" => 'Thursday',"4" => 'Friday',"5" => 'Saturday',"6" => 'Sunday' );
?>					
						
						<table border="0" >
<?php
						$schedule=unserialize($itm[$id]);
						//$index->show($schedule);
						foreach($dayz as $id2=>$day)
						{
							echo '<tr>';
							echo '<td width="150px" ><span style="float:right" ><input type="checkbox" style="width:30px" name="'.$id.'_'.$day.'" ';  if(isset($schedule[$day])) { echo " checked "; } echo '></span><span style="float:left" >'.$day.' : </span></td>';
							//***********************************************************************
							echo '<td><select style="width:100px" name="'.$id.'_'.$day.'_1" >';
							for($h=1;$h<13;$h++)
							{
								echo '<option value="'.$h.':00"';  if($schedule[$day.'_1']==$h.':00') { echo " selected "; } echo ' >'.$h.':00</option>';
								echo '<option value="'.$h.':15"';  if($schedule[$day.'_1']==$h.':15') { echo " selected "; } echo ' >'.$h.':15</option>';
								echo '<option value="'.$h.':30"';  if($schedule[$day.'_1']==$h.':30') { echo " selected "; } echo ' >'.$h.':30</option>';
								echo '<option value="'.$h.':45"';  if($schedule[$day.'_1']==$h.':45') { echo " selected "; } echo ' >'.$h.':45</option>';
							}
							echo '</select></td>';
							//***********************************************************************
							echo '<td><select style="width:70px" name="'.$id.'_'.$day.'_2">';
							echo '<option value="AM" '; if($schedule[$day.'_2']=='AM') { echo " selected "; } echo '>AM</option>';
							echo '<option value="PM" '; if($schedule[$day.'_2']=='PM') { echo " selected "; } echo '>PM</option>';
							echo '</select></td>';
							//***********************************************************************
							echo '<td>Till</td>';
							//***********************************************************************
							echo '<td><select style="width:100px" name="'.$id.'_'.$day.'_3" >';
							for($h=1;$h<13;$h++)
							{
								echo '<option value="'.$h.':00"';  if($schedule[$day.'_3']==$h.':00') { echo " selected "; } echo ' >'.$h.':00</option>';
								echo '<option value="'.$h.':15"';  if($schedule[$day.'_3']==$h.':15') { echo " selected "; } echo ' >'.$h.':15</option>';
								echo '<option value="'.$h.':30"';  if($schedule[$day.'_3']==$h.':30') { echo " selected "; } echo ' >'.$h.':30</option>';
								echo '<option value="'.$h.':45"';  if($schedule[$day.'_3']==$h.':45') { echo " selected "; } echo ' >'.$h.':45</option>';
							}
							echo '</select></td>';
							//***********************************************************************
							echo '<td><select style="width:70px" name="'.$id.'_'.$day.'_4" >';
							echo '<option value="AM" '; if($schedule[$day.'_4']=='AM') { echo " selected "; } echo '>AM</option>';
							echo '<option value="PM" '; if($schedule[$day.'_4']=='PM') { echo " selected "; } echo '>PM</option>';
							echo '</select></td>';
							//***********************************************************************
							echo '</tr>';
						}
?>
						</table>
<?php
						break;
//*********************************************************  END case when the user has a schedule ******************************
						case 'image_id':
?>					
<?php if(file_exists('../../public/images/'.$index->showValue($itm[$id],$id))) { ?><img src="../../public/images/thumbs/<?php echo $index->showValue($itm[$id],$id); ?>" ></img><br><?php } ?>
						<input type="file" name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" >
<?php				
						break;
						case 'name':
?>
						<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" ><input type="file" name="file" id="file" value="" />
						<br /><?php if(isset($itm['control_p_folder_id'])) { $folder=$index->getFolderPath($itm['control_p_folder_id']); } else{ $folder='';} $path='../../public/files/'.$folder.$itm['path']; if(file_exists($path)) { echo '<a target="_blank" href="'.$path.'" >Show File</a>'; } else { echo 'No file'; } ?>
<?php				
						break;
						default :
						switch ($type['type'])
							{
								case 'int':
?>						
						
									<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
<?php	
								break;
								case 'varchar':
?>						
						
									<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
<?php	
								break;
								case 'tinyint':
?>
									<table border="0" width="100%" >
										<tr>
											<td>
											<input type="radio"<?php if($itm[$id]=='0' || $itm[$id]=='') echo " checked "; ?> style="width:10px" name="<?php echo $id; ?>" id="<?php echo 'no'.$outer.$optional; ?>" value="0" ><label><?php if($id=='sex') echo ' Male'; else echo ' No'; ?></label>
											</td>
											<td>
											<input type="radio"<?php if($itm[$id]!='0' && $itm[$id]!='') echo " checked "; ?> style="width:10px" name="<?php echo $id; ?>" id="<?php echo 'yes'.$outer.$optional; ?>" value="1" ><label><?php if($id=='sex') echo ' Female'; else echo ' Yes'; ?></label>
											</td>
										</tr>
									</table>
<?php	
								break;
								case 'text':
?>
									<textarea  name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" maxlength="<?php echo $type['length']; ?>" ><?php echo $itm[$id]; ?></textarea>
<?php	
								break;
								default:
?>
									<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
<?php
							}
					}
				}	
?>
				</td>
				</tr>
<?php
			}
		}
		if(isset($showStat))
		if($showStat==true)
		{
?>
<tr><td><label>Status:</label></td><td> 
<select name="status" id="status">
<option value="ACTIVE"<?php if($itm['status']=="ACTIVE") echo("selected"); else echo(""); ?> >ACTIVE</option>
<option value="INACTIVE" <?php if($itm['status']=="INACTIVE") echo("selected"); else echo(""); ?> >INACTIVE</option>
<option value="PENDING" <?php if($itm['status']=="PENDING") echo("selected"); else echo(""); ?> >PENDING</option></select></td></tr>
<?php
		}
	}
}
?>
<tr><td><span id="saveChanges" ><input type="submit" value="Save Changes" /></span><?php if(isset($showLangButton)){ ?><span style="display:none" id="saveNewLang" ><input  type="submit" value="Save New Language" onClick="" /></span><td/><td><?php } ?></td></tr>
</table>
<iframe id="upload_target" name="upload_target" src="" style="width:0px;height:0px;"  frameborder="0" scrolling="no" allowtransparency="true" ></iframe>
<input type="hidden" name="table" value="<?php echo $table; ?>">
</form>
<div id="bigNote" ></div>
<br><br><br><br>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>