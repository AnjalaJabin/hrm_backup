<?php
/* Generate Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>
<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><strong>Generate Payslip</strong></h2>
            <div class="row">
                <div class="col-md-12">
                    <form class="m-b-1 add form-hrm" method="post" name="set_salary_details" id="set_salary_details">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department">Employee</label>
                                </div>
<!--                            </div>-->
<!--                            <div class="col-md-9">-->
                                <div class="form-group">
                                    <select id="employee_id" name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                                        <option value="0">All Employees</option>
                                        <?php foreach($all_employees as $employee) {?>
                                            <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?> (<?php echo $employee->username;?>)</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
<!--                        </div>-->
<!--                        <div class="row">-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="month_year">Select Month</label>
                                </div>
<!--                            </div>-->
<!--                            <div class="col-md-9">-->
                                <div class="form-group">
                                    <input class="form-control month_year" placeholder="Select Month" readonly id="month_year" name="month_year" type="text" value="<?php echo date('M Y');?>">
                                </div>
                            </div>
<!--                        </div>-->
<!--                        <div class="row">-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="department"><?php echo $this->lang->line('xin_employee_department');?></label>
                                </div>
                                <div class="form-group">

                                <select class="form-control" name="department_id" id="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
                                        <option selected value="0"></option>
                                        <?php
                                        $all_departments=$this->Department_model->all_departments();
                                        foreach($all_departments as $department) {
                                            ?>
                                            <option value="<?php echo $department->department_id?>" ><?php echo $department->department_name;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                                <!--
                                <                           </div>-->
                            <div class="col-md-2">
                                <div class="form-group">

                                                                <label></label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary save">Search</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><span> <strong>Payment Info for <span class="text-danger" id="p_month"><?php echo date('F, Y');?></span></strong> </span></h2>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" id="generateBtn" class="btn btn-primary save">Make Payments</button>
                    <div id="loader" style="display:none;">Loading...</div>

                    <button type="button" id="downloadbtn" class="btn btn-success">Download TXT</button>
                    <button type="button" id="emailbtn" class="btn btn-warning">Send Emails</button>


                </div>
            </div>
<br>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable hover" id="xin_table">
                    <thead>
                    <tr>
                        <th class="no-export"><input type="checkbox" id="select-all"></>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Basic Salary</th>
                        <th>Total Allowance</th>
                        <th>Expenses</th>
                        <th>Extra Allow.</th>
                        <th>Ticket</th>
                        <th>Leave Salary</th>
                        <th>Overtime</th>
                        <th>OT Hours</th>
                        <th title="Loan + Leave + Advance Salary + Extra Deductions">Total Deductions</th>
                        <th>Net Salary</th>
                        <th class="no-export">Details</th>
                        <th>Status</th>
                        <th class="no-export">Action</th>
                        <th class="no-export" hidden>#</th>
                        <th>Comments</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Send Emails</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to send emails with payslips attached?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmSendEmails" class="btn btn-primary">Send Emails</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .hide-calendar .ui-datepicker-calendar { display:none !important; }
    .hide-calendar .ui-priority-secondary { display:none !important; }
</style>
