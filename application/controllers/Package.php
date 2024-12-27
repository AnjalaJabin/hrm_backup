<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Package_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	 public function index()
     {
        $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		
		$data['title'] = $this->Xin_model->site_title();
		$data['all_packages'] = $this->Package_model->get_packages();
		$data['root_account'] = $this->Xin_model->get_root_account();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Packages';
		$data['path_url'] = 'package';
		$role_resources_ids = $this->Xin_model->user_role_resource();
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("package/package_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}	  
     }
 
    public function package_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("package/package_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$designation = $this->Package_model->get_packages();
		
		$data = array();

          foreach($designation->result() as $r) {
			  
			  // get user > added by
			  $user = $this->Xin_model->read_user_info($r->added_by);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;

               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-designation_id="'. $r->designation_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->designation_id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->designation_name,
                    $department[0]->department_name,
					$full_name
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $designation->num_rows(),
                 "recordsFiltered" => $designation->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
     
     
     
     // All Payments List
     public function payment_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("package/package_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$payments = $this->Package_model->get_payments();
		
		$data = array();

          foreach($payments->result() as $r) {

               $data[] = array(
			   		$r->order_number,
			   		$r->first_name.' '.$r->last_name,
			   		$r->email,
			   		$r->ip_country,
			   		$r->currency_code.' '.$r->total,
			   		date('d M Y', strtotime($r->date))
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $payments->num_rows(),
                 "recordsFiltered" => $payments->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='package') {
		$plan            = $this->input->post('plan');
		$total_employees = $this->Employees_model->get_total_employees();
		$root_account    = $this->Xin_model->get_root_account();
		$package_info    = $this->Package_model->read_package_information($plan);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'redirect'=>'');
		
		if($total_employees>$package_info[0]->employees) {
			$Return['error'] = "You can't downgrade this package because you already have ".$total_employees." employees";
		}
		else
		{
	        if($plan==1)
	        {
	            if($root_account[0]->package_id==$plan) {
        			$Return['error'] = "You are using the same plan";
        		}
        		else
        		{
            		$data = array(
            		'package_id' => $this->input->post('plan'),	
            		);
            		
            		$result = $this->Package_model->update_record($data);		
            		
            		if ($result == TRUE) {
            			$Return['result'] = 'Package Successfully Updated';
            		} else {
            			$Return['error'] = $this->lang->line('xin_error_msg');
            		}
        		}
	        }
	        else
	        {
	            $_SESSION['package_price'] = $this->input->post('total_price');
	            $_SESSION['package_id']  = $plan;
	            if($this->input->post('plan_type')=='yearly')
	            {
	                $_SESSION['package_months'] = 12;
	                $_SESSION['package_price'] = $package_info[0]->price*10;
	            }
	            else
	            {
	                $_SESSION['package_months'] = 1;
	                $_SESSION['package_price'] = $package_info[0]->price;
	            }
	            
	            $Return['redirect'] = site_url("package/payment");
	            
	        }
		
		}
		
		$this->output($Return);
		exit;
		}
	}
	
	
	public function plans() {
        $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Package > Plans';
		$data['all_packages'] = $this->Package_model->get_packages();
		$data['root_account'] = $this->Xin_model->get_root_account();
		$data['path_url'] = 'plans';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(!empty($session)){ 
		$data['subview'] = $this->load->view("package/plans", $data, TRUE);
		$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}
	
	
	public function payment() {
	    if(!empty($_SESSION['package_price']))
	    {
	        $session = $this->session->userdata('username');
    		if(!empty($session)){ 
    			
    		} else {
    			redirect('');
    		}
    		$plan = $_SESSION['package_id'];
    		$package_info    = $this->Package_model->read_package_information($plan);
    		
    		if($_SESSION['package_months']==1)
    		{
    		    $package_price = $package_info[0]->price;
    		}
    		else if($_SESSION['package_months']==12)
    		{
    		    $package_price = $package_info[0]->price*10;
    		}
    		
    		$data['title'] = $this->Xin_model->site_title();
    		$session = $this->session->userdata('username');
    		$data['breadcrumbs'] = 'Payment';
    		$data['path_url'] = 'package';
    		$data['package_info'] = $this->Package_model->read_package_information($plan);
    		$data['package_price']  = $package_price;
    		$data['package_months']  = $_SESSION['package_months'];
    		$role_resources_ids = $this->Xin_model->user_role_resource();
    			if(!empty($session)){ 
    			$data['subview'] = $this->load->view("package/payment", $data, TRUE);
    			$this->load->view('layout_main', $data); //page load
    			} else {
    				redirect('');
    			}
	    }
	    else
	    {
	        redirect('');
	    }
	}
	
	
	public function cancel_subscription() {
        $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Cancel Subscription';
		$data['path_url'] = 'cancel_subscription';
		if(!empty($session)){ 
		$data['subview'] = $this->load->view("package/cancel_subscription", $data, TRUE);
		$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}
	
	
	public function cancel_subscription_update() {
	
		if($this->input->post('edit_type')=='cancel_subscription') {
		$reason            = $this->input->post('reason');
		$des               = $this->input->post('des');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'redirect'=>'');
		
		if(empty($reason)) {
			$Return['error'] = "Please select one option.";
		}
		elseif(empty($des)) {
			$Return['error'] = "Please type something.";
		}
		else
		{
	        $root_account_data = $this->Xin_model->get_root_account();
    		$order_id = $root_account_data[0]->order_number;
    		if($order_id>0)
    		{
    		    $this->stop_subscription_payment($order_id);
    		}
    		$this->db->query("INSERT INTO `canceled_subscriptions`(`root_id`, `employee_id`, `reason`, `des`, `date`) VALUES ('".$_SESSION['root_id']."','".$_SESSION['user_id']."','".$reason."','".$des."','".date('Y-m-d H:i:s')."')");
    		$data = array(
    		'is_subscription' => 0,
    		);
    		$result = $this->Package_model->update_record($data);
    		
    		$Return['redirect'] = site_url("package");
		}
		
		$this->output($Return);
		exit;
		}
	}
	
	
	public function twocheckoutglobalre(){
	    
	    if ($_POST['message_type'] == 'FRAUD_STATUS_CHANGED') {

            $insMessage = array();
            foreach ($_POST as $k => $v) {
            $insMessage[$k] = $v;
            }
    
            # Validate the Hash
            $hashSecretWord = 'YWFiMzFhMTYtMTU0NC00NGQxLTkyODEtMjNiYjBlOTUxOTdj'; //2Checkout Secret Word
            $hashSid = 203457002; //2Checkout account number
            $hashOrder = $insMessage['sale_id'];
            $hashInvoice = $insMessage['invoice_id'];
            $StringToHash = strtoupper(md5($hashOrder . $hashSid . $hashInvoice . $hashSecretWord));
    
            if ($StringToHash != $insMessage['md5_hash']) {
                die('Hash Incorrect');
            }
            else
            {
    
                switch ($insMessage['fraud_status']) {
                    case 'pass':
                          $f_status = 'pass';
                        break;
                    case 'fail':
                          $f_status = 'fail';
                        break;
                    case 'wait':
                          $f_status = 'wait';
                        break;
                }
                $this->db->query("UPDATE `payment_data` SET `fraud_status`='".$f_status."' WHERE `order_number`='".$insMessage['sale_id']."'");
            }
        }
        
        if ($_POST['message_type'] == 'RECURRING_INSTALLMENT_SUCCESS') {
            
            $insMessage = array();
            foreach ($_POST as $k => $v) {
            $insMessage[$k] = $v;
            }
        
            # Validate the Hash
            $hashSecretWord = 'YWFiMzFhMTYtMTU0NC00NGQxLTkyODEtMjNiYjBlOTUxOTdj'; //2Checkout Secret Word
            $hashSid = 203457002; //2Checkout account number
            $hashOrder = $insMessage['sale_id'];
            $hashInvoice = $insMessage['invoice_id'];
            $StringToHash = strtoupper(md5($hashOrder . $hashSid . $hashInvoice . $hashSecretWord));
    
            if ($StringToHash != $insMessage['md5_hash']) {
                die('Hash Incorrect');
            }
            else
            {
                $order_paid_data = $this->db->query("SELECT * FROM `payment_data` WHERE `order_number`='".$insMessage['sale_id']."' order by id desc limit 1");
                $order_paid_data = $order_paid_data->result();
                $order_paid_data = json_decode(json_encode($order_paid_data), True);
                $data = $order_paid_data[0];
                $data['invoice_id'] = $insMessage['invoice_id'];
                $data['date']       = $insMessage['timestamp'];
                $result = $this->Package_model->add_re_payment_data($data);
                
                $root_paid_data = $this->db->query("SELECT * FROM `root_accounts` WHERE `order_number`='".$insMessage['sale_id']."' order by id desc limit 1");
                $root_paid_data = $root_paid_data->result();
                $root_paid_data = json_decode(json_encode($root_paid_data), True);
                
                $end_date = $root_paid_data[0]['end_date'];
                if($root_paid_data[0]['monthly']==1)
                {
                    $time = strtotime($root_paid_data[0]['end_date']);
                    $end_date = date("Y-m-d", strtotime("+1 month", $time));
                }
                else if($root_paid_data[0]['yearly']==1)
                {
                    $time = strtotime($root_paid_data[0]['end_date']);
                    $end_date = date("Y-m-d", strtotime("+12 month", $time));
                }
                
                $data = array(
        		'root_id' => $root_paid_data[0]['root_id'],
        		'end_date' => $end_date,
        		);
        		
        		$result = $this->Package_model->update_re_root_record($data);
            }
        }
	    
	    //$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    $this->db->query("INSERT INTO `global_re`(`data`, `date`) VALUES ('".$_POST['message_type']."','".date('Y-m-d H:i:s')."')");
	}
	
	
	private function stop_subscription_payment($order_id){
	    require_once("system/libraries/2checkout/lib/Twocheckout.php");
	    Twocheckout::username('corbuzmainapi');
        Twocheckout::password('Dubai@2020');
        //Twocheckout::sandbox(true);  #Uncomment to use Sandbox
        
        $args = array(
            'sale_id' => $order_id
        );
        try {
            $result = Twocheckout_Sale::stop($args);
        } catch (Twocheckout_Error $e) {
            $e->getMessage();
        }
	}
	
	
	public function payment_return(){
	    $hashSecretWord = 'YWFiMzFhMTYtMTU0NC00NGQxLTkyODEtMjNiYjBlOTUxOTdj'; //2Checkout Secret Word
        $hashSid = 203457002; //2Checkout account number
        //$hashSecretWord = 'OGMxZTFlMGQtYjE3Mi00MWVkLWIzZjktMGIyYWM3N2M3NGM3'; //2Checkout Secret Word
        //$hashSid = 901363887; //2Checkout account number
        $hashTotal = $_SESSION['package_price'].'.00'; //Sale total to validate against
        $hashOrder = $_REQUEST['order_number']; //2Checkout Order Number
        
        $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
        //$StringToHash = strtoupper(md5($hashSecretWord . $hashSid . 1 . $hashTotal));
        
        if ($StringToHash != $_REQUEST['key']) {
          $status = 0;
        } else {
    		
    		$root_account_data = $this->Xin_model->get_root_account();
    		$order_id = $root_account_data[0]->order_number;
    		if($order_id>0)
    		{
    		    $this->stop_subscription_payment($order_id);
    		}
    		if($root_account_data[0]->is_subscription==0)
    		{
    		    $start_date = date('Y-m-d');
    		}
    		else
    		{
    		    $start_date = $root_account_data[0]->end_date;
    		}
    		
    		$end_date = date('Y-m-d', strtotime(date("Y-m-d", strtotime($start_date)) . " +".$_SESSION['package_months'].' months'));
    		
    		if($_SESSION['package_months']==12)
    		{
    		    $monthly_sub = 0;
    		    $yearly_sub  = 1;
    		}
    		else
    		{
    		    $monthly_sub = 1;
    		    $yearly_sub  = 0;
    		}
    		
    		$data = array(
    		'package_id' => $_SESSION['package_id'],
    		'start_date' => $start_date,
    		'end_date' => $end_date,
    		'order_number' => $hashOrder,
    		'is_subscription' => 1,
    		'monthly' => $monthly_sub,
    		'yearly'  => $yearly_sub,
    		);
    		
    		$result = $this->Package_model->update_record($data);
    		
    		$status = 1;
        }
        $re_data = $_REQUEST;
        
        if(!empty($_REQUEST['order_number']))
        {
            $session = $this->session->userdata('username');
            $data = array(
				'emp_id' => $session['user_id'],
				'first_name' => $re_data['first_name'],
				'last_name' => $re_data['last_name'],
				'plan_id' => $_SESSION['package_id'],
				'months' => $_SESSION['package_months'],
				'key_val' => $re_data['key'],
				'order_number' => $re_data['order_number'],
				'invoice_id' => $re_data['invoice_id'],
				'total' => $re_data['total'],
				'credit_card_processed' => $re_data['credit_card_processed'],
				'zip' => $re_data['zip'],
				'email' => $re_data['email'],
				'currency_code' => $re_data['currency_code'],
				'country' => $re_data['country'],
				'state' => $re_data['state'],
				'city' => $re_data['city'],
				'street_address' => $re_data['street_address'],
				'merchant_order_id' => $re_data['merchant_order_id'],
				'ip_country' => $re_data['ip_country'],
				'pay_method' => $re_data['pay_method'],
				'phone' => $re_data['phone'],
				'street_address2' => $re_data['street_address2'],
				'card_holder_name' => $re_data['card_holder_name'],
				'date' => date('Y-m-d H:i:s'),
				'status' => $status
				);
            $result = $this->Package_model->add_payment_data($data);
            header('location:'.site_url('package?order_id='.$re_data['order_number']));
        }
	}
	
	
	
	// Check Package Info
	public function check_package() {
	
		if($this->input->post('edit_type')=='package') {
		$plan            = $this->input->post('plan');
		$total_employees = $this->Employees_model->get_total_employees();
		$root_account    = $this->Xin_model->get_root_account();
		$package_info    = $this->Package_model->read_package_information($plan);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		if($total_employees>$package_info[0]->employees) {
			$Return['error'] = "You can't downgrade this package because you already have ".$total_employees." employees";
		}
		else if($root_account[0]->package_id==$plan) {
			$Return['error'] = "You are using the same plan";
		}
		else
		{
    		$Return['success'] = 1;
		}
		
		$this->output($Return);
		exit;
		}
	}
	
	
	public function custom_price(){
	    $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Package > Custom Price';
		$data['path_url'] = 'custom_price';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(!empty($session)){ 
		$data['subview'] = $this->load->view("package/custom_price", $data, TRUE);
		$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_custom_price() {
	
		if($this->input->post('add_type')=='custom_price') {
		// Check validation for user input
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('name')==='') {
        	$Return['error'] = 'Name is required';
		} else if($this->input->post('email')==='') {
			$Return['error'] = 'Email is required';
		} else if($this->input->post('phone')==='') {
			$Return['error'] = 'Phone number is required';
		} else if($this->input->post('employees')==='') {
			$Return['error'] = 'Number of employees is required';
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
    	
	    $session = $this->session->userdata('username');
		$data = array(
		'name' => $this->input->post('name'),
		'email' => $this->input->post('email'),
		'phone' => $this->input->post('phone'),
		'note' => $this->input->post('note'),
		'employees' => $this->input->post('employees'),
		'emp_id' => $session['user_id'],
		'date' => date('Y-m-d H:i:s'),
		);
		
		$result = $this->Package_model->add_custom_price($data);
		if ($result == TRUE) {
			$Return['result'] = 'Thank you for your request. We will get back to you soon.';
			
			$root_account    = $this->Xin_model->get_root_account();
			$message = 'Hi<br>You Have a new custom price request from corbuz.com<br>Name : '.$this->input->post('name').'<br> Phone : '.$this->input->post('phone').'<br> Email : '.$this->input->post('email').'<br> Number of employees : '.$this->input->post('employees').'<br> Note : '.$this->input->post('note').'<br> Company Name : '.$root_account[0]->company_name;
			require '../mail/gmail.php';
            $mail->addAddress('info@gligx.com');
            $mail->Subject = 'New custom price request from corbuz';
            $mail->msgHTML($message);
            
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
            }
			
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
}
