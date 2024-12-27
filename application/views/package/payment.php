<?php
/*
* Package View
*/
$session = $this->session->userdata('username');
?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <form class="m-b-1" action='https://www.2checkout.com/checkout/purchase' method='post'>
          <input type='hidden' name='sid' value='203457002' />
          <input type='hidden' name='mode' value='2CO' />
          <input type='hidden' name='li_0_type' value='product' />
          <input type='hidden' name='li_0_name' value='Corbuz Subscription' />
          <input type='hidden' name='currency_code' value='USD'>
          <input type='hidden' name='li_0_price' value='<?php echo $package_price; ?>' />
          <input type="hidden" name="li_0_recurrence" value="<?php echo $package_months; ?> Month">
          <input type="hidden" name="li_0_duration" value="Forever">
          <p class="h5">Payment Details</p>
          <table class="table table-striped table-bordered dataTable" id="xin_table">
          <tbody>
            <tr>
              <td>Package</td>
              <td><?php echo $package_info[0]->package_name; ?></td>
            </tr>
            <tr>
              <td>Storage</td>
              <td><?php echo $package_info[0]->storage; ?></td>
            </tr>
            <tr>
              <td>Employee Limit</td>
              <td><?php echo $package_info[0]->employees; ?> (Employees)</td>
            </tr>
            <tr>
              <td>Validity</td>
              <td><?php echo $_SESSION['package_months']; ?> Month(s)</td>
            </tr>
            <tr>
              <td><p class="h5">Total Price</p></td>
              <td><p class="h4">$<?php echo number_format($package_price,2); ?></p></td>
            </tr>
          </tbody>
        </table>
        
        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-lg btn-success pull-right">  <i class="fa fa-money fa-lg"></i> Pay Now</button>
                <a href="<?php echo site_url('package'); ?>" onclick="return confirm('Are you sure do you want to cancel this payment?')" class="btn btn-lg btn-default pull-right">  <i class="fa fa-close fa-lg"></i>  Cancel</a>
            </div>
        </div>
        
        <?php
        $payments = $this->Package_model->get_payments();
        foreach($payments->result() as $r) {
               $data = $r;
          }
        if(!empty($payments->result())){
        ?>
            <input type='hidden' name='card_holder_name' value='<?php echo $data->first_name; ?>' >
            <input type='hidden' name='street_address' value='<?php echo $data->street_address; ?>' >
            <input type='hidden' name='city' value='<?php echo $data->city; ?>' >
            <input type='hidden' name='state' value='<?php echo $data->state; ?>' >
            <input type='hidden' name='zip' value='<?php echo $data->zip; ?>' >
            <input type='hidden' name='country' value='<?php echo $data->country; ?>' >
            <input type='hidden' name='email' value='<?php echo $data->email; ?>' >
            <input type='hidden' name='phone' value='<?php echo $data->phone; ?>' >
            <input type='hidden' name='purchase_step' value='payment-method' >
        <?php
        }
        ?>
        
      </form>
    </div>
  </div>
  
</div>
