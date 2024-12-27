<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaves extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Leaves_model");
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
	
		$data['breadcrumbs'] = 'Leaves';
		$data['path_url'] = 'leaves';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('15',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("leaves/leave_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function leave_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("leaves/leave_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$leave = $this->Leaves_model->get_leaves();
		
		$data = array();

          foreach($leave->result() as $r) {
			 
				
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-award_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-award_id="'. $r->id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
			$r->id,
			$r->name,
			$r->type,
			$r->days,
			$r->gender,
			$r->description
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
	 
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('id');
		$result = $this->leaves_model->read_leave_type_information($id);
		$data = array(
				'id' => $result[0]->id,
				'name' =>$result[0]->name,
				'type' => $result[0]->type,
				'days' => $result[0]->days,
				'gender' => $result[0]->gender,
				'description' => $result[0]->description
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('awards/dialog_award', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_leave() {
	
		if($this->input->post('add_type')=='award') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
        $Return['error'] = "The employee field is required.";
		} else if($this->input->post('lname')==='') {
			$Return['error'] = "The leave name  field is required.";
		} else if($this->input->post('leavetype')==='') {
			$Return['error'] = "The leave type field is required.";
		} else if($this->input->post('month_year')==='') {
			$Return['error'] = "The award month & year field is required.";
		}  else {
			
				$data = array(
				'name' => $this->input->post('lname'),
				'type' => $this->input->post('leavetype'),
				'gender' =>$this->input->post('gendertype'),
				'days' => $this->input->post('days'),
				'description' => $this->input->post('leave_information'),		
				);
				//print_r($data);
				$result = $this->Leaves_model->add($data);
				if ($result == TRUE) {
				$Return['result'] = 'Leave added.';
				
				}
				
				
				$this->output($Return);
				exit;	
		
			}
		}
	
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		
		
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='award') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
        $Return['error'] = "The employee field is required.";
		} else if($this->input->post('award_type_id')==='') {
			$Return['error'] = "The award type field is required.";
		} else if($this->input->post('award_date')==='') {
			$Return['error'] = "The award date field is required.";
		} else if($this->input->post('month_year')==='') {
			$Return['error'] = "The award month & year field is required.";
		}  
				
		
		
		/* Check if file uploaded..*/
		else if($_FILES['award_picture']['size'] == 0) {
			 $fname = '';
			 $data = array(
			'employee_id' => $this->input->post('employee_id'),
			'award_type_id' => $this->input->post('award_type_id'),
			'created_at' => $this->input->post('award_date'),
			'award_month_year' => $this->input->post('month_year'),
			'gift_item' => $this->input->post('gift'),
			'cash_price' => $this->input->post('cash'),
			'description' => $qt_description,
			'award_information' => $this->input->post('award_information'),		
			);
			 $result = $this->Awards_model->update_record($data,$id);
		} else {
			if(is_uploaded_file($_FILES['award_picture']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['award_picture']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["award_picture"]["tmp_name"];
					$bill_copy = "uploads/award/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$lname = basename($_FILES["award_picture"]["name"]);
					$newfilename = 'award_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $bill_copy.$newfilename);
					$fname = $newfilename;
					 $data = array(
					'employee_id' => $this->input->post('employee_id'),
					'award_type_id' => $this->input->post('award_type_id'),
					'created_at' => $this->input->post('award_date'),
					'award_photo' => $fname,
					'award_month_year' => $this->input->post('month_year'),
					'gift_item' => $this->input->post('gift'),
					'cash_price' => $this->input->post('cash'),
					'description' => $qt_description,
					'award_information' => $this->input->post('award_information'),		
					);
					// update record > model
					
					$result2     = $this->Awards_model->read_award_type_information($id);
            		$delete_file = "uploads/award/".$result2[0]->award_photo;
            		unlink($delete_file);
					
					$result = $this->Awards_model->update_record($data,$id);
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		
		if ($result == TRUE) {
			$Return['result'] = 'Award updated.';
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
		
		$result2     = $this->Awards_model->read_award_type_information($id);
		$delete_file = "uploads/award/".$result2[0]->award_photo;
		unlink($delete_file);
		
		$result = $this->Awards_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Award deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}