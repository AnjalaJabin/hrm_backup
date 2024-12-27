<?php
class dboper
{
var $sql;
	function getconnect()
	{
		include("../../../../mysql.php");
	}
	
	function insertgal($smlimg,$actimg,$filesize,$root_id,$user_id,$document_id)
	{
		$this->getconnect();
		$sql="insert into xin_document_files(img_name,img_list_name,root_id,uid,document_id,time,filesize) values('$actimg','$smlimg','$root_id','$user_id','$document_id','".time()."','$filesize')";
		mysql_query($sql);
	}
	 
	function delimg($img_id,$root_id)
	{
		$this->getconnect();
		$result=mysql_query("select * from xin_document_files where id='$img_id' and root_id='".$root_id."'");
		$row=mysql_fetch_array($result);
		$act_img="../../uploads/company_documents/".$row['img_name'];
		if(file_exists($act_img))
		{
		unlink($act_img);
		}
		$lst_img="../../uploads/company_documents/".$row['img_list_name'];
		if(file_exists($lst_img))
		{
		unlink($lst_img);
		}
		$sql="delete from xin_document_files where id='$img_id' and root_id='".$root_id."'";
		mysql_query($sql);
	}
	
	function editcapt($img_id,$caption,$root_id)
	{
	$this->getconnect();
	$sql="update xin_document_files set img_title='$caption' where id='$img_id' and root_id='".$root_id."'";
	mysql_query($sql);
	}

	function img_upload($source_path,$filename)
	{ 
	
	 $i = strrpos($filename,".");
         if (!$i) { return ""; }
         $l = strlen($filename) - $i;
         $ext = substr($filename,$i+1,$l);
 	
  		$extension = $ext;
 		$extension = strtolower($extension);
 		
		$ua=rand(1111111,9999999999);
		$ub=time();
		
		$ud=$ua."_".$ub;
	
			$act_img=$ud.".".$extension;;
			$targetfile="../../../uploads/company_documents/".$act_img;
			move_uploaded_file($source_path,$targetfile);
			return $act_img;
	}
}
?>