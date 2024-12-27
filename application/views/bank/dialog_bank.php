<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['bank_id']) && $_GET['data']=='bank'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"> Edit Bank</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("bank/update").'/'.$bank_id; ?>" method="post" name="edit_bank" id="edit_bank">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $bank_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $bank_name;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="company_name"><?php echo $this->lang->line('xin_edit_company');?></label>
          <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_edit_company');?>">
            <option value=""><?php echo $this->lang->line('xin_edit_company');?></option>
            <?php foreach($all_companies as $company) {?>
            <option value="<?php echo $company->company_id;?>" <?php if($company_id==$company->company_id):?> selected <?php endif;?>> <?php echo $company->name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="name">Bank Name</label>
          <input class="form-control" placeholder="Bank Name" name="bank_name" type="text" value="<?php echo $bank_name;?>">
        </div>
        <div class="form-group">
          <label for="email">Account Name</label>
          <input class="form-control" placeholder="Account Name" name="account_name" type="text" value="<?php echo $account_name;?>">
        </div>
      </div>
      
      <div class="col-sm-6">
                  
                  <div class="form-group">
                    <label for="phone">Account Number</label>
                   	 <input class="form-control" placeholder="Account Number" name="account_number" type="text" value="<?php echo $account_number;?>">
                  </div>
                  
                  <div class="form-group">
                    
                    <div class="row">
                      <div class="col-md-6">
                      	<label for="xin_faxn">IBAN</label>
                    	<input class="form-control" placeholder="IBAN" name="iban" type="text" value="<?php echo $iban;?>">
                      </div>
                      
                      <div class="col-md-6">
                        <label for="phone">Swift Code</label>
                   	 <input class="form-control" placeholder="Swift Code" name="swift_code" type="text" value="<?php echo $swift_code;?>">
                      </div>
                      
                    </div>
                    
                  </div>
                  
                    <div class="form-group">
                    <label for="phone">Address</label>
                   	 <input class="form-control" placeholder="Address" name="address" type="text" value="<?php echo $address;?>">
                  </div>
                  
                </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("bank/bank_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 

		/* Edit data */
		$("#edit_bank").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=bank&form="+action,
				cache: false,
				success: function (JSON) {
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
				}
			});
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['bank_id']) && $_GET['data']=='view_bank'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Bank</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="company_name"><?php echo $this->lang->line('xin_edit_company');?></label>
          <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_edit_company');?>" readonly>
            <option value=""><?php echo $this->lang->line('xin_edit_company');?></option>
            <?php foreach($all_companies as $company) {?>
            <option value="<?php echo $company->company_id;?>" <?php if($company_id==$company->company_id):?> selected <?php endif;?>> <?php echo $company->name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="name">Bank Name</label>
          <input class="form-control" placeholder="Bank Name" name="bank_name" type="text" value="<?php echo $bank_name;?>" readonly>
        </div>
        <div class="form-group">
          <label for="email">Account Name</label>
          <input class="form-control" placeholder="Account Name" name="account_name" type="text" value="<?php echo $account_name;?>" readonly>
        </div>
      </div>
      
      <div class="col-sm-6">
                  
                  <div class="form-group">
                    <label for="phone">Account Number</label>
                   	 <input class="form-control" placeholder="Account Number" name="account_number" type="text" value="<?php echo $account_number;?>" readonly>
                  </div>
                  
                  <div class="form-group">
                    
                    <div class="row">
                      <div class="col-md-6">
                      	<label for="xin_faxn">IBAN</label>
                    	<input class="form-control" placeholder="IBAN" name="iban" type="text" value="<?php echo $iban;?>" readonly>
                      </div>
                      
                      <div class="col-md-6">
                        <label for="phone">Swift Code</label>
                   	 <input class="form-control" placeholder="Swift Code" name="swift_code" type="text" value="<?php echo $swift_code;?>" readonly>
                      </div>
                      
                    </div>
                    
                  </div>
                  
                    <div class="form-group">
                    <label for="phone">Address</label>
                   	 <input class="form-control" placeholder="Address" name="address" type="text" value="<?php echo $address;?>" readonly>
                  </div>
                  
                </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
</form>
<?php }
?>
