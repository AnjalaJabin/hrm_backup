<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training_type extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Training_model");
		$this->load->model("Xin_model");
		$this->load->model("Trainers_model");
		$this->load->model("Designation_model");
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
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Training Type';
		$data['path_url'] = 'training_type';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('50',$role_resources_ids)) {
			if(!empty($session)){
			$data['subview'] = $this->load->view("training/training_type", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("training/training_type", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$training_type = $this->Training_model->get_training_type();
		
		$data = array();

        foreach($training_type->result() as $r) {
				
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-training_type_id="'. $r->training_type_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->training_type_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$r->training_type_id,
			$r->type
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $training_type->num_rows(),
			 "recordsFiltered" => $training_type->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('training_type_id');
		$result = $this->Training_model->read_training_type_information($id);
		$data = array(
				'training_type_id' => $result[0]->training_type_id,
				'type' => $result[0]->type
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('training/dialog_training_type', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_type() {
	
		if($this->input->post('add_type')=='training') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('type_name')==='') {
        	$Return['error'] = "The type name field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('type_name'),
		'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Training_model->add_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Training type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='training') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('type_name')==='') {
        	$Return['error'] = "The type name field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('type_name')
		);
		
		$result = $this->Training_model->update_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Training type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// training details
	public function details()
     {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		$result = $this->Training_model->read_training_information($id);
		// get training type
		$type = $this->Training_model->read_training_type_information($result[0]->training_type_id);
		// get trainer
		$trainer = $this->Trainers_model->read_trainer_information($result[0]->trainer_id);
		// trainer full name
		$trainer_name = $trainer[0]->first_name.' '.$trainer[0]->last_name;
		$data = array(
				'training_id' => $result[0]->training_id,
				'type' => $type[0]->type,
				'trainer_first_name' => $trainer[0]->first_name,
				'trainer_last_name' => $trainer[0]->last_name,
				'training_cost' => $result[0]->training_cost,
				'start_date' => $result[0]->start_date,
				'finish_date' => $result[0]->finish_date,
				'created_at' => $result[0]->created_at,
				'description' => $result[0]->description,
				'performance' => $result[0]->performance,
				'training_status' => $result[0]->training_status,
				'remarks' => $result[0]->remarks,
				'employee_id' => $result[0]->employee_id,
				'all_employees' => $this->Xin_model->all_employees(),
				);
			$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("training/training_details", $data);
		} else {
			redirect('');
		}		  
     }
	 
	 // Validate and update info in database
	public function update_status() {
	
		if($this->input->post('edit_type')=='update_status') {
			
		$id = $this->input->post('token_status');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
	
		$data = array(
		'performance' => $this->input->post('performance'),
		'training_status' => $this->input->post('status'),
		'remarks' => $this->input->post('remarks')
		);
		
		$result = $this->Training_model->update_status($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Training status updated.';
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
		$result = $this->Training_model->delete_type_record($id);
		if(isset($id)) {
			$Return['result'] = 'Training type deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
