<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
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
	 
	 // leave>index
	 public function index()
     {
        $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Leave';
		$data['path_url'] = 'user/user_leave';
		if(!empty($session)){ 
			$data['subview'] = $this->load->view("user/leave", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
     }
	 
	 // leave list 
	 public function leave_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("timesheet/leave", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		
		$leave = $this->Timesheet_model->get_employee_leaves($session['user_id']);
		
		$data = array();

          foreach($leave->result() as $r) {
			  
			 // get start date and end date
			 $user = $this->Xin_model->read_user_info($r->employee_id);
			 $full_name = $user[0]->first_name. ' '.$user[0]->last_name;
			 
			 // get leave type
		 	 $leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);
			 
			 $applied_on = $this->Xin_model->set_date_format($r->applied_on);
			 $duration = $this->Xin_model->set_date_format($r->from_date).' to '.$this->Xin_model->set_date_format($r->to_date);
			 
			 // get status
			 if($r->status==1): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==2): $status = '<span class="tag tag-success">Accepted</span>'; elseif($r->status==3): $status = '<span class="tag tag-warning">Rejected</span>'; endif;
              if($r->approval==1): $approvalstatus = '<span class="tag tag-danger">Approved</span>'; elseif($r->approval==2): $approvalstatus = '<span class="tag tag-success">Accepted</span>'; elseif($r->approval==3): $approvalstatus = '<span class="tag tag-warning">Rejected</span>';else:$approvalstatus = '<span class="tag tag-warning">Not Available</span>'; endif;

              $data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '" title="Delete"><i class="fa fa-trash-o"></i></button></span>',
                  $full_name,
				$leave_type[0]->type_name,
				$duration,
				$applied_on,
				$r->reason,
                $approvalstatus,
				$status
		   );
	  }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $leave->num_rows(),
			 "recordsFiltered" => $leave->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
}
