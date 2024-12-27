<?php
/*
* Package View
*/
$session = $this->session->userdata('username');
?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <form class="m-b-1" action="<?php echo site_url("package/cancel_subscription_update") ?>" method="post" name="cancel_subscription_update" id="xin-form">
          <p class="h4">Sorry to see you go, please let us know why</p>
            <br>
            <div class="radio h6">
              <label><input type="radio" name="reason" value="Subscription is too expensive"> Subscription is too expensive</label>
            </div>
            <div class="radio h6">
              <label><input type="radio" name="reason" value="I do not need the subscription anymore"> I do not need the subscription anymore</label>
            </div>
            <div class="radio h6">
              <label><input type="radio" name="reason" value="Other"> Other</label>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <textarea class="form-control" name="des" placeholder="Please let us know why"></textarea>
                </div>
            </div>
        
        <div class="row">
            <div class="col-sm-12"><br>
                <button type="submit" class="btn btn-md btn-success pull-right">Cancel subscription</button>
                <a href="<?php echo site_url('package'); ?>" class="btn btn-md btn-default pull-right"> Keep subscription</a>
            </div>
        </div>

        
      </form>
    </div>
  </div>
  
</div>