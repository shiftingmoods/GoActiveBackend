<?php	require_once("../../models/index/index.php"); ?>
<?php	require_once("../connection/connect.php");
$index=new index();
$cnct=new cnct_class();
$cnct->cnct();
?>
<?php
if($_POST['id']=="0")
{
echo('please Select Type');die();
}
$content= $index->getGeneralItemById($_POST['id'],'type'); 
eval ('?>'.$content[$_POST['id']]['structure_code'].'<?php');
?>