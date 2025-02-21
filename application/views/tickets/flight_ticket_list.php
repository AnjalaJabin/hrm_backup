<?php
/*
* Tickets view
*/
$session = $this->session->userdata('username');
?>

<div class="add-form" style="display:none;">
    <div class="box box-block bg-white">
        <h2>Create New Ticket
            <div class="add-record-btn">
                <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
            </div>
        </h2>
        <div class="row m-b-1">
            <div class="col-md-12">
                <form action="<?php echo site_url("flights/add_ticket") ?>" method="post" name="add_ticket" id="xin-form">
                    <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
                    <div class="bg-white">
                        <div class="box-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employees">Ticket for Employee</label>
                                        <select class="form-control" id="employee-select" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                            <option value=""></option>
                                            <?php foreach($all_employees as $employee) {
                                            if($employee->ticket_eligibilty		){?>
                                                ?>
                                                <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="balance" class="control-label">Balance</label>
                                        <input id="leave_balance" class="form-control" placeholder="Ticket Balance" readonly name="balance" type="text" value="">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="ticket_no" class="control-label">Ticket Number</label>

                                        <input type="text" class="form-control" placeholder="Ticket No"  name="ticket_no">
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="airlines" class="control-label">Airlines </label>

                                        <input type="text" class="form-control" placeholder="Airlines Name"  name="airlines">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ticket_date" class="control-label">Date</label>
                                        <input class="form-control date" placeholder="Date" readonly name="ticket_date" type="text" value="">

                                    </div>
                                    <div class="form-group">
                                        <label for="ticket_date" class="control-label">Amount</label>
                                        <input class="form-control" placeholder="Amount"  name="amount" type="text" value="">

                                    </div>
                                    <div class="form-group">
                                        <label for="destination" class="control-label">Destination </label>

                                        <input type="text" class="form-control" placeholder="Destination Name"  name="destination">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Ticket Remarks</label>
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
    <h2><strong>List All</strong> Tickets
        <div class="add-record-btn">
            <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
        </div>
    </h2>
    <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table">
            <thead>
            <tr>
                <th>Action</th>
                <th>Emp ID</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Destination</th>
                <th>Airlines</th>
                <th>Amount</th>
                <th>Ticket No</th>
                <th>Created Date</th>
                <th>Current Ticket Balance</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
