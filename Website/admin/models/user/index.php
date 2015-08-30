<?php
class user
{
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
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result))
		{
			return $row;
		}
		else
		{
			return false;
		}
	}
	function logout($id)
	{
		$sql = 'UPDATE control_p_login SET end_date="'.date("F j, Y, g:i a").'", online="No" WHERE id = "'.$id.'" ';
		$result = mysql_query($sql);
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