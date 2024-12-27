<?php
/* Office Shift view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>My</strong> Office Shift </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Office Shift</th>
          <th>Duration</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
