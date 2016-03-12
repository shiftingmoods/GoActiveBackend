<?php require_once('../public/layouts/theme_1/_header.html');?>
<?php
$path_parts0 = pathinfo(__FILE__);
$page0=$path_parts0['filename'];
$table0=strtolower(substr($page0,4));
$Table0=$index->capitalize($table0);
if(file_exists("../source/custom/".$page0.".php"))
{
	eval(file_get_contents("../source/custom/".$page0.".php"));
}
else
{
	if(strpos($table0,'_language')!==false)
	{
		eval(file_get_contents("../source/template/iframe/editItem_language.php"));
	}
	else
	{
		//************************* general Variables **************************************
		
			if($seqTemp=$index->getSeqArray($page0))
			{
				$seq=$seqTemp;
			}
			
		//************************* End general Variables **************************************
		
		eval(file_get_contents("../source/template/iframe/editItem.php"));
	}
}
?>