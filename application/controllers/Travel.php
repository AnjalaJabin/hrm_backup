<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Travel extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Travel_model");
		$this->load->model("Xin_model");
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
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['travel_arrangement_types'] = $this->Travel_model->travel_arrangement_types();
		$data['breadcrumbs'] = 'Travel';
		$data['path_url'] = 'travel';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('18',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("travel/travel_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function travel_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("travel/travel_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$travel = $this->Travel_model->get_travel();
		
		$data = array();

          foreach($travel->result() as $r) {
			 			  
		// get user > added by
		$user = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		
		// get user > employee_
		$employee = $this->Xin_model->read_user_info($r->employee_id);
		// employee full name
		$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
		// get start date
		$start_date = $this->Xin_model->set_date_format($r->start_date);
		// get end date
		$end_date = $this->Xin_model->set_date_format($r->end_date);
		// status
		if($r->status==0): $status = 'Pending';
		elseif($r->status==1): $status = 'Accepted'; else: $status = 'Rejected'; endif;
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-travel_id="'. $r->travel_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-travel_id="'. $r->travel_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->travel_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$employee_name,
			$r->visit_purpose,
			$r->visit_place,
			$start_date,
			$end_date,
			$status,
			$full_name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $travel->num_rows(),
			 "recordsFiltered" => $travel->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('travel_id');
		$result = $this->Travel_model->read_travel_information($id);
		$data = array(
				'travel_id' => $result[0]->travel_id,
				'employee_id' => $result[0]->employee_id,
				'start_date' => $result[0]->start_date,
				'end_date' => $result[0]->end_date,
				'visit_purpose' => $result[0]->visit_purpose,
				'visit_place' => $result[0]->visit_place,
				'travel_mode' => $result[0]->travel_mode,
				'arrangement_type' => $result[0]->arrangement_type,
				'expected_budget' => $result[0]->expected_budget,
				'actual_budget' => $result[0]->actual_budget,
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'travel_arrangement_types' => $this->Travel_model->travel_arrangement_types(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('travel/dialog_travel', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_travel() {
	
		if($this->input->post('add_type')=='travel') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$description = $this->input->post('description');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
        	$Return['error'] = "The employee field is required.";
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = "The start date field is required.";
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = "The end date field is required.";
		} else if($st_date > $ed_date) {
			$Return['error'] = "Start Date should be less than or equal to End Date.";
		} else if($this->input->post('visit_purpose')==='') {
			$Return['error'] = "The purpose of visit field is required.";
		} else if($this->input->post('visit_place')==='') {
			$Return['error'] = "The place of visit field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'start_date' => $this->input->post('start_date'),
		'end_date' => $this->input->post('end_date'),
		'visit_purpose' => $this->input->post('visit_purpose'),
		'visit_place' => $this->input->post('visit_place'),
		'travel_mode' => $this->input->post('travel_mode'),
		'arrangement_type' => $this->input->post('arrangement_type'),
		'expected_budget' => $this->input->post('expected_budget'),
		'actual_budget' => $this->input->post('actual_budget'),
		'description' => $qt_description,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Travel_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Travel added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='travel') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$description = $this->input->post('description');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
        	$Return['error'] = "The employee field is required.";
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = "The start date field is required.";
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = "The end date field is required.";
		} else if($st_date > $ed_date) {
			$Return['error'] = "Start Date should be less than or equal to End Date.";
		} else if($this->input->post('visit_purpose')==='') {
			$Return['error'] = "The purpose of visit field is required.";
		} else if($this->input->post('visit_place')==='') {
			$Return['error'] = "The place of visit field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'start_date' => $this->input->post('start_date'),
		'end_date' => $this->input->post('end_date'),
		'visit_purpose' => $this->input->post('visit_purpose'),
		'visit_place' => $this->input->post('visit_place'),
		'travel_mode' => $this->input->post('travel_mode'),
		'arrangement_type' => $this->input->post('arrangement_type'),
		'expected_budget' => $this->input->post('expected_budget'),
		'actual_budget' => $this->input->post('actual_budget'),
		'description' => $qt_description,
		'status' => $this->input->post('status'),		
		);
		
		$result = $this->Travel_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Travel updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Travel_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Travel deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
