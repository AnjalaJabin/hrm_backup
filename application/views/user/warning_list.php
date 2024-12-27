<?php
/* Warning view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Warnings </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Warning Date</th>
          <th>Subject</th>
          <th>Warning Type</th>
          <th>Approval Status</th>
          <th>Warning By</th>
          <th>Details</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
