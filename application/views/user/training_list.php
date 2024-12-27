<?php
/* Training view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Training </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Training Type</th>
          <th>Trainer</th>
          <th>Training Duration</th>
          <th>Cost</th>
          <th>Status</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
