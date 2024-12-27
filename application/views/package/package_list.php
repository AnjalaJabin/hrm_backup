<?php
/*
* Package View
*/
$session = $this->session->userdata('username');
$package_info    = $this->Package_model->read_package_information($root_account[0]->package_id);
?>



<?php
if(isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id']))
{
?>
<div class="row">
  <div class="col-md-6">
        <div class="box box-block bg-white">
        <div align="center">
            <?php
            $payment_data = $this->Package_model->read_payment_information($_REQUEST['order_id']);
            if($payment_data[0]->status==1)
            {
            ?>
                <img src="<?php echo site_url('skin/img/tick.png'); ?>" width="70"/><br><br>
                <p class="h3 text-success">Payment Complete</p><br>
                <p>We've sent you an email with all details of your order</p>
            <?php
            }
            else
            {
            ?>
                <img src="<?php echo site_url('skin/img/error.png'); ?>" width="70"/><br><br>
                <p class="h3 text-danger">Payment Error</p><br>
                <p>We found some errors on your payment.</p>
                <p>Please contact us : support@corbuz.com</p>
            <?php
            }
            ?>
            <p class="h6">Your Order Number</p>
            <p class="h2"><?php echo $payment_data[0]->order_number; ?></p>
            
        </div>
        </div>
  </div>
</div>
<?php
}
?>  



<div class="row m-b-1 animated fadeInRight">

  <div class="col-md-12">
    <div class="box box-block bg-white">
          <?php 
          if($root_account[0]->package_id!=1)
          {
          ?>
        <div class="alert alert-warning" align="center" style="font-size:16px;">
          <!-- You are using <strong><?php echo $package_info[0]->employees; ?> Employees Package</strong> (<?php echo $package_info[0]->package_name; ?>)<br> -->
          
                Your Subscription will be <?php if($root_account[0]->is_subscription==0){ echo 'expired'; }else{ echo 'auto renewed'; } ?> on <strong><?php echo date('d M Y',strtotime($root_account[0]->end_date)); ?></strong>
        </div>
          <?php
          }
          ?>
        
        
        <style>
    .content {
    padding: 30px 0;
}

/***
Pricing table
***/
.pricing {
  position: relative;
  margin-bottom: 15px;
  border: 3px solid #eee;
}

.pricing-active {
  border: 3px solid #36d7ac;
  margin-top: -10px;
  box-shadow: 7px 7px rgba(54, 215, 172, 0.2);
}

.pricing:hover {
  border: 3px solid #36d7ac;
}

.pricing:hover h4 {
  color: #36d7ac;
}

.pricing-head {
  text-align: center;
}

.pricing-head h3,
.pricing-head h4 {
  margin: 0;
  line-height: normal;
}

.pricing-head h3 span,
.pricing-head h4 span {
  display: block;
  margin-top: 5px;
  font-size: 14px;
  font-style: italic;
}

.pricing-head h3 {
  font-weight: 300;
  color: #fafafa;
  padding: 12px 0;
  font-size: 20px;
  background: #36d7ac;
  border-bottom: solid 1px #41b91c;
}

.pricing-head .grey {
  font-weight: 300;
  color: #fafafa;
  padding: 12px 0;
  font-size: 20px;
  background: #969696;
  border-bottom: solid 1px #7d7d7d;
}

.pricing-head h4 {
  color: #bac39f;
  padding: 5px 0;
  font-size: 35px;
  font-weight: 300;
  background: #fbfef2;
  border-bottom: solid 1px #f5f9e7;
}

.pricing-head-active h4 {
  color: #36d7ac;
}

.pricing-head h4 i {
  top: -8px;
  font-size: 28px;
  font-style: normal;
  position: relative;
}

.pricing-head h4 span {
  top: -10px;
  font-size: 14px;
  font-style: normal;
  position: relative;
}

/*Pricing Content*/
.pricing-content li {
  color: #888;
  font-size: 12px;
  padding: 7px 15px;
  border-bottom: solid 1px #f5f9e7;
}

/*Pricing Footer*/
.pricing-footer {
  color: #777;
  font-size: 11px;
  line-height: 17px;
  text-align: center;
  padding: 0 20px 19px;
}

/*Priceing Active*/
.price-active,
.pricing:hover {
  z-index: 9;
}

.price-active h4 {
  color: #36d7ac;
}

.no-space-pricing .pricing:hover {
  transition: box-shadow 0.2s ease-in-out;
}

.no-space-pricing .price-active .pricing-head h4,
.no-space-pricing .pricing:hover .pricing-head h4 {
  color: #36d7ac;
  padding: 15px 0;
  font-size: 80px;
  transition: color 0.5s ease-in-out;
}

.yellow-crusta.btn {
  color: #FFFFFF;
  background-color: #f3c200;
}
.yellow-crusta.btn:hover,
.yellow-crusta.btn:focus,
.yellow-crusta.btn:active,
.yellow-crusta.btn.active {
    color: #FFFFFF;
    background-color: #cfa500;
}
.list-unstyled{
    text-align:center !important;
}
</style>
    <div class="container content">
        <div class="row" align="center">
            <button class="btn btn-lg btn-info btn_yearly">Yearly</button><button class="btn btn-lg btn_monthly">Monthly</button>
    	    <p class="h6 text-success" style="margin-top:8px;">Get 2 months discount on yearly subscription</p>
    	    <br><br>
    	</div>
    	<div class="row">
    	    <form class="m-b-1" method="post" name="update" id="price_form">
		    <input type="hidden" class="plan_book_type" name="plan_type" value="yearly">
		    <input type="hidden" class="plan__type" name="plan" value="1">
    		<!-- Pricing -->
    		<?php foreach($all_packages as $package) {?>
    		<div class="col-md-3">
    			<div class="pricing hover-effect <?php if($package->id==$root_account[0]->package_id){ echo 'pricing-active'; } ?>">
    			    <?php if($package->id==$root_account[0]->package_id){?>
        		    <div class="alert alert-success" align="center" style="margin:0;"><b>Your Plan</b></div>
        		    <?php } ?>
    				<div class="pricing-head <?php if($package->id==$root_account[0]->package_id){ echo 'pricing-head-active'; } ?>">
    					<h3 <?php if($package->id<$root_account[0]->package_id){ echo 'class="grey"'; }?>><?php echo $package->package_name?><span><?php echo $package->employees?> Employees</span>
    					</h3>
    					<h4 class="price_monthly" style="display:none;"><i>$</i><?php echo $package->price?><i></i>
    					<span>
    					Per Month </span>
    					</h4>
    					
    					<h4 class="price_yearly"><i>$</i><?php echo $package->price*10?><i></i>
    					<span>
    					Per Year </span>
    					</h4>
    					
    				</div>
    				<ul class="pricing-content list-unstyled">
    					<li>
    						<?php echo $package->employees?> Employees
    					</li>
    					<li>
    						<?php echo $package->storage?> Cloud Storage
    					</li>
    					<li>
    						<?php echo $package->employees?> User Accounts
    					</li>
    					<li>
    						<?php if($package->id!=1){ echo '30 Days Backup'; }else{ echo '---'; } ?>
    					</li>
    					<li>
    						<?php if($package->id!=1){ echo '15 Days Cash Refund'; }else{ echo '---'; } ?>
    					</li>
    					<li>
    						<?php if($package->id!=1){ echo '24x7 Support'; }else{ echo '---'; } ?>
    					</li>
    				</ul>
    				<div class="pricing-footer">
        				    <?php 
        				    if($package->id<$root_account[0]->package_id){ ?> <a href="#" data-plan="<?php echo $package->id; ?>" class="btn btn-info save price_up_btn"> Downgrade </a> <?php }
        				    else if($package->id>$root_account[0]->package_id){ ?> <a href="#" data-plan="<?php echo $package->id; ?>" class="btn btn-warning save price_up_btn"> Upgrade </a> <?php }
        				    else{ ?> <a href="#" data-plan="<?php echo $package->id; ?>" class="btn btn-success save price_up_btn"> Renew </a> <?php } ?>
    				    
    				</div>
    			</div>
    		</div>
    		
    		<?php
    		}
    		?>
    		</form>
    		<!--//End Pricing -->
    	</div>
    </div>

      <!--
      <h2> Packages </h2>
      <form class="m-b-1" action="<?php echo site_url("package/update") ?>" method="post" name="update" id="xin-form">
        <input type="hidden" name="edit_type" value="package"/>
        
        <table class="table table-striped table-bordered dataTable">
          <thead>
            <tr>
              <th>Package</th>
              <th>Employees</th>
              <th>Storage</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($all_packages as $package) {?>
            <tr>
              <td>
                <div class="radio">
                  <label><input type="radio" class="package_option plan_change" data-price="<?php echo $package->price?>" data-id="<?php echo $package->id?>" name="plan" value="<?php echo $package->id; ?>" <?php if($package->id==$root_account[0]->package_id){ echo 'checked="checked"'; } ?> > &nbsp; <?php echo $package->package_name?></label>
                </div> 
              </td>
              <td><?php echo $package->employees?></td>
              <td><?php echo $package->storage?></td>
              <td>$<?php echo $package->price?> /Month</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <br><br>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">
                </div>
                <div class="col-md-10">
                    <div class="pull-right h6">
                        <label><input type="radio" class="package_yearly plan_change" name="plan_type" checked value="yearly"> &nbsp; Yearly <span class="yearly_price">$<?php echo $package_info[0]->price*10 ?></span> <span class="text-success">(Two Months Free)</span></label><br>
                        <label><input type="radio" class="package_monthly plan_change" name="plan_type" value="monthly"> &nbsp; Monthly <span class="monthly_price">$<?php echo $package_info[0]->price; ?></span></label>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <span class="pull-right h4 text-success gtotal">Total : $<?php echo number_format($package_info[0]->price*10,2); ?></span>
                </div>
            </div>
            
            <input type="hidden" id="total_price" name="total_price" value="<?php echo $package_info[0]->price*10; ?>"/>
            
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-lg btn-success update pull-right"><?php echo $this->lang->line('xin_update');?></button>
            </div>
        </div>
        
      </form>
      -->
      
        <div class="alert" align="center" style="font-size:18px;">
           More than 100 employees? <a href="<?php echo site_url('package/custom_price'); ?>" class="alert-link">Get your custom price</a>.
        </div>
      
        <?php if($root_account[0]->is_subscription==1){ ?>
        <div class="row">
            <div class="col-md-12">
                <h6><a class="text-info" href="<?php echo site_url("package/cancel_subscription") ?>">Cancel Subscription</a></h6>
            </div>
        </div>
        <?php } ?>
      
    </div>
  </div>
  
  
  
  
  
  
  
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2> Payment History </h2>
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Order Number</th>
          <th>Name</th>
          <th>Email</th>
          <th>Country</th>
          <th>Amount</th>
          <th>Date</th>
        </tr>
      </thead>
    </table>
    </div>
  </div>
  
  
  
  
  
  
</div>
