<?php
/* Payment History view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Payment History</h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Payment Month</th>
          <th>Payment Date</th>
          <th>Paid Amount</th>
          <th>Payment Type</th>
          <th>Payslip</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
