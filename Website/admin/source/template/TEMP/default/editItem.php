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
<form method="post" name="form" enctype="multipart/form-data" >
<table>
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
			if($id=='language_id'){ $showLangButton=true; }
			if($id=='status'){$showStat=true;}else{$showStat=false;}
			if($id!='id' && $id!='status')
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
				}
				else
				{
					$optional='';
				}
				$outer= $index->toView($id);
?>
				<tr>
				<td><label><?php echo $outer; ?>:</label></td>
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
					<select name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" >
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
					</select>
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
						default :
						if(strpos($id,'_code')===false)
						{
?>							<input type="text" <?php echo $date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" >
<?php					
						}
						else
						{
?>						
							<textarea class="code" name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" ><?php echo htmlentities($itm[$id]); ?></textarea>
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
<tr><td><input type="button" value="Save Changes" onClick="changeFormMethod('POST')" /></td><?php if(isset($showLangButton)){ ?><td><input type="button" value="Save New Language" onClick="changeFormMethod('newLang')" /></td><?php } ?></tr>
</table>
<input type="hidden" name="table" value="<?php echo $table; ?>">
</form>
<br><br><br><br>
</body>
</html>