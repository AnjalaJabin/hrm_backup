<?php
/* Complaints view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Complaints </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Complaint From</th>
          <th>Complaint Against</th>
          <th>Title</th>
          <th>Complaint Date</th>
          <th>Approval Status</th>
          <th>Details</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
