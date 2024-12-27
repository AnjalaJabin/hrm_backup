<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		// load email library
		
		$this->load->database();
		$this->load->library('Pdf');
		//$this->load->library('email');
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
		 
	 // advance salary
	 public function advance_salary()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = $this->lang->line('xin_advance_salary');
		$data['path_url'] = 'user/advance_salary';
		$role_resources_ids = $this->Xin_model->user_role_resource();
			$data['subview'] = $this->load->view("user/advance_salary", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
     }
     
     // Loan
	 public function loan()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Loan';
		$data['path_url'] = 'user/loan';
		$role_resources_ids = $this->Xin_model->user_role_resource();
			$data['subview'] = $this->load->view("user/loan", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
     }
     
      // loan report
	 public function loan_report()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Loan Report';
		$data['path_url'] = 'user/loan_report';
		$role_resources_ids = $this->Xin_model->user_role_resource();
			$data['subview'] = $this->load->view("user/loan_report", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
     }
	 
	  // advance salary report
	 public function advance_salary_report()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = $this->lang->line('xin_advance_salary_report');
		$data['path_url'] = 'user/advance_salary_report';
		$role_resources_ids = $this->Xin_model->user_role_resource();
			$data['subview'] = $this->load->view("user/advance_salary_report", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
     }
	 
	// advance salary list
    public function advance_salary_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/advance_salary", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$advance_salary = $this->Payroll_model->get_advance_salaries_single($session['user_id']);
		
		$data = array();

          foreach($advance_salary->result() as $r) {

			// get addd by > template
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			if(!is_null($user)){
				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			} else {
				$full_name = '--';	
			}
			
			$d = explode('-',$r->month_year);
			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
			$month_year = $get_month.', '.$d[0];
			// get net salary
			$advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
			// get date > created at > and format
			$cdate = $this->Xin_model->set_date_format($r->created_at);
			// get status
			if($r->status==0): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==1): $status = '<span class="tag tag-success">Accepted</span>'; elseif($r->status==2): $status = '<span class="tag tag-warning">Rejected</span>'; endif;
			
			
			// get monthly installment
			$monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);
			
			// get onetime deduction value
			if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;
			
			$data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-advance_salary_id="'. $r->advance_salary_id . '"><i class="fa fa-eye"></i></button></span>',
                    $advance_amount,
                    $month_year,
					$cdate,
					$status
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $advance_salary->num_rows(),
                 "recordsFiltered" => $advance_salary->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
     
     // loan list
    public function loan_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/loan", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$loan = $this->Payroll_model->get_loan_single($session['user_id']);
		
		$data = array();

          foreach($loan->result() as $r) {

			// get addd by > template
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			if(!is_null($user)){
				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			} else {
				$full_name = '--';	
			}
			
			$d = explode('-',$r->month_year);
			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
			$month_year = $get_month.', '.$d[0];
			// get net salary
			$advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
			// get date > created at > and format
			$cdate = $this->Xin_model->set_date_format($r->created_at);
			
			// get status
			if($r->status==0): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==1): $status = '<span class="tag tag-success">Accepted</span>'; elseif($r->status==2): $status = '<span class="tag tag-warning">Rejected</span>'; endif;
			
			
			// get monthly installment
			$monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);
			
			// get onetime deduction value
			if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;
			
			$data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-loan_id="'. $r->loan_id . '"><i class="fa fa-eye"></i></button></span>',
                    $advance_amount,
                    $monthly_installment,
                    $month_year,
					$cdate,
					number_format($r->total_paid),
					$status
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $loan->num_rows(),
                 "recordsFiltered" => $loan->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 // advance salary report list
    public function advance_salary_report_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/advance_salary", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$advance_salary = $this->Payroll_model->advance_salaries_report_single($session['user_id']);
		
		$data = array();

          foreach($advance_salary->result() as $r) {

			// get addd by > template
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			if(!is_null($user)){
				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			} else {
				$full_name = '--';	
			}
			
			$d = explode('-',$r->month_year);
			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
			$month_year = $get_month.', '.$d[0];
			// get net salary
			$advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
			// get date > created at > and format
			$cdate = $this->Xin_model->set_date_format($r->created_at);
			// get status
			if($r->status==0): $status = $this->lang->line('xin_pending'); elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
			// get monthly installment
			$monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);
			
			$remainig_amount = $r->advance_amount - $r->total_paid;
			$ramount = $this->Xin_model->currency_sign($remainig_amount);
			
			// get onetime deduction value
			if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;
			if($r->advance_amount == $r->total_paid){
				$all_paid = '<span class="tag tag-success">'.$this->lang->line('xin_all_paid').'</span>';
			} else {
				$all_paid = '<span class="tag tag-warning">'.$this->lang->line('xin_remaining').'</span>';
			}
			//total paid
			$total_paid = $this->Xin_model->currency_sign($r->total_paid);
			
			$data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-employee_id="'. $r->employee_id . '"><i class="fa fa-eye"></i></button></span>',
                    $advance_amount,
                    $total_paid,
					$ramount,
					$all_paid,
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $advance_salary->num_rows(),
                 "recordsFiltered" => $advance_salary->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
     
     // loan report list
    public function loan_report_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/loan", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$loan = $this->Payroll_model->loan_report_single($session['user_id']);
		
		$data = array();

          foreach($loan->result() as $r) {

			// get addd by > template
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			if(!is_null($user)){
				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			} else {
				$full_name = '--';	
			}
			
			$d = explode('-',$r->month_year);
			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
			$month_year = $get_month.', '.$d[0];
			// get net salary
			$advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
			// get date > created at > and format
			$cdate = $this->Xin_model->set_date_format($r->created_at);
			// get status
			if($r->status==0): $status = $this->lang->line('xin_pending'); elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
			// get monthly installment
			$monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);
			
			$remainig_amount = $r->advance_amount - $r->total_paid;
			$ramount = $this->Xin_model->currency_sign($remainig_amount);
			
			// get onetime deduction value
			if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;
			if($r->advance_amount == $r->total_paid){
				$all_paid = '<span class="tag tag-success">'.$this->lang->line('xin_all_paid').'</span>';
			} else {
				$all_paid = '<span class="tag tag-warning">'.$this->lang->line('xin_remaining').'</span>';
			}
			//total paid
			$total_paid = $this->Xin_model->currency_sign($r->total_paid);
			
			$data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-employee_id="'. $r->employee_id . '"><i class="fa fa-eye"></i></button></span>',
                    $advance_amount,
                    $total_paid,
					$ramount,
					$all_paid,
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $loan->num_rows(),
                 "recordsFiltered" => $loan->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	
	// get advance salary info by id
	public function advance_salary_read()
	{
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('advance_salary_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_advance_salary_info($id);
		$data = array(
				'advance_salary_id' => $result[0]->advance_salary_id,
				'employee_id' => $result[0]->employee_id,
				'month_year' => $result[0]->month_year,
				'advance_amount' => $result[0]->advance_amount,
				'one_time_deduct' => $result[0]->one_time_deduct,
				'monthly_installment' => $result[0]->monthly_installment,
				'reason' => $result[0]->reason,
				'status' => $result[0]->status,
				'created_at' => $result[0]->created_at,
				'all_employees' => $this->Xin_model->all_employees()
				);
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_advance_salary', $data);
		} else {
			redirect('');
		}
	}
	
	// get advance salary info by id
	public function advance_salary_report_read()
	{
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->advance_salaries_report_view($id);
		$data = array(
				'advance_salary_id' => $result[0]->advance_salary_id,
				'employee_id' => $result[0]->employee_id,
				'month_year' => $result[0]->month_year,
				'advance_amount' => $result[0]->advance_amount,
				'total_paid' => $result[0]->total_paid,
				'one_time_deduct' => $result[0]->one_time_deduct,
				'monthly_installment' => $result[0]->monthly_installment,
				'reason' => $result[0]->reason,
				'status' => $result[0]->status,
				'created_at' => $result[0]->created_at,
				'all_employees' => $this->Xin_model->all_employees()
				);
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_advance_salary', $data);
		} else {
			redirect('');
		}
	}
	
	// add advance salary
	// Validate and add info in database
	public function add_advance_salary() {
	
		if($this->input->post('add_type')=='advance_salary') {	
		
		$session = $this->session->userdata('username');	
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$reason = $this->input->post('reason');
		
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
			
		/* Server side PHP input validation */		
		if($this->input->post('month_year')==='') {
			$Return['error'] = $this->lang->line('xin_error_advance_salary_month_year');
		} else if($this->input->post('amount')==='') {
			$Return['error'] = $this->lang->line('xin_error_amount_field');
		}
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		// get one time value
		if($this->input->post('one_time_deduct')==1){
			$monthly_installment = 0;
		} else {
			$monthly_installment = $this->input->post('monthly_installment');
		}
	
		$data = array(
		'employee_id' => $session['user_id'],
		'reason' => $qt_reason,
		'month_year' => $this->input->post('month_year'),
		'advance_amount' => $this->input->post('amount'),
		'monthly_installment' => $monthly_installment,
		'total_paid' => 0,
		'one_time_deduct' => $this->input->post('one_time_deduct'),
		'status' => 0,
		'created_at' => date('Y-m-d h:i:s')
		);
		
		$result = $this->Payroll_model->add_advance_salary_payroll($data);
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_request_sent_advance_salary');
			
			
			
			//get setting info 
			$setting = $this->Xin_model->read_setting_info(1);
//			if($setting[0]->enable_email_notification == 'yes') {
//
//				//load email library
//				$this->load->library('email');
//				$this->email->set_mailtype("html");
//				//get company info
//				$cinfo = $this->Xin_model->read_company_setting_info(1);
//				//get email template
//				$template = $this->Xin_model->read_email_template(16);
//				//get employee info
//				$user_info = $this->Xin_model->read_user_info($session['user_id']);
//				$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
//
//				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
//				$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
//
//				$message = '
//			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
//			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}"),array($cinfo[0]->company_name,site_url(),$full_name),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
//
//				/*
//				$this->email->from($user_info[0]->email, $full_name);
//				$this->email->to($cinfo[0]->email);
//
//				$this->email->subject($subject);
//				$this->email->message($message);
//
//				$this->email->send();
//				*/
//
//				require '../mail/gmail.php';
//				//echo '<pre>';
//				$allemails = $this->Xin_model->get_manager_emails(36);
//				foreach($allemails as $email_data)
//				{
//				    $mail->AddBCC($email_data['email'], $email_data['name']);
//				}
//
//				//print_r($mail);
//                $mail->Subject = $subject;
//                $mail->msgHTML($message);
//
//                if (!$mail->send()) {
//                    //echo "Mailer Error: " . $mail->ErrorInfo;
//                } else {
//                    //echo "Message sent!";
//                }
//
//			}
			
			
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// add loan
	// Validate and add info in database
	public function add_loan() {
	
		if($this->input->post('add_type')=='loan') {	
		
		$session = $this->session->userdata('username');	
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$reason = $this->input->post('reason');
		
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
			
		/* Server side PHP input validation */		
		if($this->input->post('month_year')==='') {
			$Return['error'] = $this->lang->line('xin_error_advance_salary_month_year');
		} else if($this->input->post('amount')==='') {
			$Return['error'] = $this->lang->line('xin_error_amount_field');
		}
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		// get one time value
		if($this->input->post('one_time_deduct')==1){
			$monthly_installment = 0;
		} else {
			$monthly_installment = $this->input->post('monthly_installment');
		}
	
		$data = array(
		'employee_id' => $session['user_id'],
		'reason' => $qt_reason,
		'month_year' => $this->input->post('month_year'),
		'advance_amount' => $this->input->post('amount'),
		'monthly_installment' => $monthly_installment,
		'total_paid' => 0,
		'one_time_deduct' => $this->input->post('one_time_deduct'),
		'status' => 0,
		'created_at' => date('Y-m-d h:i:s')
		);
		
		$result = $this->Payroll_model->add_loan_payroll($data);
		
		if ($result == TRUE) {
			$Return['result'] = 'Loan Request Successfully Sent.';
			
			
			
			
			//get setting info 
			$setting = $this->Xin_model->read_setting_info(1);
//			if($setting[0]->enable_email_notification == 'yes') {
//
//				//load email library
//				$this->load->library('email');
//				$this->email->set_mailtype("html");
//				//get company info
//				$cinfo = $this->Xin_model->read_company_setting_info(1);
//				//get email template
//				$template = $this->Xin_model->read_email_template(17);
//				//get employee info
//				$user_info = $this->Xin_model->read_user_info($session['user_id']);
//				$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
//
//				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
//				$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
//
//				$message = '
//			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
//			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}"),array($cinfo[0]->company_name,site_url(),$full_name),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
//
//				/*
//				$this->email->from($user_info[0]->email, $full_name);
//				$this->email->to($cinfo[0]->email);
//
//				$this->email->subject($subject);
//				$this->email->message($message);
//
//				$this->email->send();
//				*/
//
//				require '../mail/gmail.php';
//				//echo '<pre>';
//				$allemails = $this->Xin_model->get_manager_emails(36);
//				foreach($allemails as $email_data)
//				{
//				    $mail->AddBCC($email_data['email'], $email_data['name']);
//				}
//
//				//print_r($mail);
//                $mail->Subject = $subject;
//                $mail->msgHTML($message);
//
//                if (!$mail->send()) {
//                    //echo "Mailer Error: " . $mail->ErrorInfo;
//                } else {
//                    //echo "Message sent!";
//                }
//
//			}
			
			
			
			
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
}
