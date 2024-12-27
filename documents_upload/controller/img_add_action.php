<?php
if(isset($_POST))
{
include("../model/dbact.php");
include("../model/SimpleImage.php");
	$oper=new dboper();

$root_id = $_POST['root_id'];
$user_id = $_POST['user_id'];
		
		//$w=800;
		//$h=600;
		//$lrge_img=$oper->imgresize($filename,$source_path,$w,$h);
	
	$array=count($_FILES['userfile']['name']);
		$j=0;
	while($j<=$array-1)
	{
		$wdth=250;
		$hght=200;
		$source_path = $_FILES['userfile']['tmp_name'][$j];
		$filename    = $_FILES['userfile']['name'][$j];
		$filesize    = $_FILES['userfile']['size'][$j];
		$act_img     = $oper->img_upload($source_path,$filename);
		$oper->insertgal($filename,$act_img,$filesize,$root_id,$user_id);
	$j=$j+1;
	}
	?>
       <?php    
                $query = "select * from xin_document_files WHERE document_id='' and root_id='".$root_id."' and uid='".$user_id."' order by id DESC";
                $result=mysql_query($query);
                while($row=mysql_fetch_array($result))
                {
                
                    $ximage=$row['img_name'];
                
                    $filename = $ximage;
	                $file_url = site_url().'uploads/company_documents/'.$ximage;
					                
					$ext = substr(strrchr($filename, '.'), 1);
					
					if($ext=="pdf")
					{
					   $imgico="pdf-file.png";
					}
					else if($ext=="doc" || $ext=="docx")
					{
					   $imgico="word_icon.png";
					   $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
					}
					else if($ext=="csv" || $ext=="xlsx" || $ext=="xls")
					{
					   $imgico="excel_icon.png";
					   $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
					}
					else if($ext=="zip")
					{
					   $imgico="zip_icon.png";
					}
					else
					{
					   $imgico="img-file.png";
					}
                
                
                ?>
                <!--loop box images-->
                <div class="loop-box">
                    
                <div class="row">
                    <div class="col-sm-3">
	                    <a target="blank" href="<?php echo $file_url; ?>"><img src="skin/icons/<?php echo $imgico; ?>" height="80" title="<?=$row['img_list_name']; ?>"/></a>
	                </div>
	                <div class="col-sm-6">
	                    <p><input name="txtcaption" type="text" class="input" <?php if($row['img_title']){ ?> value="<?php echo $row['img_title']; ?>" <?php } else { ?> placeholder="Caption Here" <?php } ?> onchange="editcapt(this.value,'<?php echo $row['id']; ?>','<?php echo $root_id; ?>','<?php echo $user_id; ?>')" /></p>
	                </div>
	                <div class="col-sm-3">
	                    <a class="dbtn" href="javascript:delpic('<?php echo $row['id']; ?>','<?php echo $root_id; ?>','<?php echo $user_id; ?>')"><span>Delete</span></a>
	                </div>
	            </div>
	            
	            </div>
                <?php
                }
                ?>          
    <?php
}

?>