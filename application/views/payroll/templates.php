<?php
/* Payroll Template view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Setup</strong> Payroll Template
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form class="form-hrm" action="<?php echo site_url("payroll/add_template") ?>" method="post" name="add_template" id="xin-form" autocomplete="off">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="salary_grades">Name of Template</label>
                        <input class="form-control" placeholder="Name of Template" name="salary_grades" type="text">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basic_salary" class="control-label">Basic Salary</label>
                        <input class="form-control salary" placeholder="Basic Salary" name="basic_salary" type="text">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="overtime_rate" class="control-label">Overtime Rate ( Per Hour)</label>
                        <input class="form-control" placeholder="Overtime Rate ( Per Hour)" name="overtime_rate" type="text">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="house_rent_allowance">House Rent Allowance</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="house_rent_allowance" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="medical_allowance">Medical Allowance</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="medical_allowance" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="travelling_allowance">Travelling Allowance</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="travelling_allowance" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="dearness_allowance">Dearness Allowance</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="dearness_allowance" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <!--
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="provident_fund">Provident Fund</label>
                        <input class="form-control deduction" placeholder="Amount" name="provident_fund" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="tax_deduction">Tax Deduction</label>
                        <input class="form-control deduction" placeholder="Amount" name="tax_deduction" type="text" value="">
                      </div>
                    </div>
                  </div>
                  -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="security_deposit">Security Deposit</label>
                        <input class="form-control deduction" placeholder="Amount" name="security_deposit" type="text" value="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-right">
                  <h2><strong>Total Salary Details</strong></h2>
                  <table class="table table-bordered custom-table">
                    <tbody>
                      <tr>
                        <th class="col-sm-4 vertical-td" style="text-align:right;">Gross Salary :</th>
                        <td class="hidden-print"><input type="text" name="gross_salary" readonly id="total" class="form-control"></td>
                      </tr>
                      <tr>
                        <th class="col-sm-4 vertical-td" style="text-align:right;">Total Allowance :</th>
                        <td class="hidden-print"><input type="text" name="total_allowance" readonly id="total_allowance" class="form-control"></td>
                      </tr>
                      <tr>
                        <th class="col-sm-4 vertical-td" style="text-align:right;">Total Deduction :</th>
                        <td class="hidden-print"><input type="text" name="total_deduction" readonly id="total_deduction" class="form-control"></td>
                      </tr>
                      <tr>
                        <th class="col-sm-4 vertical-td" style="text-align:right;">Net Salary :</th>
                        <td class="hidden-print"><input type="text" name="net_salary" readonly id="net_salary" class="form-control"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <button type="submit" class="btn btn-primary save primary-btn-block col-right">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong>List All</strong> Payroll Templates
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Payroll Template</th>
          <th>Basic Salary</th>
          <th>Net Salary</th>
          <th>Total Allowance</th>
          <th>Created By</th>
          <th>Created Date</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
