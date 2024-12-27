<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Workable Zone License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.workablezone.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to workablezone@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please contact us at workablezone@gmail.com for more information.
 *
 * @author   Mian Abdullah Jan - Workable Zone
 * @package  Workable Zone - Employee Exit
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_exit extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Employee_exit_model");
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
		$data['all_exit_types'] = $this->Employee_exit_model->all_exit_types();
		$data['breadcrumbs'] = 'Exployee Exit';
		$data['path_url'] = 'employee_exit';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('27',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("exit/exit_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function exit_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("exit/exit_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$exit = $this->Employee_exit_model->get_exit();
		
		$data = array();

          foreach($exit->result() as $r) {
			 			  
		// get user > employee to exit
		$user = $this->Xin_model->read_user_info($r->employee_id);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get user > added by
		$user_by = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		$added_by = $user_by[0]->first_name.' '.$user_by[0]->last_name;
		// get exit date
		$exit_date = $this->Xin_model->set_date_format($r->exit_date);
				
		// get exit type
		$exit_type = $this->Employee_exit_model->read_exit_type_information($r->exit_type_id);
		if($r->exit_interview==0): $exit_interview = 'No'; else: $exit_interview = 'Yes'; endif;
		if($r->is_inactivate_account==0): $account = 'No'; else: $account = 'Yes'; endif;
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-exit_id="'. $r->exit_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-exit_id="'. $r->exit_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->exit_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$full_name,
			$exit_type[0]->type,
			$exit_date,
			$exit_interview,
			$account,
			$added_by
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $exit->num_rows(),
			 "recordsFiltered" => $exit->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('exit_id');
		$result = $this->Employee_exit_model->read_exit_information($id);
		$data = array(
				'exit_id' => $result[0]->exit_id,
				'employee_id' => $result[0]->employee_id,
				'exit_date' => $result[0]->exit_date,
				'exit_type_id' => $result[0]->exit_type_id,
				'exit_interview' => $result[0]->exit_interview,
				'is_inactivate_account' => $result[0]->is_inactivate_account,
				'reason' => $result[0]->reason,
				'all_employees' => $this->Xin_model->all_employees(),
				'all_exit_types' => $this->Employee_exit_model->all_exit_types(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('exit/dialog_exit', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_exit() {
	
		if($this->input->post('add_type')=='exit') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$reason = $this->input->post('reason');
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('exit_date')==='') {
			$Return['error'] = "The exit date field is required.";
		} else if($this->input->post('type')==='') {
			 $Return['error'] = "The exit type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'exit_date' => $this->input->post('exit_date'),
		'reason' => $qt_reason,
		'exit_type_id' => $this->input->post('type'),
		'exit_interview' => $this->input->post('exit_interview'),
		'is_inactivate_account' => $this->input->post('is_inactivate_account'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		);
		$result = $this->Employee_exit_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Employee Exit added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='exit') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$reason = $this->input->post('reason');
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('exit_date')==='') {
			$Return['error'] = "The exit date field is required.";
		} else if($this->input->post('type')==='') {
			 $Return['error'] = "The exit type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'exit_date' => $this->input->post('exit_date'),
		'reason' => $qt_reason,
		'exit_type_id' => $this->input->post('type'),
		'exit_interview' => $this->input->post('exit_interview'),
		'is_inactivate_account' => $this->input->post('is_inactivate_account'),
		);
		
		$result = $this->Employee_exit_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Employee Exit updated.';
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
		$result = $this->Employee_exit_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Employee Exit deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
