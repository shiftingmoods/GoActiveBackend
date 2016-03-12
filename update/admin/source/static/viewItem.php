<?php require_once('../public/configuration.php');?>
<?php require_once('../public/layouts/theme_1/_header.html');?>
<?php require_once("../models/index/index.php");
$index=new index();
$cnct=new cnct_class();
$cnct->cnct();
$table=$_GET['table'];
$TABLE=$index->capitalize($table);
$selecBoxName='company_username';
?>
<?php
$Table=$index->toView($table);
$cols = $index->getGeneralColums($table);
$keys = $cols['keys'];

$PRI=$cols['primaryKeys'];
$PRI=$PRI[0];
if(isset($_GET[$PRI]))
{
	if($_GET[$PRI]!='0')
	{	
		$selectedItemId=$_GET[$PRI];
		if(!$index->checkIdIfExist($selectedItemId,$table))
		{
			header('Location:index.php?note=Invalid Inputs');
		}
		$Aitm = $index->getGeneralItemById($selectedItemId,$table);
		$itm = $Aitm[$selectedItemId];
	}
}

?>
<div id="body">	
		
		<div id="firstColumn" >
		<div >
			<input id="add_item_buttom" type="button" style="width:<?php echo(strlen($table)*9); ?>px" onClick="window.location='<?php echo '../index/edit'.$TABLE; ?>.php?id=<?php echo $selectedItemId; ?>'" value="<?php echo 'Edit '.$Table; ?>" >
		</div>
		<table >
		</table>
		</div>
		<div id="secondColumn"  >

			<center><h1><?php echo $index->toView($table).': '.$index->showValue($selectedItemId,$table.'_id'); ?></h1>
			</center>
<SCRIPT language=JavaScript>
	function reload()
	{
		var selectedItemId = document.form.item.value;
		
		
		window.location='?selectedItemId='+selectedItemId ;
	}	
		
	</script>
<br><br><br>
<form method="post" name="form" >
<table>
<tr>
<td >
</td>
<td>
<input type="hidden" value="<?php echo $selectedItemId;?>" name="item" >
</td>
</tr>
<?php 

//************************* general Variables **************************************

	$seq[$table]=array();

//************************* End general Variables **************************************

if(isset($selectedItemId))
			{
				if($selectedItemId!='0')
				{
					foreach($keys as $id=>$value)
					{
						//$index->show($keys);
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
								
								<select disabled name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>"<?php if($Ftable=='language') echo (' onchange="changeEditMode()"'); ?> >
								<option value="null" >Select</option>
			<?php
								foreach($foreignTable as $id2=>$item)
								{
									$dataCFDN['item']=$item;
									$FDN=$index->composeFullDisplayName($dataCFDN);
									// old function low performance : $FDN=$index->GetFullDisplayName($item[$PRI],$Ftable);
									if($itm[$id]==$item[$PRI])
									{
										echo('<option value="'.$item[$PRI].'" selected="selected" >'.$FDN.'</option>');
									}
									else
									{
										echo('<option value="'.$item[$PRI].'" >'.$FDN.'</option>');
									}
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
									<input type="password" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.'_optional'; ?>" value="Private" >
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
										echo '<td width="150px" ><span style="float:right" ><input disabled type="checkbox" style="width:30px" name="'.$id.'_'.$day.'" ';  if(isset($schedule[$day])) { echo " checked "; } echo '></span><span style="float:left" >'.$day.' : </span></td>';
										//***********************************************************************
										echo '<td><select disabled style="width:100px" name="'.$id.'_'.$day.'_1" >';
										for($h=1;$h<13;$h++)
										{
											echo '<option value="'.$h.':00"';  if($schedule[$day.'_1']==$h.':00') { echo " selected "; } echo ' >'.$h.':00</option>';
											echo '<option value="'.$h.':15"';  if($schedule[$day.'_1']==$h.':15') { echo " selected "; } echo ' >'.$h.':15</option>';
											echo '<option value="'.$h.':30"';  if($schedule[$day.'_1']==$h.':30') { echo " selected "; } echo ' >'.$h.':30</option>';
											echo '<option value="'.$h.':45"';  if($schedule[$day.'_1']==$h.':45') { echo " selected "; } echo ' >'.$h.':45</option>';
										}
										echo '</select></td>';
										//***********************************************************************
										echo '<td><select disabled style="width:70px" name="'.$id.'_'.$day.'_2">';
										echo '<option value="AM" '; if($schedule[$day.'_2']=='AM') { echo " selected "; } echo '>AM</option>';
										echo '<option value="PM" '; if($schedule[$day.'_2']=='PM') { echo " selected "; } echo '>PM</option>';
										echo '</select></td>';
										//***********************************************************************
										echo '<td>Till</td>';
										//***********************************************************************
										echo '<td><select disabled style="width:100px" name="'.$id.'_'.$day.'_3" >';
										for($h=1;$h<13;$h++)
										{
											echo '<option value="'.$h.':00"';  if($schedule[$day.'_3']==$h.':00') { echo " selected "; } echo ' >'.$h.':00</option>';
											echo '<option value="'.$h.':15"';  if($schedule[$day.'_3']==$h.':15') { echo " selected "; } echo ' >'.$h.':15</option>';
											echo '<option value="'.$h.':30"';  if($schedule[$day.'_3']==$h.':30') { echo " selected "; } echo ' >'.$h.':30</option>';
											echo '<option value="'.$h.':45"';  if($schedule[$day.'_3']==$h.':45') { echo " selected "; } echo ' >'.$h.':45</option>';
										}
										echo '</select></td>';
										//***********************************************************************
										echo '<td><select disabled style="width:70px" name="'.$id.'_'.$day.'_4" >';
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
									
			<?php				
									break;
									default :
									switch ($type['type'])
										{
											case 'int':
			?>						
									
												<input  disabled type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
			<?php	
											break;
											case 'date':
												$date=' alt="" readonly ';
												$hideDate='';
												if($id=='date_created')
												{
													$date=' readonly ';
													$hideDate= '<script language="javascript" >hideDate("'.$table.'['.$id.']");</script>';
												}
			?>						
									
												<input disabled type="text"<?php echo ' '.$date; ?> name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
			<?php	
												echo $hideDate;
											break;
											case 'varchar':
			?>						
									
												<input disabled type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
			<?php	
											break;
											case 'tinyint':
			?>
												<table border="0" width="100%" >
													<tr>
														<td>
														<input disabled type="radio"<?php if($itm[$id]=='0' || $itm[$id]=='') echo " checked "; ?> style="width:10px" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo 'no'.$outer.$optional; ?>" value="0" ><label><?php if($id=='sex') echo ' Male'; else echo ' No'; ?></label>
														</td>
														<td>
														<input disabled type="radio"<?php if($itm[$id]!='0' && $itm[$id]!='') echo " checked "; ?> style="width:10px" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo 'yes'.$outer.$optional; ?>" value="1" ><label><?php if($id=='sex') echo ' Female'; else echo ' Yes'; ?></label>
														</td>
													</tr>
												</table>
			<?php	
											break;
											case 'text':
			?>
												<textarea disabled name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" maxlength="<?php echo $type['length']; ?>" ><?php echo $itm[$id]; ?></textarea>
			<?php	
											break;
											default:
			?>
												<input disabled type="text" name="<?php echo $table; ?>[<?php echo $id; ?>]" id="<?php echo $Table.'-'.$outer.$optional; ?>" value="<?php echo $itm[$id]; ?>" maxlength="<?php echo $type['length']; ?>" >
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
					<select disabled name="<?php echo $table; ?>[status]" id="status">
					<option value="ACTIVE"<?php if($itm['status']=="ACTIVE") echo("selected"); else echo(""); ?> >ACTIVE</option>
					<option value="INACTIVE" <?php if($itm['status']=="INACTIVE") echo("selected"); else echo(""); ?> >INACTIVE</option>
					<option value="PENDING" <?php if($itm['status']=="PENDING") echo("selected"); else echo(""); ?> >PENDING</option></select></td></tr>
					<?php
					}
				}
			}
		
?>
<tr><td><input type="button" value="Close" onClick="window.close()" /></td></tr>
</table>
<input type="hidden" name="table" value="<?php echo $table; ?>">
</form>
<br><br><br><br>
</div>
</div>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>