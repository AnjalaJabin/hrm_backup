<?php
/* Travel view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Travels </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Travel Purpose</th>
          <th>Visit Place</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Approval Status</th>
          <th>Added By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
