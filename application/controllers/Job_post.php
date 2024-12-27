<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_post extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Job_post_model");
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
		$data['all_job_types'] = $this->Job_post_model->all_job_types();
		$data['breadcrumbs'] = 'Job Post';
		$data['path_url'] = 'job_post';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('45',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("job_post/job_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function job_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("job_post/job_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$jobs = $this->Job_post_model->get_jobs();
	    
		$data = array();

        foreach($jobs->result() as $r) {
			 			
		// get job designation
		$designation = $this->Designation_model->read_designation_information($r->designation_id);
		// get job type
		$job_type = $this->Job_post_model->read_job_type_information($r->job_type);
// 		echo $this->db->last_query();
		// get date
		$date_of_closing = $this->Xin_model->set_date_format($r->date_of_closing);
		$created_at = $this->Xin_model->set_date_format($r->created_at);
		/* get job status*/
		if($r->status==1): $status = 'Published'; elseif($r->status==2): $status = 'Un Published'; endif;
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-job_id="'. $r->job_id . '"><i class="fa fa-pencil-square-o"></i></button></span><a href="'.site_url().'frontend/jobs/detail/'.$r->job_id.'" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"><i class="fa fa-eye"></i></button></a><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->job_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$r->job_title,
			$designation[0]->designation_name,
			$job_type[0]->type,
			$r->job_vacancy,
			$date_of_closing,
			$status,
			$created_at
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $jobs->num_rows(),
			 "recordsFiltered" => $jobs->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('job_id');
		$result = $this->Job_post_model->read_job_information($id);
		$data = array(
				'job_id' => $result[0]->job_id,
				'job_title' => $result[0]->job_title,
				'designation_id' => $result[0]->designation_id,
				'job_type_id' => $result[0]->job_type,
				'job_vacancy' => $result[0]->job_vacancy,
				'gender' => $result[0]->gender,
				'minimum_experience' => $result[0]->minimum_experience,
				'date_of_closing' => $result[0]->date_of_closing,
				'short_description' => $result[0]->short_description,
				'long_description' => $result[0]->long_description,
				'status' => $result[0]->status,
				'all_designations' => $this->Designation_model->all_designations(),
				'all_job_types' => $this->Job_post_model->all_job_types()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('job_post/dialog_job_post', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_job() {
	
		if($this->input->post('add_type')=='job') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$long_description = $_POST['long_description'];	
		$short_description = $_POST['short_description'];	
		$qt_short_description = htmlspecialchars(addslashes($short_description), ENT_QUOTES);
		$qt_description = htmlspecialchars(addslashes($long_description), ENT_QUOTES);
		
		if($this->input->post('job_title')==='') {
       		$Return['error'] = "The job title field is required.";
		} else if($this->input->post('job_type')==='') {
			$Return['error'] = "The job type field is required.";
		} else if($this->input->post('designation_id')==='') {
			$Return['error'] = "The job designation field is required.";
		} else if($this->input->post('vacancy')==='') {
			$Return['error'] = "The number of positions field is required.";
		} else if($this->input->post('date_of_closing')==='') {
       		$Return['error'] = "The date of closing field is required.";
		} else if($qt_short_description==='') {
       		$Return['error'] = "The short description field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'job_title' => $this->input->post('job_title'),
		'job_type' => $this->input->post('job_type'),
		'designation_id' => $this->input->post('designation_id'),
		'short_description' => $qt_short_description,
		'long_description' => $qt_description,
		'status' => $this->input->post('status'),
		'job_vacancy' => $this->input->post('vacancy'),
		'date_of_closing' => $this->input->post('date_of_closing'),
		'gender' => $this->input->post('gender'),
		'minimum_experience' => $this->input->post('experience'),
		'created_at' => date('Y-m-d h:i:s'),
		
		);
		$result = $this->Job_post_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Job added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='job') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$long_description = $_POST['long_description'];	
		$short_description = $_POST['short_description'];	
		$qt_short_description = htmlspecialchars(addslashes($short_description), ENT_QUOTES);
		$qt_description = htmlspecialchars(addslashes($long_description), ENT_QUOTES);
		
		if($this->input->post('job_title')==='') {
       		$Return['error'] = "The job title field is required.";
		} else if($this->input->post('job_type')==='') {
			$Return['error'] = "The job type field is required.";
		} else if($this->input->post('designation_id')==='') {
			$Return['error'] = "The job designation field is required.";
		} else if($this->input->post('vacancy')==='') {
			$Return['error'] = "The number of positions field is required.";
		} else if($this->input->post('date_of_closing')==='') {
       		$Return['error'] = "The date of closing field is required.";
		} else if($qt_short_description==='') {
       		$Return['error'] = "The short description field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'job_title' => $this->input->post('job_title'),
		'job_type' => $this->input->post('job_type'),
		'designation_id' => $this->input->post('designation_id'),
		'short_description' => $qt_short_description,
		'long_description' => $qt_description,
		'status' => $this->input->post('status'),
		'job_vacancy' => $this->input->post('vacancy'),
		'date_of_closing' => $this->input->post('date_of_closing'),
		'gender' => $this->input->post('gender'),
		'minimum_experience' => $this->input->post('experience')		
		);
		
		$result = $this->Job_post_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Job updated.';
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
		$result = $this->Job_post_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Job deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
