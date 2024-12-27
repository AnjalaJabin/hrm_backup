
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;" >
  <div class="box box-block bg-white">
    <h2><strong><?php echo $this->lang->line('xin_add_new');?></strong> Bank
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> <?php echo $this->lang->line('xin_hide');?></button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form class="m-b-1 add" method="post" name="add_bank" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                    <select class="form-control" name="company_id" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="name">Bank Name</label>
                    <input class="form-control" placeholder="Bank Name" name="bank_name" type="text">
                  </div>
                  <div class="form-group">
                    <label for="email">Account Name</label>
                    <input class="form-control" placeholder="Account Name" name="account_name" type="text">
                  </div>
                  
                </div>
                <div class="col-sm-6">
                  
                  <div class="form-group">
                    <label for="phone">Account Number</label>
                   	 <input class="form-control" placeholder="Account Number" name="account_number" type="text">
                  </div>
                  
                  <div class="form-group">
                    
                    <div class="row">
                      <div class="col-md-6">
                      	<label for="xin_faxn">IBAN</label>
                    	<input class="form-control" placeholder="IBAN" name="iban" type="text">
                      </div>
                      
                      <div class="col-md-6">
                        <label for="phone">Swift Code</label>
                   	 <input class="form-control" placeholder="Swift Code" name="swift_code" type="text">
                      </div>
                      
                    </div>
                    
                  </div>
                  
                    <div class="form-group">
                    <label for="phone">Address</label>
                   	 <input class="form-control" placeholder="Address" name="address" type="text">
                  </div>
                  
                  <div class="text-right">
                    <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
                  </div>
                  
                </div>
              </div>
              
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong><?php echo $this->lang->line('xin_list_all');?></strong> Banks
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> <?php echo $this->lang->line('xin_add_new');?></button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
        <tr>
          <th><?php echo $this->lang->line('xin_action');?></th>
          <th>Bank Name</th>
          <th>Account Name</th>
          <th>Account Number</th>
          <th>Company</th>
        </tr>
          </tr>
        
      </thead>
    </table>
  </div>
</div>
