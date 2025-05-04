//************************************* check if the user control_p_group allow him to enter this page *******
$path_parts = pathinfo(__FILE__);
$page=$path_parts['filename'];
$data['control_p_privilege']=$page;
$data['control_p_group_id']=$_SESSION['control_p_group_id'];
//************************************* check if the user control_p_group allow him to enter this page *******
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
//******************************************************** sub menu *****************************
$data['page']=$page;
$menu=$index->getMenuList($data);
$index_Mdata['page']="index";
$common_menu=$index->getMenuList($index_Mdata);
?>
<?php
if(!isset($seq))
{
	//************************* general Variables **************************************

		$seq[$table0]=array();
		if($index->checkTableIfExist($table0.'_language'))
		{
			$seq[$table0.'_language']=array($table0.'_id');
		}

	//************************* End general Variables **************************************
}

	foreach($seq as $t=>$ar)
	{
		$tables[]=$t;
	}
	if(isset($_SESSION['postData'])) { unset($_SESSION['postData']); }
	$_SESSION['postData']['seq']=$seq;

?>
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

			<center>
			<h1>Add<?php echo ' '.$index->toView($tables[0]).' '; ?></h1>
			</center>

			<form method="POST" action="../applications/itemCustom/saveGeneralItem.php" name='form' id='form' enctype="multipart/form-data" >
			<?php foreach($tables as $ind=>$table)
			{
				$Table=$index->toView($table);

			?>
			<?php
			$cols = $index->getGeneralColums($table);
			$cols = $cols['keys'];

			?>
			<?php if($ind!=0) { ?><center><h1><?php echo $index->toView($table).' '; ?></h1></center><?php } ?>
			<div class="form_div" >
			<table class="form_table" >
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
						if($id=='status'){$showStat=true;}else{$showStat=false;}
							$par=true;
							foreach($seq[$table] as $ind2=>$parent)
							{
								if($parent==$id)
								{
									$par=false;
								}
							}
						if($id!='id' && $id!='status' && $par )
						{

							$outer= $index->toView($id);
			?>
			<script language="javascript">
			$(function(){
				$("#da").hide();
			});
			</script>

			<?php if($id=='date_cr'){?><tr id="da"><?php }else{ ?><tr><?php } ?><td><label><?php echo $outer; ?>:<?php echo ' '.$star; ?></label></td>
								<td>

			<?php
								$foreignTable=$index->composeSelectBox($id); //return false if the table is not foreign and return an array of all values if its a forien
								$countForeignTable = is_array($foreignTable) ? count($foreignTable) == '0' : false;
								if (($foreignTable || $countForeignTable) && $id != 'image_id')
							{
			//var_dump($item);
			//$index->show($foreignTable);
			?>

								<select name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php  echo $Table.'-'.$outer.$optional;  ?>"  >
			<?php
								if(count($foreignTable)=='0')
								{
			?>
									<option value="null">Empty</option>

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
									//*********** get what columns to display *******
									$dataDN['multiple']=true;
									$display_name=$index->getTableDisplayName($Ftable,$dataDN);
									$dataCFDN['display_name']=$display_name;
									//*********** get what columns to display *******
									{
				?>
									<option value="null">Select</option>
				<?php
									}
									foreach($foreignTable as $id2=>$item)
									{
										$dataCFDN['item']=$item;
										$FDN=$index->composeFullDisplayName($dataCFDN);
										// old function low performance : $FDN=$index->GetFullDisplayName($item[$PRI],$Ftable);
										$defVal='';
										if(isset($_SESSION['context'][$Ftable]) && $_SESSION['context'][$Ftable]==$item[$PRI] ) { $defVal='selected="selected"'; }
										echo('<option '.$defVal.' value="'.$item[$PRI].'" >'.$FDN.'</option>');
									}
								}
			?>
								</select>
			<!------------------------------ Set The Language To English As Default -------------------------------------->
			<?php if($id=='language_id'){ ?>
			<script language="javascript" >
			var lng=document.form.elements["<?php echo $table.'[language_id]'; ?>"];
			var all=lng.length;
			for(var i=0 ; i<all ;i++)
			{
				if(lng.options[i].value==1)
				{
					lng.options[i].selected=true;
					lng.disabled=true;
				}
			}
			</script>
			<span style="font-size:10px;color:red;" id="saveNewLangNote" >(You can add info in another language later just by editing<?php echo ' "'.$index->toView($table).'"'; ?>)</span>
			<?php }?>
			<!------------------------------ End Set The Language To English As Default -------------------------------------->
			<?php
							}
							else
							{
								switch($id)
								{
									case 'password':
			?>

									<input type="password" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" >
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

									<input type="file" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" />
			<?php
									break;

									default:
									{
										switch ($type['type'])
										{
											case 'int':
			?>

												<input type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
			<?php
											break;
											case 'varchar':
			?>

												<input type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
			<?php
											break;
											case 'date':

												$date=' alt="date" readonly ';
												$hideDate='';
												if($id=='date_created')
												{
													$date=' value="'.date('Y-m-d').'" readonly ';
													$hideDate= '<script language="javascript" >hideDate("'.$table.'['.$id.']");</script>';
												}
			?>

												<input type="text"<?php echo ' '.$date; ?> name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
			<?php
												echo $hideDate;
											break;
											case 'datetime':
												$date=' alt="datetime" ';
												$hideDate='';
												if($id=='date_created')
												{
													$date=' value="'.date('Y-m-d H:i:s').'" readonly ';
													$hideDate= '<script language="javascript" >hideDate("'.$table.'['.$id.']");</script>';
												}
			?>

												<input type="text"<?php echo ' '.$date; ?> name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
			<?php
												echo $hideDate;
											break;
											case 'text':
			?>
												<textarea  name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" maxlength="<?php echo $type['length']; ?>" ></textarea>
			<?php
											break;
											case 'tinyint':
			?>
												<table border="0" width="100%" >
													<tr>
														<td>
														<input type="radio"<?php if($value['Default']=='0' || $value['Default']=='') echo " checked "; ?> style="width:10px" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo 'no'.$outer.$optional; ?>" value="0" ><label><?php if($id=='sex') echo ' Male'; else echo ' No'; ?></label>
														</td>
														<td>
														<input type="radio"<?php if($value['Default']!='0' && $value['Default']!='') echo " checked "; ?> style="width:10px" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo 'yes'.$outer.$optional; ?>" value="1" ><label><?php if($id=='sex') echo ' Female'; else echo ' Yes'; ?></label>
														</td>
													</tr>
												</table>
			<?php
											break;
											default:
			?>
												<input type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="" maxlength="<?php echo $type['length']; ?>" >
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
					if($showStat==true && $table==$tables[0])
					{
			?>
			<tr><td ><label>Status:</label></td><td>
			<select name="<?php echo $table; ?>[status]" id="status">
			<option value="ACTIVE">ACTIVE</option>
			<option value="INACTIVE">INACTIVE</option>
			<option value="PENDING">PENDING</option></select></td></tr>
			<?php
					}
			?>
			<tr><td colspan="2" ></td></tr>
			</table>
			</div>
			<?php } ?>
<input class="submit" type="submit" onclick="" value="Add<?php echo ' '.$index->toView($tables[0]); ?>" />
<!------------------------------------------- iframe data ------------------------------------------------------------->
<script language="javascript" >
/*
function init() {
	$('#form').submit(function(){
	if(!check('form'))
	{ return false; }
	$('#form').target = '_application'; //'upload_target' is the name of the iframe
	$('#_application').style.width="100%";
	$('#_application').style.height="400px";
	});


}
*/
/*
$(document).ready(function(){
	$("#form").submit(function(){
		if(!check('form'))
		{
			return false;
		}
		$('#form').target = '_application';
		$('#_application').style.width="100%";
		$('#_application').style.height="400px";
	});
});
*/
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
<iframe id="_application" name="_application" src="" style="width:0px;height:0px;"  frameborder="0" scrolling="no" allowtransparency="true" ></iframe>
</form>
<!--------------------------------------------- End iframe data ------------------------------------------------------------------------>
<?php // if(isset($_SESSION)){ /*unset($_SESSION['post']); unset($_SESSION['parentPage']);*/ $index->show($_SESSION); } ?>
</div>
</div>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>
