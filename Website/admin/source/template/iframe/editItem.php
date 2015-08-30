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
			//ob_end_clean();
			$wlurl=$_SERVER["HTTP_REFERER"].$char.'note=No Permition';
			echo "<script language='javascript' >window.location = '".$wlurl."';</script>";
			die('No Permition, Please enable JavaScript To Continue');
			header('Location:'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition');
		}
		else
		{
			//ob_end_clean();
			echo "<script language='javascript' >window.location = 'index.php?note=No Permition';</script>";
			die('No Permition, Please enable JavaScript To Continue');
			header('Location:index.php?note=No Permition');
		}
	}
	else
	{
		//ob_end_clean();
		echo "<script language='javascript' >window.location = 'index.php?note=No Permition';</script>";
		die('No Permition, Please enable JavaScript To Continue');
		header('Location:index.php?note=No Permition');
	}
}
//************************************* check if the user control_p_group allow him to enter this page *******
//******************************************************** sub menu *****************************
$data['page']=$page;
$menu=$index->getMenuList($data);
$index_Mdata['page']="index";
$common_menu=$index->getMenuList($index_Mdata);
?>
<?php
//******************************************************** sub menu *****************************

if(!isset($seq))
{
	//************************* general Variables **************************************
	
		$seq[$table0]=array();
	
	//************************* End general Variables **************************************
}

	foreach($seq as $t=>$ar)
	{
		$tables[]=$t;
	}
	if(isset($_SESSION['postData'])) { unset($_SESSION['postData']); }
	$_SESSION['postData']['seq']=$seq;
//$index->show($seq);
?>
<?php
//********************************************************* context id if exists ************************************
if(!isset($_GET['id']) && isset($_SESSION['context'][$tables[0]]))
{
	$_GET['id']=$_SESSION['context'][$tables[0]];
}
//***********************************************************************************************************									
if(isset($_GET['id']))
{
	if($_GET['id']!='0' && filter_var($_GET['id'], FILTER_VALIDATE_INT))
	{	
		$selectedItemId=$_GET['id'];
		$selectedItemIdMain=$_GET['id'];
		if(!$index->checkIdIfExist($selectedItemId,$tables[0]))
		{
			//ob_end_clean();
			header('Location:'.$tables[0].'.php?note=Invalid '.$index->toView($tables[0]).' Id');
		}
		if($index->checkTableIfExist($tables[0].'_language'))
		{ 
				$selectedItemIdA['id']=$selectedItemId;
				$selectedItemIdA['language_id']=$lang; 
				$Aitm = $index->getGeneralItemById($selectedItemIdA,$tables[0]);
		}
		else
		{
				$Aitm = $index->getGeneralItemById($selectedItemId,$tables[0]);
		}	
		$itm = $Aitm[$selectedItemId];

	}
	else
	{
		//ob_end_clean();
		header('Location:'.$tables[0].'.php?note=Invalid '.$index->toView($tables[0]).' Id');
	}
}
else
{
	//ob_end_clean();
	header('Location:'.$tables[0].'.php?note=Invalid '.$index->toView($tables[0]).' Id');
}

?>
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
		else if(document.form.language_id.value!='null')
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

	<div id="body">	
		
		<div id="firstColumn" >
		<div >
			<input id="add_item_buttom" type="button" style="width:<?php echo(strlen($tables[0])*9); ?>px" onClick="window.location='<?php echo $tables[0]; ?>.php'" value="<?php echo $index->toView($tables[0]); ?>" >
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

			<center><h1>Edit<?php echo ' '.$index->toView($tables[0]); ?></h1>
			</center>
			<?php if($index->checkTableIfExist($tables[0].'_language')) {  ?>
			<?php if($index->checkTableIfExist($tables[0].'_language')) {  ?><span  ><input style="float:left" type="button" value="<?php echo 'Edit Language Info'; ?>" onclick="window.open('<?php echo 'edit'.ucfirst($tables[0]).'_language'; ?>.php?id=<?php echo $selectedItemId; ?>&lang=1')"; ></span><br/><?php } ?>
			<?php } ?>
			<form method="POST" name="form" id="form" action="../applications/itemCustom/saveGeneralItemEdit.php" enctype="multipart/form-data"  >
			<?php foreach($tables as $ind=>$table)
			{
				$Table=$index->toView($table);
				$cols = $index->getGeneralColums($table);
				$keys = $cols['keys'];

				if($ind!=0)
				{
					foreach($seq[$table] as $ind2=>$fk)
					{
						$selectedItemId=$index->getGeneralItemId(array($fk=>$selectedItemIdMain),$table);
					}
					if(!$selectedItemId)
					{
						//ob_end_clean();
						header('Location:'.$tables[0].'.php?note=Missing '.$index->toView($tables[0]).' Information');
					}
					$Aitm = $index->getGeneralItemById($selectedItemId,$table);
					$itm=$Aitm[$selectedItemId];
				}
			?>
			<?php if($ind!=0) { ?><center><h1><?php echo $index->toView($table).' '; ?></h1></center><?php } ?>
		<div class="form_div" >
			<table class="form_table" >

			<tr>
			<td>
			<input type="hidden" value="<?php echo $selectedItemId;?>" name="<?php echo $table; ?>[item]" >
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
						$par=true;
							foreach($seq[$table] as $ind2=>$parent)
							{
								if($parent==$id)
								{
									$par=false;
								}
							}
						if($id!='id' && $id!='status' && $par)
						{
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
									//*********** get what columns to display *******
									$dataDN['multiple']=true;
									$display_name=$index->getTableDisplayName($Ftable,$dataDN);
									$dataCFDN['display_name']=$display_name;
									//*********** get what columns to display *******
			?>						
								
								<select name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>"<?php if($Ftable=='language') echo (' onchange="changeEditMode()"'); ?> >
								<option value="null" >Select</option>
			<?php
								foreach($foreignTable as $id2=>$item)
								{
									$dataCFDN['item']=$item;
									$FDN=$index->composeFullDisplayName($dataCFDN);
									// old function low performance : $FDN=$index->GetFullDisplayName($item[$PRI],$Ftable);
									$defVal='';
									if($itm[$id]==$item[$PRI])
									{
										$defVal='selected="selected"'; 
									}
									echo('<option '.$defVal.' value="'.$item[$PRI].'" >'.$FDN.'</option>');
								}
			?>
								</select><span style="display:none;font-size:10px;color:red;" id="saveNewLangNote" >(You are adding a new language definition)</span>
			<?php
							}
							else
							{
								switch($id)
								{
									case 'password':
			?>					
									<input type="password" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.'_optional'; ?>" value="" >
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
									<input type="file" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" >
			<?php				
									break;
									default :
									switch ($type['type'])
										{
											case 'int':
			?>						
									
												<input type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
			<?php	
											break;
											case 'date':
												$date=' alt="date" readonly ';
												$hideDate='';
												if($id=='date_created')
												{
													$date=' readonly ';
													$hideDate= '<script language="javascript" >hideDate("'.$table.'['.$id.']");</script>';
												}
			?>						
									
												<input type="text"<?php echo ' '.$date; ?> name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
			<?php	
												echo $hideDate;
											break;
											case 'varchar':
			?>						
									
												<input type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
			<?php	
											break;
											case 'tinyint':
			?>
												<table border="0" width="100%" >
													<tr>
														<td>
														<input type="radio"<?php if($itm[$id]=='0' || $itm[$id]=='') echo " checked "; ?> style="width:10px" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo 'no'.$outer.$optional; ?>" value="0" ><label><?php if($id=='sex') echo ' Male'; else echo ' No'; ?></label>
														</td>
														<td>
														<input type="radio"<?php if($itm[$id]!='0' && $itm[$id]!='') echo " checked "; ?> style="width:10px" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo 'yes'.$outer.$optional; ?>" value="1" ><label><?php if($id=='sex') echo ' Female'; else echo ' Yes'; ?></label>
														</td>
													</tr>
												</table>
			<?php	
											break;
											case 'text':
			?>
												<textarea  name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" maxlength="<?php echo $type['length']; ?>" ><?php echo $itm[$id]; ?></textarea>
			<?php	
											break;
											default:
			?>
												<input type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
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
					if($showStat==true && $table==$tables[0])
					{
			?>
			<tr><td><label>Status:</label></td><td> 
			<select name="<?php echo $table; ?>[status]" id="status">
			<option value="ACTIVE"<?php if($itm['status']=="ACTIVE") echo("selected"); else echo(""); ?> >ACTIVE</option>
			<option value="INACTIVE" <?php if($itm['status']=="INACTIVE") echo("selected"); else echo(""); ?> >INACTIVE</option>
			<option value="PENDING" <?php if($itm['status']=="PENDING") echo("selected"); else echo(""); ?> >PENDING</option></select></td></tr>
			<?php
					}
				}
			}
			?>
			</table>
</div>
<?php } ?>
<span id="saveChanges" ><input class="submit" type="submit" value="Save Changes" /></span><?php if(isset($showLangButton)){ ?><span style="display:none" id="saveNewLang" ><input  type="submit" value="Save New Language" onClick="" /></span><td/><td><?php } ?>
<!------------------------------------------- iframe data ------------------------------------------------------------->
<script language="javascript" >
function init() {
	document.getElementById('form').onsubmit=function() {
	if(!check('form'))
	{ return false; }
	document.getElementById('form').target = '_application'; //'upload_target' is the name of the iframe
	document.getElementById('_application').style.width="100%";
	document.getElementById('_application').style.height="400px";
}
}
window.onload=init;
</script>
<script language="javascript">
	$(function() {
		var pickerOpts = 
		{
			showAnim: 'fold',
			//showOn: 'both',
			hideIfNoPrevNext: true,
			nextText: 'Later',
			dateFormat:"dd-mm-yy",
			changeFirstDay: false,
			changeMonth: false,
			changeYear: true,
			closeAtTop: false,
			showOtherMonths: true,
			showStatus: true,
			showWeeks: true,
			duration: "fast",
			yearRange: "1940:1993"
        };
		$("input[alt='date']").datepicker(pickerOpts);
	});
</script>
<iframe id="_application" name="_application" src="" style="width:0px;height:0px;"  frameborder="0" scrolling="no" allowtransparency="true" ></iframe>
<!--------------------------------------------- End iframe data ------------------------------------------------------------------------>
</form>
<div id="bigNote" ></div>
<br><br><br><br>
</div>
</div>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>