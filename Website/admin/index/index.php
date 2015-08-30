<?php require_once('../public/layouts/theme_1/_header.html');?>
<?php
$Mdata['page']='index';
$menu=$index->getMenuList($Mdata);
?>
	<div id="body">	
		
		<div id="firstColumn" >

		<table style="margin-top:0px;" >


			<?php
				foreach($menu['menu_pages'] as $menu_id=>$mnu)
				{
					$display_name=$menu['menu_display_names'][$menu_id];
					//var_dump($menu['menu_pages']); die();
			?>
					
			<?php 
					$res=$index->isAllowed_2($_SESSION['control_p_group_id'],$mnu); if($res) { ?><tr ><td><span ><a href="<?php echo $mnu;?>.php"><?php if($display_name=="")echo $index->toView($mnu); else { echo $display_name; }?></a></span></td></tr><?php } ?>	
			<?php
				}
			?>
		</table>
		</div>
		<div id="secondColumn"  >
<?php
		switch ($_SESSION['control_p_group_id'])
		{
			case 0:
			?>
			<div class="home_table" >
			<?php
			//************************************** all control_p_admin pages ************************************
			$fold = opendir('.');
			while(($file=readdir($fold))!=false)
			{
				if($file!='.' && $file!='..')
				{
					$pages[]=str_replace('.php','',$file);
				}
			}
			//$index->show($pages);
			//$pages=$index->getAllGeneralItemsWithJoins('','control_p_privilege');
			$i=0;
			$display=''; 
			if($_SESSION['control_p_group_id']!='0')
			{
				 $display='style="display:none"'; 
			}
			echo '<table  '.$display.' ><tr>';
			foreach($pages as $id=>$value)
			{
				if( strpos($value,'control_p_')===false && $value!='index' && $value!='manageFiles' && $value!='manageImages' && $value!='login' && $value!='settings' && $value!='viewItem'  && $value!='file'  && $value!='files'  && $value!='del'  && $value!='language' )
				{
					if(stripos($value,'add')===false && stripos($value,'edit')===false && stripos($value,'image_to_')===false )
					{
						$btnValue=$index->toView($value);
						if(strlen($btnValue)>20)
						{
							$btnValue=substr($btnValue, 0, -(strlen($btnValue)-20)).'..';  
						}
						echo ('<td ><input type="button" title="'.$index->toView($value).'" style="float:left" onclick="window.location=\''.$value.'.php\'" value="'.$btnValue.'" ></td>');
						$i++;
					}
					
					if($i==6){echo '</tr><tr>'; $i=0;}
				}
			}
			echo '<tr></table>';
			//************************************** end : all control_p_admin pages ************************************
			?>
			</div>
		<?php
		break;
		case 1:
		?>
		<div class="home_table" >
		<table>
			<tr>
				<td>
				<input type="button" title="Fill New Form" style="float:left" onclick="window.location='../../entry/index.php'" value="Fill New Form" >
				</td>
				<td>
				<input type="button" title="Fill New Form" style="float:left" onclick="window.location='../../index/index.php'" value="Advanced Search" >
				</td>
				
				<td>
				</td>
			</tr>
		</table>
		</div>
		<?php
		break;
		?>
		
		<?php
		}
		?>
		</div>	
		
	</div>
<?php require_once('../public/layouts/theme_1/_footer.html');