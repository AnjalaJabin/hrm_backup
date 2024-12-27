<?php
/* Employee Exit view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Employee Exit
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("endofservice/add_exit") ?>" method="post" name="add_exit" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Employee to Exit</label>
                    <select name="employee_id" required id="employee_select" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="exit_date">Exit Date</label>
                        <input class="form-control date" placeholder="Exit Date" id="exit_date" required readonly name="exit_date" type="text">
                      </div>
                    </div>
                      <div class="col-md-3">
                          <div class="form-group">
                              <label for="notice_date">Notice Date</label>
                              <input class="form-control date" placeholder="Notice Date" readonly name="notice_date" type="text">
                          </div>
                      </div>

                      <div class="col-md-6">
                      <div class="form-group">
                        <label for="type">Type of Exit</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Exit" name="type">
                          <option value=""></option>
                          <?php foreach($all_exit_types as $exit_type) {?>
                          <option value="<?php echo $exit_type->exit_type_id?>"><?php echo $exit_type->type;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Reason" name="reason" cols="30" rows="10" id="reason"></textarea>
                  </div>
                </div>
              </div>
                <div class="box box-block bg-white">
                    <h2><strong>Service Detail</strong></h2>
                    <table class="table table-striped m-md-b-0">
                        <tbody>
                        <tr>
                            <th scope="row">Employee Name</th>
                            <td id="employee_name" class="text-right"></td>
                            <th scope="row">Employee ID</th>
                            <td id="employee_id" class="text-right"></td>
                        </tr>
                        <tr>
                            <th scope="row">Department</th>
                            <td id="department" class="text-right"></td>
                            <th scope="row">Designation</th>
                            <td id="designation" class="text-right"></td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Joining</th>
                            <td id="doj" class="text-right"></td>
                            <th scope="row">Email</th>
                            <td id="email" class="text-right"></td>
                        </tr>
                        <tr>
                            <th scope="row">Gratuity Amount</th>
                            <td class="text-right"> <input id="gratuity_amount" placeholder="Gratuity Amount" class="form-control"  name="gratuity" type="text">
                            </td>
                            <th scope="row">Leave Balance</th>
                            <td class="text-right"><input id="leave_balance" placeholder="Leave Balance" class="form-control"  name="leave_balance" type="text"></td>
                        </tr>
                        <tr>
                            <th scope="row">Leave Salary </th>
                            <td  class="text-right"><input id="leave_salary" placeholder="Leave Salary" class="form-control"  name="leave_salary" type="text"></td>
                            <th scope="row">Ticket Balance</th>
                            <td  class="text-right"><input id="ticket_balance" placeholder="Ticket Balance" class="form-control"  name="ticket_balance" type="text"></td>
                        </tr>
                        <tr>
                            <th scope="row">Ticket Amount</th>
                            <td  class="text-right"><input id="ticket_amount" placeholder="Ticket Amount" class="form-control"  value="0" name="ticket_amount" type="text"></td>

                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row">Pending Salary</th>
                            <td  class="text-right"><input id="pending_salary" placeholder="Pending Salary" class="form-control"  name="pending_salary" type="text"></td>
                            <th scope="row">Pending Expenses </th>
                            <td  class="text-right"><input id="expenses" placeholder=" Expenses" class="form-control"  name="expenses" type="text"></td>
                        </tr>
                        <tr>
                            <th scope="row">Loan  Balance </th>
                            <td  class="text-right"><input id="loan_balance" placeholder="Loan Balance" class="form-control"  name="loan" type="text"></td>
                            <th scope="row">Overtime Amount </th>
                            <td  class="text-right"><input id="overtime" placeholder="Overtime Amount" value="0" class="form-control"  name="overtime" type="text"></td>
                        </tr>
                        <tr>
                            <th scope="row">Other Deductions </th>
                            <td  class="text-right"><input id="other_deductions" placeholder="Other deductions" value="0" class="form-control"  name="other_deductions" type="text"></td>
                            <th scope="row">Assets Returned </th>
                            <td  class="text-right"><select class="select2" data-plugin="select_hrm" data-placeholder="Assets Returned" name="returned_assets">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <div  id="net_sal" class="the-notes info">Total Net:</div><input id="net_amount" placeholder="Overtime Amount" class="form-control" hidden  name="net_amount" type="text">
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
  <h2><strong>List All</strong> Employee Exit
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Exit Type</th>
          <th>Exit Date</th>
          <th>Notice Date </th>
          <th>Net  Amount</th>
          <th>Added By</th>
          <th>Document</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
