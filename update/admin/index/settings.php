<?php require_once('../public/layouts/theme_1/_header.html');?>
	<div id="body">	
		
		<div id="firstColumn" >
		</div>
		<div id="secondColumn"  >
			<div class="home_table" >
<?php
//************************************** all control_p_admin pages ************************************
$fold = opendir('.');
while(($file=readdir($fold))!=false)
{
	if(($file!='.' && $file!='..' && strpos($file,'control_p_')!==false) || $file=='language.php')
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
echo '<table border="0" '.$display.' ><tr>';
foreach($pages as $id=>$value)
{
	if(true )//place here the exeption pages that must not be shown in this page
	{
		if(stripos($value,'add')===false && stripos($value,'edit')===false)
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
		</div>
</div>
<?php require_once('../public/layouts/theme_1/_footer.html');?>