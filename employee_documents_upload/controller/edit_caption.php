<script language="javascript" src="../js/ind.js">
</script>
<?php
include("../model/dbact.php");
$oper=new dboper();

$img_id=$_REQUEST['id'];
$caption=$_REQUEST['cpt'];
$root_id = $_REQUEST['root_id'];
$user_id = $_REQUEST['user_id'];

$oper->editcapt($img_id,$caption,$root_id,$user_id);

    $query = "select * from xin_employee_document_files WHERE document_id='' and root_id='".$root_id."' and employee_id='".$user_id."' order by id DESC";
    $result=mysql_query($query);
    while($row=mysql_fetch_array($result))
    {
    
    $ximage=$row['img_name'];
    
        $filename = $ximage;
        $file_url = site_url().'uploads/document/'.$ximage;
		                
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
            <a target="blank" href="<?php echo $file_url; ?>"><img src="<?php echo site_url(); ?>skin/icons/<?php echo $imgico; ?>" height="50" title="<?=$row['img_list_name']; ?>"/></a>
        </div>
        <div class="col-sm-6">
            <p><input name="txtcaption" type="text" class="input" <?php if($row['img_title']){ ?> value="<?php echo $row['img_title']; ?>" <?php } else { ?> placeholder="Caption Here" <?php } ?> onchange="editcapt(this.value,'<?php echo $row['id']; ?>','<?php echo $root_id; ?>','<?php echo $employee_id; ?>')" /></p>
        </div>
        <div class="col-sm-3">
            <a class="dbtn" href="javascript:delpic('<?php echo $row['id']; ?>','<?php echo $root_id; ?>','<?php echo $employee_id; ?>')"><span>Delete</span></a>
        </div>
    </div>
    
    </div>
    <div class="clear"></div>
    <?php
    }
    ?>