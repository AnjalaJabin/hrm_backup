<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Project_model");
		$this->load->model("Xin_model");
		$this->load->model("Company_model");
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
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = 'Projects';
		$data['path_url'] = 'user/user_projects';
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$data['subview'] = $this->load->view("user/projects", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
     }
 
    public function project_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("project/project_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$project = $this->Project_model->get_projects();
		
		$data = array();

        foreach($project->result() as $r) {
			 			  
		 $aim = explode(',',$r->assigned_to);
		 foreach($aim as $dIds) {
		 if($session['user_id'] == $dIds) {
				 // get user > added by
		$user = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get date
		$pdate = '<i class="fa fa-calendar position-left"></i> '.$this->Xin_model->set_date_format($r->end_date);
		
		//project_progress
		if($r->project_progress <= 20) {
			$progress_class = 'progress-danger';
		} else if($r->project_progress > 20 && $r->project_progress <= 50){
			$progress_class = 'progress-warning';
		} else if($r->project_progress > 50 && $r->project_progress <= 75){
			$progress_class = 'progress-info';
		} else {
			$progress_class = 'progress-success';
		}
		
		// progress
		
		$pbar = '<p class="m-b-0-5">Completed <span class="pull-xs-right">'.$r->project_progress.'%</span></p><progress class="progress '.$progress_class.' progress-sm" value="'.$r->project_progress.'" max="100">'.$r->project_progress.'%</progress>';
				
		//status
		if($r->status == 0) {
			$status = 'Not Started';
		} else if($r->status ==1){
			$status = 'In Progress';
		} else if($r->status ==2){
			$status = 'Completed';
		} else {
			$status = 'Deferred';
		}
		
		// priority
		if($r->priority == 1) {
			$priority = '<span class="label label-danger">Highest</span>';
		} else if($r->priority ==2){
			$priority = '<span class="label label-info">High</span>';
		} else if($r->priority ==3){
			$priority = '<span class="label label-primary">Normal</span>';
		} else {
			$priority = '<span class="label label-success">Low</span>';
		}
		
		$project_summary = '<div class="text-semibold"><a href="'.site_url().'project/detail/'.$r->project_id . '">'.$r->title.'</a></div>
					        <div class="text-muted">'.$r->summary.'</div>';
		
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'project/detail/'.$r->project_id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span>',
			$project_summary,
			$priority,
			$pdate,
			$pbar
		);
      }
		 } } // e-project

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $project->num_rows(),
			 "recordsFiltered" => $project->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('project_id');
		$result = $this->Project_model->read_project_information($id);
		$data = array(
				'project_id' => $result[0]->project_id,
				'title' => $result[0]->title,
				'client_name' => $result[0]->client_name,
				'start_date' => $result[0]->start_date,
				'end_date' => $result[0]->end_date,
				'company_id' => $result[0]->company_id,
				'assigned_to' => $result[0]->assigned_to,
				'description' => $result[0]->description,
				'project_progress' => $result[0]->project_progress,
				'status' => $result[0]->status,
				'all_employees' => $this->xin_model->all_employees(),
				'all_companies' => $this->xin_model->get_companies()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('project/dialog_project', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_project() {
	
		if($this->input->post('add_type')=='project') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$description = $this->input->post('description');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('title')==='') {
        $Return['error'] = "The title field is required.";
		} else if($this->input->post('client_name')==='') {
			$Return['error'] = "The client name field is required.";
		} else if($this->input->post('company_id')==='') {
			$Return['error'] = "The company field is required.";
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = "The start date field is required.";
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = "The end date field is required.";
		} else if($st_date > $ed_date) {
			$Return['error'] = "Start Date should be less than or equal to End Date.";
		}  
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		if(null!=$this->input->post('assigned_to')) {
			$assigned_ids = implode(',',$this->input->post('assigned_to'));
			$employee_ids = $assigned_ids;
		} else {
			$employee_ids = 'all-employees';
		}
	
		$data = array(
		'title' => $this->input->post('title'),
		'client_name' => $this->input->post('client_name'),
		'company_id' => $this->input->post('company_id'),
		'start_date' => $this->input->post('start_date'),
		'end_date' => $this->input->post('end_date'),
		'assigned_to' => $employee_ids,
		'description' => $qt_description,
		'project_progress' => '0',
		'status' => '0',
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Project_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Project added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='project') {
			
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
		
		if($this->input->post('title')==='') {
        $Return['error'] = "The title field is required.";
		} else if($this->input->post('client_name')==='') {
			$Return['error'] = "The client name field is required.";
		} else if($this->input->post('company_id')==='') {
			$Return['error'] = "The company field is required.";
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = "The start date field is required.";
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = "The end date field is required.";
		} else if($st_date > $ed_date) {
			$Return['error'] = "Start Date should be less than or equal to End Date.";
		}  
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		if(null!=$this->input->post('assigned_to')) {
			$assigned_ids = implode(',',$this->input->post('assigned_to'));
			$employee_ids = $assigned_ids;
		} else {
			$employee_ids = 'all-employees';
		}
	
		$data = array(
		'title' => $this->input->post('title'),
		'client_name' => $this->input->post('client_name'),
		'company_id' => $this->input->post('company_id'),
		'start_date' => $this->input->post('start_date'),
		'end_date' => $this->input->post('end_date'),
		'assigned_to' => $employee_ids,
		'description' => $qt_description,
		'project_progress' => $this->input->post('progres_val'),
		'status' => $this->input->post('status'),		
		);
		
		$result = $this->Project_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Project updated.';
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
		$result = $this->Project_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Project deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
