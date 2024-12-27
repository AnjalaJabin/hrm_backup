<?php
/* Attendance Import view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white product-view mb-8">
          <h5>Import CSV file only</h5>
          <p class="font-100 text-muted mb-1">The first line in downloaded csv file should remain as it is. Please do not change the order of columns in csv file.</p>
          <p class="font-100 text-muted mb-1">The correct column order is (Employee ID, Attendance Date, Clock In Time and Date, Clock Out Time and Date) and <strong>you must follow</strong> the csv file, otherwise you will get an error while importing the csv file.</p>
          <h6><a href="<?php echo base_url();?>uploads/csv/sample-csv-attendance.csv" class="btn btn-info"> <i class="fa fa-download"></i> Download sample File
          </a></h6>
          <div class="pv-form mt-2">
            <h6 class="mt-0">Upload File</h6>
            <form name="import_attendance" method="post" action="<?php echo site_url("timesheet/import_attendance"); ?>" id="xin-form" enctype="multipart/form-data">
            <span class="btn btn-primary btn-file">
              Browse <input type="file" name="file" id="file">
            </span>
            <br>
              <small>Please select csv or excel file (allowed file size 500 KB)</small>
            	<div class="mt-1">
              <button type="submit" class="btn btn-primary">Import</button>
            </div>
           </form> 
          </div>
        </div>
  </div>
</div>