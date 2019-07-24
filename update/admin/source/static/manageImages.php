<?php require_once('../public/layouts/theme_1/_header.html');

require_once('../public/configuration.php');
$cnct=new cnct_class();
$index_data['cnx']=$cnct->cnct();

require_once("../models/index/index.php");
$index=new index($index_data);

//************************************* check if the user control_p_group allow him to enter this page *******
$path_parts = pathinfo(__FILE__);
$page=$path_parts['filename'];
$data['control_p_privilege']=$page;
$data['control_p_group_id']=$_SESSION['control_p_group_id'];
if(!$index->isAllowed($data))
{
//if(strpos($_SERVER["HTTP_REFERER"],'?')===false) $char='?' ; else $char='&'; header('Location:'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition');
//if(strpos($_SERVER["HTTP_REFERER"],'?')===false) { $char='?' ; } else { $char='&'; } echo  '<script language="javascript" > window.location="'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition"; </script>';
}
//************************************* check if the user control_p_group allow him to enter this page *******
//******************************************* constants *****************************************
$mainTable=strtolower(substr($page,6));//get the name of the table from the file name
$Table=$index->capitalize($mainTable);
//******************************************* constants end *************************************
//******************************************************** sub menu *****************************
$data['page']=$page;
$menu=$index->getMenuList($data);

//********************************************************* get folder uploaded to ************************************
if(isset($_GET['table']) && isset($_GET['id']))// validate the inputs
{
		$T=$_GET['table'];
		$I=$_GET['id'];
}
elseif(isset($_GET['table']) && !isset($_GET['id']) && isset($_SESSION['context'][$_GET['table']]))// get context id if exists
{
		$T=$_GET['table'];
		$I=$_SESSION['context'][$_GET['table']];
}
else
{
	if(strpos($_SERVER["HTTP_REFERER"],'?')===false) { $char='?' ; } else { $char='&'; } echo  '<script language="javascript" > window.location="'.$_SERVER["HTTP_REFERER"].$char.'note=Not Exist"; </script>';
	die('Please enable JavaScript To Continue');
}
$_SESSION['postData']['seq']='';// clear seq
$_SESSION['postData']['seq']['image_to_'.$T]=''; // gen the default seq (only one table)
//********************************************************* get folder uploaded to ************************************
?>
<?php
$F['keyword']=$I;
$F['filterBy']=$T.'_id';
$F['exact']=true;
$F['searchId']=false;
$FD['multiFilterBy'][]=$F;
$files=$index->getAllGeneralItemsWithJoins($FD,'image_to_'.$T);
//$index->show($FD);
?>
	<div id="body">

		<div id="firstColumn" >
		<div >
			<input id="add_item_buttom" type="button" style="width:<?php echo(strlen($index->toView($T))*9); ?>px" onClick="window.location='<?php echo $T; ?>.php'" value="<?php echo $index->toView($T); ?>" >
		</div>
		<table >
			<?php
				foreach($menu['menu_pages'] as $menu_id=>$mnu)
				{
					$display_name=$menu['menu_display_names'][$menu_id];
					//var_dump($menu['menu_pages']); die();
			?>

			<?php
					$res=$index->isAllowed_2($_SESSION['control_p_group_id'],$mnu); if($res) { ?><tr ><td><span ><a href="<?php if(strpos($mnu, '.php')===false) echo $mnu.'.php'; else echo $mnu; ?>" ><?php if($display_name=="")echo $index->toView($mnu); else { echo $display_name; }?></a></span></td></tr><?php } ?>
			<?php
				}
			?>
		</table>
		</div>
<div id="secondColumn"  >
	<div id="item_name" style="padding-bottom:30px;" >
		<center><label >Add<?php echo ' '.$index->toView($mainTable).' '; ?></label></center>
	</div>

	<form  method="post" action="../applications/item/saveImages.php" name='form' id='form' enctype="multipart/form-data" onsubmit="">

<!-------------------------------------------------- load more input from table ------------------------------------------------>
		<?php
			$table="image_to_".$T;
			$cols = $index->getGeneralColums($table);
			$cols = $cols['keys'];
		?>
		<table style="float:left;width:200px;!important" >
		<tr><td><b><?php echo $index->toView($T).' :</b></td><td> '.$index->toView($index->showValue($I,$T.'_id')); ?></td></tr>
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
						// ************* removed this because there is no parent here to hide its combo and set automatic
						/*
						foreach($seq[$table] as $ind2=>$parent)
						{
							if($parent==$id)
							{
								$par=false;
							}
						}
						*/
					if($id!='id' && $id!='status' && $id!=$T."_id" && $par )
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
							$foreignTable=$index->composeSelectBox($id);//return false if the table is not forien and return an array aff all values if its a forien
							if($foreignTable["status"] != 0 && $id!='image_id')
						{
		//var_dump($item);
		?>

							<select name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php  echo $Table.'-'.$outer.$optional;  ?>"  >
		<?php
							if(count($foreignTable["items"])=='0')
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
								foreach($foreignTable["items"] as $id2=>$item)
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
		<tr><td colspan="2" ><input type="submit" value="Add<?php echo ' '.$index->toView($mainTable); ?>" /></td></tr>
		<tr><td colspan="2" >
		<iframe id="_application" name="_application" src="" style="width:0px;height:0px;"  frameborder="0" scrolling="no" allowtransparency="true" ></iframe>
		</td></tr>
		</table>

<!-------------------------------------------------- load more input from table ------------------------------------------------>

	<!-------------------------------- documents of a table ------------------------------------>
	<table style="float:right;"  >
	<tr>
	<?php
	$dir='../../';
	$edit=true;
	$C=0;
	if(count($files))
	{
		foreach($files as $id=>$file)
		{
				$ext='jpeg';
				if($edit)
				{
					$del='<input type="button" style="width:17px;height:17px;font-size:10px;padding-bottom:10px" value="X" onclick="window.location=\'../applications/item/delImage.php?table='.$T.'&item_id='.$I.'&id='.$id.'\'" >';
				}
				else
				{
					$del='';
				}
				echo '<td style="padding-left:20px;font-size:12px" align="center" ><a  class="fancybox" rel="images" href="'.$dir.'public/images/'.$file['image_name'].'" ><img src="'.$dir.'public/images/thumbs/'.$file['image_name'].'" ></a>'.$del.'</td>';
				if($C==3)
				{
					echo '</tr><tr>';
					$C=-1;
				}
				$C++;
		}
	}
	?>
	</tr>
	</table>
	<!-------------------------------- END: documents of a table ------------------------------------>
	<input type="hidden" name="<?php echo 'image_to_'.$T; ?>[<?php echo $T; ?>_id]" value="<?php echo $I; ?>" >
	</form>
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
	<!--------------------------------------------- End iframe data ------------------------------------------------------------------------>
	<!--------------------------------------------- datepicker ------------------------------------------------------------------------>
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
	<!--------------------------------------------- datepicker ------------------------------------------------------------------------>
</div>
</div>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>
