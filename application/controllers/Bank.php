<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Bank_model");
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
		$data['all_banks'] = $this->Bank_model->get_banks();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = 'Bank';
		$data['path_url'] = 'bank';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('58',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("bank/bank_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function bank_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("bank/bank_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$bank = $this->Bank_model->get_banks();
		
		$data = array();

          foreach($bank->result() as $r) {
			  
			  // get company
			  $company = $this->Xin_model->read_company_info($r->company_id);
			  // get user
			  $user = $this->Xin_model->read_user_info($r->added_by);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  

               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-bank_id="'. $r->bank_id . '"><i class="fa fa-pencil-square-o"></i></button></span></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-bank_id="'. $r->bank_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->bank_id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->bank_name,
					$r->account_name,
                    $r->account_number,
					$company[0]->name
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $bank->num_rows(),
                 "recordsFiltered" => $bank->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('bank_id');
		$result = $this->Bank_model->read_bank_information($id);
		
		$data = array(
				'bank_id' => $result[0]->bank_id,
				'company_id' => $result[0]->company_id,
				'account_name' => $result[0]->account_name,
				'account_number' => $result[0]->account_number,
				'iban' => $result[0]->iban,
				'swift_code' => $result[0]->swift_code,
				'bank_name' => $result[0]->bank_name,
				'address' => $result[0]->address,
				'all_companies' => $this->Xin_model->get_companies()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('bank/dialog_bank', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_bank() {
	
		if($this->input->post('add_type')=='bank') {
		// Check validation for user input
		$this->form_validation->set_rules('company_id', 'Company', 'trim|required|xss_clean');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('company_id')==='') {
        	$Return['error'] = $this->lang->line('error_company_field');
		} else if($this->input->post('bank_name')==='') {
			$Return['error'] = 'The bank name field is required.';
		} else if($this->input->post('account_name')==='') {
			$Return['error'] = 'The bank name field is required.';
		} else if($this->input->post('account_number')==='') {
			$Return['error'] = 'The account number field is required.';
		} 
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company_id'),
		'bank_name' => $this->input->post('bank_name'),
		'account_name' => $this->input->post('account_name'),
		'account_number' => $this->input->post('account_number'),
		'iban' => $this->input->post('iban'),
		'swift_code' => $this->input->post('swift_code'),
		'address' => $this->input->post('address'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Bank_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Bank added.';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='location') {
			
		$id = $this->uri->segment(3);
		
		// Check validation for user input
		$this->form_validation->set_rules('company_id', 'Company', 'trim|required|xss_clean');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('company')==='') {
        	$Return['error'] = $this->lang->line('error_company_field');
		} else if($this->input->post('bank_name')==='') {
			$Return['error'] = 'The bank name field is required.';
		} else if($this->input->post('account_name')==='') {
			$Return['error'] = 'The bank name field is required.';
		} else if($this->input->post('account_number')==='') {
			$Return['error'] = 'The account number field is required.';
		} 
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company_id'),
		'bank_name' => $this->input->post('bank_name'),
		'account_name' => $this->input->post('account_name'),
		'account_number' => $this->input->post('account_number'),
		'iban' => $this->input->post('iban'),
		'swift_code' => $this->input->post('swift_code'),
		'address' => $this->input->post('address'),	
		);	
		
		$result = $this->Location_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Bank updated.';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		
    		$result = $this->Bank_model->delete_record($id);
    		if(isset($id)) {
    			$Return['result'] = 'Bank Deleted.';
    		} else {
    			$Return['error'] = $this->lang->line('xin_error_msg');
    		}
		$this->output($Return);
	}
}
