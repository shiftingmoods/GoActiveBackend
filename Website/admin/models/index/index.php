<?php

class index
{
	public $lang=1;
	var $cnx;

	function __construct($data)
	{
		$this->cnx=$data['cnx'];
		date_default_timezone_set("Asia/Beirut");
	}
	//******************  Set/Get any variable **************************
	function setVar($data0,$varName)
	{
		$data=array();
		if(is_array($data0))
		{
			$data=$data0;
		}
		else
		{
			$data['value']=$data0;
		}
		$this->$varName=$data['value'];
	}

	function getVar($varName)
	{
		return $this->$varName;
	}
	//******************  Set/Get any variable **************************

	function show($data,$fin='yes')
	{
		if(!is_array($data))
		{
			echo $data; die();
		}
		if($fin=='yes')
		{$w='';}
		else
		{$w='width="100%"';}
		$item='<table '.$w.' border="1" bordercolor="black">';
		foreach($data as $id => $val)
		{
			if(is_array($val))
			{
				$item=$item.'<tr><td>'.$id.'</td><td>'.$this->show($val,'no').'</td></tr>';
			}
			else
			{
				$item=$item.'<tr><td>'.$id.'</td><td>'.$val.'</td></tr>';
			}
		}
		$item=$item.'</table>';
		if($fin=='yes')
		{die($item);}
		return $item;
	}

	function createStaticPagesAuto()//automaticaly add an "addPage" , "editPage" , "page" for tables in database . And also add the static pages ("settings","login",....)
	{
		$c='';
		$static=scandir('../../source/static/');//add the static pages if they r not exist
		unset($static[0]);
		unset($static[1]);
		foreach($static as $value)
		{
			if (!file_exists('../../index/'.$value))
			{	//sleep(1);
				$c=$c.'File '.$value.' Added<br />';
				copy("../../source/static/".$value, '../../index/'.$value);
			}
		}
		if($c=='')
		{
			$c='No Static pages Added<br/>';
		}
		return $c.'<br/>--------------------------------<br/>';
	}
	function createFilesFromDbTablesAuto()//automaticaly add an "addPage" , "editPage" , "page" for tables in database . And also add the static pages ("settings","login",....)
	{
		echo $this->createStaticPagesAuto();
		echo'<br/>';
		$item=array();
		$c='';
		$sql0='SELECT DATABASE()';
		$result0=mysqli_query($this->cnx,$sql0);
		$row0=mysqli_fetch_assoc($result0);
		$DB=$row0['DATABASE()'];
		$sql="SHOW FULL TABLES ";
		$result=mysqli_query($this->cnx,$sql);
		while($row=mysqli_fetch_assoc($result))
		{
			if($row['Tables_in_'.$DB]!='image' && $row['Tables_in_'.$DB]!='login')//exclude this tables
			{
				$item[]=$row['Tables_in_'.$DB];
			}
		}
		//$this->show($item);
		foreach($item as $id=>$value)
		{
			if (!file_exists("../../index/".$value.".php"))
			{	//sleep(1);
				copy("../../source/dinamic/item.php", "../../index/".$value.".php");
				$c=$c.$value.'.php File Added<br />';
			}
			if (!file_exists("../../index/add".ucfirst($value).".php"))
			{	//sleep(1);
				copy("../../source/dinamic/addItem.php", "../../index/add".ucfirst($value).".php");
				$c=$c.'add'.ucfirst($value).'.php File Added<br />';
			}
			if (!file_exists("../../index/edit".ucfirst($value).".php"))
			{	//sleep(1);
				copy("../../source/dinamic/editItem.php", "../../index/edit".ucfirst($value).".php");
				$c=$c.'edit'.ucfirst($value).'.php File Added<br />';
			}
		}
		if($c=='')
		{
			$c='No Dinamic Pages Added';
		}
		return $c.'<br/>--------------------------------<br/>'.$this->addAllPrivilegesAuto();
	}
	function deleteTableAndItsFiles($table)//automaticaly add an "addPage" , "editPage" , "page" for tables in database . And also add the static pages ("settings","login",....)
	{
		$item=array();
		$c='';
		$sql='DROP TABLE `'.$table.'`';
		$result=mysqli_query($this->cnx,$sql);
		if($result)
		{
			$c=$c.$table." Table Deleted From DB<br />";
		}
			if (file_exists("../../index/".$table.".php"))
			{	//sleep(1);
				unlink('../../index/'.$table.".php");
				$c=$c.$table.".php File Deleted<br />";
			}
			if (file_exists("../../index/add".ucfirst($table).".php"))
			{	//sleep(1);
				unlink("../../index/add".ucfirst($table).".php");
				$c=$c."add".ucfirst($table).".php File Deleted<br />";
			}
			if (file_exists("../../index/edit".ucfirst($table).".php"))
			{	//sleep(1);
				unlink("../../index/edit".ucfirst($table).".php");
				$c=$c."edit".ucfirst($table).".php File Deleted<br />";
			}
		if($c=='')
		{
			$c='No Changes';
		}
		return $c;
	}

	function clearAdmin()//Delete all files in admin index folder
	{
		$files = glob('../../index/*'); // get all file names
		foreach($files as $file) // iterate files
		{
		  if(is_file($file))
			unlink($file); // delete file
		}
		echo ('Index Cleared<br/>--------------------------------<br/>');
	}

	function deleteAllPrivilegesAuto()//automaticaly add all the pages to the privilege table and also asign them to the super control_p_admin with id=1 and make the basic menu for all pages
	{
		$c=mysqli_query($this->cnx,'TRUNCATE TABLE `control_p_privilege_to_group`');
		$b=mysqli_query($this->cnx,'TRUNCATE TABLE `control_p_page_levels`');
		$a=mysqli_query($this->cnx,'TRUNCATE TABLE `control_p_privilege`');

		if($a && $b && $c)
		{
		echo('Privileges Deleted<br/>--------------------------------<br/>');
		}
	}
	function addAllPrivilegesAuto()//automaticaly add all the pages to the privilege table and also asign them to the super control_p_admin with id=1 and make the basic menu for all pages
	{
		$i=0;
		if ($handle = opendir('../../index/'))
		{
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..")
				{
					$Ffile=$file;
					$itemPage=true;
					if(stripos($file,'add')!==false && $file!='address.php')
					{
						$Ffile=strtolower(substr($file,3));
						$itemPage=false;
					}
					if(stripos($file,'edit')!==false)
					{
						$Ffile=strtolower(substr($file,4));
						$itemPage=false;
					}
					$sql='INSERT INTO control_p_privilege (name) VALUES ("'.substr($file,0,-4).'")';
					if($itemPage)
					{
						$sql1='INSERT INTO control_p_privilege (name) VALUES ("delete_'.substr($file,0,-4).'")';
						if(!mysqli_query($this->cnx,$sql1))
						{
							echo 'delete_'.substr($file,0,-4).' Privilege Exists<br/>';
						}
					}
					//$sql3='INSERT INTO control_p_page_levels (page,menu_display_names,menu_pages) VALUES ("'.substr($file,0,-4).'","'.$this->toView(substr($Ffile,0,-4)).'","'.substr($Ffile,0,-4).'")';
					$sql3='INSERT INTO control_p_page_levels (page,menu_display_names,menu_pages) VALUES ("'.substr($file,0,-4).'","","")';
					if(mysqli_query($this->cnx,$sql))
					{
							if(mysqli_query($this->cnx,$sql3))
							{
								$i++;
							}

					}
					else
					{
						echo substr($file,0,-4).' Privilege Exists<br/>';
					}

				}
			}
			closedir($handle);
			echo($i.' Privilege(s) Added<br/>--------------------------------<br/>');
		}
	}
	function getSeqArray($page)
	{
		if(!$this->checkTableIfExist('control_p_seq'))
		{
			return false;
		}
		$sql='SELECT * FROM `control_p_seq` WHERE `page_name`="'.$page.'"';
		$result=mysqli_query($this->cnx,$sql);
		if(mysqli_num_rows($result)==1)
		{
			$row=mysqli_fetch_assoc($result);
			if($row['seq_array']!='')
			{
				eval($row['seq_array']);
				if(isset($seq))
				{
					if(is_array($seq))
					{
						return $seq;
					}
				}
			}
		}
		return false;
	}
	function checkTableIfExist($table)//check table if exist in db
	{
		$sql='SELECT * FROM `'.$table.'`';
		if(mysqli_query($this->cnx,$sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function checkColumnIfExist($column,$table)//check table if exist in db
	{
		$sql='SELECT '.$column.' FROM `'.$table.'`';
		if(mysqli_query($this->cnx,$sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function capitalize($data)
	{
		$item=ucfirst(strtolower($data));
		return $item;
	}
	function composeSelectBox($data)
	{

		if($pos=strrpos($data,'_id'))
		{
			$table=substr($data, 0, $pos);
			if($this->hasLanguage($table))
			{
				$filter1['filterBy']='language_id';
				$filter1['keyword']=1;
				$filter1['exact']=true;
				$filterData['multiFilterBy'][]=$filter1;
				$item=$this->getAllGeneralItemsWithJoins($filterData,$table);
				if(!$item)
				{
					$item=true;
				}
			}
			else
			{
				$item=$this->getAllGeneralItemsWithJoins('',$table);
				if(!$item)
				{
					$item=true;
				}
			}
			return $item;
		}
		else
		{
			return false;
		}
	}
	function composeSelectBoxWithFilter($data,$filterData)
	{

		if($pos=strrpos($data,'_id'))
		{
			$table=substr($data, 0, $pos);
			$item=$this->getAllGeneralItemsWithJoins($filterData,$table);
			if(!$item)
			{
				$item=true;
			}
			return $item;
		}
		else
		{
			return false;
		}
	}
	function showPercentage($data)
	{
		$view='<span style="width=50px;float:left!important" >'.$data['percent'].'% </span><span style="width=200px;height:15px;border:1px solid grey;float:right!important" >';
		for($i=0;$i<$data['percent'];$i++)
		{
			$view=$view.'<img height="7px" width="2px" src="..\public\design-images\percentage.JPG" >';
		}
		for($j=0;$j<(100-$data['percent']);$j++)
		{
			$view=$view.'<img height="7px" width="2px" src="..\public\design-images\percentageLeft.JPG" >';
		}
		$view=$view.'</span>';
		return $view;
	}
/*	function showPercentage($data)
	{
		$view=$data['percent'].'%';
		return $view;
	}*/
	function showValue2($data,$keyId,$table) //show the name() instead of showing the id in the vew page, this function tests the value if it is an id or name :give the function $data['id'],$data['lang'] .. to get acurate language result
	{
	//$this->show($data);
		setlocale(LC_CTYPE,'arabic');
		if($pos=strrpos($keyId,'_id'))
		{
			$table=substr($keyId, 0, $pos);
			if(is_array($data))
			{
				$item=$this->getGeneralItemByIdAndLangId($data['id'],$data['lang'],$table);
				if(isset($item[$data['id']][$this->getTableDisplayName($table,'')]))
				{
					return $this->capitalize($item[$data['id']][$this->getTableDisplayName($table,'')]);
				}
				else
				{
					return '';
				}
			}
			else
			{
				$item=$this->getGeneralItemById($data,$table);
				if(isset($item[$data][$this->getTableDisplayName($table,'')]))
				{
					if($this->getTableDisplayName($table,'')=='first_name')
					{
						$all=$this->capitalize($item[$data]['id']);
						$all.=': ';
						$all.=$this->capitalize($item[$data]['first_name']);
						$all.=' ';
						$all.=$this->capitalize($item[$data]['last_name']);
						return $all;
					}
					else
					{
						return $this->capitalize($item[$data][$this->getTableDisplayName($table,'')]);
					}
				}
				else
				{
					return '';
				}
			}
		}
		else
		{
			if(is_array($data))
			{
				return $data['id'];
			}
			else
			{


				setlocale(LC_CTYPE,'arabic');
				$col_prop=$this->getColumnProperties(Array('column_name'=>$keyId),$table);
				//$this->show($col_prop);
				switch ($col_prop['Type'])
				{
					case 'Text':
					return ucfirst($data);
					break;
					case 'tinyint(4)':
						switch ($keyId)
						{
							case 'sex':
							{
								if($data==0)
								{
									return 'Male';
								}
								else
								{
									return 'Female';
								}
							}
							default :
								if($data==0)
								{
									return 'No';
								}
								else
								{
									return 'Yes';
								}
						}
					break;
					default :
						return $this->capitalize($data);
				}
			}
		}
	}

	function showValue($data,$keyId)//show the name() instead of showing the id in the vew page, this function tests the value if it is an id or name :give the function $data['id'],$data['lang'] .. to get acurate language result
	{
		//$this->show($data);
		setlocale(LC_CTYPE,'arabic');
		if($pos=strrpos($keyId,'_id'))
		{
			$table=substr($keyId, 0, $pos);
			if(is_array($data))
			{
				$item=$this->getGeneralItemByIdAndLangId($data['id'],$data['lang'],$table);
				if(isset($item[$data['id']][$this->getTableDisplayName($table,'')]))
				{
					return $this->capitalize($item[$data['id']][$this->getTableDisplayName($table,'')]);
				}
				else
				{
					return '';
				}
			}
			else
			{
				$item=$this->getGeneralItemById($data,$table);
				if(isset($item[$data][$this->getTableDisplayName($table,'')]))
				{
					if($this->getTableDisplayName($table,'')=='first_name')
					{
						$all=$this->capitalize($item[$data]['id']);
						$all.=': ';
						$all.=$this->capitalize($item[$data]['first_name']);
						$all.=' ';
						$all.=$this->capitalize($item[$data]['last_name']);
						return $all;
					}
					else
					{
						return $this->capitalize($item[$data][$this->getTableDisplayName($table,'')]);
					}
				}
				else
				{
					return '';
				}
			}
		}
		else
		{
			if(is_array($data))
			{
				return $data['id'];
			}
			else
			{
				if($keyId=='description')
				{
					return ucfirst($data);
				}
				return $this->capitalize($data);
			}
		}
	}
	function getGeneralParentId($data,$table)
	{
		if(isset($data['column']) && isset($data['id']) && isset($table))
		{
			$sql='SELECT '.$data['column'].' FROM `'.$table.'` WHERE id="'.$data['id'].'"';
			if($result=mysqli_query($this->cnx,$sql))
			{
				$row=mysqli_fetch_assoc($result);
				$item=$row[$data['column']];
				return $item;
			}
			else
			{
				return 'Empty';
			}
		}
		else
		{
			return 'Empty';
		}
	}

	function isForien($data)//this function check if the column name $data is a forien key or not
	{
		if($pos=strrpos($data,'_id'))//if forien it returns ForienTableName_ForienTableDisplayName to be used on view page or search
		{
			$table=substr($data, 0, $pos);
			$item['show']=$table.'_'.$this->getTableDisplayName($table,'');//this output is used in the view composition
			if($this->checkTableIfExist($table.'_language'))
			{
				$item['sql']='`'.$table.'_language'.'`.`'.$this->getTableDisplayName($table,'').'`';//this output is used in sql composition
			}
			else
			{
				$item['sql']='`'.$table.'`.`'.$this->getTableDisplayName($table,'').'`';//this output is used in sql composition
			}
			$item['table']=$table;
			return $item;
		}
		else// if it is not a forien key
		{
			return false;
		}
	}
	function isOptional($id,$table)//return whether the column is optional or not
	{
		$cols=$this->getGeneralColums($table);
		if($cols['keys'][$id]['Null']=='NO')
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	/*
	Function to get the display name(s) of the table
	- $table : the table to get the data from
	- $data['multiple'] : boolean to ask for muliple display names if exist or only one of them
	*/
	function getTableDisplayName($table,$data)// returns the column that represents the table in the view from indexes in table ..  if not found .. check field "name" if exist and return "name" .. if also not exist .. return primary key
	{
		if(!is_array($data))
		{
			$data=array();
			$data['multiple']=false;
		}
		else
		{
			if(!isset($data['multiple']))
			{
				$data['multiple']=false;
			}
		}

		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		if($this->checkTableIfExist($table.'_language'))
		{
			$sql='SHOW KEYS FROM `'.$table.'_language'.'` WHERE Key_name="display_name"';
		}
		else
		{
			$sql='SHOW KEYS FROM `'.$table.'` WHERE Key_name="display_name"';
		}
		$result = mysqli_query($this->cnx,$sql);
		$row=array();
		while($row0 = mysqli_fetch_assoc($result))
		{
			$row[] = $row0;
		}
		if(count($row))
		{
			if(count($row)>1 && $data['multiple'])
			{
				foreach($row as $Cid=>$Cvalue)
				{
					$return[]=$Cvalue['Column_name'];
				}
				return $return;
			}
			else
			{
				return $row[0]['Column_name'];
			}
		}
		else
		{
			if($this->checkTableIfExist($table.'_language'))
			{
				$sql='SHOW COLUMNS FROM `'.$table.'_language` WHERE Field="name"';
			}
			else
			{
				$sql='SHOW COLUMNS FROM `'.$table.'` WHERE Field="name"';
			}

			$result = mysqli_query($this->cnx,$sql);
			$row = mysqli_fetch_assoc($result);//  if ($table=='item_language' ) { $this->show($row); die(mysqli_error($this->cnx)); }
			if($row)
			{
				return 'name';
			}
			else
			{
				return $PRI;
			}
		}
	}
	/* missing language case */
	function GetFullDisplayName($id,$table)
	{
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		$item=$this->GetGeneralItemById($id,$table);
		$item=$item[$id];
		$display_name_value='';
		$display_name0=array();
		$data['multiple']=true;
		$display_name=$this->getTableDisplayName($table,$data);
		//var_dump($item);
		if(is_array($display_name))
		{
			foreach($display_name as $idDN=>$dataDN)
			{
				$display_name0[]=$item[$dataDN];
			}
			$display_name_value=implode(' ',$display_name0);
		}
		else
		{
			$display_name_value=$item[$display_name];
		}
		return $display_name_value;
	}

	/*
		use the function getTableDisplayName to get the colums of display_name then this function take $data['display_name'] and $data['item'] as the item data to return the name of item as string
		Example:
		input
			$data['item']=Array('id'=>'2','first_name'=>'adham','last_name'=>'ghannam');
			$data['display_name']=Array(0=>'first_name',1=>'last_name');
		output
			adham ghannam
	*/
	function composeFullDisplayName($data)
	{
		$display_name_value='';
		$display_name0=array();
		$item=$data['item'];
		$display_name=$data['display_name'];
		if(is_array($display_name))
		{
			foreach($display_name as $idDN=>$dataDN)
			{
				$display_name0[]=$item[$dataDN];
			}
			$display_name_value=implode(' ',$display_name0);
		}
		else
		{
			$display_name_value=$item[$display_name];
		}
		return $display_name_value;
	}

	function getColumnIndex($column,$table)// returns the column that represents the table in the view from indexes in table ..  if not found .. check field "name" if exist and return "name" .. if also not exist .. return primary key
	{
		$sql='SHOW KEYS FROM `'.$table.'` WHERE Column_name="'.$column.'"';

		$result = mysqli_query($this->cnx,$sql);
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_assoc($result);
			return $row['Key_name'];
		}
		else
		{
			return $column;
		}
	}
	/*
	output:
		Tis Function returns the Properties (field,type,null,.. ) as an array
		or an empty array if column not found if this table

	input:
		$table :name of the table this column in
		$data: array
			'column_name' : thename of the column of the table
	*/
	function getColumnProperties($data,$table)
	{
		$sql='SHOW COLUMNS FROM `'.$table.'` WHERE Field="'.$data['column_name'].'"';

		$result = mysqli_query($this->cnx,$sql);
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_assoc($result);
			return $row;
		}
		else
		{
			return Array();
		}
	}

	function getIdByName($name,$table)
	{
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		$sql='SELECT '.$PRI.' FROM `'.$table.'` WHERE name="'.$name.'"';
		$result = mysqli_query($this->cnx,$sql);
		$row = mysqli_fetch_assoc($result);
		if($row)
		{
			return $row[$PRI];
		}
		else
		{
			return false;
		}
	}
	function isAllowed($data)//check if the inputs control_p_group and page name exists in the table control_p_privilege_to_group
	{
		if($data['control_p_group_id']==0)
		{
			return true;
		}
		$table='control_p_privilege_to_group';
		$data2['control_p_privilege_id']=$this->getIdByName($data['control_p_privilege'],'control_p_privilege');
		$data2['control_p_group_id']=$data['control_p_group_id'];
		$res=$this->checkGeneralItemIfExist($id='0',$data2,$table);
		if(!empty($res))
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	function isAllowed_2($x,$y)//(same as isAllowed function different input type )check if the inputs control_p_group and page name exists in the table control_p_privilege_to_group
	{
		if($x==0)
		{
			return true;
		}
		$y2=explode('?', $y);
		$y=$y2[0];
		$y=str_replace('.php', '', $y);
		
		$data['control_p_group_id']=$x;
		$data['control_p_privilege']=$y;
		$table='control_p_privilege_to_group';
		$data2['control_p_privilege_id']=$this->getIdByName($data['control_p_privilege'],'control_p_privilege');
		$data2['control_p_group_id']=$data['control_p_group_id'];
		$res=$this->checkGeneralItemIfExist($id='0',$data2,$table);
		if(!empty($res))
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	function getViewColumns($table)//returns the number of columns displayed when viewing the table
	{
		$sql='SELECT columns FROM control_p_table_view_columns WHERE table_name="'.$table.'" LIMIT 1';
		$result = mysqli_query($this->cnx,$sql);
		if($row = mysqli_fetch_assoc($result))
		{
			return $row['columns'];
		}
		else//in case the number of cols not defined use 5 as default
		{
			return '5';
		}
	}
	function toView($data)//remove "_" and capitalize first letter of th input word and remove "id" if its forien
	{//var_dump($data);

		if(strpos($data,'control_p_')!==false)
		{
			$data=str_replace('control_p_','',$data);
		}
		$tempItem=explode("_",$data);
		for($i=0 ;$i<count($tempItem) ;$i++)
		{
			if($tempItem[$i]!='id')
			{
				if(substr($tempItem[$i], 0, 4)=='edit')
				{
					$tempItem[$i]='edit '.substr($tempItem[$i], 4);
				}
				if(substr($tempItem[$i], 0, 3)=='add' && $tempItem[$i]!='address' && $tempItem[$i]!='additional')
				{
					$tempItem[$i]='add '.substr($tempItem[$i], 3);
				}
				$tempItem[$i]=ucfirst($tempItem[$i]);
			}
			else
			{
				$tempItem[$i]='';
			}
		}
		$item=implode(" ",$tempItem);
		return $item;
	}
	function getGeneralColums($table)
	{
		$sql ='SHOW COLUMNS FROM `'.$table.'`';
		$result = mysqli_query($this->cnx,$sql);
		while($row = mysqli_fetch_assoc($result))
		{
			$item['keys'][$row['Field']] = $row; // fill up the array
		}//var_dump($row);die();
		foreach($item['keys'] as $keyId=>$keyValue)
		{
			if($keyId!='password')
			$item['filterKeys'][$keyId]=$keyValue;
		}
		foreach($item['keys'] as $keyId2=>$keyValue2)
		{
			if($keyValue2['Key']=='PRI')
			{
				$item['primaryKeys'][$keyId2]=$keyValue2['Field'];
			}
		}
		$item['primaryKeys']=array_values($item['primaryKeys']);
		//var_dump($item['primaryKeys']);

		return $item; // array must return something

	}
	function getGeneralIndexes($table)
	{
		$item=array();
		$sql ='SHOW INDEX FROM `'.$table.'`';
		$result = mysqli_query($this->cnx,$sql);
		while($row = mysqli_fetch_assoc($result))
		{
			$item[]= $row; // fill up the array
		}//var_dump($row);die();
		return $item; // array must return something

	}
	function getGeneralUniqueIndexes($table)
	{
		$item=array();
		$sql ='SHOW INDEX FROM `'.$table.'` WHERE `Non_unique`="0" ';
		$result = mysqli_query($this->cnx,$sql);
		while($row = mysqli_fetch_assoc($result))
		{
			$item[$row["Key_name"]][]=$row ;
		}//var_dump($row);die();
		return $item; // array must return something

	}
	function getMenuList($data)//get the roots that this this page belongs to ... in an array with each "page" and its "display_name"
	{
		$menu=array();
		$filterData['filterBy']='page';
		$filterData['keyword']=$data['page'];
		$column=$this->getGeneralColums('control_p_page_levels');
		$filterData['filterKeys']=$column['filterKeys'];
		if($row=$this->getAllGeneralExactItemsWithJoins($filterData,'control_p_page_levels'))
		{
			foreach($row as $id=>$value)
			{
				$menu['menu_display_names']=explode("-", $value['menu_display_names']);
				$menu['menu_pages']=explode("-", $value['menu_pages']);
			}
		}
		else
		{
			$menu['menu_pages']=explode("-", $data['page']);
			$menuTemp=explode("-", $data['page']);
			foreach($menuTemp as $id2=>$value2 )
			{
				$menu['menu_display_names'][$id2]=$this->toView($value2);
			}
		}

		return $menu;
	}
	function deleteGeneralItems($data,$table)
	{ //var_dump($data);die();
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		$imageExist=array();
		$item_images=false;
		$images=false;
		foreach($cols['keys'] as $key1=>$value1)// check if the table has an image in order to delete it too
		{
			if($key1=='image_id')//direct id link to the image
			{
				$imageExist[]=$key1;
			}
		}
		if($this->checkTableIfExist('image_to_'.$table))//table joining the image with table (multiple images)
		{
			$imageExist[]='table';
		}
		if(!is_array($data))
		{
			return false;
		}
		$fillSQL='';
		foreach ($data as $key => $value)
		{
			if($value!='0')
			{
				if(array_search('image_id', $imageExist)!==false)//get the name of the images before the item is deleted
				{
					$item=$this->getGeneralItemById($value,$table);
					$idImage=$item[$value]['image_id'];
					$images[]=$idImage;
					$imgItem=$this->getGeneralItemById($idImage,'image');
					$imagesFiles[]=$imgItem[$idImage]['name'];
				}
				if(array_search('table', $imageExist)!==false)//get the name of the images before the item is deleted
				{
					$data2['column']=$table;
					$data2['value']=$value;
					$itemImages=$this->getGeneralIdByForeignId($data2,'image_to_'.$table);// get the ids of the images related to this item
					foreach($itemImages as $key4=>$value4)
					{
						$item_images[]=$value4;
					}
				}
				if($fillSQL=='')
				{
					$fillSQL=$fillSQL.'WHERE '.$PRI.'="'.$value.'" ';
				}
				else
				{
					$fillSQL=$fillSQL.'OR '.$PRI.'="'.$value.'" ';
				}
			}
		}
		if($fillSQL=='')
		{
			return true;
		}
		$sql = 'DELETE FROM `'.$table.'` '.$fillSQL;
		//die($sql);
		if ($result = mysqli_query($this->cnx,$sql))
		{
			if($images)//delete the images from database and from images folder
			{
				$this->deleteGeneralItems($images,'image');
				foreach($imagesFiles as $key3=>$value3)
				{
					$this->deleteImageFile($value3);
				}
			}
			if($item_images)//get the name of the images before the item is deleted
			{
				$this->deleteGeneralItems($item_images,'image_to_'.$table);
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	function deleteOrfanImages($data,$table)
	{ /*vardump($data); die();*/
		if(count($data))
		{
			foreach($data as $ind=>$id)
			{
				$imgItem=$this->getGeneralItemById($id,'image');
				$this->deleteImageFile($imgItem[$id]['name']);
			}
			$this->deleteGeneralItems($data,'image');
		}
		else
		{ /*vardump($data); die();*/ }
	}
	function getGeneralIdByForeignId($data,$table)
	{
		$item=array();
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		$sql='SELECT `'.$PRI.'` FROM `'.$table.'` WHERE `'.$data["column"].'_id`="'.$data['value'].'" ';

		$result=mysqli_query($this->cnx,$sql);
		while($row = mysqli_fetch_assoc($result))
		{
			$item[] = $row[$PRI];
		}
		return $item;

	}
	function deleteImageFile($name)
	{
		if($name!='default.jpg')
		{
			$dir='../../../public/images/'.$name;
			$thumbDir='../../../public/images/thumbs/'.$name;
			if(unlink($dir) && unlink($thumbDir))
			{
				return TRUE;
			}
		}
	}
	function checkIdIfExist($id,$table)
	{
		$item = array();
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];//this is the primary key default id
		$sql = 'SELECT '.$PRI.' FROM `'.$table.'` WHERE '.$PRI.'="'.$id.'"'; // change by function

		$result = mysqli_query($this->cnx,$sql); // use it to fetch
		while($row = mysqli_fetch_assoc($result))
		{
			$item[$row[$PRI]] = $row; // fill up the array
		}
		return $item; // array must return something
	}
	function checkIfExist($value,$column,$table)
	{
		$item = array();
		if(!$this->checkTableIfExist($table))
		{
			return $item;
		}
		$sql = 'SELECT `'.$column.'` FROM `'.$table.'` WHERE `'.$column.'`="'.$value.'"'; // change by function

		$result = mysqli_query($this->cnx,$sql); // use it to fetch
		while($row = mysqli_fetch_assoc($result))
		{
			$item[$row[$column]] = $row; // fill up the array
		}
		return $item; // array must return something
	}

	function getAllGeneralItemsWith2KeysWithJoins($filterData,$table)//return the same as getAllGeneralItemsWithJoins if the primary keys =1 and  if the primary keys =2
	{
		$filterData0=$filterData;
		if(!is_array($filterData))//if there is $filterData given is not array
		{
			$filterData= Array();
		}
			$column=$this->getGeneralColums($table);
			$filterData['filterKeys']=$column['filterKeys'];


//***************************************************************************************
		if(isset($filterData['limit']))//if there is paging or limit on output
		{
			$limit=$filterData["limit"];
		}
		else//empty stinr will be added to the end of sql
		{
			$limit='';
		}
//***************************************************************************************
//******************************** Language Filter *****************************************
		if(isset($filterData['language_id']))//if there is paging or limit on output
		{
			$langID=$filterData["language_id"];
		}
		else//empty stinr will be added to the end of sql
		{
			$langID=$this->lang;
		}
//********************************* End Language Filter **************************************
		if($this->checkTableIfExist($table.'_language'))
		{
			$tableLang=$table.'_language';
			$dispN=$this->getTableDisplayName($tableLang,'');
		}
		else
		{
			$tableLang=false;
		}
		if(isset($filterData['orderBy']))//if there is a filter by set
		{
			if($filterData['orderBy']!='')
			{
				$nowTable=$table;
				if(!$this->checkColumnIfExist($filterData['orderBy'],$table))
				{
					$nowTable=$tableLang;
				}
				$orderBy='ORDER By `'.$nowTable.'`.`'.$filterData["orderBy"].'` '.$filterData["order"];
			}
			else
			{
				$orderBy='';
			}
		}
		else//case wont be executed cuz by default the variable id defined as null is the page
		{
			$orderBy='';
		}

//***************************************************************************************
		if( isset($filterData['multiFilterBy']))//if there is a filter by set
		{
			$filters=count($filterData['multiFilterBy']);
			$filterBy='WHERE (';
			$x=1;
			foreach($filterData['multiFilterBy'] as $ind=>$filterDataChild)
			{
				if($filterDataChild['filterBy']!='all')
				{
					$like='%';
					$oparator=' LIKE ';
					$checkId=false;//if we want LIKE search && we didnt specifiy the search if on id or name ... default it will search the name
					$between=false;
					if(isset($filterDataChild['exact']))
					{
						if($filterDataChild['exact']==true)
						{
							$like='';
							$oparator=' = ';
							$checkId=true;//if we want exact search && we didnt specifiy the search if on id or name ... default it will search the id
						}
					}
					if(isset($filterDataChild['searchId']))
					{
						if($filterDataChild['searchId']==true)
						{
							$checkId=true;
						}
					}
					if(!empty($filterDataChild['between']))
					{
						if(is_array($filterDataChild['keyword']))
						{
							$between=true;
							$numeric=false;
							$like='';
							$oparator=' BETWEEN ';
							$checkId=true;//if we want exact search && we didnt specifiy the search if on id or name ... default it will search the id
							$from=$filterDataChild['keyword'][0];
							$to=$filterDataChild['keyword'][1];
							if(is_numeric($from) && is_numeric($to))
							{
								$numeric=true;
							}
						}
					}
					if(($res=$this->isForien($filterDataChild['filterBy'])) && !$checkId)//if the key is forien (and we r using like) then the key name will be changed to new name according to the output of the table
					{
						$filterBy=$filterBy.' '.$res['sql'].$oparator.'"'.$like.$filterDataChild["keyword"].$like.'" ';
						//if($x!=$filters){ $filterBy=$filterBy.' AND '; }
					}
					else
					{
						$nowTable=$table;
						if(!$this->checkColumnIfExist($filterDataChild["filterBy"],$table))
						{
							$nowTable=$tableLang;
						}
						if(!$between)
						{
							if($filterDataChild['keyword'] == NULL)
							{
								$filterBy=$filterBy.' `'.$nowTable.'`.`'.$filterDataChild["filterBy"].'` IS NULL ';
							}else
							{
								$filterBy=$filterBy.' `'.$nowTable.'`.`'.$filterDataChild["filterBy"].'`'.$oparator.'"'.$like.$filterDataChild["keyword"].$like.'" ';
							//if($x!=$filters){ $filterBy=$filterBy.' AND '; }
							}
						}
						else
						{
							if($numeric)
							{
								$filterBy=$filterBy.' ( `'.$nowTable.'`.`'.$filterDataChild["filterBy"].'` '.$oparator.' '.$from.' AND '.$to.' ) ';
							}
							else
							{
								$filterBy=$filterBy.' ( `'.$nowTable.'`.`'.$filterDataChild["filterBy"].'` '.$oparator.' "'.$from.'" AND "'.$to.'" ) ';
							}
						}
					}
				}
				else// in case one of the filters = all
				{
					$like='%';
					$oparator=' LIKE ';
					$checkId=false;//if we want LIKE search && we didnt specifiy the search if on id or name ... default it will search the name
					if(isset($filterDataChild['exact']))
					{
						if($filterDataChild['exact']==true)//if the search want the exact keyword (not like)
						{
							$like='';
							$oparator=' = ';
							$checkId=true;//if we want exact search && we didnt specifiy the search if on id or name ... default it will search the id
						}
					}
					if(isset($filterDataChild['searchId']))
					{
						if($filterDataChild['searchId']==true)//if the search want the exact keyword (not like)
						{
							$checkId=true;
						}
					}
					$i=1;
					$FDwithLang=$filterData['filterKeys'];
					if($tableLang)
						{
							$FDwithLang[$dispN]='';
						}
					foreach($FDwithLang as $keyName=>$keyValue)//filterKeys are the names of the colomns of the table in db but without some elements like (password )
					{
						if($i==1){$filterBy=$filterBy.' (';}
						if($i<count($FDwithLang))
						{
							if(($res=$this->isForien($keyName)) && !$checkId)//if the key is forien  (and we r using like) then the key name will be changed to new name according to the output of the table
							{
								$filterBy=$filterBy.' '.$res['sql'].$oparator.'"'.$like.$filterDataChild['keyword'].$like.'" OR ';
							}
							else
							{
								$nowTable=$table;
								if(!$this->checkColumnIfExist($keyName,$table))
								{
									$nowTable=$tableLang;
								}
								$filterBy=$filterBy.' `'.$nowTable.'`.`'.$keyName.'`'.$oparator.'"'.$like.$filterDataChild['keyword'].$like.'" OR ';
							}
						}
						else
						{
							if(($res=$this->isForien($keyName)) && !$checkId)//if the key is forien then the key name will be changed to new name according to the output of the table
							{
								$filterBy=$filterBy.' '.$res['sql'].$oparator.'"'.$like.$filterDataChild['keyword'].$like.'")';
							}
							else
							{
								$nowTable=$table;
								if(!$this->checkColumnIfExist($keyName,$table))
								{
									$nowTable=$tableLang;
								}
								$filterBy=$filterBy.' `'.$nowTable.'`.`'.$keyName.'`'.$oparator.'"'.$like.$filterDataChild['keyword'].$like.'")';
							}
						}
						$i++;
					}
				}
				if($x==$filters){ $filterBy=$filterBy.' ) '; }else{ $filterBy=$filterBy.' AND '; }
				$x++;
			}
		}
		else//case wont be executed cuz by default the variable id defined as null is the page
		{
			$filterBy='';
		}
//***************************************************************************************

//***************************************************************************************
		$joinsSelect='';
		$joinsInner='';
		//$joinsFrom='';
		foreach($filterData['filterKeys'] as $keyName=>$keyValue)//filterKeys are the names of the colomns of the table in db but without some elements like (password )
				{
					if($pos=strrpos($keyName,'_id'))
					{
						$forienTable=substr($keyName, 0, $pos);
						$forienTableO=$forienTable;
						if($forienTable!=$table)//in case the table hase parent_id and looks like TableName_id no joins then
						{
							$langJoin='';
							if( $this->checkTableIfExist($forienTableO.'_language'))//if this table has an extended language table it must innerjoin it and add  where lang to 1
							{
								$forienTable=$forienTableO.'_language';

								$colsO=$this->getGeneralColums($forienTableO);
								$PRIO=$colsO['primaryKeys'];
								$PRIO=$PRIO[0];

								if($table!=$forienTable)
								{
									$joinsInner=' INNER JOIN `'.$forienTable.'` ON (`'.$forienTable.'`.`'.$forienTableO.'_id` = `'.$forienTableO.'`.`'.$PRIO.'`)'.' AND ( `'.$forienTable.'`.language_id="'.$langID.'" ) '.$joinsInner;
								}
							}
							elseif($this->hasLanguage($forienTable))
							{
								$langJoin=' AND ( `'.$forienTable.'`.language_id="'.$langID.'" ) ';
							}
							$cols=$this->getGeneralColums($forienTable);
							$PRI=$cols['primaryKeys'];
							$PRI=$PRI[0];
							if(!isset($PRIO)){ $PRIO=$PRI; }
							$forienColumn=$this->getTableDisplayName($forienTable,'');
							//$joinsFrom=$joinsFrom.', '.$forienTable.' ';
							$joinsSelect=$joinsSelect.',`'.$forienTable.'`.`'.$forienColumn.'` AS `'.$forienTableO.'_'.$forienColumn.'` ';
							$joinsInner=' LEFT JOIN `'.$forienTableO.'` ON (`'.$table.'`.`'.$forienTableO.'_id` = `'.$forienTableO.'`.`'.$PRIO.'`)'.$langJoin.$joinsInner;
						}
					}
				}
//***************************************************************************************
		$item = array(); // use it to avoid return false from database

		$cols=$this->getGeneralColums($table);
		$PRIS=$cols['primaryKeys'];
		$PRI=$PRIS[0];
		$allPRIS=count($cols['primaryKeys']);

		if($tableLang)
		{
			$joinsInner.=' LEFT JOIN `'.$tableLang.'`
						ON (`'.$table.'`.`'.$PRI.'` = `'.$tableLang.'`.'.$table.'_id)
						AND(`'.$tableLang.'`.language_id="'.$langID.'") ';

			$joinsSelect.=', `'.$tableLang.'`.*';
		}
		$sql = 'SELECT `'.$table.'`.* '.$joinsSelect.'  FROM `'.$table.'` '.$joinsInner.' '.$filterBy.' '.$orderBy.' '.$limit; // change by function
		//if($table=='item') die($sql);
		$result = mysqli_query($this->cnx,$sql);

		//if(mysqli_error($this->cnx)) $this->show(mysqli_error($this->cnx));

		while($row = mysqli_fetch_assoc($result))
		{
			if($allPRIS==1)
			{
				$item[$row[$PRI]] = $row; // fill up the array
			}
			if($allPRIS==2)
			{
				$item[$row[$PRI]][$row[$PRIS[1]]] = $row;
			}
		}
		//************* if not found in other language use default ********


		if($tableLang)
		{
			if(!count($item) && $langID!='1')
			{
				$filterData0['language_id']='1';
				return $this->getAllGeneralItemsWith2KeysWithJoins($filterData0,$table);
			}
		}
		//************* if not found in other language use default ********
		/*
		if($this->checkTableIfExist($table.'_language'))
		{
			foreach($item as $ind1=>$item1)
			{
				$itmLang=$this->getGeneralItemByIdAndLangId($item[$ind1][$PRI],'1',$table.'_language');
				$item[$ind1]=array_merge($itmLang[$ind1],$item[$ind1]);
			}
		}*/
		//if($table=='property') $this->show($sql);
		return $item; // array must return something
	}
	function getAllGeneralItemsWithJoins($filterData,$table)
	{
		$item=array();
		$cols=$this->getGeneralColums($table);
		$PRIS=$cols['primaryKeys'];
		$PRI=$PRIS[0];
		$allPRIS=count($cols['primaryKeys']);
		if($allPRIS==2)
		{
			$allItem=$this->getAllGeneralItemsWith2KeysWithJoins($filterData,$table);
			foreach($allItem as $id=>$value)
			{
				foreach($value as $id1=>$value1)
				{
					$item[]=$value1;
				}
			}
		}
		if($allPRIS==1)
		{
			$item=$this->getAllGeneralItemsWith2KeysWithJoins($filterData,$table);
		}
			//die(mysqli_error($this->cnx));
		return $item; // array must return something
	}

	function getAllGeneralExactItemsWithJoins($filterData,$table)//this function filter result by exactly the value given (use = not like) and it filter forien table by ids too ex. project_id=2
	{
		if(!is_array($filterData))//if there is $filterData given is not array
		{
			$filterData= Array();
			$filterData['filterBy']='';
			$column=$this->getGeneralColums($table);
			$filterData['filterKeys']=$column['filterKeys'];
		}
		else//in case there is filter and keyword and no forien keys send
		{
			if(!isset($filterData['filterKeys']))
			{
				$column=$this->getGeneralColums($table);
				$filterData['filterKeys']=$column['filterKeys'];
			}
		}
//***************************************************************************************
		if(isset($filterData['limit']))//if there is paging or limit on output
		{
			$limit=$filterData["limit"];
		}
		else//empty stinr will be added to the end of sql
		{
			$limit='';
		}
//***************************************************************************************
		if(isset($filterData['orderBy']))//if there is a filter by set
		{
			if($filterData['orderBy']!='')
			{
				$orderBy='ORDER By '.$filterData["orderBy"].' '.$filterData["order"];
			}
			else
			{
				$orderBy='';
			}
		}
		else//case wont be executed cuz by default the variable id defined as null is the page
		{
			$orderBy='';
		}
//***************************************************************************************
		if($filterData['filterBy']!='')//if there is a filter by set
		{
			if(!isset($filterData['multiFilterBy']))// if the mutliFilterBy data is not set (just 1 filter)
			{
				if($filterData["keyword"]!="")
				{
					if($filterData['filterBy']!='all')//if filter is not set to default
					{
						if($res=$this->isForien($filterData['filterBy']))//if the key is forien then the key name will be changed to new name according to the output of the table
						{
							$filterBy='WHERE ('.$table.'.'.$filterData['filterBy'].' = "'.$filterData["keyword"].'" )';
						}
						else
						{
							$filterBy='WHERE ('.$table.'.'.$filterData["filterBy"].' = "'.$filterData["keyword"].'" )';
						}
					}
					else
					{
						$i=1;
						foreach($filterData['filterKeys'] as $keyName=>$keyValue)//filterKeys are the names of the colomns of the table in db but without some elements like (password )
						{
							if($i==1)
							{
								$filterBy='WHERE ( ';
							}
							if($i<count($filterData['filterKeys']))
							{
								if($res=$this->isForien($keyName))//if the key is forien then the key name will be changed to new name according to the output of the table
								{
									$filterBy=$filterBy.' '.$table.'.'.$keyName.' = "'.$filterData["keyword"].'" OR ';
								}
								else
								{
									$filterBy=$filterBy.' '.$table.'.'.$keyName.' = "'.$filterData["keyword"].'" OR ';
								}
							}
							else
							{
								if($res=$this->isForien($keyName))//if the key is forien then the key name will be changed to new name according to the output of the table
								{
									$filterBy=$filterBy.' '.$table.'.'.$keyName.' = "'.$filterData["keyword"].'"';
								}
								else
								{
									$filterBy=$filterBy.' '.$table.'.'.$keyName.' = "'.$filterData["keyword"].'"';
								}
								$filterBy=$filterBy.' ) ';
							}
							$i++;
						}
					}
				}
				else//the keyword is null and we r using equal not like so no results will be shown
				{
					$filterBy='';
				}
			}
			else//if the multiFilterData is set (multi filters)
			{
				$filters=count($filterData['multiFilterBy']);
				$filterBy='WHERE (';
				$x=1;
				foreach($filterData['multiFilterBy'] as $filterDataChild['filterBy']=>$filterDataChild['keyword'])
				{
					if($res=$this->isForien($filterDataChild['filterBy']))//if the key is forien then the key name will be changed to new name according to the output of the table
					{
						$filterBy=$filterBy.' '.$table.'.'.$filterDataChild["filterBy"].'="'.$filterDataChild["keyword"].'" ';
						if($x!=$filters){ $filterBy=$filterBy.' AND '; }
					}
					else
					{
						$filterBy=$filterBy.' '.$table.'.'.$filterDataChild["filterBy"].'="'.$filterDataChild["keyword"].'" ';
						if($x!=$filters){ $filterBy=$filterBy.' AND '; }
					}
					$x++;
				}
				$filterBy=$filterBy.' )';
			}
		}
		else//case wont be executed cuz by default the variable id defined as null is the page
		{
			$filterBy='';
		}
//***************************************************************************************

//***************************************************************************************
		$joinsSelect='';
		//$joinsFrom='';
		$joinsInner='';
		foreach($filterData['filterKeys'] as $keyName=>$keyValue)//filterKeys are the names of the colomns of the table in db but without some elements like (password )
				{
					if($pos=strrpos($keyName,'_id'))
					{
						$forienTable=substr($keyName, 0, $pos);
						if($forienTable!=$table)//in case the table hase parent_id and looks like TableName_id no joins then
						{
							$cols=$this->getGeneralColums($forienTable);
							$PRI=$cols['primaryKeys'];
							$PRI=$PRI[0];
							$forienColumn=$this->getTableDisplayName($forienTable,'');
							//$joinsFrom=$joinsFrom.', '.$forienTable.' ';
							$joinsSelect=$joinsSelect.','.$forienTable.'.'.$forienColumn.' AS '.$forienTable.'_'.$forienColumn.' ';
							$joinsInner=' INNER JOIN `'.$forienTable.'` ON ('.$table.'.'.$forienTable.'_id = '.$forienTable.'.'.$PRI.')'.$joinsInner;
						}
					}
				}

//***************************************************************************************
		$item = array(); // use it to avoid return false from database
		$sql = 'SELECT `'.$table.'`.* '.$joinsSelect.'  FROM `'.$table.'` '.$joinsInner.' '.$filterBy.' '.$orderBy.' '.$limit; // change by function
		//die($sql);
		$result = mysqli_query($this->cnx,$sql); // use it to fetch
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];

		while($row = mysqli_fetch_assoc($result))
		{
			$item[$row[$PRI]] = $row; // fill up the array
		}
		return $item; // array must return something
	}
	function getAllGeneralItems($status='all',$filterData,$table)
	{
		if(!is_array($filterData))//if there is $filterData given is not array
		{
			$filterData= Array();
		}
//***************************************************************************************
		if(isset($filterData['limit']))//if there is paging or limit on output
		{
			$limit=$filterData["limit"];
		}
		else//empty stinr will be added to the end of sql
		{
			$limit='';
		}
//***************************************************************************************
		if(isset($filterData['orderBy']))//if there is a filter by set
		{
			if($filterData['orderBy']!='')
			{
				$orderBy='ORDER By '.$filterData["orderBy"].' '.$filterData["order"];
			}
			else
			{
				$orderBy='';
			}
		}
		else//case wont be executed cuz by default the variable id defined as null is the page
		{
			$orderBy='';
		}
//***************************************************************************************
		if($filterData['filterBy']!='')//if there is a filter by set
		{
			if($filterData['filterBy']!='all')//if filter is not set to default
			{
				if($status=='all')//then there is no where in the sql statment
				{
					$filterBy='WHERE ('.$filterData["filterBy"].' LIKE "%'.$filterData["keyword"].'%" )';
				}
				else
				{
					$filterBy='AND ('.$filterData["filterBy"].' LIKE "%'.$filterData["keyword"].'%" )';
				}
			}
			else
			{
				$i=1;
				foreach($filterData['filterKeys'] as $keyName=>$keyValue)//filterKeys are the names of the colomns of the table in db but without some elements like (password )
				{
					if($i==1)
					{
						if($status=='all')
						{
							$filterBy='WHERE ( ';
						}
						else
						{
							$filterBy='AND ( ';
						}
					}
					elseif($i<count($filterData['filterKeys']))
					{
						$filterBy=$filterBy.' '.$keyName.' LIKE "%'.$filterData["keyword"].'%" OR ';
					}
					else
					{
						$filterBy=$filterBy.' '.$keyName.' LIKE "%'.$filterData["keyword"].'%"';
						$filterBy=$filterBy.' ) ';
					}
					$i++;
				}
			}
		}
		else//case wont be executed cuz by default the variable id defined as null is the page
		{
			$filterBy='';
		}
//***************************************************************************************
		if($status=='all')
		{
			$sql2='';
		}
		else
		{
			$sql2 ='WHERE status = "'.addslashes($status).'"';
		}
//***************************************************************************************
		$item = array(); // use it to avoid return false from database
		$sql = 'SELECT *  FROM `'.$table.'` '.$sql2.' '.$filterBy.' '.$orderBy.' '.$limit; // change by function
		//var_dump($sql);die();
		$result = mysqli_query($this->cnx,$sql); // use it to fetch
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];

		while($row = mysqli_fetch_assoc($result))
		{
			$item[$row[$PRI]] = $row; // fill up the array
		}
		return $item; // array must return something
	}

	/********************************
	Get item data by item ID
	$id=x use to get item data without language option
	OR
	$id['id']=x where x is the id of the item
	$id['language_id']=x where x is the language_id
	$id['useLang']=false set true to get data in foreign language and not default language  (optional)
	********************************/
function getGeneralItemById($id,$table)
	{
		//$id0=$id;
		//$id=Array();
		$item=Array();
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		if(!is_array($id))
		{
			$id0[$table.'_id']=$id;
			$column=$PRI;
			$id=$id0;
		}
		else
		{
			$id[$table.'_id']=$id['id'];
			$column=$table.'_id';
		}
		//****** set defaults if not defined **************
		if(!isset($id['useLang']))
		{
			$id['useLang']='false';
		}
		if(!isset($id['language_id']))
		{
			$id['language_id']=$this->lang;
		}
		//****** set defaults if not defined **************
		$filter['keyword']=$id[$table.'_id'];
		$filter['filterBy']=$column;
		$filter['exact']=true;
		$filterData['multiFilterBy'][]=$filter;
		// ******************* add language filter **********************
		if($this->checkTableIfExist($table.'_language') && $id['useLang'])
		{
			$filterData['language_id']=$id['language_id'];
		}
		// ******************* add language filter **********************
		$filterData['limit']='LIMIT 1';
		$item=$this->getAllGeneralItemsWithJoins($filterData,$table);

		return $item;
	}

	/********************************
	Used Only IN Front End To Be Updated and Removed
	********************************/

	function getGeneralItemByIdAndLangId($id,$table)
	{

		//$id0=$id;
		//$id=Array();
		//****** set defaults if not defined **************
		//****** set defaults if not defined **************
		$filter['keyword']=$id;
		$filter['filterBy']=$table.'_id';
		$filter['exact']=true;
		$filterData['multiFilterBy'][]=$filter;
		// ******************* add language filter **********************
		$filterLang['keyword']=$this->getVar('lang');
		$filterLang['filterBy']='language_id';
		$filterLang['exact']=true;
		$filterData['multiFilterBy'][]=$filterLang;
		// ******************* add language filter **********************
		$filterData['limit']='LIMIT 1';
		$item=$this->getAllGeneralItemsWithJoins($filterData,$table.'_language');
		if(!count($item))
		{
			$this->setVar('1','lang');
			return $this->getGeneralItemByIdAndLangId($id,$table);
		}
		else
		{
			$item=$item[0];
			return $item;
		}
	}

	//get the item id of the specified name or  returns id
	function getGeneralItemId($data,$table)
	{
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		$fillSQL='';
		if(!is_array($data))
		{
			$data= array();
		}
		foreach ($data as $key => $value)
		{
			if($fillSQL=='')
			{
				$fillSQL=$fillSQL.'WHERE '.$key.'="'.$value.'" ';
			}
			else
			{
				$fillSQL=$fillSQL.'AND '.$key.'="'.$value.'" ';
			}
		}
		$item = array(); // use it to avoid return false from database
		$sql = 'SELECT '.$PRI.' FROM `'.$table.'` '.$fillSQL.' LIMIT 1 '; // change by function
		//die($sql);
		$result = mysqli_query($this->cnx,$sql); // use it to fetch
		$row = mysqli_fetch_assoc($result);
		{
			$item= $row[$PRI]; // fill up the array
		}

		return $item; // array must return something
	}

	//this function must return an id just if an inpunt of the values given by $data[] are already exists
	//otherwise it will return an empty array
	//give $id the id of the item being edited while editing,else $id=0 while adding new item
	//give $data['columnNameInDB']="inputValue"; fill all the required colomns
	function checkGeneralItemIfExist($id,$data0,$table)
	{
		$item =array(); // use it to avoid return false from database
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		$data0[$PRI]=$id;
		$indexes=$this->getGeneralUniqueIndexes($table);

		foreach($indexes as $ind =>$cols2)
		{
			$data=array();
			$missingInfo=false;
			foreach ($cols2 as $ind2 => $values)
			{
				if(!isset($data0[$values['Column_name']]))
				{
					$missingInfo=true;
					break;
				}
				$data[$values['Column_name']]=$data0[$values['Column_name']];
			}
			if(!$missingInfo && $ind!="PRIMARY") //break if not all the colums of the index_key given
			{
				if(!is_array($data))
				{
					$data= array();
				}
				$fillSQL='';
				foreach ($data as $key => $value)
				{
					$returnA[]=$key;
					if($fillSQL=='')
					{
						$fillSQL=$fillSQL.'WHERE (`'.$key.'`="'.addslashes($value).'" ';
					}
					else
					{
						$fillSQL=$fillSQL.'AND `'.$key.'`="'.addslashes($value).'" ';
					}
				}
				if($fillSQL!='')
				{
					$fillSQL=$fillSQL.') AND `'.$PRI.'`!="'.$id.'"';
					$sql = 'SELECT '.$PRI.' FROM `'.$table.'` '.$fillSQL; // change by function
					//if($table=="member") echo $sql.'<br/>';
					$result = mysqli_query($this->cnx,$sql); // use it to fetch
					while($row = mysqli_fetch_assoc($result))
					{
						$item[]='('.implode(',',$returnA).')'; // return to user like "(phone,email,name)"
						unset($returnA);// not to effect the new loop term
					}
				}
			}
		}
		//if($table=="member") $this->show($item);
		if(count($item))
		{
		return implode(' Or ',$item);
		}
		else
		{
			return $item;
		}
	}

	function editGeneralItem($id,$data,$table)   //$name_en,$name_ar
	{
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		if(!is_array($data))
		{
			$data= array();
		}
		$fillSQL='';
		foreach ($data as $key => $value)
		{
			if($fillSQL=='')
				{
					if($key=='password')
					{
						if($value!='')
						{
							$fillSQL=$fillSQL.'SET `'.$key.'`=MD5("'.$value.'") ';
						}
					}
					else
					{
						if(strtolower($value)=="null" || $value==='')
						{
							$fillSQL=$fillSQL.'SET `'.$key.'`= NULL ';
						}
						else
						{
							$fillSQL=$fillSQL.'SET `'.$key.'`="'.addslashes($value).'" ';
						}
					}
				}
				else
				{
					if($key=='password')
					{
						if($value!='')
						{
							$fillSQL=$fillSQL.', `'.$key.'`=MD5("'.$value.'") ';
						}
					}
					else
					{
						if(strtolower($value)=="null" || $value==='')
						{
							$fillSQL=$fillSQL.', `'.$key.'`= NULL  ';
						}
						else
						{
							$fillSQL=$fillSQL.', `'.$key.'`="'.addslashes($value).'" ';
						}
					}
				}
		}
		if($fillSQL=='')//
		{
			return false;
		}
		else
		{
			$fillSQL=$fillSQL.' WHERE '.$PRI.'="'.$id.'"';
		}
		$sql = 'UPDATE `'.$table.'` '.$fillSQL;
		// die($sql);
		if ($result = mysqli_query($this->cnx,$sql))
		{
			return TRUE ;
		}
		else
		{
			return FALSE ;
		}
	}

	function editGeneralItemByIdAndLangId($id,$data,$table)   //$name_en,$name_ar
	{
		if($this->hasLanguage($table))
		{
			$langId=' AND `language_id`='.$data['language_id'];
		}
		else
		{
			$langId='';
		}
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		if(!is_array($data))
		{
			$data= array();
		}
		$fillSQL='';
		foreach ($data as $key => $value)
		{
			if($fillSQL=='')
				{
					if($key=='password')
					{
						if($value!='')
						{
							$fillSQL=$fillSQL.'SET `'.$key.'`=MD5("'.$value.'") ';
						}
					}
					else
					{
						if(strtolower($value)=="null" || $value==='')
						{
							$fillSQL=$fillSQL.'SET `'.$key.'`= NULL  ';
						}
						else
						{
							$fillSQL=$fillSQL.'SET `'.$key.'`="'.addslashes($value).'" ';
						}
					}
				}
				else
				{
					if($key=='password')
					{
						if($value!='')
						{
							$fillSQL=$fillSQL.', `'.$key.'`=MD5("'.$value.'") ';
						}
					}
					else
					{
						if(strtolower($value)=="null" || $value==='')
						{
							$fillSQL=$fillSQL.', `'.$key.'`= NULL  ';
						}
						else
						{
							$fillSQL=$fillSQL.', `'.$key.'`="'.addslashes($value).'" ';
						}
					}
				}
		}
		if($fillSQL=='')//
		{
			return false;
		}
		else
		{
			$fillSQL=$fillSQL.' WHERE '.$PRI.'="'.$id.'"'.$langId;
		}
		$sql = 'UPDATE `'.$table.'` '.$fillSQL;
		
		if ($result = mysqli_query($this->cnx,$sql))
		{
			return TRUE ;
		}
		else
		{
			return FALSE ;
		}
	}

	function addGeneralItem($data,$table)
	{
		if(!is_array($data))
		{
			$data= array();
		}
		$fillSQLcol='';
		$fillSQLval='';
		foreach ($data as $key => $value)
		{
			if($value==='')
			{
				continue;
			}
				if($fillSQLval=='')
				{
					if($key!='date_cr')
					{
						$fillSQLcol=$fillSQLcol.'`'.$key.'`';
					}
					if($key=='password')
					{
						$fillSQLval=$fillSQLval.'MD5("'.$value.'")';
					}
					else
					{
						if($key!='date_cr' && $value!="null")
						{
							$fillSQLval=$fillSQLval.'"'.addslashes($value).'"';
						}
						elseif($value=="null")
						{
							$fillSQLval=$fillSQLval.' '.addslashes($value).' ';
						}
					}
				}
				else
				{
					if($key!='date_cr'){
					$fillSQLcol=$fillSQLcol.',`'.$key.'`';}
					if($key=='password')
					{
						$fillSQLval=$fillSQLval.',MD5("'.$value.'")';
					}
					else
					{
						if($key!='date_cr' && $value!="null")
						{
							$fillSQLval=$fillSQLval.',"'.addslashes($value).'"';
						}
						elseif($value=="null")
						{
							$fillSQLval=$fillSQLval.', '.addslashes($value).' ';
						}
					}
				}
		}
		$sql = 'INSERT INTO `'.$table.'`('.$fillSQLcol.') VALUES ('.$fillSQLval.')';
		//die($sql);
		if ($result = mysqli_query($this->cnx,$sql))
		{
			if($id = mysqli_insert_id($this->cnx))
			{
				return $id;
			}
			else
			{
				return true;
			}
		}
	}
	function editGeneralItemNewLang($id,$data,$table)   //$name_en,$name_ar
	{
		$cols=$this->getGeneralColums($table);
		$PRI=$cols['primaryKeys'];
		$PRI=$PRI[0];
		if(!is_array($data))
		{
			$data= array();
		}
		$data[$PRI]=$id;
		$fillSQLcol='';
		$fillSQLval='';
		if($this->checkColumnIfExist('image_id',$table))
		{
			$motherItem=$this->getGeneralItemById($id,$table);
			$data['image_id']=$motherItem[$id]['image_id'];
		}
		foreach ($data as $key => $value)
		{
				if($fillSQLval=='')
				{
					$fillSQLcol=$fillSQLcol.'`'.$key.'`';
					if($key=='password')
					{
						$fillSQLval=$fillSQLval.'MD5("'.$value.'")';
					}
					else
					{
						if($value=="null")
						{
							$fillSQLval=$fillSQLval.' '.addslashes($value).' ';
						}
						else
						{
							$fillSQLval=$fillSQLval.'"'.addslashes($value).'"';
						}
					}
				}
				else
				{
					$fillSQLcol=$fillSQLcol.',`'.$key.'`';
					if($key=='password')
					{
						$fillSQLval=$fillSQLval.',MD5("'.$value.'")';
					}
					else
					{
						if($value=="null")
						{
							$fillSQLval=$fillSQLval.', '.addslashes($value).' ';
						}
						else
						{
							$fillSQLval=$fillSQLval.',"'.addslashes($value).'"';
						}
					}
				}
		}
		$sql = 'INSERT INTO `'.$table.'`('.$fillSQLcol.') VALUES ('.$fillSQLval.')';
		//die($sql);
		if ($result = mysqli_query($this->cnx,$sql))
		{
			if($id=mysqli_insert_id($this->cnx))
			{
				return $id;
			}
			else
			{
				return true;
			}
		}
	}
	function hasFiles($table)
	{
		$result=$this->checkIfExist($table,'table','files');
		return $result;
	}
	function hasImages($table)
	{
		$result=$this->checkTableIfExist('image_to_'.$table);
		return $result;
	}
	// check if this table is a language table and not the main
	function hasLanguage($table)
	{
		$sql='SELECT language_id FROM `'.$table.'`';
		if(mysqli_query($this->cnx,$sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function isLanguageDefined($data,$table)
	{
		$sql='SELECT `language_id` FROM `'.$table.'` WHERE `language_id`="'.$data['language_id'].'" AND `'.str_replace('_language','',$table).'_id`="'.$data['id'].'"';
		if($result=mysqli_query($this->cnx,$sql))
		{
			if(mysqli_num_rows($result))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	function uploadFile($data) // $name,$status
	{
		if(is_uploaded_file($data['tmp_name']))
		{
			$new_name=date('d-m-y h-i-s A');
			$old_name=$data['name'];
			$strpos=strrpos($old_name,'.');
			$ext=substr($old_name,$strpos,strlen($old_name));
			$old_name=substr($old_name,0,$strpos);
			$new_name=$old_name.' '.$new_name.$ext;
			if(!file_exists('../../../public/files'))
			{
				mkdir('../../../public/files');
			}
			if(!file_exists('../../../public/files/'.$data['table']))
			{
				mkdir('../../../public/files/'.$data['table']);
			}
			if(!file_exists('../../../public/files/'.$data['table'].'/'.$data['id']))
			{
				mkdir('../../../public/files/'.$data['table'].'/'.$data['id']);
			}
			if(!file_exists('../../../public/files/'.$data['table'].'/'.$data['id'].'/'.$new_name))
			{
				if(move_uploaded_file($data['tmp_name'],'../../../public/files/'.$data['table'].'/'.$data['id'].'/'.$new_name))
				{
					return $new_name;
				}
				else
				{
					return false;
				}
			}

		}
		else
		{
			return false;
		}
	}
	function deleteFile($dir)
	{
		$dir='../../../public/files/'.$dir;
		if(unlink($dir))
		{
			return TRUE;
		}
	}
	function send_one ($text, $subject, $name, $sender_email, $to)
	{

		$headers="From:$name <$sender_email>\r\n";
		$headers .= "Reply-To: $sender_email\r\n";
		$headers .= "Date: " . date("r") . "\r\n";
		$headers .= "Return-Path: $sender_email\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Message-ID: " . date("r") . $name ."\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		$headers .= "X-Priority: 1\r\n";
		$headers .= "Importance: High\r\n";
		$headers .= "X-MXMail-Priority: High\r\n";
		$headers .= "X-Mailer: PHP Mailer 1.0\r\n";

		mail($to,$subject, $text, $headers);
		return true;
	}
	function getColType($col,$table)
	{
		$cols=$this->getGeneralColums($table);
		if(strpos($cols['keys'][$col]['Type'],'(')===false)
		{
			$item['type']=$cols['keys'][$col]['Type'];
			$item['length']='';
		}
		else
		{
			$item['type']=substr($cols['keys'][$col]['Type'],0,strpos($cols['keys'][$col]['Type'],'('));
			$item['length']=substr($cols['keys'][$col]['Type'],strpos($cols['keys'][$col]['Type'],'(')+1,-1);
		}
		return $item;
	}
	function getNextStep($page,$seq)
	{
		$item='end';
		foreach($seq as $ind=>$data)
		{
			if($data['page']==$page)
			{
				if(isset($seq[$ind+1]))
				{
					$item=$seq[$ind+1]['page'];
				}
			}
		}
		return $item;
	}
	function getPrevStep($next,$seq)
	{
		$item='start';
		foreach($seq as $ind=>$data)
		if($data['page']==$next)
		{
			if($data['col']!='start')
			{

				$item=$seq[$ind-1]['page'];
			}
		}//die($item);
		return $item;

	}
	function getParentPage($page)
	{
		$item=false;
		if($this->checkTableIfExist('Control_p_page_levels'))
		{
			$sql='SELECT `next` FROM `Control_p_page_levels` WHERE `page`="'.$page.'"';
			$result=mysqli_query($this->cnx,$sql);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_assoc($result);
				if($row['next'])
				{
					if($row['next'])
					$item=unserialize($row['next']);
				}
			}
		}
		return $item;
	}
//******************************************* end of general functions *******************************
	function getFolderPath($id)
	{
		$returnA=array();
		$return='';
		$folder=$this->getGeneralItemById($id,'control_p_folder');
		$folder=$folder[$id];
		$returnA[]=$folder['name'].'/';
		while($folder['control_p_folder_id']!=0)
		{
			$newId=$folder['control_p_folder_id'];
			$folder=$this->getGeneralItemById($newId,'control_p_folder');
			$folder=$folder[$newId];
			$returnA[]=$folder['name'].'/';
		}
		foreach(array_reverse($returnA) as $ind=>$val)
		{
			$return.=$val;
		}
		return $return;
	}
}
?>
