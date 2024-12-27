<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the models
		$this->load->model("Document_model");
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
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = 'Documents';
		$data['path_url'] = 'Document';
		$session = $this->session->userdata('username');
		
		$data['all_files'] = $this->Document_model->get_all_files($session['user_id']);
		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('57',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("documents/document_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function document_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("documents/document_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$document = $this->Document_model->get_documents();
		
		$data = array();

          foreach($document->result() as $r) {
			  
			  // get country
			  $company = $this->Xin_model->read_company_info($r->company_id);
			  // get user
			  $user = $this->Xin_model->read_user_info($r->added_by);
			  // user full name
			  if(!is_null($user)){
			  	$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  } else {
				  $full_name = '--';	
			  }
			  
			  if(strtotime($r->expiry)<1)
			  {
			      $exp_date = '-';
			  }
			  else
			  {
			      $exp_date = date('d-m-Y', strtotime($r->expiry));
			  }
			  
               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-Document_id="'. $r->document_id . '"><i class="fa fa-pencil-square-o"></i></button></span>
			   		<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-Document_id="'. $r->document_id . '"><i class="fa fa-eye"></i></button></span>
			   		<span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->document_id . '"><i class="fa fa-trash-o"></i></button></span>',
			   		$r->title,
                    $exp_date,
                    $company[0]->name,
					$full_name
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $document->num_rows(),
                 "recordsFiltered" => $document->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
     
     public function document_title_list()
     {
         if(!empty($_REQUEST["keyword"])) 
         {
            $result = $this->Document_model->read_document_title_list($_REQUEST["keyword"]);
            if(!empty($result)) 
            {
                echo '<ul id="title-list">';
                    foreach($result->result() as $title) 
                    {
                        $selectOnclk = "selectDocName('".$title->title."')";
                        echo '<li onClick="'.$selectOnclk.'">'.$title->title.'</li>';
                    } 
                echo '</ul>';
            } 
        }
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('document_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Document_model->read_document_information($id);
		$data = array(
				'document_id' => $result[0]->document_id,
				'title' => $result[0]->title,
				'expiry' => $result[0]->expiry,
				'des' => $result[0]->des,
				'file' => $result[0]->file,
				'company_id' => $result[0]->company_id,
				'all_companies' => $this->Xin_model->get_companies()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('documents/dialog_document', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_document() {
	    
		if($this->input->post('add_type')=='document') {
		// Check validation for user input
		$company_id = $this->input->post('company');
		$document_title = $this->input->post('document_title');
		$description = $this->input->post('description');
		$expiry_date = $this->input->post('expiry_date');
		
		$all_files_count = $this->Document_model->get_all_files_count($this->input->post('user_id'));
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($company_id==='') {
			$Return['error'] = $this->lang->line('xin_error_company');
		} else if($document_title==='') {
			$Return['error'] = 'The document title field is required.';
		} 
		
		/* Check if file uploaded..*/
		else if($all_files_count == 0) {
			$fname = 'no file';
			$Return['error'] = 'The document is required.';
		} 
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company'),
		'title' => $this->input->post('document_title'),
		'des' => $this->input->post('description'),
		'expiry' => $this->input->post('expiry_date'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Document_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Document added.';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='document') {
		$id = $this->uri->segment(3);
		// Check validation for user input
		$company_id = $this->input->post('company');
		$document_title = $this->input->post('document_title');
		$description = $this->input->post('description');
		$expiry_date = $this->input->post('expiry_date');
				
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($company_id==='') {
			$Return['error'] = $this->lang->line('xin_error_company');
		} else if($document_title==='') {
			$Return['error'] = 'The document title field is required.';
		} else if($expiry_date==='') {
			$Return['error'] = 'The expiry date field is required.';
		} 
		
		/* Check if file uploaded..*/
		else{
			 $fname = 'no file';
			 $no_logo_data = array(
			'company_id' => $this->input->post('company'),
    		'title' => $this->input->post('document_title'),
    		'des' => $this->input->post('description'),
    		'expiry' => $this->input->post('expiry_date'),
    		'created_at' => date('d-m-Y'),
			);
			 $result = $this->Document_model->update_record_no_logo($no_logo_data,$id);
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_document');
		} else {
			$Return['error'] = $Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		
		$all_files = $this->Document_model->get_document_files($id);
		
		foreach($all_files as $row) {
		   $delete_file = "uploads/company_documents/".$row->img_name;
		   unlink($delete_file);   
		}
		$result = $this->Document_model->delete_all_file($id);
		$result = $this->Document_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_document');
		} else {
			$Return['error'] = $Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
