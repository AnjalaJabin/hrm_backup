<?php
/* Constants view
*/
?> 
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-3">
    <div class="box bg-white">
      <ul class="nav nav-4">
        <li class="nav-item nav-item-link active-link" id="config_8"> <a class="nav-link nav-tabs-link" href="#contract" data-config="8" data-config-block="contract" data-toggle="tab" aria-expanded="true"> <i class="fa fa-pencil"></i> Contract Type </a> </li>
        <li class="nav-item nav-item-link" id="config_7"> <a class="nav-link nav-tabs-link" href="#qualification" data-config="7" data-config-block="qualification" data-toggle="tab" aria-expanded="true"> <i class="fa fa-coffee"></i> Qualification </a> </li>
        <li class="nav-item nav-item-link" id="config_9"> <a class="nav-link nav-tabs-link" href="#document_type" data-config="9" data-config-block="document_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-file"></i> Document Type</a> </li>
<!--        <li class="nav-item nav-item-link" id="config_10"> <a class="nav-link nav-tabs-link" href="#award_type" data-config="10" data-config-block="award_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-trophy"></i> Award Type</a> </li>-->
        <li class="nav-item nav-item-link" id="config_11"> <a class="nav-link nav-tabs-link" href="#leave_type" data-config="11" data-config-block="leave_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-plane"></i> Leave Type</a> </li>
<!--        <li class="nav-item nav-item-link" id="config_12"> <a class="nav-link nav-tabs-link" href="#warning_type" data-config="12" data-config-block="warning_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-exclamation-triangle"></i> Warning Type</a> </li>-->
        <li class="nav-item nav-item-link" id="config_13"> <a class="nav-link nav-tabs-link" href="#termination_type" data-config="13" data-config-block="termination_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-remove"></i> Termination Type </a> </li>
         <li class="nav-item nav-item-link" id="config_17"> <a class="nav-link nav-tabs-link" href="#expense_type" data-config="17" data-config-block="expense_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-bar-chart"></i> Expense Type</a> </li>
<!--        <li class="nav-item nav-item-link" id="config_14"> <a class="nav-link nav-tabs-link" href="#job_type" data-config="14" data-config-block="job_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-file-text-o"></i> Job Type</a> </li>-->
        <li class="nav-item nav-item-link" id="config_15"> <a class="nav-link nav-tabs-link" href="#exit_type" data-config="15" data-config-block="exit_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-sign-out"></i> Employee Exit Type</a> </li>
<!--        <li class="nav-item nav-item-link" id="config_18"> <a class="nav-link nav-tabs-link" href="#travel_arr_type" data-config="18" data-config-block="travel_arr_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-car"></i> Travel Arrangement Type</a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_16"> <a class="nav-link nav-tabs-link" href="#payment_method" data-config="16" data-config-block="payment_method" data-toggle="tab" aria-expanded="true"> <i class="fa fa-money"></i> Payment Methods </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_19"> <a class="nav-link nav-tabs-link" href="#currency_type" data-config="19" data-config-block="currency_type" data-toggle="tab" aria-expanded="true"> <i class="fa fa-dollar"></i> Currency Type </a> </li>-->
      </ul>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="contract">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Contract Type</h2>
            <form class="m-b-1 add" id="contract_type_info" action="<?php echo site_url("settings/contract_type_info") ?>" name="contract_type_info" method="post">
              <div class="form-group">
                <label for="name">Contract Type</label>
                <input type="text" class="form-control" name="contract_type" placeholder="Enter Contract Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Contract Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_contract_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Contract Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="document_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Document Type</h2>
            <form class="m-b-1 add" id="document_type_info" action="<?php echo site_url("settings/document_type_info") ?>" name="document_type_info" method="post">
              <div class="form-group">
                <label for="name">Document Type</label>
                <input type="text" class="form-control" name="document_type" placeholder="Enter Document Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Document Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_document_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Document Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="qualification" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Education Level</h2>
            <form class="m-b-1 add" id="edu_level_info" action="<?php echo site_url("settings/edu_level_info") ?>" name="edu_level_info" method="post">
              <div class="form-group">
                <label for="name">Education Level</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Education Level">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Education Level</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_education_level" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Education Level</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Language</h2>
            <form class="m-b-1 add" id="edu_language_info" action="<?php echo site_url("settings/edu_language_info") ?>" name="edu_language_info" method="post">
              <div class="form-group">
                <label for="name">Language</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Language">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Language</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_qualification_language" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Language</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Skill</h2>
            <form class="m-b-1 add" id="edu_skill_info" action="<?php echo site_url("settings/edu_skill_info") ?>" name="edu_skill_info" method="post">
              <div class="form-group">
                <label for="name">Skill</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Skill">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Skill</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_qualification_skill" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Skill</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="payment_method" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Payment Method</h2>
            <form class="m-b-1 add" id="payment_method_info" action="<?php echo site_url("settings/payment_method_info") ?>" name="payment_method_info" method="post">
              <div class="form-group">
                <label for="name">Payment Method</label>
                <input type="text" class="form-control" name="payment_method" placeholder="Enter Payment Method">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Payment Method</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_payment_method" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Payment Method</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="award_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Award Type</h2>
            <form class="m-b-1 add" id="award_type_info" action="<?php echo site_url("settings/award_type_info") ?>" name="award_type_info" method="post">
              <div class="form-group">
                <label for="name">Award Type</label>
                <input type="text" class="form-control" name="award_type" placeholder="Enter Award Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Award Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_award_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Award Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="leave_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Leave Type</h2>
            <form class="m-b-1 add" id="leave_type_info" action="<?php echo site_url("settings/leave_type_info") ?>" name="leave_type_info" method="post">
              <div class="form-group">
                <label for="name">Leave Type</label>
                <input type="text" class="form-control" name="leave_type" placeholder="Enter Leave Type">
              </div>
              <div class="form-group">
                <label for="name">Days per Year</label>
                <input type="text" class="form-control" name="days_per_year" placeholder="Days per Year">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Leave Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_leave_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Leave Type</th>
                    <th>Days per Year</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="warning_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Warning Type</h2>
            <form class="m-b-1 add" id="warning_type_info" action="<?php echo site_url("settings/warning_type_info") ?>" name="warning_type_info" method="post">
              <div class="form-group">
                <label for="name">Warning Type</label>
                <input type="text" class="form-control" name="warning_type" placeholder="Enter Warning Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Warning Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_warning_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Warning Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="termination_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Termination Type</h2>
            <form class="m-b-1 add" id="termination_type_info" action="<?php echo site_url("settings/termination_type_info") ?>" name="termination_type_info" method="post">
              <div class="form-group">
                <label for="name">Termination Type</label>
                <input type="text" class="form-control" name="termination_type" placeholder="Enter Termination Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Termination Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_termination_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Termination Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="expense_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Expense Type</h2>
            <form class="m-b-1 add" id="expense_type_info" action="<?php echo site_url("settings/expense_type_info") ?>" name="expense_type_info" method="post">
              <div class="form-group">
                <label for="name">Expense Type</label>
                <input type="text" class="form-control" name="expense_type" placeholder="Enter Expense Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Expense Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_expense_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Expense Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="job_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Job Type</h2>
            <form class="m-b-1 add" id="job_type_info" action="<?php echo site_url("settings/job_type_info") ?>" name="job_type_info" method="post">
              <div class="form-group">
                <label for="name">Job Type</label>
                <input type="text" class="form-control" name="job_type" placeholder="Enter Job Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Job Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_job_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Job Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="exit_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Employee Exit Type</h2>
            <form class="m-b-1 add" id="exit_type_info" action="<?php echo site_url("settings/exit_type_info") ?>" name="exit_type_info" method="post">
              <div class="form-group">
                <label for="name">Employee Exit Type</label>
                <input type="text" class="form-control" name="exit_type" placeholder="Enter Employee Exit Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Employee Exit Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_exit_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Employee Exit Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="travel_arr_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Travel Arrangement Type</h2>
            <form class="m-b-1 add" id="travel_arr_type_info" action="<?php echo site_url("settings/travel_arr_type_info") ?>" name="travel_arr_type_info" method="post">
              <div class="form-group">
                <label for="name">Travel Arrangement Type</label>
                <input type="text" class="form-control" name="travel_arr_type" placeholder="Enter Travel Arrangement Type">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Travel Arrangement Type</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_travel_arr_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Travel Arrangement Type</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="currency_type" style="display:none;">
    <div  class="box box-block bg-white">
      <div class="row">
        <div class="col-md-5">
          <div class="box box-block bg-white">
            <h2><strong>Add New</strong> Currency Type</h2>
            <form class="m-b-1 add" id="currency_type_info" action="<?php echo site_url("settings/currency_type_info") ?>" name="currency_type_info" method="post">
              <div class="form-group">
                <label for="name">Currency Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Currency Name">
              </div>
              <div class="form-group">
                <label for="name">Currency Code</label>
                <input type="text" class="form-control" name="code" placeholder="Enter Currency Code">
              </div>
              <div class="form-group">
                <label for="name">Currency Symbol</label>
                <input type="text" class="form-control" name="symbol" placeholder="Enter Currency Symbol">
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </form>
          </div>
        </div>
        <div class="col-md-7">
          <div class="box box-block bg-white">
            <h2><strong>List All</strong> Currencies</h2>
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="table table-striped table-bordered dataTable" id="xin_table_currency_type" style="width:100%;">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Symbol</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade edit_setting_datail" id="edit_setting_datail" tabindex="-1" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_setting_info"></div>
  </div>
</div>
