<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['contact_id']) && $_GET['data']=='contact'){
    
$user_id = $this->session->userdata('user_id');
$get_contact_group = $this->Contact_model->get_contact_group($user_id);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="icon-pencil7"></i> Edit Contact</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("contact/update").'/'.$contact_id; ?>" method="post" name="edit_contact" enctype="multipart/form-data" id="edit_contact">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['contact_id'];?>">
  <input type="hidden" name="ext_name" value="<?php echo $name;?>">
  <div class="modal-body">
                  
                  <div class="row">
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <?php
                          if(!empty($photo))
                          {
                              $photo_url = site_url().'uploads/contacts/'.$photo;
                          }
                          else
                          {
                              $photo_url = site_url().'uploads/profile/default_male.jpg';
                          }
                          ?>
                          <div style="width:100px; height:100px;"> <a href="javascript:showfunc2()"> <img src="<?php echo $photo_url; ?>" width="100" id="user_photo2"/> </a> </div>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="name">Full Name</label>
                          <input class="form-control" placeholder="Full Name" name="name" type="text" value="<?php echo $name;?>">
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="company_name">Company</label>
                          <input class="form-control" placeholder="Company" name="company" type="text" value="<?php echo $company;?>">
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="job_title">Job title</label>
                          <input class="form-control" placeholder="Job title" name="job_title" type="text" value="<?php echo $job_title;?>">
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input class="form-control" placeholder="Email" name="email" type="email" value="<?php echo $email;?>">
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="share">Share Public</label>
                            <div class="checkbox">
                                <label> <input class="form-control" <?php if($share_public==1){ echo 'checked="checked"'; } ?> name="share_public" type="checkbox" value="1"> If you are sharing this as public all employees can see this contact</label>
                            </div>
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group" id="group_ajax2">
                          <label for="email">Contact Group</label>
                          <select class="form-control" name="contact_group" data-placeholder="Contact Group">
                            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                            <?php foreach($get_contact_group as $group) {?>
                            <option <?php if($contact_group==$group->id){ echo 'selected'; } ?> value="<?php echo $group->id;?>"> <?php echo $group->name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                        
                        <a class="btn btn-sm btn-primary pull-right text-white" id="group_add_btn2"><i class="fa fa-plus icon"></i>New</a>
                        <div style="background:#ddd; padding:7px; display:none;" id="group_add_div2">
                            <div class="input-group">
                               <input type="text" class="form-control" placeholder="Group Name" id="group_add_val2">
                               <span class="input-group-btn">
                                    <button class="btn btn-success" type="button" id="group_sub_btn2">Add</button>
                               </span>
                               <span class="input-group-btn">
                                    <button class="btn" type="button" id="group_close_btn2">X</button>
                               </span>
                            </div>
                        </div>
                        
                      </div>
                  
                  </div>
                  
                  <div class="row">
                      <div class="col-sm-6">
                          
                          <div class="form-group">
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:40%;" name="label1">
                                        <option <? if($label1=='Mobile'){ echo 'selected'; } ?> value="Mobile">Mobile</option>
                                        <option <? if($label1=='Office'){ echo 'selected'; } ?> value="Office">Office</option>
                                        <option <? if($label1=='Residence'){ echo 'selected'; } ?> value="Residence">Residence</option>
                                        <option <? if($label1=='Whatsapp'){ echo 'selected'; } ?> value="Whatsapp">Whatsapp</option>
                                        <option <? if($label1=='Fax'){ echo 'selected'; } ?> value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:60%;" name="phone1" value="<?php echo $phone1;?>">
                                  </div>
                              </div>
                              
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:40%;" name="label2">
                                        <option <? if($label2=='Mobile'){ echo 'selected'; } ?> value="Mobile">Mobile</option>
                                        <option <? if($label2=='Office'){ echo 'selected'; } ?> value="Office">Office</option>
                                        <option <? if($label2=='Residence'){ echo 'selected'; } ?> value="Residence">Residence</option>
                                        <option <? if($label2=='Whatsapp'){ echo 'selected'; } ?> value="Whatsapp">Whatsapp</option>
                                        <option <? if($label2=='Fax'){ echo 'selected'; } ?> value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:60%;" name="phone2" value="<?php echo $phone2;?>">
                                  </div>
                              </div>
                            </div>
                              
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:40%;" name="label3">
                                        <option <? if($label3=='Mobile'){ echo 'selected'; } ?> value="Mobile">Mobile</option>
                                        <option <? if($label3=='Office'){ echo 'selected'; } ?> value="Office">Office</option>
                                        <option <? if($label3=='Residence'){ echo 'selected'; } ?> value="Residence">Residence</option>
                                        <option <? if($label3=='Whatsapp'){ echo 'selected'; } ?> value="Whatsapp">Whatsapp</option>
                                        <option <? if($label3=='Fax'){ echo 'selected'; } ?> value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:60%;" name="phone3" value="<?php echo $phone3;?>">
                                  </div>
                              </div>
                              
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:40%;" name="label4">
                                        <option <? if($label4=='Mobile'){ echo 'selected'; } ?> value="Mobile">Mobile</option>
                                        <option <? if($label4=='Office'){ echo 'selected'; } ?> value="Office">Office</option>
                                        <option <? if($label4=='Residence'){ echo 'selected'; } ?> value="Residence">Residence</option>
                                        <option <? if($label4=='Whatsapp'){ echo 'selected'; } ?> value="Whatsapp">Whatsapp</option>
                                        <option <? if($label4=='Fax'){ echo 'selected'; } ?> value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:60%;" name="phone4" value="<?php echo $phone4;?>">
                                  </div>
                              </div>
                            </div>
                              
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:40%;" name="label5">
                                        <option <? if($label5=='Mobile'){ echo 'selected'; } ?> value="Mobile">Mobile</option>
                                        <option <? if($label5=='Office'){ echo 'selected'; } ?> value="Office">Office</option>
                                        <option <? if($label5=='Residence'){ echo 'selected'; } ?> value="Residence">Residence</option>
                                        <option <? if($label5=='Whatsapp'){ echo 'selected'; } ?> value="Whatsapp">Whatsapp</option>
                                        <option <? if($label5=='Fax'){ echo 'selected'; } ?> value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:60%;" name="phone5" value="<?php echo $phone5;?>">
                                  </div>
                              </div>
                              
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:40%;" name="label6">
                                        <option <? if($label6=='Mobile'){ echo 'selected'; } ?> value="Mobile">Mobile</option>
                                        <option <? if($label6=='Office'){ echo 'selected'; } ?> value="Office">Office</option>
                                        <option <? if($label6=='Residence'){ echo 'selected'; } ?> value="Residence">Residence</option>
                                        <option <? if($label6=='Whatsapp'){ echo 'selected'; } ?> value="Whatsapp">Whatsapp</option>
                                        <option <? if($label6=='Fax'){ echo 'selected'; } ?> value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:60%;" name="phone6" value="<?php echo $phone5;?>">
                                  </div>
                              </div>
                            </div>
                          
                          </div>
                      </div>
                      
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label for="address">Address</label>
                              <textarea class="form-control" name="address" placeholder="Address"><?php echo $address;?></textarea>
                          </div>
                          
                          <div class="form-group"> 
                              <label for="notes">Notes</label>
                              <textarea class="form-control" name="note" placeholder="Notes"><?php echo $note;?></textarea>
                          </div>
                      </div>
                      
                      <input type="file" name="photo" id="photo2" style="display:none" accept="image/x-png, image/jpeg">
                      
                  </div>
                
                
            </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#user_photo2').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
    
    function showfunc2()
    {
        $('#photo2').click();
    }
    
    $("#photo2").change(function(){
        readURL(this);
    });

 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("contact/contact_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 

		/* Edit data */
		$("#edit_contact").submit(function(e){
			var fd = new FormData(this);
			var obj = $(this), action = obj.attr('name');
			fd.append("is_ajax", 2);
			fd.append("edit_type", 'contact');
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
	
	
	$(document).on( "click", "#group_add_btn2", function() {
    $('#group_add_div2').show();
    $('#group_add_btn2').hide();
});

$(document).on( "click", "#group_close_btn2", function() {
    $('#group_add_div2').hide();
    $('#group_add_btn2').show();
});

$(document).on( "click", "#group_sub_btn2", function() {
    var group_val = $('#group_add_val2').val();
    if(group_val==='')
    {
        toastr.error('Type Group Name');
    }
    else
    {
        
        var group_name = $('#group_add_val2').val();
        $.ajax({
			type: "POST",
			url: base_url+'/add_group/',
			data: { name:group_name, is_ajax:'1', add_type:'group'},
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					toastr.success(JSON.result);
					$('#group_add_div2').hide();
                    $('#group_add_btn2').show();
                    $('#group_add_val2').val('');
				}
			}
		});
		
		jQuery.get(base_url+"/group_list/", function(data, status){
    		jQuery('#group_ajax2').html(data);
    	});
           
    }
});
  </script>
<?php } else if(isset($_GET['jd']) && $_GET['data']=='view_contact' && isset($_GET['contact_id']) ){
if(empty($label1))
{
    $label1 = 'Mobile';
}
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="icon-pencil7"></i> Contact Details</h4>
</div>

  <div class="modal-body">
                  
                  <div class="row">
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <?php
                          if(!empty($photo))
                          {
                              $photo_url = site_url().'uploads/contacts/'.$photo;
                          }
                          else
                          {
                              $photo_url = site_url().'uploads/profile/default_male.jpg';
                          }
                          ?>
                          <div style="width:100px; height:100px;"> <img src="<?php echo $photo_url; ?>" width="100" /> </div>
                        </div>
                      </div>
                      
                <div class="col-sm-10">
                   <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="name">Full Name : <?php echo $name;?></label>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="company_name">Company : <?php echo $company;?></label>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="job_title">Job title : <?php echo $job_title;?></label>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="email">Email : <?php echo $email;?></label>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="share"><?php if($share_public==1){ echo 'Public Contact'; } ?></label>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="share">Group : <?php echo $contact_group_name; ?></label>
                        </div>
                      </div>
                  
                  </div>
                  
                  <div class="row">
                        <div class="col-md-8">      
                            <div class="row">
                              <div class="col-md-6">
                                  <label for="phone"><?php if(!empty($phone1)){ echo $label1.' : '.$phone1; } ?></label>
                              </div>
                              
                              <div class="col-md-6">
                                  <label for="phone"><?php if(!empty($phone2)){ echo $label2.' : '.$phone2; } ?></label>
                              </div>
                            </div>
                              
                            <div class="row">
                              <div class="col-md-6">
                                  <label for="phone"><?php if(!empty($phone3)){ echo $label3.' : '.$phone3; } ?></label>
                              </div>
                              
                              <div class="col-md-6">
                                  <label for="phone"><?php if(!empty($phone4)){ echo $label4.' : '.$phone4; } ?></label>
                              </div>
                            </div>
                              
                            <div class="row">
                              <div class="col-md-6">
                                  <label for="phone"><?php if(!empty($phone5)){ echo $label5.' : '.$phone5; } ?></label>
                              </div>
                              
                              <div class="col-md-6">
                                  <label for="phone"><?php if(!empty($phone6)){ echo $label6.' : '.$phone6; } ?></label>
                              </div>
                            </div>
                        </div>  
                      
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label for="address">Address : <?php echo nl2br($address) ;?></label>
                          </div>
                          
                          <div class="form-group">
                              <label for="notes">Notes : <?php echo nl2br($note);?></label>
                          </div>
                      </div>
                      
                      
                      </div>
                    </div>
                  </div>
                
                
            </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
<?php }
?>
