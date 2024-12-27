<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct()
     {
          parent::__construct();
          $this->load->library('session');
          $this->load->helper('form');
          $this->load->helper('url');
          $this->load->helper('html');
          $this->load->database();
          $this->load->library('form_validation');
          //load the models
          $this->load->model('Login_model');
		  $this->load->model('Designation_model');
		  $this->load->model('Department_model');
		  $this->load->model('Employees_model');
		  $this->load->model('Xin_model');
		  $this->load->model('Expense_model');
		  $this->load->model('Document_model');
		  $this->load->model('Timesheet_model');
		  $this->load->model('Job_post_model');
		  $this->load->model('Project_model');
		  $this->load->model('Awards_model');
		  $this->load->model('Announcement_model');
		  $this->load->model('Package_model');
     }
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	} 
	
	public function newd()
	{
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		// get user > added by
		$user = $this->Xin_model->read_user_info($session['user_id']);
		// get designation
		$_designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		$data = array(
            'title' => $this->Xin_model->site_title(),
            'breadcrumbs' => $this->lang->line('dashboard_title'),
            'path_url' => 'dashboard',
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'username' => $user[0]->username,
            'email' => $user[0]->email,
            'date_of_birth' => $user[0]->date_of_birth,
            'date_of_joining' => $user[0]->date_of_joining,
            'contact_no' => $user[0]->contact_no,
            'last_four_employees' => $this->Xin_model->last_four_employees(),
            'last_jobs' => $this->Xin_model->last_jobs(),
            'employee_count' => $this->Xin_model->employee_count(),
            'department_count' => $this->Xin_model->department_count(),
            'request_count' => $this->Xin_model->request_count(),
            'my_request_count' => $this->Xin_model->get_employee_requests_count($user[0]->user_id),
            'document_count' => $this->Xin_model->countExpiredDocuments(),
            'my_document_count' => $this->Xin_model->countExpiredDocuments($user[0]->user_id),
            'expense_count' => $this->Xin_model->expense_count(),
            'my_expense_count' => $this->Xin_model->expense_count($user[0]->user_id),
            'asset_count' => $this->Xin_model->asset_count(),
            'asset_count' => $this->Xin_model->asset_count(),
            'requests' => $this->Xin_model->get_employee_requests_dashboard(),
            'my_requests' => $this->Xin_model->get_employee_requests_dashboard_user($user[0]->user_id),
            'birthdays' => $this->Xin_model->getUpcomingBirthdays(),
            'leave_balance' =>$this->check_leave_balance($user[0]->user_id),
            'unpaid_leaves_count' => $this->Timesheet_model->count_total_un_paid_leaves($user[0]->user_id),
            'ticket_balance'=>round($this->check_ticket_balance($user[0]->user_id),3),

        );

        $data['subview'] = $this->load->view('dashboard/dashboard_user', $data, TRUE);
			$this->load->view('layout_main', $data); //page load
	}
    public function check_ticket_balance($id=null)
    {
        $employee_id = $this->input->post('employee_id') ?? $id;
        $date = $this->input->post('date');

        $user_info = $this->Xin_model->read_user_info($employee_id);
        if($user_info[0]->ticket_eligibilty){
            if ($date)
                $result = $this->Xin_model->check_ticket_balance($employee_id, $date);
            else
                $result = $this->Xin_model->check_ticket_balance($employee_id);

            if ($result && isset($result->ticket_date) && $result->ticket_date != '0000-00-00') {
                $last_date =$result->ticket_date;

            } else {
                $last_date = $user_info[0]->date_of_joining;
            }
            if ($date)
                $current_date = new DateTime($date);
            else
                $current_date = new DateTime();
            $last_date_time = new DateTime($last_date);
            $interval = date_diff($current_date, $last_date_time);
            $days_since_doj = $interval->days;

            $unpaid_leaves = $this->Timesheet_model->count_all_unpaid_leaves($employee_id);

            if ($unpaid_leaves)
                $days_since_doj = $days_since_doj - ($unpaid_leaves); // add unpaid leaves to years of service

            $ticket_balance = $days_since_doj / 365;

            if ($result && $result->remaining_balance) {
                $ticket_balance+=$result->remaining_balance;
            }

        }else
        {
            $ticket_balance = 0;
        }
        if($id)
            return round($ticket_balance,3);
        else
            echo round($ticket_balance,3);


    }

    public function index()
	{
		$session = $this->session->userdata('username');
		if(!empty($session)){
	    
		} else {
			redirect('');
		}
		// get user > added by
		$user = $this->Xin_model->read_user_info($session['user_id']);
		// get designation
		$data = array(
            'title' => $this->Xin_model->site_title(),
            'breadcrumbs' => $this->lang->line('dashboard_title'),
            'path_url' => 'dashboard',
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'username' => $user[0]->username,
            'email' => $user[0]->email,
            'date_of_birth' => $user[0]->date_of_birth,
            'date_of_joining' => $user[0]->date_of_joining,
            'contact_no' => $user[0]->contact_no,
            'last_four_employees' => $this->Xin_model->last_four_employees(),
            'last_jobs' => $this->Xin_model->last_jobs(),
            'employee_count' => $this->Xin_model->employee_count(),
            'department_count' => $this->Xin_model->department_count(),
            'request_count' => $this->Xin_model->request_count(),
            'my_request_count' => $this->Xin_model->get_employee_requests_count($user[0]->user_id),
            'document_count' => $this->Xin_model->countExpiredDocuments(),
            'my_document_count' => $this->Xin_model->countExpiredDocuments($user[0]->user_id),
            'expense_count' => $this->Xin_model->expense_count(),
            'my_expense_count' => $this->Xin_model->expense_count($user[0]->user_id),
            'asset_count' => $this->Xin_model->asset_count(),
            'asset_count' => $this->Xin_model->asset_count(),
            'requests' => $this->Xin_model->get_employee_requests_dashboard(),
            'my_requests' => $this->Xin_model->get_employee_requests_dashboard_user($user[0]->user_id),
            'birthdays' => $this->Xin_model->getUpcomingBirthdays(),
            'leave_balance' =>$this->check_leave_balance($user[0]->user_id),
            'unpaid_leaves_count' => $this->Timesheet_model->count_total_un_paid_leaves($user[0]->user_id),
            'ticket_balance'=>round($this->check_ticket_balance($user[0]->user_id),3),

        );

        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('11',$role_resources_ids)) {

            $data['subview'] = $this->load->view('dashboard/dashboard', $data, TRUE);
        }else{
            $data['subview'] = $this->load->view('dashboard/dashboard_user', $data, TRUE);

        }
        $this->load->view('layout_main', $data); //page load

    }
	
	// get opened and closed tickets for chart
	public function tickets_data()
	{
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('opened'=>'', 'closed'=>'');
		// open
		$Return['opened'] = $this->Xin_model->all_open_tickets();
		// closed
		$Return['closed'] = $this->Xin_model->all_closed_tickets();
		$this->output($Return);
		exit;
	}
	
	// get company wise salary
	public function payroll_company_wise()
	{
		$Return = array('chart_data'=>'', 'c_name'=>'', 'c_am'=>'','c_color'=>'');
		$c_name = array();
		$c_am = array();	
		$c_color = array('#ff4dff','#a64dff','#cc33ff','#9966ff','#0099ff','#33cc33','#ff4dff','#ff1aff','#0099cc','#ff0066');
		$someArray = array();
		$j=0;
		foreach($this->Xin_model->all_companies_chart() as $comp) {
		$company_pay = $this->Xin_model->get_company_make_payment($comp->company_id);
		$c_name[] = htmlspecialchars_decode($comp->name);
		$c_am[] = $company_pay[0]->paidAmount;
		$someArray[] = array(
		  'label'   => htmlspecialchars_decode($comp->name),
		  'value' => $company_pay[0]->paidAmount,
		  'bgcolor' => $c_color[$j]
		  );
		  $j++;
		}
		$Return['c_name'] = $c_name;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $someArray;
		$this->output($Return);
		exit;
	}
	
	// get location|station wise salary
	public function payroll_location_wise()
	{
		$Return = array('chart_data'=>'', 'c_name'=>'', 'c_am'=>'','c_color'=>'');
		$c_name = array();
		$c_am = array();	
		$c_color = array('#3e70c9','#f59345','#f44236','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC');
		$someArray = array();
		$j=0;
		foreach($this->Xin_model->all_location_chart() as $location) {
		$location_pay = $this->Xin_model->get_location_make_payment($location->location_id);
		$c_name[] = htmlspecialchars_decode($location->location_name);
		$c_am[] = $location_pay[0]->paidAmount;
		$someArray[] = array(
		  'label'   => htmlspecialchars_decode($location->location_name),
		  'value' => $location_pay[0]->paidAmount,
		  'bgcolor' => $c_color[$j]
		  );
		  $j++;
		}
		$Return['c_name'] = $c_name;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $someArray;
		$this->output($Return);
		exit;
	}
	
	// get department wise salary
	public function payroll_department_wise()
	{
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('chart_data'=>'', 'c_name'=>'', 'c_am'=>'','c_color'=>'');
		$c_name = array();
		$c_am = array();	
		$c_color = array('#3e70c9','#f59345','#f44236','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC');
		$someArray = array();
		$j=0;
		foreach($this->Xin_model->all_departments_chart() as $department) {
		$department_pay = $this->Xin_model->get_department_make_payment($department->department_id);
		$c_name[] = htmlspecialchars_decode($department->department_name);
		$c_am[] = $department_pay[0]->paidAmount;
		$someArray[] = array(
		  'label'   => htmlspecialchars_decode($department->department_name),
		  'value' => $department_pay[0]->paidAmount,
		  'bgcolor' => $c_color[$j]
		  );
		  $j++;
		}
		$Return['c_name'] = $c_name;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $someArray;
		$this->output($Return);
		exit;
	}
	
	// get designation wise salary
	public function payroll_designation_wise()
	{
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('chart_data'=>'', 'c_name'=>'', 'c_am'=>'','c_color'=>'');
		$c_name = array();
		$c_am = array();	
		$c_color = array('#1AAF5D','#F2C500','#F45B00','#8E0000','#0E948C','#6495ED','#DC143C','#006400','#556B2F','#9932CC');
		$someArray = array();
		$j=0;
		foreach($this->Xin_model->all_designations_chart() as $designation) {
		$result = $this->Xin_model->get_designation_make_payment($designation->designation_id);
		$c_name[] = htmlspecialchars_decode($designation->designation_name);
		$c_am[] = $result[0]->paidAmount;
		$someArray[] = array(
		  'label'   => htmlspecialchars_decode($designation->designation_name),
		  'value' => $result[0]->paidAmount,
		  'bgcolor' => $c_color[$j]
		  );
		  $j++;
		}
		$Return['c_name'] = $c_name;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $someArray;
		$this->output($Return);
		exit;
	}
	
	// set new language
	public function set_language($language = "") {
        
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        redirect($_SERVER['HTTP_REFERER']);
        
    }
    
    public function check_session(){
       $cookie_name = "myhrmusername";

        if(!isset($_COOKIE[$cookie_name])) {
            echo 0;
        } else {
            $session_data = unserialize($_COOKIE[$cookie_name]);
            $session_data = array(
        	'user_id' => $session_data['user_id'],
        	'username' => $session_data['username'],
        	'email' => $session_data['email'],
        	'root_id' => $session_data['root_id'],
        	);
        	$this->session->set_userdata('username', $session_data);
            $_SESSION['user_id'] = $session_data['user_id'];
            $_SESSION['root_id'] = $session_data['root_id'];
            echo 1;
        }
        
        $this->db->query("UPDATE `xin_employees` SET `online`=1,`last_online`='".time()."' WHERE `user_id`='".$_SESSION['user_id']."' and `root_id`='".$_SESSION['root_id']."'");
        
        $log_out_time = time()-20;
        $this->db->query("UPDATE `xin_employees` SET `online`=0 WHERE `last_online`<'".$log_out_time."'");
    }
    public function check_leave_balance($id=null){
        $employee_id = $this->input->post('employee_id')??$id;
        $date = $this->input->post('date');
        $user_info = $this->Xin_model->read_user_info($employee_id);
        $last_leave = $this->Timesheet_model->getLatestAnnualLeaveForEmployee($employee_id);

        $currentDate = date('Y-m-d');

        // Get the start of the year
//            $startOfYear = date('Y-01-01');
        if($last_leave) {
            $last_doj = $last_leave[0]->end_date;
            $remaining=$last_leave[0]->remaining_balance;
        }
        else {
            $last_doj = $user_info[0]->date_of_joining;
            $remaining=0;
        }
        $daysSinceStartOfYear = intval((strtotime($currentDate) - strtotime($last_doj)) / (60 * 60 * 24));
        $months=$daysSinceStartOfYear/30;
        $balance = 2.5*$months;
        $leave_salary_entries = $this->Xin_model->get_leave_salary_entries($employee_id);
        if($leave_salary_entries){
            foreach ($leave_salary_entries as $entry){
                if($entry->annual_leave_id==0)
                    $balance=$balance-$entry->leave_days;


            }
        }
        $balance= $balance+$remaining;

//        if($balance>30) {
//            $avlbalance =30;
//        }
//        else{
            $avlbalance=round($balance,2);
//        }


//        if($date) {
//            $leave_balance = $this->Xin_model->get_leave_balance($employee_id,$date);
//        }
//        else {
//            $leave_balance = $this->Xin_model->get_leave_balance($employee_id);
//        }

        if($id)
            return $avlbalance;
        else
            echo $avlbalance;



    }


}
