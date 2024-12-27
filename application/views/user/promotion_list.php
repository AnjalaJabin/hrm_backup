<?php
/* Promotion view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Promotions </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee Name</th>
          <th>Promotion Title</th>
          <th>Promotion Date</th>
          <th>Description</th>
          <th>Added By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
