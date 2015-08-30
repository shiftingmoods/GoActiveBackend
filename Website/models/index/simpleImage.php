<?php
class simpleImage 
{
   
   var $image;
   var $image_type;
 
   function load($filename) 
   { 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
	 
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) 
   {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) 
   {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() 
   {
      return imagesx($this->image);
   }
   function getHeight() 
   {
      return imagesy($this->image);
   }
   function resizeToHeight($height) 
   {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) 
   {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) 
   {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) 
   {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
	//add image to database
	function addImage($data)
	{
		$sql='INSERT into `image` (name) VALUES ("'.$data['name'].'")';
		if(mysql_query($sql))
		return $id=mysql_insert_id();
	
	}
	
	//upload new image
	function uploadImage($data,$name='image') // $name,$status 
	{
		if(( $data[$name]['type']=="image/gif" || $data[$name]['type']=="image/jpg" || $data[$name]['type']=="image/pjpeg" || $data[$name]['type']=="image/x-png"  || $data[$name]['type']=="image/jpeg" ) && $data[$name]['size']< 2097152  )
		{
			$valid=TRUE; 
		}
		else 
		{
			$valid=FALSE;
		}
		if(is_uploaded_file($data[$name]['tmp_name']) && $valid==TRUE )
		 { 
			$new_name=microtime(date('y-m-d H:i:s'));
			$old_name=$data[$name]['name'];
			$strpos=strrpos($old_name,'.');
			$ext=strtolower(substr($old_name,$strpos,strlen($old_name)));
			$new_name=$new_name.$ext;
			if(!is_dir('../../../public/temp'))
			{
				mkdir('../../../public/temp');
			}
			move_uploaded_file($data[$name]['tmp_name'],'../../../public/temp/'.$new_name);
			return $new_name;
			
			
		}
	}
function uploadFile($data,$name='file') // $name,$status 
	{
		if(is_uploaded_file($data['_FILES'][$name]['tmp_name']))
		{
			$folder='';
			$valInc='';
			if(isset($data['folder']))
			{
				$folder=$data['folder'];
				if(!is_dir('../../../public/files'))
					{
						mkdir('../../../public/files');
					}
				$folderA=explode('/',$folder);
				foreach($folderA as $ind=>$val)
				{
					$valInc.=$val.'/';
					if(!is_dir('../../../public/files/'.$valInc))
					{
						mkdir('../../../public/files/'.$valInc);
					}
				}
			}
			//var_dump($folderA);die();
			$new_name=strtotime(date('y-m-d H:i:s'));
			$old_name=$data['_FILES'][$name]['name'];
			$strpos=strrpos($old_name,'.');
			$ext=strtolower(substr($old_name,$strpos,strlen($old_name)));
			$new_name=$new_name.$ext;
			
			
			move_uploaded_file($data['_FILES'][$name]['tmp_name'],'../../../public/files/'.$valInc.$new_name);
			return $new_name;
		}
	}
	function masterImage($data)
	{	
		
		
		$this->load('../../../public/temp/'.$data['name']);
		$h=$this->getHeight();
		$w=$this->getWidth();
		if($h > '512' || $w > '512')
		{
			if($w > $h)
			{
				$this->resizeToWidth('512');
			}
			else
			{
				$this->resizeToHeight('512');
			}
			$this->save('../../../public/images/'.$data['name']);
			$this->load('../../../public/temp/'.$data['name']);
			$this->resize(100,100);
			$this->save('../../../public/images/thumbs/'.$data['name']);
			unlink('../../../public/temp/'.$data['name']);
			return TRUE;
		}
		else
		{
			$this->save('../../../public/images/'.$data['name']);
			$this->load('../../../public/temp/'.$data['name']);
			$this->resize(100,100);
			$this->save('../../../public/images/thumbs/'.$data['name']);
			unlink('../../../public/temp/'.$data['name']);
			return TRUE;
		}
		
	}
	//delete company image from image table
	function deleteImage($id)
	{	
		if($id==0)
		{
			return TRUE;
		}
		$item=$this->getImageById($id);
		$dir='../../../public/images/'.$item[$id]['name'];
		$thumbDir='../../../public/images/thumbs/'.$item[$id]['name'];
		$sql='DELETE FROM `image` WHERE id="'.addslashes($id).'"';
		if ($result = mysql_query($sql))
		{
			unlink($dir);
			unlink($thumbDir);
			return TRUE;
		}
	}
	//delete company image from image table
	function deleteImageFile($name)
	{	
		if($name=='default.jpg')
		{
			return TRUE;
		}
		$dir='../../../public/images/'.$name;
		$thumbDir='../../../public/images/thumbs/'.$name;
		if(unlink($dir) && unlink($thumbDir))
		{
			return TRUE;
		}
	}
}
?>