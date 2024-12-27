<?php
/* Announcement view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Announcements </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Title</th>
          <th>Summary</th>
          <th>Published For</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Published By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
