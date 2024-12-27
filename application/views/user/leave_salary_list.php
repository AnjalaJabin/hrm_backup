<?php
/* Leave view

*/
?>
<?php $session = $this->session->userdata('username');?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<br>
<!-- Nav tabs -->

        <div class="add-form" style="display:none;">
            <div class="box box-block bg-white">
                <h2><strong>Add Leave Salary Request</strong>
                    <div class="add-record-btn">
                        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
                    </div>
                </h2>
                <div class="row m-b-1">
                    <div class="col-md-12">
                        <form action="<?php echo site_url("leave_salary/add_leave") ?>" method="post" name="add_leave" id="xin-form">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
                            <div class="bg-white">
                                <div class="box-block">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="employees">Leave Salary for Employee</label>
                                                <select class="form-control" id="employee-select" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                                    <option value=""></option>
                                                    <?php foreach($all_employees as $employee) {?>
                                                        <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input class="form-control"  placeholder=" Amount" id="salary" readonly name="salary" type="text" value="">
                                                <input hidden placeholder=" Amount" id="amount" readonly name="amount" type="text" value="">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                              <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="start_date">No of days</label>
                                                        <input class="form-control"  id="days" placeholder=" No of Leave Days" name="days" type="text" value="30">
                                                    </div>
                                                </div>
<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="start_date">Date</label>
                                                        <input class="form-control date" placeholder=" Date" readonly  name="date" type="text" value="<?php echo date('Y-m-d');?>">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-primary save">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-block bg-white">
            <h2><strong>List All</strong>  Leave Salary
                <div class="add-record-btn">
                    <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Apply Now </button>
                </div>
            </h2>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Emp ID</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Request Duration</th>
                        <th>Amount</th>
                        <th>Applied On</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
