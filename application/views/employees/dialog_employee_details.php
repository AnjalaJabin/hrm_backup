<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_contact' && $_GET['type']=='emp_contact'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_contact');?></h4>
</div>
<form id="e_contact_info" action="<?php echo site_url("employees/e_contact_info") ?>" name="e_contact_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $contact_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label for="relation"><?php echo $this->lang->line('xin_e_details_relation');?></label>
          <select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <option value="Self" <?php if($relation=='Self'){?> selected="selected" <?php }?>>Self</option>
            <option value="Parent" <?php if($relation=='Parent'){?> selected="selected" <?php }?>>Parent</option>
            <option value="Spouse" <?php if($relation=='Spouse'){?> selected="selected" <?php }?>>Spouse</option>
            <option value="Child" <?php if($relation=='Child'){?> selected="selected" <?php }?>>Child</option>
            <option value="Sibling" <?php if($relation=='Sibling'){?> selected="selected" <?php }?>>Sibling</option>
            <option value="In Laws" <?php if($relation=='In Laws'){?> selected="selected" <?php }?>>In Laws</option>
          </select>
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <label for="work_email" class="control-label"><?php echo $this->lang->line('dashboard_email');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_work');?>" name="work_email" type="text" value="<?php echo $work_email;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_primary" value="1" name="is_primary" <?php if($is_primary=='1'){?> checked="checked" <?php }?>>
            <span class="custom-control-indicator"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_pcontact');?></span> </label>
          &nbsp;
          <label class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_dependent" value="2" name="is_dependent" <?php if($is_dependent=='2'){?> checked="checked"<?php }?>>
            <span class="custom-control-indicator"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_dependent');?></span> </label>
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_personal');?>" name="personal_email" type="text" value="<?php echo $personal_email;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label for="name" class="control-label"><?php echo $this->lang->line('xin_name');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_name');?>" name="contact_name" type="text" value="<?php echo $contact_name;?>">
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group" id="designation_ajax">
          <label for="address_1" class="control-label"><?php echo $this->lang->line('xin_address');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text" value="<?php echo $address_1;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label for="work_phone"><?php echo $this->lang->line('xin_phone');?></label>
          <div class="row">
            <div class="col-xs-8">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_work');?>" name="work_phone" type="text" value="<?php echo $work_phone;?>">
            </div>
            <div class="col-xs-4">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_phone_ext');?>" name="work_phone_extension" type="text" value="<?php echo $work_phone_extension;?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text" value="<?php echo $address_2;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="mobile_phone" type="text" value="<?php echo $mobile_phone;?>">
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <div class="row">
            <div class="col-xs-5">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text" value="<?php echo $city;?>">
            </div>
            <div class="col-xs-4">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="state" type="text" value="<?php echo $state;?>">
            </div>
            <div class="col-xs-3">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text" value="<?php echo $zipcode;?>">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_home');?>" name="home_phone" type="text" value="<?php echo $home_phone;?>">
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <select name="country" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
            <option value=""></option>
            <?php foreach($all_countries as $country) {?>
            <option value="<?php echo $country->country_id;?>" <?php if($country->country_id==$country){?> selected="selected" <?php }?>> <?php echo $country->country_name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load: table_contacts
	 var xin_table_contact = $('#xin_table_contact').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/contacts") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
			
	/* Update contact info */
	$("#e_contact_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=5&data=e_contact_info&type=e_contact_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_contact.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_document' && $_GET['type']=='emp_document'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_document');?></h4>
</div>
<form id="e_document_info" action="<?php echo site_url("employees/e_document_info") ?>" name="e_document_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $d_employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $document_id;?>">
  <input type="hidden" name="u_document_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="relation"><?php echo $this->lang->line('xin_e_details_dtype');?></label>
          <select name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
            <option value=""></option>
            <?php foreach($all_document_types as $document_type) {?>
            <option value="<?php echo $document_type->document_type_id;?>" <?php if($document_type->document_type_id==$document_type_id) {?> selected="selected" <?php } ?>> <?php echo $document_type->document_type;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="date_of_expiry" class="control-label"><?php echo $this->lang->line('xin_e_details_doe');?></label>
          <input class="form-control e_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_doe');?>" name="date_of_expiry" type="text" value="<?php echo $date_of_expiry;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title" class="control-label"><?php echo $this->lang->line('xin_e_details_dtitle');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_dtitle');?>" name="title" type="text" value="<?php echo $title;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="send_mail"><?php echo $this->lang->line('xin_e_details_send_notifyemail');?></label>
          <select name="send_mail" id="send_mail" class="form-control" data-plugin="select_hrm">
            <option value="1" <?php if($is_alert=='1') {?> selected="selected" <?php } ?>><?php echo $this->lang->line('xin_yes');?></option>
            <option value="2" <?php if($is_alert=='2') {?> selected="selected" <?php } ?>><?php echo $this->lang->line('xin_no');?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="description" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
    
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="uplpad-top-box"><a href="javascript:showfunc2()"><span>+</span>Add Files</a></div> 
                                <div class="clear"></div>
            					<div id="progressbox2">
            					    <div id="progressbar2"></div>
            					    <div id="statustxt2">0%</div>
            					</div>
            					
                    </div>
            </div>
            
            <div class="col-sm-12">
                        
                        <div class="box-body table-responsive">
        					
        					<div id="output2">
        					
        					<?php
        	                foreach($all_files as $row) {
        					    $user_id = $row->employee_id;            
            	                $ximage=$row->img_name;
            	                
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
                    	                    <a target="blank" href="<?php echo site_url(); ?>uploads/document/<?php echo $ximage; ?>"><img src="<?php echo site_url(); ?>skin/icons/<?php echo $imgico; ?>" height="50" title="<?=$row->img_list_name; ?>"/></a>
                    	                </div>
                    	                <div class="col-sm-6">
                    	                    <p><input name="txtcaption" type="text" class="input" <?php if($row->img_title){ ?> value="<?php echo $row->img_title; ?>" <?php } else { ?> placeholder="Caption Here" <?php } ?> onchange="editcapt2(this.value,'<?php echo $row->id; ?>','<?php echo $_SESSION['root_id']; ?>','<?php echo $user_id; ?>','<?php echo $document_id; ?>')" /></p>
                    	                </div>
                    	                <div class="col-sm-3">
                    	                    <a class="dbtn" href="javascript:delpic2('<?php echo $row->id; ?>','<?php echo $_SESSION['root_id']; ?>','<?php echo $user_id; ?>','<?php echo $document_id; ?>')"><span>Delete</span></a>
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
    <button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>

<form action="<?php echo site_url().'image_add/e_add_document';?>" method="post" enctype="multipart/form-data" name="UploadForm2" id="UploadForm2">
 <input type="hidden" name="root_id" value="<?php echo $_SESSION['root_id']; ?>"/>
 <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"/>
 <input type="hidden" name="employee_id" value="<?php echo $d_employee_id; ?>"/>
 <input type="hidden" name="document_id" value="<?php echo $document_id; ?>"/>
 <input name="userfile2[]" id="userfile2" type="file" style="display:none" multiple accept="image/x-png, image/gif, image/jpeg, .pdf,.doc,.docx,.zip,.xlsx,.csv,.xls" onChange="actfunc2()"  required />
 <input type="submit"  id="SubmitButton2" value="Upload" style="display:none;"/>
</form>

<script type="text/javascript">
$(document).ready(function(){			
	// On page load: table_contacts
	var xin_table_document = $('#xin_table_document').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/documents") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	// Date
	$('.e_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1900:' + (new Date().getFullYear() + 10),
	});
			
	/* Update document info */
	$("#e_document_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 9);
		fd.append("type", 'e_document_info');
		fd.append("data", 'e_document_info');
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
					$('.edit-modal-data').modal('toggle');
					xin_table_document.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
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
       console.log("kjue");
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
	xmlhttp.open("GET","<?php echo site_url(); ?>image_add/e_edit_caption?id="+id+"&cpt="+capt+"&root_id="+root_id+"&user_id="+user_id+"&document_id="+document_id,true);
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
    	xmlhttp.open("GET","<?php echo site_url(); ?>image_add/delpic_action?img_id="+img_id+"&root_id="+root_id+"&user_id="+user_id+"&document_id="+document_id,true);
    	xmlhttp.send();
    }
	
}
</script>
<?php }else if(isset($_GET['jd']) && $_GET['data']=='view_document' && isset($_GET['document_id']) ){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Document</h4>
</div>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="relation"><?php echo $this->lang->line('xin_e_details_dtype');?></label>
          <select readonly name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
            <option value=""></option>
            <?php foreach($all_document_types as $document_type) {?>
            <option value="<?php echo $document_type->document_type_id;?>" <?php if($document_type->document_type_id==$document_type_id) {?> selected="selected" <?php } ?>> <?php echo $document_type->document_type;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="date_of_expiry" class="control-label"><?php echo $this->lang->line('xin_e_details_doe');?></label>
          <input class="form-control e_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_doe');?>" name="date_of_expiry" type="text" value="<?php echo $date_of_expiry;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title" class="control-label"><?php echo $this->lang->line('xin_e_details_dtitle');?></label>
          <input class="form-control" readonly placeholder="<?php echo $this->lang->line('xin_e_details_dtitle');?>" name="title" type="text" value="<?php echo $title;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="send_mail"><?php echo $this->lang->line('xin_e_details_send_notifyemail');?></label>
          <select name="send_mail" id="send_mail" class="form-control" data-plugin="select_hrm" readonly>
            <option value="1" <?php if($is_alert=='1') {?> selected="selected" <?php } ?>><?php echo $this->lang->line('xin_yes');?></option>
            <option value="2" <?php if($is_alert=='2') {?> selected="selected" <?php } ?>><?php echo $this->lang->line('xin_no');?></option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="description" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" readonly placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
    
    
    <div class="row">
            
            <div class="col-sm-12">
                        
                        <div class="box-body table-responsive">
        					
        					<div id="output2">
        					
        					<?php
        	                foreach($all_files as $row) {
        					    $user_id = $row->employee_id;            
            	                $ximage=$row->img_name;
            	                
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
                    	                    <a target="blank" href="<?php echo site_url(); ?>uploads/document/<?php echo $ximage; ?>"><img src="<?php echo site_url(); ?>skin/icons/<?php echo $imgico; ?>" height="50" title="<?=$row->img_list_name; ?>"/></a>
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
    <button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>



<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='e_imgdocument' && $_GET['type']=='e_imgdocument'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Immigration</h4>
</div>
<form id="e_imgdocument_info" action="<?php echo site_url("employees/e_immigration_info") ?>" enctype="multipart/form-data" name="e_imgdocument_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $d_employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $immigration_id;?>">
  <input type="hidden" name="u_document_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="relation">Document</label>
          <select name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
            <option value=""></option>
            <?php foreach($all_document_types as $document_type) {?>
            <option value="<?php echo $document_type->document_type_id;?>" <?php if($document_type->document_type_id==$document_type_id) {?> selected="selected" <?php } ?>> <?php echo $document_type->document_type;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="document_number" class="control-label">Document Number</label>
          <input class="form-control" placeholder="Document Number" name="document_number" type="text" value="<?php echo $document_number;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="issue_date" class="control-label">Issue Date</label>
          <input class="form-control e_date" readonly="readonly" placeholder="Issue Date" name="issue_date" type="text" value="<?php echo $issue_date;?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="expiry_date" class="control-label">Date of Expiry</label>
          <input class="form-control e_date" readonly="readonly" placeholder="Expiry Date" name="expiry_date" type="text" value="<?php echo $expiry_date;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <h6><?php echo $this->lang->line('xin_e_details_document_file');?></h6>
          <span class="btn btn-primary btn-file"> Browse
          <input type="file" name="document_file" id="p_file2">
          </span><br />
          <small><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
          <?php if($document_file!='' && $document_file!='no file') {?>
          <br />
          <a href="<?php echo site_url();?>download?type=document/immigration&filename=<?php echo $document_file;?>"><?php echo $document_file;?></a>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="eligible_review_date" class="control-label">Eligible Review Date</label>
          <input class="form-control e_date" readonly="readonly" placeholder="Issue Date" name="eligible_review_date" type="text" value="<?php echo $eligible_review_date;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="send_mail">Country</label>
          <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="Country">
            <option value="">Select One</option>
            <?php foreach($all_countries as $scountry) {?>
            <option value="<?php echo $scountry->country_id;?>" <?php if($scountry->country_id==$country_id) {?> selected="selected" <?php } ?>> <?php echo $scountry->country_name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="text-right">
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load: table_contacts
	var xin_table_immigration = $('#xin_table_imgdocument').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/immigration") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	// Date
	$('.e_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1900:' + (new Date().getFullYear() + 10),
	});
			
	/* Update document info */
	$("#e_imgdocument_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 9);
		fd.append("type", 'e_immigration_info');
		fd.append("data", 'e_immigration_info');
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
					$('.edit-modal-data').modal('toggle');
					xin_table_immigration.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
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
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_qualification' && $_GET['type']=='emp_qualification'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_qualification');?></h4>
</div>
<form id="e_qualification_info" action="<?php echo site_url("employees/e_qualification_info") ?>" name="e_qualification_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $qualification_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_e_details_inst_name');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_inst_name');?>" name="name" type="text" value="<?php echo $name;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="education_level" class="control-label"><?php echo $this->lang->line('xin_e_details_edu_level');?></label>
          <select class="form-control" name="education_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_edu_level');?>">
            <?php foreach($all_education_level as $education_level) {?>
            <option value="<?php echo $education_level->education_level_id;?>" <?php if($education_level->education_level_id==$education_level_id) {?> selected="selected" <?php } ?>> <?php echo $education_level->name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
          <div class="row">
            <div class="col-md-6">
              <input class="form-control edate" readonly="readonly" value="<?php echo $from_year;?>" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_year" type="text">
            </div>
            <div class="col-md-6">
              <input class="form-control edate" readonly="readonly" value="<?php echo $to_year;?>" placeholder="<?php echo $this->lang->line('dashboard_to');?>" name="to_year" type="text">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="language" class="control-label"><?php echo $this->lang->line('xin_e_details_language');?></label>
          <select class="form-control" name="language" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_language');?>">
            <?php foreach($all_qualification_language as $qualification_language) {?>
            <option value="<?php echo $qualification_language->language_id;?>" <?php if($qualification_language->language_id==$language_id) {?> selected="selected" <?php } ?>> <?php echo $qualification_language->name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="skill" class="control-label"><?php echo $this->lang->line('xin_e_details_skill');?></label>
          <select class="form-control" name="skill" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_skill');?>">
            <?php foreach($all_qualification_skill as $qualification_skill) {?>
            <option value="<?php echo $qualification_skill->skill_id?>" <?php if($qualification_skill->skill_id==$skill_id) {?> selected="selected" <?php } ?>><?php echo $qualification_skill->name?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="to_year" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load: table_contacts
	var xin_table_qualification = $('#xin_table_qualification').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/qualification") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	$('.edate').datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat:'yy-mm-dd',
	yearRange: '1900:' + (new Date().getFullYear() + 15),
	beforeShow: function(input) {
		$(input).datepicker("widget").show();
	}
	});
			
	/* Update qualification info */
	$("#e_qualification_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&data=e_qualification_info&type=e_qualification_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_qualification.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_work_experience' && $_GET['type']=='emp_work_experience'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_wexp');?></h4>
</div>
<form id="e_work_experience_info" action="<?php echo site_url("employees/e_work_experience_info") ?>" name="e_work_experience_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $work_experience_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="company_name"><?php echo $this->lang->line('xin_company_name');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name');?>" name="company_name" type="text" value="<?php echo $company_name;?>" id="company_name">
        </div>
        <div class="form-group">
          <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?></label>
          <input type="text" class="form-control edate" id="e_from_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" readonly value="<?php echo $from_date;?>">
        </div>
        <div class="form-group">
          <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
          <input type="text" class="form-control edate" id="e_to_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" readonly value="<?php echo $to_date;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="post"><?php echo $this->lang->line('xin_e_details_post');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_post');?>" name="post" type="text" value="<?php echo $post;?>" id="post">
        </div>
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="4" id="description"><?php echo $description;?></textarea>
          <span class="countdown"></span> </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load: table_contacts
	var xin_table_work_experience = $('#xin_table_work_experience').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/experience") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	$('.edate').datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat:'yy-mm-dd',
	yearRange: '1900:' + (new Date().getFullYear() + 15),
	beforeShow: function(input) {
		$(input).datepicker("widget").show();
	}
	});
			
	/* Update work experience info */
	$("#e_work_experience_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=14&data=e_work_experience_info&type=e_work_experience_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_work_experience.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_bank_account' && $_GET['type']=='emp_bank_account'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_baccount');?></h4>
</div>
<form id="e_bank_account_info" action="<?php echo site_url("employees/e_bank_account_info") ?>" name="e_bank_account_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $bankaccount_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="account_title"><?php echo $this->lang->line('xin_e_details_acc_title');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_title');?>" name="account_title" type="text" value="<?php echo $account_title;?>" id="account_name">
        </div>
        <div class="form-group">
          <label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number');?>" name="account_number" type="text" value="<?php echo $account_number;?>" id="account_number">
        </div>
          <div class="form-group">
              <label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch');?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch');?>" name="bank_branch" type="text" value="<?php echo $bank_branch;?>" id="bank_branch">
          </div>
          <div class="form-group">
              <label>
                  <input type="checkbox" <?php if($is_primary) echo "checked";?> value="1" name="is_primary"> Make Account Primary </label>
          </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name');?>" name="bank_name" type="text" value="<?php echo $bank_name;?>" id="bank_name">
        </div>
        <div class="form-group">
          <label for="bank_code"><?php echo $this->lang->line('xin_e_details_bank_code');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_code');?>" name="bank_code" type="text" value="<?php echo $bank_code;?>" id="bank_code">
        </div>
          <div class="form-group">
              <label for="bank_branch">IBAN Number</label>
              <input class="form-control" placeholder="IBAN" name="iban" type="text" value="<?php echo $iban;?>" id="bank_branch">
          </div>
      </div>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load:
	var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/bank_account") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
			
	/* Update bank acount info */
	$("#e_bank_account_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=17&data=e_bank_account_info&type=e_bank_account_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_bank_account.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_contract' && $_GET['type']=='emp_contract'){?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_contract');?></h4>
</div>
<form id="e_contract_info" action="<?php echo site_url("employees/e_contract_info") ?>" name="e_contract_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $contract_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="contract_type_id" class=""><?php echo $this->lang->line('xin_e_details_contract_type');?></label>
          <select class="form-control" name="contract_type_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <?php foreach($all_contract_types as $contract_type) {?>
            <option value="<?php echo $contract_type->contract_type_id;?>" <?php if($contract_type->contract_type_id==$contract_type_id) {?> selected="selected" <?php } ?>> <?php echo $contract_type->name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label class="" for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?></label>
          <input type="text" class="form-control e_cont_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" readonly value="<?php echo $from_date;?>">
        </div>
        <div class="form-group">
          <label for="designation_id" class=""><?php echo $this->lang->line('dashboard_designation');?></label>
          <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <?php foreach($all_designations as $designation) {?>
            <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="title" class=""><?php echo $this->lang->line('xin_e_details_contract_title');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_contract_title');?>" name="title" type="text" value="<?php echo $title;?>" id="title">
        </div>
        <div class="form-group">
          <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
          <input type="text" class="form-control e_cont_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" readonly value="<?php echo $to_date;?>">
        </div>
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"><?php echo $description;?></textarea>
          <span class="countdown"></span> </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load:
	var xin_table_contract = $('#xin_table_contract').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/contract") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });	
	// Date
	$('.e_cont_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1950:' + new Date().getFullYear()
	});
			
	/* Update bank acount info */
	$("#e_contract_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=20&data=e_contract_info&type=e_contract_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_contract.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_leave' && $_GET['type']=='emp_leave'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_leave');?></h4>
</div>
<form id="e_leave_info" action="<?php echo site_url("employees/e_leave_info") ?>" name="e_leave_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $leave_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label for="casual_leave" class="control-label"><?php echo $this->lang->line('xin_e_details_casual_leave');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_casual_leave');?>" name="casual_leave" type="text" value="<?php echo $casual_leave;?>">
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <label for="medical_leave" class="control-label"><?php echo $this->lang->line('xin_e_details_medical_leave');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_medical_leave');?>" name="medical_leave" type="text" value="<?php echo $medical_leave;?>">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load:
	var xin_table_leave = $('#xin_table_leave').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/leave") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });	
			
	/* Update leave info */
	$("#e_leave_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=23&data=e_leave_info&type=e_leave_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_leave.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_shift' && $_GET['type']=='emp_shift'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_e_details_edit_shift');?></h4>
</div>
<form id="e_shift_info" action="<?php echo site_url("employees/e_shift_info") ?>" name="e_shift_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $emp_shift_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?></label>
          <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" name="from_date" type="text" value="<?php echo $from_date;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="to_date" class="control-label"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
          <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" name="to_date" type="text" value="<?php echo $to_date;?>">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load:
	var xin_table_shift = $('#xin_table_shift').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/shift") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	// Date
	$('.es_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1950:' + new Date().getFullYear()
	});
			
	/* Update leave info */
	$("#e_shift_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=26&data=e_shift_info&type=e_shift_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_shift.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_location' && $_GET['type']=='emp_location'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_location');?></h4>
</div>
<form id="e_location_info" action="<?php echo site_url("employees/e_location_info") ?>" name="e_location_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $office_location_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?></label>
          <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" name="from_date" type="text" value="<?php echo $from_date;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="to_date" class="control-label"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
          <input class="form-control es_date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" name="to_date" type="text" value="<?php echo $to_date;?>">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){			
	// On page load:
	var xin_table_location = $('#xin_table_location').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employees/location") ?>/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// Date
	$('.es_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1950:' + new Date().getFullYear()
	});
			
	/* Update location info */
	$("#e_location_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=29&data=e_location_info&type=e_location_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_location.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php }
?>
