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
 * @package  Workable Zone - Employees Last Login
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_last_login extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Employees_model");
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
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Employees Last Login';
		$data['path_url'] = 'employees_last_login';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('26',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("last_login/last_login_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function last_login_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("last_login/last_login_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$employee = $this->Employees_model->get_employees();
		
		$data = array();
		
		  foreach($employee->result() as $r) {
						  
		// login date and time
		if($r->last_login_date==''){
			$edate = '-';
			$etime = '-';
		} else {
			$edate = $this->Xin_model->set_date_format($r->last_login_date);
			$last_login =  new DateTime($r->last_login_date);
			$etime = $last_login->format('h:i a');
		}
		// employee link
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('13',$role_resources_ids)) {
			$emp_link = '<a href="employees/detail/'.$r->user_id.'/">'.$r->employee_id.'</a>';
		} else {
			$emp_link = $r->employee_id;
		}
		// user full name
		$full_name = $r->first_name.' '.$r->last_name;
		// user role
		$role = $this->Xin_model->read_user_role_info($r->user_role_id);
		/* get status*/
		if($r->is_active==1): $status = 'Active'; elseif($r->is_active==0): $status = 'In Active'; endif;
		
		$data[] = array(
			$emp_link,
			$full_name,
			$r->username,
			$edate,
			$etime,
			$role[0]->role_name,
			$status
		);
		}
		
		$output = array(
		   "draw" => $draw,
			 "recordsTotal" => $employee->num_rows(),
			 "recordsFiltered" => $employee->num_rows(),
			 "data" => $data
		);
		echo json_encode($output);
		exit();
		}
}
