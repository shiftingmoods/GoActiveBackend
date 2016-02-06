<?php
	require_once("../connection/connect.php");
	require_once("../../models/index/index.php");
	
	$cnct=new cnct_class();
	$cnx=$cnct->cnct();
	$index_data['cnx']=$cnx;
	$index=new index($index_data); 
?>
<?php
if($_POST['id']=="0")
{
echo('please Select Type');die();
}
$content= $index->getGeneralItemById($_POST['id'],'type'); 
eval ('?>'.$content[$_POST['id']]['structure_code'].'<?php');
?>