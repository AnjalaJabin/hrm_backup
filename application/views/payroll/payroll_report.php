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

                    <!--                    <form class="m-b-1 add form-hrm" method="post" name="set_salary_details" id="set_salary_details">-->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department">Employee</label>
                                <select id="employee_id" multiple name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                                    <!--                                        <option value="0">All Employees</option>-->
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
                            <select class="form-control" name="month_year" multiple id="month_year" data-plugin="select_hrm" data-placeholder="Select Months">
                                <!--                                    <option selected value="0"></option>-->
                                <?php
                                $all_months=$this->Payroll_model->get_all_payment_months();
                                foreach($all_months->result() as $month) {
                                    ?>
                                    <option <?php if(date('Y-m')==$month->payment_date)echo "selected";?>value="<?php echo $month->payment_date?>" ><?php echo date('M Y',strtotime($month->payment_date));?></option>
                                <?php } ?>
                            </select>
                            </div>
                            <!--                            </div>-->
                            <!--                            <div class="col-md-9">-->
                            </div>
                        <!--                        </div>-->
                        <!--                        <div class="row">-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department"><?php echo $this->lang->line('xin_employee_department');?></label>

                                <select class="form-control" name="department_id" id="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
                                    <option selected value="0">Select Department</option>
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
                        <div class="col-md-3"
                        <div class="form-group">
                            <label>Paid Between </label>

                            <input class="form-control form-control-success" id="reportrange1" type="text" name="daterange" value="" />
                        </div> <!--end::Datepicker-->
                    </div>

                </div>
            </div>
            <!--                </form>-->
        </div>
    </div>
</div>
</div>
</div>
<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><span> <strong>Payment Information </strong><span class="text-danger" id="p_month"></span></h2>
            <div class="row">

            </div>
            <br>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable hover" id="xin_table_report">
                    <thead>
                    <tr>
                        <!--                        <th class="no-export"><input type="checkbox" id="select-all"></>-->
                        <th>Date</th>
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
                        <th>Paid Amount</th>
                        <th>Paid Date</th>
                        <th>Comments</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .hide-calendar .ui-datepicker-calendar { display:none !important; }
    .hide-calendar .ui-priority-secondary { display:none !important; }
</style>
