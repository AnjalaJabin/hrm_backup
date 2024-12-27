<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['document_id']) && $_GET['data']=='document'){
    
$all_files = $this->Document_model->get_document_files($_GET['document_id']);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="icon-pencil7"></i> <?php echo $this->lang->line('xin_edit_document');?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("documents/update").'/'.$document_id; ?>" method="post" name="edit_document" id="edit_document">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="edit_type" value="document">
  <input type="hidden" name="_token" value="<?php echo $_GET['document_id'];?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
          
                  <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                    <select class="form-control" name="company">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>" <?php if($company_id==$company->company_id){?> selected="selected" <?php } ?>> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>
        
                <div class="form-group">
                    <label for="expiry_date">Document Expiry Date</label>
                    <input class="form-control expiry_date" readonly placeholder="Document Expiry Date" name="expiry_date" type="text" value="<?php echo $expiry;?>">
                </div>
        
                <!--
                <div class="form-group">
                    <h6>Document</h6>
                    <span class="btn btn-primary btn-file">
                    	Browse <input type="file" name="file" id="file">
                    </span>
                    <br>
                    <small><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                </div>
                -->
        
      </div>
      
      <div class="col-sm-6">
          
                <div class="form-group">
                  <label for="xin_document_title">Document Title</label>
                  <input class="form-control" placeholder="Document Title" name="document_title" type="text" value="<?php echo $title;?>">
                </div>
                
                <div class="form-group">
                  <label for="xin_des">Description</label>
                  <textarea class="form-control" placeholder="Description" name="description"><?php echo $des;?></textarea>
                </div>
      </div>
    </div>
    
    <div class="row">
        
                <div class="col-sm-8">
                    
                    <div class="uplpad-top-box"><a href="javascript:showfunc2()"><span>+</span>Add Files</a></div> 
                                <div class="clear"></div>
            					<div id="progressbox2">
            					    <div id="progressbar2"></div>
            					    <div id="statustxt2">0%</div>
            					</div>
            					
                    </div>
              
              </div>
    </div>
    
    <div class="row">
              
              <div class="col-sm-8">
                        
                        <div class="box-body table-responsive">
        					
        					<div id="output2">
        					
        					<?php
        	                foreach($all_files as $row) {
        					                
            	                $ximage=$row->img_name;
            	                
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
                    	                    <a target="blank" href="<?php echo $file_url; ?>"><img src="skin/icons/<?php echo $imgico; ?>" height="50" title="<?=$row->img_list_name; ?>"/></a>
                    	                </div>
                    	                <div class="col-sm-6">
                    	                    <p><input name="txtcaption" type="text" class="input" <?php if($row->img_title){ ?> value="<?php echo $row->img_title; ?>" <?php } else { ?> placeholder="Caption Here" <?php } ?> onchange="editcapt2(this.value,'<?php echo $row->id; ?>','<?php echo $_SESSION['root_id']; ?>','<?php echo $_SESSION['user_id']; ?>','<?php echo $_GET['document_id']; ?>')" /></p>
                    	                </div>
                    	                <div class="col-sm-3">
                    	                    <a class="dbtn" href="javascript:delpic2('<?php echo $row->id; ?>','<?php echo $_SESSION['root_id']; ?>','<?php echo $_SESSION['user_id']; ?>','<?php echo $_GET['document_id']; ?>')"><span>Delete</span></a>
                    	                </div>
                    	            </div>
                    	            
            	                    </div>
            	                    <div class="clear"></div>
            	                <?php
        	                }
        	                ?>
                        </div>
                    </div>
                </div>
            </div>
    
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>

<form action="<?php echo site_url(); ?>documents_upload/edit/controller/img_add_action.php" method="post" enctype="multipart/form-data" name="UploadForm2" id="UploadForm2">
 <input type="hidden" name="root_id" value="<?php echo $_SESSION['root_id']; ?>"/>
 <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"/>
 <input type="hidden" name="document_id" value="<?php echo $_GET['document_id']; ?>"/>
 <input name="userfile2[]" id="userfile2" type="file" style="display:none" multiple accept="image/x-png, image/gif, image/jpeg, .pdf,.doc,.docx,.zip,.xlsx,.csv,.xls" onChange="actfunc2()"  required />
 <input type="submit"  id="SubmitButton2" value="Upload" style="display:none;"/>
</form>



<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("documents/document_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
    	
    	$('.expiry_date').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat:'yy-mm-dd',
          minDate : 1,
          yearRange: '-0:+15'
        });
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 

		/* Edit data */
		$("#edit_document").submit(function(e){ 
			var fd = new FormData(this);
			var obj = $(this), action = obj.attr('name');
			fd.append("is_ajax", 2);
			fd.append("edit_type", 'document');
			fd.append("form", action);
			e.preventDefault();
			$('.save').prop('disabled', true);
			$.ajax({
				url: e.target.action,
				type: "POST",
				data:  fd,
				contentType: false,
				cache: false,
				processData:false,
				success: function(JSON)
				{
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						xin_table.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				},
				error: function() 
				{
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} 	        
		   });
		});
	});	
	
	
	
	
	
	
	
	
	
	
	
	
	
// For Edit
// JavaScript Document
    $(document).ready(function(){ 
    //elements
    var progressbox2     = $('#progressbox2');
    var progressbar2     = $('#progressbar2');
    var statustxt2       = $('#statustxt2');
    var submitbutton2    = $("#SubmitButton2");
    var myform2          = $("#UploadForm2");
    var output2          = $("#output2");
    var completed2       = '0%';
 
    $(myform2).ajaxForm({
        beforeSend: function() { //brfore sending form
        //submitbutton.attr('disabled', ''); // disable upload button
        statustxt2.empty();
        progressbox2.slideDown(); //show progressbar
        progressbar2.width(completed2); //initial value 0% of progressbar
        statustxt2.html(completed2); //set status text
        statustxt2.css('color','#000'); //initial color of status text
        },
        uploadProgress: function(event, position, total, percentComplete) { //on progress
        progressbar2.width(percentComplete + '%') //update progressbar percent complete
        statustxt2.html(percentComplete + '%'); //update status text
        if(percentComplete>50)
        {
            statustxt2.css('color','#fff'); //change status text to white after 50%
        }
        },

        complete: function(response) { // on complete
        output2.html(response.responseText); //update element with received data
        myform2.resetForm();  // reset form
        // submitbutton.removeAttr('disabled'); //enable submit button
        progressbox2.slideUp(); // hide progressbar
        }
    });
});



function showfunc2()
{
   $('#userfile2').click();
}

function actfunc2()
{ 
sts=0;
var files = document.getElementsByName('userfile2[]'); 

for (var i = 0, j = files.length; i < j; i++) {
					var file = files[i];


fname=file.value;
var re = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png|\.pdf|\.zip|\.doc|\.docx|\.zip|\.csv|\.xlsx|\.xls)$/i;
if(!re.exec(fname))
{
var sts=1;
}

}
var vl=document.getElementById('userfile2').value;
	if(vl!='')
	{

		if(sts==1)
		{
		alert('File Type Not Supported');
		}
		else
		{
		//document.getElementById('SubmitButton').click();
		$("#UploadForm2").submit();
		//document.getElementById('SubmitButton').click();
		//document.UploadForm.action='gallery/controller/img_add_action.php';
		//document.frmimg.submit();
		}
	}
	
}
//function editcapt(capt,id)
//{
//document.location="controller/edit_caption.php?id="+id+"&cpt="+capt;
//}
function editcapt2(capt,id,root_id,user_id,document_id)
{
	if (window.XMLHttpRequest)   
	{
		xmlhttp=new XMLHttpRequest(); 
	}
	else   
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	} 
	xmlhttp.onreadystatechange=function()   
	{ 
		if (xmlhttp.readyState==4 && xmlhttp.status==200)     
		{
		document.getElementById("output2").innerHTML=xmlhttp.responseText;
			//window.location.reload();
		}   
	}
	xmlhttp.open("GET","documents_upload/edit/controller/edit_caption.php?id="+id+"&cpt="+capt+"&root_id="+root_id+"&user_id="+user_id+"&document_id="+document_id,true);
	xmlhttp.send();
}
function delpic2(img_id,root_id,user_id,document_id)
{

    if(confirm("Are you sure you want to delete this file?")) {
    	if (window.XMLHttpRequest)   
    	{
    		xmlhttp=new XMLHttpRequest(); 
    	}
    	else   
    	{
    		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    	} 
    	xmlhttp.onreadystatechange=function()   
    	{
    		if (xmlhttp.readyState==4 && xmlhttp.status==200)     
    		{
    		document.getElementById("output2").innerHTML=xmlhttp.responseText;
    		}   
    	}
    	xmlhttp.open("GET","documents_upload/edit/controller/delpic_action.php?img_id="+img_id+"&root_id="+root_id+"&user_id="+user_id+"&document_id="+document_id,true);
    	xmlhttp.send();
    }
	
}
  </script>
<?php } else if(isset($_GET['jd']) && $_GET['data']=='view_document' && isset($_GET['document_id']) ){
    
    $all_files = $this->Document_model->get_document_files($_GET['document_id']);
?>








<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="icon-pencil7"></i> View Document</h4>
</div>
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
          
                  <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                    <select class="form-control" name="company" readonly="readonly">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>" <?php if($company_id==$company->company_id){?> selected="selected" <?php } ?>> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>
        
                <div class="form-group">
                    <label for="expiry_date">Document Expiry Date</label>
                    <input class="form-control expiry_date" readonly placeholder="Document Expiry Date" name="expiry_date" type="text" value="<?php echo $expiry;?>" readonly="readonly">
                </div>
        
                <!--
                <div class="form-group">
                    <h6>Document</h6>
                    <span class="btn btn-primary btn-file">
                    	Browse <input type="file" name="file" id="file">
                    </span>
                    <br>
                    <small><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                </div>
                -->
        
      </div>
      
      <div class="col-sm-6">
          
                <div class="form-group">
                  <label for="xin_document_title">Document Title</label>
                  <input class="form-control" placeholder="Document Title" name="document_title" type="text" value="<?php echo $title;?>" readonly="readonly">
                </div>
                
                <div class="form-group">
                  <label for="xin_des">Description</label>
                  <textarea class="form-control" placeholder="Description" name="description" readonly="readonly"><?php echo $des;?></textarea>
                </div>
      </div>
    </div>
    
    <div class="row">
              
              <div class="col-sm-8">
                        
                        <div class="box-body table-responsive">
        					
        					<div id="output2">
        					
        					<?php
        	                foreach($all_files as $row) {
        					                
            	                $ximage=$row->img_name;
            	                
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
                    	                    <a target="blank" href="<?php echo $file_url; ?>"><img src="skin/icons/<?php echo $imgico; ?>" height="50" title="<?=$row->img_list_name; ?>"/></a>
                    	                </div>
                    	                <div class="col-sm-6">
                    	                    <p style="margin-left:20px;"><?php echo $row->img_title; ?></p>
                    	                </div>
                    	            </div>
                    	            
            	                    </div>
            	                    <div class="clear"></div>
            	                <?php
        	                }
        	                ?>
                        </div>
                    </div>
                </div>
            </div>
    
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>






<?php
}
?>