<?php
class user
{
	function __construct()
	{
		date_default_timezone_set("Asia/Beirut");
	}

	function login($data)
	{
		if(isset($data['username']))
		{
			$sql = 'SELECT id AS control_p_admin,username,control_p_group_id FROM `control_p_admin` WHERE username = "'.$data['username'].'" AND password = MD5("'.$data['password'].'") LIMIT 1';
		}
		if(isset($data['email']))
		{
			$sql = 'SELECT id AS control_p_admin,email,control_p_group_id FROM `control_p_admin` WHERE email = "'.$data['email'].'" AND password = MD5("'.$data['password'].'") LIMIT 1';
		}
		$result = mysqli_query($data['cnx'],$sql);

		if($row = mysqli_fetch_assoc($result))
		{
			return $row;
		}
		else
		{
			return false;
		}
	}
	function logout($data)
	{
		$cnx=$data['cnx'];
		$id=$data['id'];
		$sql = 'UPDATE control_p_login SET end_date="'.date("F j, Y, g:i a").'", online="No" WHERE id = "'.$id.'" ';
		$result = mysqli_query($cnx,$sql);
		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>
