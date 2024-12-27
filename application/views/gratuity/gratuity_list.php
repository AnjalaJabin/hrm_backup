<?php
/*
* Tickets view
*/
$session = $this->session->userdata('username');
?>

<link rel="stylesheet" href="<?php echo site_url(); ?>skin/document/glrstyle.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo site_url(); ?>skin/document/jquery.form.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>-->


        <br>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home">Employee List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu1">Encashment Details</a>
            </li>
                   </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="home" class="tab-pane active"><br>
                <div class="box box-block bg-white">
                    <h2><strong>List All</strong> Gratuity Details

                    </h2>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table class="table table-striped table-bordered dataTable" id="xin_table_employee_list">
                            <thead>
                            <tr>
                                <!--                <th>Action</th>-->
                                <th> ID</th>

                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Date of Joining</th>
                                <th>Years Completed</th>
                                <th>Total Gratuity</th>
                                <th>Previous Encashments</th>
                                <th>Current Balance</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
            <div id="menu1" class="tab-pane fade"><br>
                <div class="add-form" style="display:none;">
                    <div class="box box-block bg-white">
                        <h2>Create New Gratuity Encashment
                            <div class="add-record-btn">
                                <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
                            </div>
                        </h2>
                        <div class="row m-b-1">
                            <div class="col-md-12">
                                <form action="<?php echo site_url("gratuity/add_gratuity") ?>" method="post" name="add_ticket" id="xin-form">
                                    <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
                                    <div class="bg-white">
                                        <div class="box-block">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="employees">Gratuity for Employee</label>
                                                        <select class="form-control" id="employee-select" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                                            <option value=""></option>
                                                            <?php foreach($all_employees as $employee) {
                                                                if($employee->gratuity_eligibilty	){?>
                                                                <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name	.' '.$employee->last_name;?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="balance" class="control-label">Gratuity Balance</label>
                                                        <input id="leave_balance" class="form-control " placeholder="Gratuity Balance" readonly  type="text" value="">
                                                        <input id="gratuity_balance"hidden class="form-control " placeholder="Gratuity Balance" readonly name="balance" type="text" value="">

                                                    </div>
                                                </div><div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="loan_balance" class="control-label">Loan Balance</label>
                                                        <input id="loan_balance" class="form-control " placeholder="Loan Balance" readonly  type="text" value="">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label for="ticket_no" class="control-label">Amount</label>

                                                        <input type="text" class="form-control" placeholder="Amount"  name="amount">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label for="date" class="control-label">Date</label>
                                                        <input class="form-control date" placeholder="Date" id="gratuity_date" readonly name="date" type="text" value="">

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="description"> Remarks</label>
                                                        <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="5" id="description"></textarea>
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
                    <h2><strong>List All</strong> Gratuity Encashments
                        <div class="add-record-btn">
                            <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New </button>
                        </div>
                    </h2>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Amount</th>
                                <th>Paid Date</th>
                                <th>Added By</th>                     </tr>
                            </thead>
                        </table>
                    </div>
                </div>


            </div>

        </div>
