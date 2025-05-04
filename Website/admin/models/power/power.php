<?php
class power
{
	function create_db($data)
	{
		$db=$data['db'];
		$con=$data['con'];
		$sql='CREATE DATABASE `'.$db.'` CHARACTER SET utf8 COLLATE utf8_unicode_ci';
		$result=$con->query($sql);
		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function constructBasicDB($con)
	{
		if(file_exists("../../source/basicDB/DB.txt"))
		{
			$DBdir = "../../source/basicDB/DB.txt";
			$DBfile = fopen($DBdir, 'r');
			$DBsql = fread($DBfile, filesize($DBdir));
			fclose($DBfile);
			$queries=explode(';',$DBsql,-1);
			$error='';
			foreach($queries as $ind=>$query)
			{	if($query)
				{
					if(!mysqli_query($con, $query))
					{
						$error.= mysqli_error($con).' _'.$query.'_ <br/>';
					}
				}
			}
			if($error!='')
			{
				echo $error.'<br/>--------------------------------<br/>';
				return false;
			}
			else
			{
				return $error;
			}
		}
		else
		return false;
	}
}
?>