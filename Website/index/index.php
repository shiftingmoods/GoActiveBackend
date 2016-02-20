<?php  include("../public/layouts/theme_1/header.php"); ?>

<!-------------------------------------- Page Header ----------------------------------------->
<head>

<title></title>
<link rel="stylesheet" type="text/css" href="../public/css/style.css" media="all" />
<script type="text/javascript" src="../public/js/jquery.js"></script>

</head>
<body>
<div id=body>
<div id="header" >

</div>
<!-------------------------------------- Page Header End ------------------------------------->

<?php
$table='item';
// $index->setVar('2','lang');
$languages=$index->getAllGeneralItemsWithJoins('','language');

// $index->show($allItems);
?>

<!-------------------------------------- paging Step 1 ----------------------------------------->
<form id="form" method="post" >
<input type="hidden" id="cur_page" name="cur_page" value="0" >
<!-------------------------------------- pagingE ----------------------------------------->


<!-------------------------------------- language Step 1 ----------------------------------------->
<?php
$lang=1;
if(count($languages)>1)
{

	if(isset($_POST['lang']) && filter_var($_POST['lang'],FILTER_VALIDATE_INT) &&  $index->checkIdIfExist($_POST['lang'],'language'))
	{
		$lang=$_POST['lang'];
	}
?>
	<select name="lang" id="lang" >
<?php
	foreach($languages as $langID=>$LangData)
	{
		$selected="";
		if($lang==$langID)
		{
			$selected="selected";
		}
		echo '<option '.$selected.' value="'.$langID.'" >'.$LangData['code'].'</option>';
	}
?>
	</select>

<?php
}
$index->setVar($lang,'lang');
?>
<!-------------------------------------- language Step 1 ----------------------------------------->
<?php
$allItems=$index->getAllGeneralItemsWithJoins('','item');
?>
</form>
<?php
//************ paging Step 2 ****************************
	$per_page='1';
	$pages_data=array_chunk($allItems,$per_page);// get pages data each page number(index) with its ids
	$all_items=count($allItems);
	$all_pages=count($pages_data);
	$cur_page=0;
	if(isset($_POST['cur_page']) && filter_var($_POST['cur_page'],FILTER_VALIDATE_INT) && $_POST['cur_page']<=$all_pages)
	{
		 $cur_page=$_POST['cur_page'];
	}
	//$index->show($pages_data);
	$pageItems=Array();
	if(isset($pages_data[$cur_page]))
	{
		 $pageItems=$pages_data[$cur_page];
	}
	//$index->show($all_pages);
//************ pagingE ****************************
?>
<table style="width:500px" >
	<tr style="background:lightgray">
		<td>
		ID
		</td>
		<td>
		Name
		</td>
		<td>
		Price
		</td>
		<td>
		Description
		</td>
		<td>
		Status
		</td>
	</tr>
<?php
foreach($pageItems as $id=>$data)
{
	// $langData=$data;
	// if($lang!=1)
	// {
	// 	/*
	// 	$itemId['id']=$id;
	// 	$itemId['useLang']=true;
	// 	$itemId['language_id']=$lang;
	// 	*/
	// 	//$forceNotNull=False; // true : if didn't find data defined in the selected language, return data of the default language instead
	//
	// 	$langData=$index->getGeneralItemByIdAndLangId($data['id'],$table);
	// }
	echo '<tr>';
	echo '<td>'.$data["id"].'</td>';
	echo '<td>'.$data["name"].'</td>';
	echo '<td>'.$data["price"].'</td>';
	echo '<td>'.$data["description"].'</td>';
	echo '<td>'.$data["status"].'</td>';
	echo '</tr>';
}
		//$index->show($langData);
?>

</table>
<!------------------------- paging Step 3 -------------------------------->
	  <center>
		   <table style="width:100%">
				<tr>
					 <td>
					 <?php
					 if(!($cur_page<=0))
					 {
					 ?>
					 <div style="float:left;height:25px;width:100px;color:#FFFFFF;background:#A5CB60;cursor:pointer;font-size:20px" onclick="prev_page()" ><center><span>Prev</span></center></div>
					 <?php
					 }
					 ?>
					 </td>
					 <td>
					 <?php
					 if(!($cur_page>=$all_pages-1))
					 {
					 ?>
					 <div style="float:right;height:25px;width:100px;color:#FFFFFF;background:#A5CB60;cursor:pointer;font-size:20px" onclick="next_page()" ><center><span>Next</span></center></div>
					 <?php
					 }
					 ?>
					 </td>
				</tr>
		   </table>
	  </center>
	  <script language="javascript" >
		   function prev_page()
		   {
				$('#cur_page').val(parseInt(<?php echo $cur_page; ?>)-1);
				$('#form').submit();
		   }
		   function next_page()
		   {
				$('#cur_page').val(parseInt(<?php echo $cur_page; ?>)+1);
				$('#form').submit();

		   }
		   $('#cur_page').val('<?php  echo $cur_page; ?>');
	  </script>
<!------------------------- pagingE -------------------------------->

<!------------------------- Language -------------------------------->
	  <script language="javascript" >
	  $( "#lang" ).change(function() {
		  $('#form').submit();
		});
	  </script>

<!-------------------------------------- Page Footer ----------------------------------------->
</div>
</body>
<div id="footer-container">
    <div class="footer">
    </div>
</div>
<!-------------------------------------- Page Footer End ------------------------------------->
<?php  include("../public/layouts/theme_1/footer.php"); ?>
