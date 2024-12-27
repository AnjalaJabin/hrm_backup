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
             $this->load->view("job_post/job_apply");
			//$this->load->view('layout_main', $data);
//         $session = $this->session->userdata('username');
// 		if(!empty($session)){ 
			
// 		} else {
// 			redirect('');
// 		}
		
// 		$data['title'] = $this->Xin_model->site_title();
// 		$data['all_designations'] = $this->Designation_model->all_designations();
// 		$data['all_job_types'] = $this->Job_post_model->all_job_types();
// 		$data['breadcrumbs'] = 'Job Post';
// 		$data['path_url'] = 'job_post';
// 		$session = $this->session->userdata('username');
// 		$role_resources_ids = $this->Xin_model->user_role_resource();
// 		if(in_array('45',$role_resources_ids)) {
// 			if(!empty($session)){ 
// 			$data['subview'] = $this->load->view("job_post/job_apply", $data, TRUE);
// 			$this->load->view('layout_main', $data); //page load
// 			} else {
// 				redirect('');
// 			}
// 		} else {
// 			redirect('dashboard/');
// 		}
     }
    
}
     ?>