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
$table=strtolower(substr($page,3));//get the name of the table from the file name
$Table=$index->capitalize($table);
//******************************************* constants end *************************************
//******************************************************** sub menu *****************************
$data['page']=$page;
$menu=$index->getMenuList($data);
?>
<script language="javascript" >
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
$cols = $index->getGeneralColums($table);
$cols = $cols['keys'];
?>
<html>
<head>
<title>Add<?php echo ' '.$index->toView($table); ?></title>

</head>
<body>
<script language="javascript" src="../public/js/validate.js" type="text/javascript"></script>
<script language="javascript" src="../public/js/jquery.js" type="text/javascript"></script>
<script language="javascript" src="../public/js/general.js" type="text/javascript"></script>
<link type="text/css" href="../public/js/css/custom-theme/jquery-ui-1.8.9.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="../public/js/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../public/js/js/jquery-ui-1.8.9.custom.min.js"></script>
<center><h1>Add<?php echo ' '.$index->toView($table).' '; ?></h1>
</center>
<br><br><br>
<form  method="post" action="../applications/itemFiles/saveGeneralItem.php" name='form' id='form' enctype="multipart/form-data" >
<table>
<?php 

	if($cols!='0')
	{
		foreach($cols as $id=>$value)
		{
			$type=$index->getColType($id,$table);
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
			if($value['Type']=='date')
			{
				$date=' alt="date" readonly ';
				if($id=='date_created')
				{
					$date=' value="'.date('Y-m-d').'" readonly ';
				}
			}
			else
			{
				$date='';
			}
			if($id=='status'){$showStat=true;}else{$showStat=false;}
			
			if($id!='id' && $id!='status' && $id!='path')
			{
				$outer= $index->toView($id);
?>				<tr><td><label><?php echo $outer; ?>:<?php echo ' '.$star; ?></label></td>
					<td>
					
<?php				
					$foreignTable=$index->composeSelectBox($id);//return false if the table is not forien and return an array aff all values if its a forien
					if(($foreignTable || count($foreignTable)=='0') && $id!='image_id')
				{

?>
					<select name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>"  >
<?php
					if(count($foreignTable)=='0')
					{
?>
						<option value="">Empty</option>
					
<?php
					}
					else
					{
						if($pos=strrpos($id,'_id'))
						{
							$Ftable=substr($id, 0, $pos);
						}
						$cols=$index->getGeneralColums($Ftable);
						$PRI=$cols['primaryKeys'];
						$PRI=$PRI[0];
						
						{
	?>
						<option value="">Select</option>
	<?php
						}
						foreach($foreignTable as $id2=>$item)
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
						
						<input type="password" name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="" >
<?php
						break;
//******************************************************  in caye item has schedule ****************************************************
						case 'schedule':
						$dayz=array( "0" => 'Monday',"1" => 'Tuesday',"2" => 'Wednesday',"3" => 'Thursday',"4" => 'Friday',"5" => 'Saturday',"6" => 'Sunday' );
?>					
						
						<table border="0" >
<?php
						foreach($dayz as $id2=>$day)
						{
							echo '<tr>';
							echo '<td width="150px" ><span style="float:right" ><input type="checkbox" style="width:30px" name="'.$id.'_'.$day.'" ></span><span style="float:left" >'.$day.' : </span></td>';
							//***********************************************************************
							echo '<td><select style="width:100px" name="'.$id.'_'.$day.'_1" >';
							for($h=1;$h<13;$h++)
							{
								echo '<option value="'.$h.':00" >'.$h.':00</option>';
								echo '<option value="'.$h.':15" >'.$h.':15</option>';
								echo '<option value="'.$h.':30" >'.$h.':30</option>';
								echo '<option value="'.$h.':45" >'.$h.':45</option>';
							}
							echo '</select></td>';
							//***********************************************************************
							echo '<td><select style="width:70px" name="'.$id.'_'.$day.'_2">';
							echo '<option value="AM" >AM</option><option value="PM" >PM</option>';
							echo '</select></td>';
							//***********************************************************************
							echo '<td>Till</td>';
							//***********************************************************************
							echo '<td><select style="width:100px" name="'.$id.'_'.$day.'_3" >';
							for($h=1;$h<13;$h++)
							{
								echo '<option value="'.$h.':00" >'.$h.':00</option>';
								echo '<option value="'.$h.':15" >'.$h.':15</option>';
								echo '<option value="'.$h.':30" >'.$h.':30</option>';
								echo '<option value="'.$h.':45" >'.$h.':45</option>';
							}
							echo '</select></td>';
							//***********************************************************************
							echo '<td><select style="width:70px" name="'.$id.'_'.$day.'_4" >';
							echo '<option value="AM" >AM</option><option value="PM" >PM</option>';
							echo '</select></td>';
							//***********************************************************************
							echo '</tr>';
						}
?>
						</table>
<?php
						break;
//******************************************************  in caye item has schedule ****************************************************
						case 'image_id':
?>					
						
						<input type="file" name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="" />
<?php
						break;
//******************************************************  in caye item has schedule ****************************************************
						case 'name':
?>					
						
						<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" ><input type="file" name="file" id="file" value="" />
						
						
<?php
						break;
						default:
						{
							switch ($type['type'])
							{
								case 'int':
?>						
						
									<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
<?php	
								break;
								case 'varchar':
?>						
						
									<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
<?php	
								break;
								case 'text':
?>
									<textarea  name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" maxlength="<?php echo $type['length']; ?>" ></textarea>
<?php	
								break;
								case 'tinyint':
?>
									<table border="0" width="100%" >
										<tr>
											<td>
											<input type="radio"<?php if($value['Default']=='0' || $value['Default']=='') echo " checked "; ?> style="width:10px" name="<?php echo $id; ?>" id="<?php echo 'no'.$outer.$optional; ?>" value="0" ><label><?php if($id=='sex') echo ' Male'; else echo ' No'; ?></label>
											</td>
											<td>
											<input type="radio"<?php if($value['Default']!='0' && $value['Default']!='') echo " checked "; ?> style="width:10px" name="<?php echo $id; ?>" id="<?php echo 'yes'.$outer.$optional; ?>" value="1" ><label><?php if($id=='sex') echo ' Female'; else echo ' Yes'; ?></label>
											</td>
										</tr>
									</table>
<?php	
								break;
								default:
?>
									<input type="text"<?php echo ' '.$date; ?> name="<?php echo $id; ?>" id="<?php echo $outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
<?php
							}
						}
					}
				}	
?>
					</td>
				</tr>
<?php
			}
		}
?>
<?php
	}
		if(isset($showStat))
		if($showStat==true)
		{
?>
<tr><td ><label>Status:</label></td><td> 
<select name="status" id="status">
<option value="ACTIVE">ACTIVE</option>
<option value="INACTIVE">INACTIVE</option>
<option value="PENDING">PENDING</option></select></td></tr>
<?php
		}
?>
<?php if($index->hasLanguage($table)){ ?>
<script language="javascript" >
var all=document.form.language_id.length;
for(var i=0 ; i<all ;i++)
{
	if(document.form.language_id.options[i].value==1)
	{
		document.form.language_id.options[i].selected=true;
		document.form.language_id.disabled=true;
		
	}
}
</script>
<?php }?>
<tr><td colspan="2" ><input type="submit"  value="Add<?php echo ' '.$index->toView($table); ?>" /></td></tr>
</table>
<iframe id="upload_target" name="upload_target" src="" style="width:0px;height:0px;"  frameborder="0" scrolling="no" allowtransparency="true" ></iframe>
<input type="hidden" name="table" value="<?php echo $table; ?>" >
</form>
<div id="bigNote" ></div>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>