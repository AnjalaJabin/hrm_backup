<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trainers extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Trainers_model");
		$this->load->model("Xin_model");
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
		$data['all_designations'] = $this->Designation_model->all_designations();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Trainers';
		$data['path_url'] = 'trainers';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('51',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("trainers/trainer_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function trainer_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("trainers/trainer_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$trainers = $this->Trainers_model->get_trainers();
		
		$data = array();

        foreach($trainers->result() as $r) {
			 			  
		// get designation
		$designation = $this->Designation_model->read_designation_information($r->designation_id);
		// get name
		$full_name = $r->first_name.' '.$r->last_name;
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-trainer_id="'. $r->trainer_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-trainer_id="'. $r->trainer_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->trainer_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$full_name,
			$designation[0]->designation_name,
			$r->contact_number,
			$r->email,
			html_entity_decode($r->address)
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $trainers->num_rows(),
			 "recordsFiltered" => $trainers->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('trainer_id');
		$result = $this->Trainers_model->read_trainer_information($id);
		$data = array(
				'trainer_id' => $result[0]->trainer_id,
				'first_name' => $result[0]->first_name,
				'last_name' => $result[0]->last_name,
				'contact_number' => $result[0]->contact_number,
				'email' => $result[0]->email,
				'designation_id' => $result[0]->designation_id,
				'expertise' => $result[0]->expertise,
				'address' => $result[0]->address,
				'all_designations' => $this->Designation_model->all_designations()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('trainers/dialog_trainer', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_trainer() {
	
		if($this->input->post('add_type')=='trainer') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$expertise = $this->input->post('expertise');
		$qt_expertise = htmlspecialchars(addslashes($expertise), ENT_QUOTES);
		$address = $this->input->post('address');
		$qt_address = htmlspecialchars(addslashes($address), ENT_QUOTES);
		
		if($this->input->post('first_name')==='') {
       		$Return['error'] = "The first name field is required.";
		} else if($this->input->post('last_name')==='') {
			$Return['error'] = "The last name field is required.";
		} else if($this->input->post('contact_number')==='') {
			$Return['error'] = "The contact number field is required.";
		} else if($this->input->post('email')==='') {
       		$Return['error'] = "The email field is required.";
		} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
		  $Return['error'] = "Invalid email format."; 
		} else if($this->input->post('designation_id')==='') {
       		$Return['error'] = "The designation field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}				
	
		$data = array(
		'first_name' => $this->input->post('first_name'),
		'last_name' => $this->input->post('last_name'),
		'contact_number' => $this->input->post('contact_number'),
		'expertise' => $qt_expertise,
		'address' => $qt_address,
		'email' => $this->input->post('email'),
		'designation_id' => $this->input->post('designation_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Trainers_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Trainer added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='trainer') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$expertise = $this->input->post('expertise');
		$qt_expertise = htmlspecialchars(addslashes($expertise), ENT_QUOTES);
		$address = $this->input->post('address');
		$qt_address = htmlspecialchars(addslashes($address), ENT_QUOTES);
		
		if($this->input->post('first_name')==='') {
       		$Return['error'] = "The first name field is required.";
		} else if($this->input->post('last_name')==='') {
			$Return['error'] = "The last name field is required.";
		} else if($this->input->post('contact_number')==='') {
			$Return['error'] = "The contact number field is required.";
		} else if($this->input->post('email')==='') {
       		$Return['error'] = "The email field is required.";
		} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
		  $Return['error'] = "Invalid email format."; 
		} else if($this->input->post('designation_id')==='') {
       		$Return['error'] = "The designation field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}				
	
		$data = array(
		'first_name' => $this->input->post('first_name'),
		'last_name' => $this->input->post('last_name'),
		'contact_number' => $this->input->post('contact_number'),
		'expertise' => $qt_expertise,
		'address' => $qt_address,
		'email' => $this->input->post('email'),
		'designation_id' => $this->input->post('designation_id')
		);
		
		$result = $this->Trainers_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Trainer updated.';
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
		$result = $this->Trainers_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Trainer deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
