<?php require_once('../public/configuration.php');?>
<?php require_once('../public/layouts/theme_1/_header.html');?>
<?php require_once("../models/index/index.php");
$index=new index();
$cnct=new cnct_class();
$cnct->cnct();
//************************************* check if the user control_p_group allow him to enter this page *******
$path_parts = pathinfo(__FILE__);
$page=$path_parts['filename'];
$data['control_p_privilege']=$page;
$data['control_p_group_id']=$_SESSION['control_p_group_id'];
if(!$index->isAllowed($data))
{
//if(strpos($_SERVER["HTTP_REFERER"],'?')===false) $char='?' ; else $char='&'; echo  '<script language="javascript" > window.location="'.$_SERVER["HTTP_REFERER"].$char.'note=No Permition"; </script>';
//die('Please enable JavaScript To Continue');
}
//************************************* check if the user control_p_group allow him to enter this page *******
//******************************************* constants *****************************************
$table=strtolower(substr($page,6));//get the name of the table from the file name
$Table=$index->capitalize($table);
//******************************************* constants end *************************************
//******************************************************** sub menu *****************************
$data['page']=$page;
$menu=$index->getMenuList($data);

//********************************************************* get folder uploaded to ************************************
if(isset($_GET['table']) && isset($_GET['id']))// validate the inputs
{
	if(($index->hasFiles($_GET['table'])) && ($index->checkIdIfExist($_GET['id'],$_GET['table'])))
	{
		$T=$_GET['table'];
		$I=$_GET['id'];
	}
	else
	{
		if(strpos($_SERVER["HTTP_REFERER"],'?')===false) { $char='?' ; } else { $char='&'; } echo  '<script language="javascript" > window.location="'.$_SERVER["HTTP_REFERER"].$char.'note=Not Exist"; </script>';
		die('Please enable JavaScript To Continue');
	}
}
elseif(isset($_GET['table']) && !isset($_GET['id']) && isset($_SESSION['context'][$_GET['table']]))// get context id if exists
{
	if(($index->hasFiles($_GET['table'])) && ($index->checkIdIfExist($_SESSION['context'][$_GET['table']],$_GET['table'])))
	{
		$T=$_GET['table'];
		$I=$_SESSION['context'][$_GET['table']];
	}
	else
	{
		if(strpos($_SERVER["HTTP_REFERER"],'?')===false) { $char='?' ; } else { $char='&'; } echo  '<script language="javascript" > window.location="'.$_SERVER["HTTP_REFERER"].$char.'note=Not Exist"; </script>';
		die('Please enable JavaScript To Continue');
	}
}
else
{
	if(strpos($_SERVER["HTTP_REFERER"],'?')===false) { $char='?' ; } else { $char='&'; } echo  '<script language="javascript" > window.location="'.$_SERVER["HTTP_REFERER"].$char.'note=Not Exist"; </script>';
	die('Please enable JavaScript To Continue');
}
//********************************************************* get folder uploaded to ************************************
//*********************************************** get allowed extentions ***********************
$tableFilter['keyword']=$T;
$tableFilter['filterBy']='table';
$tableFilter['exact']=true;
$tableFilter['searchId']=false;
$FDext['multiFilterBy'][]=$tableFilter;
$extRow=$index->getAllGeneralItemsWithJoins($FDext,'files');
$extValue=array_values($extRow);// rest the index of array to 0
$extensions=$extValue[0]['extensions'];
//$index->show($extensions);
//*********************************************** get allowed extentions end ***********************
$all=2;
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
<?php
//******************************************************** sub menu *****************************
$cols = $index->getGeneralColums($table);
$cols = $cols['keys'];
?>
<div id="secondColumn" >
			<div id="item_name" style="padding-bottom:30px;" >
				<center><label ><?php echo ' '.$index->toView($T); ?> Files <?php if($extensions) echo '<br /><div style="font-size:14px;color:gray;word-wrap:break-word;width:700px">( '.$extensions.' )</div>'; ?></label></center>
			</div>
		<script language="javascript" >
		var extensions="<?php echo $extensions.' '; ?>";//added the space to be find index of ext.' ' without having inacurate results such as .xls and .xlsx
		</script>
		<form  method="post" action="../applications/item/saveFiles.php" id='form' name='form' enctype="multipart/form-data" onsubmit="">
		<table style="float:left;width:200px;" >
		<tr>
		<td>
			<?php echo $index->toView($T).' : '.$index->toView($index->showValue($I,$T.'_id')); ?>
			<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="0"/>
		</td>
		</tr>
		<?php
		for($i=0;$i<$all ;$i++)
		{
		?>
		<tr>
			<td>
				<input type="file" id="file_<?php echo $i; ?>" name="file_<?php echo $i; ?>" >
				<br />
				</td>
		</tr>
		<?php
		}
		?>
		<tr>
		<td>
		<iframe id="upload_frame" name="upload_frame" style="height:20px;width:195px;" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
		</td>
		</tr>
		<tr><td colspan="2" ><input type="submit" value="Add<?php echo ' '.$index->toView($table); ?>" /></td></tr>
		</table>
		<!-------------------------------- documents of a table ------------------------------------>
		<table style="float:left;width:600px" >
		<tr>
		<?php
		$dir='../../';
		$edit=true;
		if(file_exists($dir.'public/files/'.$T.'/'.$I))
		{
			$files=scandir($dir.'public/files/'.$T.'/'.$I);
			$C=0;
			foreach($files as $id=>$file)
			{
				if($file!='.' && $file!='..')
				{
					if($edit)
					{
						$del='<input type="button" style="width:17px;height:17px;font-size:10px;padding-bottom:10px" value="X" onclick="window.location=\'../applications/item/delFile.php?file='.$file.'&table='.$T.'&id='.$I.'\'" >';
					}
					else
					{
						$del='';
					}		
					echo '<td style="padding-right:0px;font-size:12px;" align="center" ><div style="width:140px" ><a target="_blank" href="'.$dir.'public/files/'.$T.'/'.$I.'/'.$file.'" ><img src="../public/design-images/file.png" ></a>'.$del.'<br>'.$file.'</div></td>';
					if($C==3)
					{
						echo '</tr><tr>';
						$C=-1;
					}
					$C++;
				}
				
			}
		}
		?>
		</tr>
		</table>
		<!-------------------------------- END: documents of a table ------------------------------------>
		<input type="hidden" name="table" value="<?php echo $T; ?>" >
		<input type="hidden" name="id" value="<?php echo $I; ?>" >
		</form>
		</div>	
		
	</div>
		<!--display bar only if file is chosen-->
	<script>

	$(document).ready(function() { 
	//

	//show the progress bar only if a file field was clicked
		<?php
		for($i=0;$i<$all ;$i++)
		{
		?>
			var show_bar_<?php echo $i; ?> = 0;
			$('#file_<?php echo $i; ?>').click(function(){
			show_bar= 1;
			});
		<?php
		}
		?>

	//show iframe on form submit
		$("#form").submit(function(){
				if(!validateFileType('form',extensions))
				{
					return false;
				}

					if (show_bar === 1)//so at least 1 input had been set 
					//if (show_bar_<?php echo $i; ?> === 1)//so at least 1 input had been set 
					{
						$('#upload_frame').css('height','20px');
						$('#upload_frame').show(600);
						function set () 
						{
							$('#upload_frame').attr('src','../applications/item/upload_progress.php?up_id=0');
						}
						setTimeout(set);
					}
		});
	//

	});

	</script>
<?php require_once('../public/layouts/theme_1/_footer.html'); ?>