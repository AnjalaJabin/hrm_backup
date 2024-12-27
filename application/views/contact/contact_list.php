<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong><?php echo $this->lang->line('xin_add_new');?></strong> Contact
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> <?php echo $this->lang->line('xin_hide');?></button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form method="post" name="add_contact" id="xin-form" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        <div class="bg-white">
          <div class="box-block">
            <div class="row">
                  
                  <div class="row">
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <div style="width:100px; height:100px;"> <a href="javascript:showfunc()"> <img src="<?php echo site_url(); ?>uploads/profile/default_male.jpg" width="100" id="user_photo"/> </a> </div>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="name">Full Name</label>
                          <input class="form-control" placeholder="Full Name" name="name" type="text">
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="company_name">Company</label>
                          <input class="form-control" placeholder="Company" name="company" type="text">
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="job_title">Job title</label>
                          <input class="form-control" placeholder="Job title" name="job_title" type="text">
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input class="form-control" placeholder="Email" name="email" type="email">
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="share">Share Public</label>
                            <div class="checkbox">
                                <label> <input class="form-control" name="share_public" type="checkbox" value="1"> If you are sharing this as public all employees can see this contact</label>
                            </div>
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group" id="group_ajax">
                          <label for="email">Contact Group</label>
                          <select class="form-control" name="contact_group" data-placeholder="Contact Group">
                            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                            <?php foreach($get_contact_group as $group) {?>
                            <option value="<?php echo $group->id;?>"> <?php echo $group->name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                        
                        <a class="btn btn-sm btn-primary pull-right text-white" id="group_add_btn"><i class="fa fa-plus icon"></i>New</a>
                        <div style="background:#ddd; padding:7px; display:none;" id="group_add_div">
                            <div class="input-group">
                               <input type="text" class="form-control" placeholder="Group Name" id="group_add_val">
                               <span class="input-group-btn">
                                    <button class="btn btn-success" type="button" id="group_sub_btn">Add</button>
                               </span>
                               <span class="input-group-btn">
                                    <button class="btn" type="button" id="group_close_btn">X</button>
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
                                    <select class="form-control" style="width:35%;" name="label1">
                                        <option value="Mobile">Mobile</option>
                                        <option value="Office">Office</option>
                                        <option value="Residence">Residence</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:65%;" name="phone1">
                                  </div>
                              </div>
                              
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:35%;" name="label2">
                                        <option value="Mobile">Mobile</option>
                                        <option value="Office">Office</option>
                                        <option value="Residence">Residence</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:65%;" name="phone2">
                                  </div>
                              </div>
                            </div>
                              
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:35%;" name="label3">
                                        <option value="Mobile">Mobile</option>
                                        <option value="Office">Office</option>
                                        <option value="Residence">Residence</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:65%;" name="phone3">
                                  </div>
                              </div>
                              
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:35%;" name="label4">
                                        <option value="Mobile">Mobile</option>
                                        <option value="Office">Office</option>
                                        <option value="Residence">Residence</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:65%;" name="phone4">
                                  </div>
                              </div>
                            </div>
                              
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:35%;" name="label5">
                                        <option value="Mobile">Mobile</option>
                                        <option value="Office">Office</option>
                                        <option value="Residence">Residence</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:65%;" name="phone5">
                                  </div>
                              </div>
                              
                              <div class="col-md-6">
                                  <div class="input-group my-group"> 
                                    <select class="form-control" style="width:35%;" name="label6">
                                        <option value="Mobile">Mobile</option>
                                        <option value="Office">Office</option>
                                        <option value="Residence">Residence</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Fax">Fax</option>
                                    </select> 
                                    <input type="text" class="form-control" placeholder="Type Here" style="width:65%;" name="phone6">
                                  </div>
                              </div>
                            </div>
                          
                          </div>
                      </div>
                      
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label for="address">Address</label>
                              <textarea class="form-control" name="address" placeholder="Address"></textarea>
                          </div>
                          
                          <div class="form-group">
                              <label for="notes">Notes</label>
                              <textarea class="form-control" name="note" placeholder="Notes"></textarea>
                          </div>
                      </div>
                      
                      <input type="file" name="photo" id="photo" style="display:none" accept="image/x-png, image/jpeg">
                      
                  </div>
                
                
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
            </div>
          </div>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong><?php echo $this->lang->line('xin_list_all');?></strong> Contacts
    <div class="add-record-btn">
      <a class="btn btn-sm btn-danger" href="<?php echo site_url('contact/import_google'); ?>"><i class="fa fa-google-plus icon"></i> Import from Google </a>
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> <?php echo $this->lang->line('xin_add_new');?></button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th><?php echo $this->lang->line('xin_action');?></th>
          <th>Name</th>
          <th></th>
          <th>Group</th>
          <th><?php echo $this->lang->line('xin_email');?></th>
          <th>Company</th>
          <th><?php echo $this->lang->line('xin_added_by');?></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<style>
   .modal-lg {
    max-width: 970px;
}
</style>
</style>
