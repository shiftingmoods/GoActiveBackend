<?php
class custom extends index
{

 /**   Function to shuffle the array for the Complementary Designs in view.php page   **/
	function getShuffleArrays(){

	$filterData1=array();
	$forherallimages=$this->getAllGeneralItemsWithJoins($filterData1,"library_for_her");
	//die('111');
    $filterData2=array();
	$forhimallimages=$this->getAllGeneralItemsWithJoins($filterData2,"library_for_him");
	
	$arrayall = array_merge($forherallimages,$forhimallimages);
	shuffle($arrayall);
	
    $ComplementaryArray=array_splice($arrayall,  0, 10);
	return $ComplementaryArray;
	}

 /**   Function to check if the key existe in the array to check if it's women-view or men-view   **/	
	function checkKeyInArray($key,$search_array){
	if (array_key_exists($key, $search_array)) {
	return true;
	}
	else return false;
	}
	

}
?>