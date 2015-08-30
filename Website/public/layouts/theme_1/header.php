<?php session_start(); ?>
<?php require_once("../models/index/index.php");
$index=new index();
?>
<?php require_once("../models/index/custom.php");
$custom=new custom();
?>
<?php require_once('../public/configuration.php');
$cnct=new cnct_class();
$cnct->cnct();
?>
<?php
require_once("../public/constants.php");
$C= 'ConstantsControl_p_admin';
$C = new ReflectionClass($C);
?>
<?php
$statusF['keyword']='ACTIVE';
$statusF['filterBy']='status';
$statusF['exact']=true;
$statusF['searchId']=false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
