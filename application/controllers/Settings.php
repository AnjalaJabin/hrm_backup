<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {
	
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
		$this->load->model("Employees_model");
		$this->load->model("Document_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Payroll_model");
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
		$setting = $this->Xin_model->read_setting_info(1);
		$company_info = $this->Xin_model->read_company_setting_info(1);
		$data = array(
			'title' => $this->Xin_model->site_title(),
			'company_info_id' => $company_info[0]->company_info_id,
			'logo' => $company_info[0]->logo,
			'logo_second' => $company_info[0]->logo_second,
			'sign_in_logo' => $company_info[0]->sign_in_logo,
			'job_logo' => $setting[0]->job_logo,
			'payroll_logo' => $setting[0]->payroll_logo,
			'company_name' => $company_info[0]->company_name,
			'contact_person' => $company_info[0]->contact_person,
			'website_url' => $company_info[0]->website_url,
			'starting_year' => $company_info[0]->starting_year,
			'company_email' => $company_info[0]->company_email,
			'company_contact' => $company_info[0]->company_contact,
			'email' => $company_info[0]->email,
			'phone' => $company_info[0]->phone,
			'address_1' => $company_info[0]->address_1,
			'address_2' => $company_info[0]->address_2,
			'city' => $company_info[0]->city,
			'state' => $company_info[0]->state,
			'zipcode' => $company_info[0]->zipcode,
			'country' => $company_info[0]->country,
			'updated_at' => $company_info[0]->updated_at,
			'application_name' => $setting[0]->application_name,
			'default_currency_symbol' => $setting[0]->default_currency_symbol,
			'show_currency' => $setting[0]->show_currency,
			'currency_position' => $setting[0]->currency_position,
			'date_format_xi' => $setting[0]->date_format_xi,
			'animation_effect' => $setting[0]->animation_effect,
			'animation_effect_topmenu' => $setting[0]->animation_effect_topmenu,
			'animation_effect_modal' => $setting[0]->animation_effect_modal,
			'notification_position' => $setting[0]->notification_position,
			'notification_close_btn' => $setting[0]->notification_close_btn,
			'notification_bar' => $setting[0]->notification_bar,
			'employee_manage_own_bank_account' => $setting[0]->employee_manage_own_bank_account,
			'employee_manage_own_contact' => $setting[0]->employee_manage_own_contact,
			'employee_manage_own_profile' => $setting[0]->employee_manage_own_profile,
			'employee_manage_own_qualification' => $setting[0]->employee_manage_own_qualification,
			'employee_manage_own_work_experience' => $setting[0]->employee_manage_own_work_experience,
			'employee_manage_own_document' => $setting[0]->employee_manage_own_document,
			'employee_manage_own_picture' => $setting[0]->employee_manage_own_picture,
			'employee_manage_own_social' => $setting[0]->employee_manage_own_social,
			'enable_attendance' => $setting[0]->enable_attendance,
			'enable_clock_in_btn' => $setting[0]->enable_clock_in_btn,
			'enable_email_notification' => $setting[0]->enable_email_notification,
			'enable_job_application_candidates' => $setting[0]->enable_job_application_candidates,
			'job_application_format' => $setting[0]->job_application_format,
			'footer_text' => $setting[0]->footer_text,
			'enable_page_rendered' => $setting[0]->enable_page_rendered,
			'enable_current_year' => $setting[0]->enable_current_year,
			'all_countries' => $this->Xin_model->get_countries()
			);
		$data['breadcrumbs'] = 'Settings';
		$data['path_url'] = 'settings';	
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('53',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("settings/settings", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
	 
	 public function theme()
     {
		$data['title'] = $this->Xin_model->site_title();
		$setting = $this->Xin_model->read_setting_info(1);
		$company_info = $this->Xin_model->read_company_setting_info(1);
		$data = array(
			'title' => $this->Xin_model->site_title(),
			'company_info_id' => $company_info[0]->company_info_id,
			'logo' => $company_info[0]->logo,
			'company_name' => $company_info[0]->company_name,
			'contact_person' => $company_info[0]->contact_person,
			'website_url' => $company_info[0]->website_url,
			'starting_year' => $company_info[0]->starting_year,
			'company_email' => $company_info[0]->company_email,
			'company_contact' => $company_info[0]->company_contact,
			'email' => $company_info[0]->email,
			'phone' => $company_info[0]->phone,
			'address_1' => $company_info[0]->address_1,
			'address_2' => $company_info[0]->address_2,
			'city' => $company_info[0]->city,
			'state' => $company_info[0]->state,
			'zipcode' => $company_info[0]->zipcode,
			'country' => $company_info[0]->country,
			'updated_at' => $company_info[0]->updated_at,
			'application_name' => $setting[0]->application_name,
			'default_currency_symbol' => $setting[0]->default_currency_symbol,
			'show_currency' => $setting[0]->show_currency,
			'currency_position' => $setting[0]->currency_position,
			'date_format_xi' => $setting[0]->date_format_xi,
			'animation_effect' => $setting[0]->animation_effect,
			'animation_effect_topmenu' => $setting[0]->animation_effect_topmenu,
			'animation_effect_modal' => $setting[0]->animation_effect_modal,
			'notification_position' => $setting[0]->notification_position,
			'employee_manage_own_contact' => $setting[0]->employee_manage_own_contact,
			'employee_manage_own_profile' => $setting[0]->employee_manage_own_profile,
			'employee_manage_own_qualification' => $setting[0]->employee_manage_own_qualification,
			'employee_manage_own_work_experience' => $setting[0]->employee_manage_own_work_experience,
			'employee_manage_own_document' => $setting[0]->employee_manage_own_document,
			'employee_manage_own_picture' => $setting[0]->employee_manage_own_picture,
			'employee_manage_own_social' => $setting[0]->employee_manage_own_social,
			'enable_attendance' => $setting[0]->enable_attendance,
			'enable_clock_in_btn' => $setting[0]->enable_clock_in_btn,
			'enable_email_notification' => $setting[0]->enable_email_notification,
			'enable_job_application_candidates' => $setting[0]->enable_job_application_candidates,
			'job_application_format' => $setting[0]->job_application_format,
			'all_countries' => $this->Xin_model->get_countries()
			);
		$data['breadcrumbs'] = 'Settings';
		$data['path_url'] = 'settings';	
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('53',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("settings/theme", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
	 
	 // database backup
	 public function database_backup()
     {
		$data['title'] = $this->Xin_model->site_title();
		$setting = $this->Xin_model->read_setting_info(1);
		$company_info = $this->Xin_model->read_company_setting_info(1);
		$data['breadcrumbs'] = 'Database Backup';
		$data['path_url'] = 'database_backup';
		
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('56',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("settings/database_backup", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }	 
	 
	 public function backup_database( $directory, $outname , $dbhost, $dbuser, $dbpass ,$dbname ) {
	  
		// check mysqli extension installed
		if( ! function_exists('mysqli_connect') ) {
		die(' This scripts need mysql extension to be running properly ! please resolve!!');
		}
		$mysqli = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		
		if( $mysqli->connect_error ) {
			print_r( $mysqli->connect_error );
			return false;
		}
		$dir = $directory;
		$result = '<p> Could not create backup directory on :'.$dir.' Please Please make sure you have set Directory on 755 or 777 for a while.</p>';  
		$res = true;
		if( ! is_dir( $dir ) ) {
		  if( ! @mkdir( $dir, 755 )) {
			$res = false;
		  }
		}
		$n = 1;
		if( $res ) {
		$name     = $outname;
		# counts
		if( file_exists($dir.'/'.$name.'.sql.gz' ) ) {
		  for($i=1;@count( file($dir.'/'.$name.'_'.$i.'.sql.gz') );$i++){
			$name = $name;
			if( ! file_exists( $dir.'/'.$name.'_'.$i.'.sql.gz') ) {
			  $name = $name.'_'.$i;
			  break;
			}
		  }
		}
		$fullname = $dir.'/'.$name.'.sql.gz'; # full structures
		if( ! $mysqli->error ) {
		  $sql = "SHOW TABLES";
		  $show = $mysqli->query($sql);
		  while ( $r = $show->fetch_array() ) {
			$tables[] = $r[0];
		  }
		  if( ! empty( $tables ) ) {
		//cycle through
		$return = '';
		foreach( $tables as $table )
		{
		$result     = $mysqli->query('SELECT * FROM '.$table);
		$num_fields = $result->field_count;
		$row2       = $mysqli->query('SHOW CREATE TABLE '.$table );
		$row2       = $row2->fetch_row();
		$return    .= 
		"\n
		-- ---------------------------------------------------------
		--
		-- Table structure for table : `{$table}`
		--
		-- ---------------------------------------------------------
		".$row2[1].";\n";
		for ($i = 0; $i < $num_fields; $i++) 
		{
		  $n = 1 ;
		  while( $row = $result->fetch_row() )
		  { 
			
			if( $n++ == 1 ) { # set the first statements
			  $return .= 
		"
		--
		-- Dumping data for table `{$table}`
		--
		";  
			/**
			 * Get structural of fields each tables
			 */
			$array_field = array(); #reset ! important to resetting when loop 
			 while( $field = $result->fetch_field() ) # get field
			{
			  $array_field[] = '`'.$field->name.'`';
			  
			}
			$array_f[$table] = $array_field;
			// $array_f = $array_f;
			# endwhile
			$array_field = implode(', ', $array_f[$table]); #implode arrays
			  $return .= "INSERT INTO `{$table}` ({$array_field}) VALUES\n(";
			} else {
			  $return .= '(';
			}
			for($j=0; $j<$num_fields; $j++) 
			{
			  
			  $row[$j] = str_replace('\'','\'\'', preg_replace("/\n/","\\n", $row[$j] ) );
			  if ( isset( $row[$j] ) ) { $return .= is_numeric( $row[$j] ) ? $row[$j] : '\''.$row[$j].'\'' ; } else { $return.= '\'\''; }
			  if ($j<($num_fields-1)) { $return.= ', '; }
			}
			  $return.= "),\n";
		  }
		  # check matching
		  @preg_match("/\),\n/", $return, $match, false, -3); # check match
		  if( isset( $match[0] ) )
		  {
			$return = substr_replace( $return, ";\n", -2);
		  }
		}
		
		  $return .= "\n";
		}
		$return = 
		"-- ---------------------------------------------------------
		--
		-- SIMPLE SQL Dump
		-- 
		-- nawa (at) yahoo (dot) com
		--
		-- Host Connection Info: ".$mysqli->host_info."
		-- Generation Time: ".date('F d, Y \a\t H:i A ( e )')."
		-- PHP Version: ".PHP_VERSION."
		--
		-- ---------------------------------------------------------\n\n
		SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
		SET time_zone = \"+00:00\";
		/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
		/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
		/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
		/*!40101 SET NAMES utf8 */;
		".$return."
		/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
		/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
		/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
		# end values result
		@ini_set('zlib.output_compression','Off');

		$gzipoutput = gzencode( $return, 9);
		if(  @ file_put_contents( $fullname, $gzipoutput  ) ) { # 9 as compression levels
		
		$result = $name.'.sql.gz'; # show the name
		
		} else { # if could not put file , automaticly you will get the file as downloadable
		$result = false;   
		// various headers, those with # are mandatory
		header('Content-Type: application/x-gzip'); // change it to mimetype
		header("Content-Description: File Transfer");
		header('Content-Encoding: gzip'); #
		header('Content-Length: '.strlen( $gzipoutput ) ); #
		header('Content-Disposition: attachment; filename="'.$name.'.sql.gz'.'"');
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		header('Connection: Keep-Alive');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Pragma: no-cache');
		
		echo $gzipoutput;
		}
		   } else {
			 $result = '<p>Error when executing database query to export.</p>'.$mysqli->error;
		   
		   }
		 }
		} else {
		  $result = '<p>Wrong mysqli input</p>';
		}
		
		if( $mysqli && ! $mysqli->error ) {
		  @$mysqli->close();
		}
		return $result;
		}
	 
	 public function create_database_backup()
     {
		$data['title'] = $this->Xin_model->site_title();
		if($this->input->post('type')==='backup') {
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			
			$db = array('default' => array());
			// get db credentials
			require 'application/config/database.php';
			$hostname = $db['default']['hostname'];
			$username = $db['default']['username'];
			$password = $db['default']['password'];
			$database = $db['default']['database'];
				
			$dir  = 'uploads/dbbackup/'; // directory files
			$name = 'backup_'.date('d-m-Y_H_i_s'); // name sql backup
			$this->backup_database( $dir, $name, $hostname, $username, $password, $database); // execute
					
			$fname = $name.'.sql.gz';
					
			$data = array(
			'backup_file' => $fname,
			'created_at' => date('d-m-Y H:i:s')
			);
			
			$result = $this->Xin_model->add_backup($data);	
			
			if ($result == TRUE) {
				$Return['result'] = 'Database Backup Generated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
     }
	 
	 public function delete_db_backup()
     {
		if($this->input->post('type')==='delete_old_backup') {
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			
			/*Delete backup*/
			$result = $this->Xin_model->delete_all_backup_record();
			$baseurl = base_url();
			$files = glob('uploads/dbbackup/*'); //get all file names
			foreach($files as $file){
				if(is_file($file))
				unlink($file); //delete file
			}
			
			
			$Return['result'] = 'Database Old Backup Deleted.';
			$this->output($Return);
			exit;
		}
     }
	 
	 // backup list
	  public function database_backup_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/database_backup", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$db_backup = $this->Xin_model->all_db_backup();

		$data = array();

        foreach($db_backup->result() as $r) {
			
			$created_at = $this->Xin_model->set_date_format($r->created_at);
						 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Download"><a href="'.site_url().'download?type=dbbackup&filename='.$r->backup_file.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="Download"><i class="fa fa-download"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->backup_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$r->backup_file,
			$created_at
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $db_backup->num_rows(),
			 "recordsFiltered" => $db_backup->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
    }
	 
	public function email_template() {
		
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Email Templates';
		$data['path_url'] = 'email_template';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('55',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("settings/email_template", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     } 
	
	// email templates > list
	  public function email_template_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/email_template", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$email_template = $this->Xin_model->get_email_templates();

		$data = array();

        foreach($email_template->result() as $r) {
			
		if($r->status==1){
			$status = '<span class="tag tag-pill tag-success">Active</span>';
		} else {
			$status = '<span class="tag tag-pill tag-warning">Inactive</span>';
		}
						 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-template_id="'. $r->template_id . '"><i class="fa fa-pencil-square-o"></i></button></span>',
			$r->name,
			$r->subject,
			$status
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $email_template->num_rows(),
			 "recordsFiltered" => $email_template->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     } 
	 
	public function read_tempalte()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('template_id');
		$result = $this->Xin_model->read_email_template_info($id);
		$data = array(
				'template_id' => $result[0]->template_id,
				'template_code' => $result[0]->template_code,
				'name' => $result[0]->name,
				'subject' => $result[0]->subject,
				'message' => $result[0]->message,
				'status' => $result[0]->status
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('settings/dialog_email_template', $data);
		} else {
			redirect('');
		}
	} 
	
	public function password_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('user_id');
		$result = $this->Xin_model->read_user_info($id);
		$data = array(
				'user_id' => $result[0]->user_id,
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('settings/dialog_constants', $data);
		} else {
			redirect('');
		}
	} 
	
	public function policy_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('settings/dialog_constants', $data);
		} else {
			redirect('');
		}
	} 
	
	// Validate and update info in database
	public function update_template() {
	
		if($this->input->post('edit_type')=='update_template') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		
		if($this->input->post('name')==='') {
       		 $Return['error'] = "The name field is required.";
		} else if($this->input->post('subject')==='') {
			$Return['error'] = "The subject field is required.";
		} else if($this->input->post('status')==='') {
			 $Return['error'] = "The status field is required.";
		} else if($this->input->post('message')==='') {
			$Return['error'] = "The message field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		$message = $this->input->post('message');
		$new_message = htmlspecialchars(addslashes($message), ENT_QUOTES);
	
		$data = array(
		'name' => $this->input->post('name'),
		'subject' => $this->input->post('subject'),
		'status' => $this->input->post('status'),
		'message' => $new_message
		);
		
		$result = $this->Xin_model->update_email_template_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Email Template updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// get all constants > all types
	public function constants()
     {
        $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
		
		$data['title'] = $this->Xin_model->site_title();
		$setting = $this->Xin_model->read_setting_info(1);
		$company_info = $this->Xin_model->read_company_setting_info(1);
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Constants';
		$data['path_url'] = 'constants';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('54',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("settings/constants", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
	 	
	// Validate and update info in database
	public function company_info() {
	
		if($this->input->post('type')=='company_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
		
		if($this->input->post('company_name')==='') {
       		 $Return['error'] = "The company name field is required.";
		} else if($this->input->post('website')==='') {
			$Return['error'] = "The website field is required.";
		} else if($this->input->post('contact_person')==='') {
			$Return['error'] = "The contact person field is required.";
		} else if($this->input->post('email')==='') {
			 $Return['error'] = "The email field is required.";
		} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
			$Return['error'] = "Invalid email format.";
		} else if($this->input->post('phone')==='') {
			$Return['error'] = "The phone field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_name' => $this->input->post('company_name'),
		'contact_person' => $this->input->post('contact_person'),
		'website_url' => $this->input->post('website'),
		'starting_year' => $this->input->post('starting_year'),
		'email' => $this->input->post('email'),
		'phone' => $this->input->post('phone'),
		'address_1' => $this->input->post('address_1'),
		'address_2' => $this->input->post('address_2'),
		'city' => $this->input->post('city'),
		'state' => $this->input->post('state'),
		'zipcode' => $this->input->post('zipcode'),
		'country' => $this->input->post('country'),
		);
		
		$result = $this->Xin_model->update_company_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Company Information updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function logo_info() {
	
		if($this->input->post('type')=='logo_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
		
		if($_FILES['p_file']['size'] == 0) {
			$Return['error'] = "Select Logo.";
		}
		if($Return['error']!=''){
				$this->output($Return);
			}
							
		if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {
		//checking image type
		$allowed =  array('png','jpg','jpeg','pdf','gif');
		$filename = $_FILES['p_file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		
        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
        $subdomain_name; // Print the sub domain
        
        $accounts_url = base_url();
		
		if(in_array($ext,$allowed)){
			$tmp_name = $_FILES["p_file"]["tmp_name"];
			$profile = "../uploads/logo/";
			$set_img = $accounts_url."uploads/logo/";
			// basename() may prevent filesystem traversal attacks;
			// further validation/sanitation of the filename may be appropriate
			$name = basename($_FILES["p_file"]["name"]);
			$uxi = rand(111, 999).round(microtime(true)).'.'.$ext;
            $newfilename = "logo_" . $uxi;
			move_uploaded_file($tmp_name, $profile.$newfilename);
			$fname = $newfilename;			
			
			
			$cinfo = $this->Xin_model->read_company_setting_info(1);
		    $delete_file = $cinfo[0]->logo;
			
			} else {
				$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif for logo first";
			}
		}	
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'logo' => $fname
		);
		$result = $this->Xin_model->update_company_info_record($data,$id);	
		if ($result == TRUE) {
		    
		    if(!empty($delete_file))
		    {
    		    if(file_exists('../uploads/logo/'.$delete_file))
    			{
    			    unlink('../uploads/logo/'.$delete_file);
    			}
		    }
		    
			$Return['img'] = $set_img.$fname;
			$Return['result'] = 'Logo updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;

		}
	}
	
	// Validate and update info in database
	public function profile_background() {
	
		if($this->input->post('type')=='profile_background') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->input->post('user_id');
		
		if($_FILES['p_file']['size'] == 0) {
			$Return['error'] = "Select Profile cover.";
		} else {
		if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {
			//checking image type
			$allowed =  array('png','jpg','jpeg','pdf','gif');
			$filename = $_FILES['p_file']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(in_array($ext,$allowed)){
				
				$uploadedfile = $_FILES['p_file']['tmp_name'];
                    
    			if ($ext == "jpg" || $ext == "jpeg")
    				{
    				    $src = imagecreatefromjpeg($uploadedfile);
    				}
    			  else if ($extension == "png")
    				{
    				    $src = imagecreatefrompng($uploadedfile);
    				}
    			  else
    				{
    				    $src = imagecreatefromgif($uploadedfile);
    				}
    
    			list($width, $height) = getimagesize($uploadedfile);
    			$newwidth = 750;
    			$newheight = ($height / $width) * $newwidth;
    			$tmp = imagecreatetruecolor($newwidth, $newheight);
    			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    			$uxi = rand(111, 999).round(microtime(true)).'.'.$ext;
                $fname = $photo = "profile_background_" . $uxi;
    			$filename = "uploads/profile/background/" . $photo;
    			imagejpeg($tmp, $filename, 100);
    			imagedestroy($src);
    			imagedestroy($tmp);
				
				$result2     = $this->Employees_model->read_employee_information($this->input->post('user_id'));
        		$delete_file = $result2[0]->profile_background;
        		
				
				$data = array(
				'profile_background' => $fname
				);
				$result = $this->Employees_model->basic_info($data,$id);	
				if ($result == TRUE) {
					$Return['profile_background'] = site_url('uploads/profile/background/'.$fname);
					$Return['result'] = 'Profile Background updated.';
					
					if(file_exists('uploads/profile/background/'.$delete_file))
					{
					    unlink('uploads/profile/background/'.$delete_file);
					}
					
				} else {
					$Return['error'] = 'Bug. Something went wrong, please try again.';
				}
				$this->output($Return);
				exit;	
		
			} else {
				$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
				}
			}
		}
				
		if($Return['error']!=''){
		$this->output($Return);
		}
		}
	}
	
	// Validate and update info in database
	public function sign_in_logo() {
	
		if($this->input->post('type')=='singin_logo') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
		
		if($_FILES['p_file3']['size'] == 0) {
			$Return['error'] = "Select sign in page logo.";
		} else {
		if(is_uploaded_file($_FILES['p_file3']['tmp_name'])) {
			//checking image type
			$allowed =  array('png','jpg','jpeg','pdf','gif');
			$filename = $_FILES['p_file3']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(in_array($ext,$allowed)){
				$tmp_name = $_FILES["p_file3"]["tmp_name"];
				$profile = "uploads/logo/signin/";
				$set_img = base_url()."uploads/logo/signin/";
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($_FILES["p_file3"]["name"]);
				$newfilename = 'signin_logo_'.round(microtime(true)).'.'.$ext;
				move_uploaded_file($tmp_name, $profile.$newfilename);
				$fname = $newfilename;			
				
				$data = array(
				'sign_in_logo' => $fname
				);
				$result = $this->Xin_model->update_company_info_record($data,$id);	
				if ($result == TRUE) {
					$Return['img'] = $set_img.$fname;
					$Return['result'] = 'Sign-in page logo updated.';
				} else {
					$Return['error'] = 'Bug. Something went wrong, please try again.';
				}
				$this->output($Return);
				exit;	
		
			} else {
				$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
				}
			}
		}
				
		if($Return['error']!=''){
		$this->output($Return);
		}
		}
	}
	
	// Validate and update info in database
	public function job_logo() {
	
		if($this->input->post('type')=='job_logo') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
		
		if($_FILES['p_file4']['size'] == 0) {
			$Return['error'] = "Select job logo.";
		} else {
		if(is_uploaded_file($_FILES['p_file4']['tmp_name'])) {
			//checking image type
			$allowed =  array('png','jpg','jpeg','pdf','gif');
			$filename = $_FILES['p_file4']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(in_array($ext,$allowed)){
				$tmp_name = $_FILES["p_file4"]["tmp_name"];
				$profile = "uploads/logo/job/";
				$set_img = base_url()."uploads/logo/job/";
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($_FILES["p_file4"]["name"]);
				$newfilename = 'job_logo_'.round(microtime(true)).'.'.$ext;
				move_uploaded_file($tmp_name, $profile.$newfilename);
				$fname = $newfilename;			
				
				$data = array(
				'job_logo' => $fname
				);
				$result = $this->Xin_model->update_setting_info_record($data,$id);	
				if ($result == TRUE) {
					$Return['img'] = $set_img.$fname;
					$Return['result'] = 'Recruitment logo updated.';
				} else {
					$Return['error'] = 'Bug. Something went wrong, please try again.';
				}
				$this->output($Return);
				exit;	
		
			} else {
				$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
				}
			}
		}
				
		if($Return['error']!=''){
		$this->output($Return);
		}
		}
	}
	
	// Validate and update info in database
	public function payroll_logo() {
	
		if($this->input->post('type')=='payroll_logo') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
		
		if($_FILES['p_file5']['size'] == 0) {
			$Return['error'] = "Select payroll logo.";
		} else {
		if(is_uploaded_file($_FILES['p_file5']['tmp_name'])) {
			//checking image type
			$allowed =  array('png','jpg','jpeg','pdf','gif');
			$filename = $_FILES['p_file5']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(in_array($ext,$allowed)){
				$tmp_name = $_FILES["p_file5"]["tmp_name"];
				$profile = "uploads/logo/payroll/";
				$set_img = base_url()."uploads/logo/payroll/";
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($_FILES["p_file5"]["name"]);
				$uxi = rand(111, 999).round(microtime(true)).'.'.$ext;
                $newfilename = "payroll_logo_" . $uxi;
				move_uploaded_file($tmp_name, $profile.$newfilename);
				$fname = $newfilename;		
			
    			$setting = $this->Xin_model->read_setting_info(1);
    		    $delete_file = $setting[0]->payroll_logo;
			
			
				
				$data = array(
				'payroll_logo' => $fname
				);
				$result = $this->Xin_model->update_setting_info_record($data,$id);	
				if ($result == TRUE) {
				    
				    if($delete_file!='your-logo-here.png')
				    {
    				    if(file_exists('uploads/logo/payroll/'.$delete_file))
    					{
    					    unlink('uploads/logo/payroll/'.$delete_file);
    					}
				    }
				    
					$Return['img'] = $set_img.$fname;
					$Return['result'] = 'Payroll logo updated.';
				} else {
					$Return['error'] = 'Bug. Something went wrong, please try again.';
				}
				$this->output($Return);
				exit;	
		
			} else {
				$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
				}
			}
		}
				
		if($Return['error']!=''){
		$this->output($Return);
		}
		}
	}
	
	// Validate and update info in database
	public function system_info() {
	
		if($this->input->post('type')=='system_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
		
		if(trim($this->input->post('application_name'))==='') {
       		 $Return['error'] = "The application name field is required.";
		} else if($this->input->post('default_currency_symbol')==='') {
			$Return['error'] = "The default currency field is required.";
		} else if($this->input->post('show_currency')==='') {
			$Return['error'] = "The default currency symbol field is required.";
		} else if($this->input->post('currency_position')==='') {
			$Return['error'] = "The currency position field is required.";
		} else if($this->input->post('date_format')==='') {
			$Return['error'] = "The date format field is required.";
		} else if($this->input->post('footer_text')==='') {
			$Return['error'] = "The footer text field is required.";
		} 
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'application_name' => $this->input->post('application_name'),
		'default_currency_symbol' => $this->input->post('default_currency_symbol'),
		'default_currency' => $this->input->post('default_currency_symbol'),
		'show_currency' => $this->input->post('show_currency'),
		'currency_position' => $this->input->post('currency_position'),
		'date_format_xi' => $this->input->post('date_format'),
		'footer_text' => $this->input->post('footer_text'),
		'enable_page_rendered' => $this->input->post('enable_page_rendered'),
		'enable_current_year' => $this->input->post('enable_current_year'),
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'System Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function layout_skin_info() {
	
		if($this->input->post('type')=='layout_skin_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
			
		$data = array(
		'fixed_header' => $this->input->post('fixed-header'),
		'fixed_sidebar' => $this->input->post('fixed-sidebar'),
		'boxed_wrapper' => $this->input->post('boxed-wrapper'),
		'layout_static' => $this->input->post('static'),
		'system_skin' => $this->input->post('skin'),
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'System Layout updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function role_info() {
	
		if($this->input->post('type')=='role_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
			
		$data = array(
		'employee_manage_own_contact' => $this->input->post('employee_manage_own_contact'),
		'employee_manage_own_social' => $this->input->post('employee_manage_own_social'),
		'employee_manage_own_bank_account' => $this->input->post('employee_manage_own_bank_account'),
		'employee_manage_own_qualification' => $this->input->post('employee_manage_own_qualification'),
		'employee_manage_own_work_experience' => $this->input->post('employee_manage_own_work_experience'),
		'employee_manage_own_document' => $this->input->post('employee_manage_own_document'),
		'employee_manage_own_picture' => $this->input->post('employee_manage_own_picture'),
		'employee_manage_own_profile' => $this->input->post('employee_manage_own_profile'),
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Role Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function sidebar_setting_info() {
	
		if($this->input->post('type')=='other_settings') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
			
		$data = array(
		'enable_attendance' => $this->input->post('enable_attendance'),
		'enable_job_application_candidates' => $this->input->post('enable_job'),
		'enable_profile_background' => $this->input->post('enable_profile_background'),
		'enable_email_notification' => $this->input->post('role_email_notification'),
		'notification_close_btn' => $this->input->post('close_btn'),
		'notification_bar' => $this->input->post('notification_bar'),
		'enable_policy_link' => $this->input->post('role_policy_link'),
		'enable_layout' => $this->input->post('enable_layout'),
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Setting Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function attendance_info() {
	
		if($this->input->post('type')=='attendance_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
			
		$data = array(
		'enable_attendance' => $this->input->post('enable_attendance'),
		'enable_clock_in_btn' => $this->input->post('enable_clock_in_btn')
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Attendance Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function email_info() {
	
		if($this->input->post('type')=='email_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
			
		$data = array(
		'enable_email_notification' => $this->input->post('enable_email_notification')
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Email Notification Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function job_info() {
	
		if($this->input->post('type')=='job_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		if($this->input->post('job_application_format')==='') {
        	$Return['error'] = "The job application format field is required.";
		}
		
		if($Return['error']!=''){
			$hrm_f->output($Return);
		}
		$id = 1;
			
		$data = array(
		'enable_job_application_candidates' => $this->input->post('enable_job'),
		'job_application_format' => $this->input->post('job_application_format')
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Job Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function animation_effect_info() {
	
		if($this->input->post('type')=='animation_effect_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = 1;
			
		$data = array(
		'animation_effect' => $this->input->post('animation_effect'),
		'animation_effect_topmenu' => $this->input->post('animation_effect_topmenu'),
		'animation_effect_modal' => $this->input->post('animation_effect_modal')
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Animation Effect Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function notification_position_info() {
	
		if($this->input->post('type')=='notification_position_info') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		if($this->input->post('notification_position')==='') {
        	$Return['error'] = "The notification position field is required.";
		}
		
		if($Return['error']!=''){
			$hrm_f->output($Return);
		}
		$id = 1;
			
		$data = array(
		'notification_position' => $this->input->post('notification_position'),
		'notification_close_btn' => $this->input->post('notification_close_btn'),
		'notification_bar' => $this->input->post('notification_bar')
		);
		
		$result = $this->Xin_model->update_setting_info_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Notification Position Configuration updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete_single_backup() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Xin_model->delete_single_backup_record($id);
		if(isset($id)) {
			$Return['result'] = 'Database Backup deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
	
	/*  ALL CONSTANTS */
	
	// Contract Type > list
	  public function contract_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$contract_type = $this->Xin_model->get_contract_types();

		$data = array();

        foreach($contract_type->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->contract_type_id . '" data-field_type="contract_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contract_type_id . '" title="Delete" data-token_type="contract_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->name,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $contract_type->num_rows(),
			 "recordsFiltered" => $contract_type->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     } 
	 
	 // Education Level > list
	  public function education_level_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_qualification_education();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->education_level_id . '" data-field_type="education_level"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->education_level_id . '" title="Delete" data-token_type="education_level"><i class="fa fa-trash-o"></i></button></span>',
			$r->name,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Language > list
	  public function qualification_language_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_qualification_language();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->language_id . '" data-field_type="qualification_language"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->language_id . '" title="Delete" data-token_type="qualification_language"><i class="fa fa-trash-o"></i></button></span>',
			$r->name,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Skill > list
	  public function qualification_skill_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_qualification_skill();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->skill_id . '" data-field_type="qualification_skill"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->skill_id . '" title="Delete" data-token_type="qualification_skill"><i class="fa fa-trash-o"></i></button></span>',
			$r->name,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Document Type > list
	  public function document_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_document_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->document_type_id . '" data-field_type="document_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->document_type_id . '" title="Delete" data-token_type="document_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->document_type,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Award Type > list
	  public function award_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_award_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->award_type_id . '" data-field_type="award_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->award_type_id . '" title="Delete" data-token_type="award_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->award_type,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Leave Type > list
	  public function leave_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_leave_type();

		$data = array();

        foreach($constant->result() as $r) {
            $action='Not editable';
            if(($r->type_name!='Unpaid Leave')&&($r->type_name!='Compoff') ){
                $action .= '<span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->leave_type_id . '" title="Delete" data-token_type="leave_type"><i class="fa fa-trash-o"></i></button></span>';
                $action =	'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->leave_type_id . '" data-field_type="leave_type"><i class="fa fa-pencil-square-o"></i></button></span>';

            }		$data[] = array($action,
			$r->type_name,
			$r->days_per_year
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Warning Type > list
	  public function warning_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_warning_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->warning_type_id . '" data-field_type="warning_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->warning_type_id . '" title="Delete" data-token_type="warning_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->type
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Termination Type > list
	  public function termination_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_termination_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->termination_type_id . '" data-field_type="termination_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->termination_type_id . '" title="Delete" data-token_type="termination_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->type
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Expense Type > list
	  public function expense_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_expense_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->expense_type_id . '" data-field_type="expense_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->expense_type_id . '" title="Delete" data-token_type="expense_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Job Type > list
	  public function job_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_job_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->job_type_id . '" data-field_type="job_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->job_type_id . '" title="Delete" data-token_type="job_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->type
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Exit Type > list
	  public function exit_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_exit_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->exit_type_id . '" data-field_type="exit_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->exit_type_id . '" title="Delete" data-token_type="exit_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->type
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Travel Arrangement Type > list
	  public function travel_arr_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_travel_type();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->arrangement_type_id . '" data-field_type="travel_arr_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->arrangement_type_id . '" title="Delete" data-token_type="travel_arr_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->type
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Payment Method > list
	  public function payment_method_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_payment_method();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->payment_method_id . '" data-field_type="payment_method"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->payment_method_id . '" title="Delete" data-token_type="payment_method"><i class="fa fa-trash-o"></i></button></span>',
			$r->method_name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 // Currency type > list
	  public function currency_type_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$constant = $this->Xin_model->get_currency_types();

		$data = array();

        foreach($constant->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->currency_id . '" data-field_type="currency_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->currency_id . '" title="Delete" data-token_type="currency_type"><i class="fa fa-trash-o"></i></button></span>',
			$r->name,
			$r->code,
			$r->symbol
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     }
	 
	 /*  Add constant data */
	 
	// Validate and add info in database
	public function contract_type_info() {
	
		if($this->input->post('type')=='contract_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('contract_type')==='') {
        	$Return['error'] = "The contract type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('contract_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Xin_model->add_contract_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Contract type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function document_type_info() {
	
		if($this->input->post('type')=='document_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('document_type')==='') {
        	$Return['error'] = "The document type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'document_type' => $this->input->post('document_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Xin_model->add_document_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Document type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function edu_level_info() {
	
		if($this->input->post('type')=='edu_level_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The education level field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_edu_level($data);
		if ($result == TRUE) {
			$Return['result'] = 'Education Level added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function edu_language_info() {
	
		if($this->input->post('type')=='edu_language_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The education language field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_edu_language($data);
		if ($result == TRUE) {
			$Return['result'] = 'Education Language added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function edu_skill_info() {
	
		if($this->input->post('type')=='edu_skill_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The education skill field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_edu_skill($data);
		if ($result == TRUE) {
			$Return['result'] = 'Education Skill added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function payment_method_info() {
	
		if($this->input->post('type')=='payment_method_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('payment_method')==='') {
        	$Return['error'] = "The payment method field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'method_name' => $this->input->post('payment_method'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_payment_method($data);
		if ($result == TRUE) {
			$Return['result'] = 'Payment Method added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function award_type_info() {
	
		if($this->input->post('type')=='award_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('award_type')==='') {
        	$Return['error'] = "The award type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'award_type' => $this->input->post('award_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_award_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Award Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function leave_type_info() {
	
		if($this->input->post('type')=='leave_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('leave_type')==='') {
        	$Return['error'] = "The leave type field is required.";
		} else if($this->input->post('days_per_year')==='') {
        	$Return['error'] = "The days per year field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type_name' => $this->input->post('leave_type'),
		'days_per_year' => $this->input->post('days_per_year'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_leave_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Leave Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function warning_type_info() {
	
		if($this->input->post('type')=='warning_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('warning_type')==='') {
        	$Return['error'] = "The warning type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('warning_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_warning_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Warning Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function termination_type_info() {
	
		if($this->input->post('type')=='termination_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('termination_type')==='') {
        	$Return['error'] = "The termination type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('termination_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_termination_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Termination Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function expense_type_info() {
	
		if($this->input->post('type')=='expense_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('expense_type')==='') {
        	$Return['error'] = "The expense type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('expense_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_expense_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Expense Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function job_type_info() {
	
		if($this->input->post('type')=='job_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('job_type')==='') {
        	$Return['error'] = "The job type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('job_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_job_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Job Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function exit_type_info() {
	
		if($this->input->post('type')=='exit_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('exit_type')==='') {
        	$Return['error'] = "The exit type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('exit_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_exit_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Exit Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function travel_arr_type_info() {
	
		if($this->input->post('type')=='travel_arr_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('travel_arr_type')==='') {
        	$Return['error'] = "The travel arrangement type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('travel_arr_type'),
		'created_at' => date('d-m-Y h:i:s')
		);
		
		$result = $this->Xin_model->add_travel_arr_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Job Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function currency_type_info() {
	
		if($this->input->post('type')=='currency_type_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The currency name field is required.";
		} else if($this->input->post('code')==='') {
        	$Return['error'] = "The currency code field is required.";
		} else if($this->input->post('symbol')==='') {
        	$Return['error'] = "The currency symbol field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name'),
		'code' => $this->input->post('code'),
		'symbol' => $this->input->post('symbol')
		);
		
		$result = $this->Xin_model->add_currency_type($data);
		if ($result == TRUE) {
			$Return['result'] = 'Currency Type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	/*  DELETE CONSTANTS */
	// delete constant record > table
	public function delete_contract_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_contract_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Contract Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_document_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_document_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Document Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_payment_method() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_payment_method_record($id);
			if(isset($id)) {
				$Return['result'] = 'Payment Method deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_education_level() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_education_level_record($id);
			if(isset($id)) {
				$Return['result'] = 'Education Level deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_qualification_language() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_qualification_language_record($id);
			if(isset($id)) {
				$Return['result'] = 'Qualification Language deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_qualification_skill() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_qualification_skill_record($id);
			if(isset($id)) {
				$Return['result'] = 'Qualification Skill deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_award_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_award_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Award Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_leave_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_leave_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Leave Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_warning_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_warning_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Warning Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_termination_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_termination_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Termination Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_expense_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_expense_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Expense Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_job_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_job_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Job Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_exit_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_exit_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Exit Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_travel_arr_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_travel_arr_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Travel Arrangement Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// delete constant record > table
	public function delete_currency_type() {
		
		if($this->input->post('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$result = $this->Xin_model->delete_currency_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Currency Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	// read and view all constants data > modal form
	public function constants_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('settings/dialog_constants', $data);
		} else {
			redirect('');
		}
	}
	
	/*  UPDATE RECORD > CONSTANTS*/
	
	// Validate and update info in database
	public function update_document_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The document type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'document_type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_document_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Document type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_contract_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The contract type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_contract_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Contract type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_payment_method() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The payment method field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'method_name' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_payment_method_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Payment Method updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_education_level() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The education level field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_education_level_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Education Level updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_qualification_language() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The qualification language field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_qualification_language_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Qualification Language updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_qualification_skill() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The qualification skill field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_qualification_skill_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Qualification Skill updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_award_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The award type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'award_type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_award_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Award type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_leave_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The leave type field is required.";
		} else if($this->input->post('days_per_year')==='') {
        	$Return['error'] = "The days per year field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type_name' => $this->input->post('name'),
		'days_per_year' => $this->input->post('days_per_year')
		);
		
		$result = $this->Xin_model->update_leave_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Leave type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_warning_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The warning type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_warning_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Warning type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_termination_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The termination type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_termination_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Termination type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_expense_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The expense type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_expense_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Expense type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_job_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The job type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_job_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Job type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_exit_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The exit type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_exit_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Exit type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_travel_arr_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The travel arrangement type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'type' => $this->input->post('name')
		);
		
		$result = $this->Xin_model->update_travel_arr_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Travel Arrangement type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_currency_type() {
	
		if($this->input->post('type')=='edit_record') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */		
		if($this->input->post('name')==='') {
        	$Return['error'] = "The currency name field is required.";
		} else if($this->input->post('code')==='') {
        	$Return['error'] = "The currency code field is required.";
		} else if($this->input->post('symbol')==='') {
        	$Return['error'] = "The currency symbol field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name'),
		'code' => $this->input->post('code'),
		'symbol' => $this->input->post('symbol')
		);
		
		$result = $this->Xin_model->update_currency_type_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Currency type updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Download Backup
	public function download_backup() {
	    
	    $session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
	    
        $zipname = 'Backup_'.rand(111111,9999999).'.zip';
        $zip = new ZipArchive;
        $zip->open('user_backups/'.$zipname, ZipArchive::CREATE);
        
        // Company Documents
        $document = $this->Document_model->get_documents();
        foreach($document->result() as $r) {
            $company_data = $this->Xin_model->read_company_info($r->company_id);
		    $all_files = $this->Document_model->get_document_files($r->document_id);
		    $file_no = 1;
    		foreach($all_files as $row) {
    		   $file_ext = substr(strrchr($row->img_name, '.'), 1);
    		   $file = 'uploads/company_documents/'.$row->img_name;
    		   $download_file = file_get_contents($file);
    		   $zip->addFromString('Docs/Company Documents/'.$company_data[0]->name.'/'.$r->title.'/'.basename($file), $download_file);
               $zip->renameName('Docs/Company Documents/'.$company_data[0]->name.'/'.$r->title.'/'.basename($file), 'Docs/Company Documents/'.$company_data[0]->name.'/'.$r->title.'/file_'.$file_no.'.'.$file_ext);
               $file_no++;
    		}
		}
		
		
		//Old Employee File Type
		/*
		// Employee Documents
		$employee = $this->Employees_model->get_employees();
		foreach($employee->result() as $r) {
		    $all_files = $this->Employees_model->set_employee_documents($r->user_id);
		    $file_no = 1;
    		foreach($all_files->result() as $row) {
    		   $doc_type = $this->Employees_model->read_document_type_information($row->document_type_id);
    		   $file_ext = substr(strrchr($row->document_file, '.'), 1);
    		   $file = 'uploads/document/'.$row->document_file;
    		   $download_file = file_get_contents($file);
    		   $zip->addFromString('Docs/Employee Documents/'.$r->first_name.' '.$r->last_name.'/'.basename($file), $download_file);
               $zip->renameName('Docs/Employee Documents/'.$r->first_name.' '.$r->last_name.'/'.basename($file), 'Docs/Employee Documents/'.$r->first_name.' '.$r->last_name.'/'.$doc_type[0]->document_type.'_'.$file_no.'.'.$file_ext);
               $file_no++;
    		}
		}
		*/
		
		// Employee Documents
		$employee = $this->Employees_model->get_employees();
		foreach($employee->result() as $r) {
		    $all_files = $this->Employees_model->set_employee_documents($r->user_id);
    		foreach($all_files->result() as $row2) {
    		   $doc_type = $this->Employees_model->read_document_type_information($row2->document_type_id);
    		   $all_files2 = $this->Xin_model->get_employee_document_files($row2->document_id);
    		   $file_no = 1;
        		foreach($all_files2 as $row) {
        		   $file_ext = substr(strrchr($row->img_name, '.'), 1);
        		   $file = 'uploads/document/'.$row->img_name;
        		   $download_file = file_get_contents($file);
                   
                   $zip->addFromString('Docs/Employee Documents/'.$r->first_name.' '.$r->last_name.'/'.$doc_type[0]->document_type.'/'.basename($file), $download_file);
                   $zip->renameName('Docs/Employee Documents/'.$r->first_name.' '.$r->last_name.'/'.$doc_type[0]->document_type.'/'.basename($file), 'Docs/Employee Documents/'.$r->first_name.' '.$r->last_name.'/'.$doc_type[0]->document_type.'/file_'.$file_no.'.'.$file_ext);
                   $file_no++;
        		}
    		}
		}
		
		
		
        		///Create Employees Table CSV
        		$employee = $this->Employees_model->get_employees();
        		$data = array();
                $csv_out = "ID,First Name,Last Name,Username,Email,Date of Birth,Department,Designation,Date of Joining \n ";
                foreach($employee->result() as $r) {
                    
            		// get designation
            		$designation = $this->Designation_model->read_designation_information($r->designation_id);
            		// department
            		$department = $this->Department_model->read_department_information($r->department_id);
            		$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
        
            		$csv_out.= $r->employee_id.',';
            		$csv_out.= $r->first_name.',';
            		$csv_out.= $r->last_name.',';
            		$csv_out.= $r->username.',';
            		$csv_out.= $r->email.',';
            		$csv_out.= $r->date_of_birth.',';
            		$csv_out.= $department[0]->department_name.',';
            		$csv_out.= $department_designation.',';
            		$csv_out.= $r->date_of_joining.',';
            		$csv_out.= "\n";
        	  }
        	    $employees_csv = rand(11111,9999999).'.csv';
        	    $csv_handler = fopen ('user_backups/'.$employees_csv,'w');
                fwrite ($csv_handler,$csv_out);
                fclose ($csv_handler);
        		
        		$file = 'user_backups/'.$employees_csv;
            	$download_file = file_get_contents($file);
        		$zip->addFromString('Docs/'.basename($file), $download_file);
                $zip->renameName('Docs/'.basename($file), 'Docs/Employees.csv');
                ///Close Employees CSV
                
                
                
                
                
                
                ///Create Payment History Table CSV
        		$history = $this->Payroll_model->all_payment_history();
        		$data = array();
                $csv_out = "Employee ID,Employee Name,Payment Month,Payment Date,Paid Amount,Payment Type \n ";
                foreach($history->result() as $r) {
                    
            		$user = $this->Xin_model->read_user_info($r->employee_id);
            		if(!is_null($user)){
			  	        $full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  	        $month_payment = date("F - Y", strtotime($r->payment_date));
			  	        $p_amount = $this->Xin_model->currency_sign($r->payment_amount);
			  	        $created_at = $this->Xin_model->set_date_format($r->created_at);
			  	        
			  	          if($r->payment_method==1){
            			  $p_method = 'Online';
            			  } else if($r->payment_method==2){
            				  $p_method = 'PayPal';
            			  } else if($r->payment_method==3) {
            				  $p_method = 'Payoneer';
            			  } else if($r->payment_method==4){
            				  $p_method = 'Bank Transfer';
            			  } else if($r->payment_method==5) {
            				  $p_method = 'Cheque';
            			  } else {
            				  $p_method = 'Cash';
            			  }
        
                		$csv_out.= $user[0]->employee_id.',';
                		$csv_out.= $full_name.',';
                		$csv_out.= $month_payment.',';
                		$csv_out.= $created_at.',';
                		$csv_out.= $p_amount.',';
                		$csv_out.= $p_method.',';
                		$csv_out.= "\n";
            		}
        	  }
        	    $payment_history_csv = rand(11111,9999999).'.csv';
        	    $csv_handler = fopen ('user_backups/'.$payment_history_csv,'w');
                fwrite ($csv_handler,$csv_out);
                fclose ($csv_handler);
        		
        		$file = 'user_backups/'.$payment_history_csv;
            	$download_file = file_get_contents($file);
        		$zip->addFromString('Docs/'.basename($file), $download_file);
                $zip->renameName('Docs/'.basename($file), 'Docs/Payment_History.csv');
                ///Close Payment History CSV
                
                
                
                
                
                ///Advance Salary Report List
                $advance_salary = $this->Payroll_model->get_advance_salaries_report();
		
        		$csv_out = "Employee Name,Total Amount,Total Paid Amount,Remaining Amount \n ";
        
                  foreach($advance_salary->result() as $r) {
        
        			// get addd by > template
        			$user = $this->Xin_model->read_user_info($r->employee_id);
        			// user full name
        			if(!is_null($user)){
        				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
        			} else {
        				$full_name = '--';	
        			}
        			
        			$d = explode('-',$r->month_year);
        			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
        			$month_year = $get_month.', '.$d[0];
        			// get net salary
        			$advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
        			// get date > created at > and format
        			$cdate = $this->Xin_model->set_date_format($r->created_at);
        			// get monthly installment
        			$monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);
        			
        			$remainig_amount = $r->advance_amount - $r->total_paid;
        			$ramount = $this->Xin_model->currency_sign($remainig_amount);
        			
        			//total paid
        			$total_paid = $this->Xin_model->currency_sign($r->total_paid);
        			
        			$csv_out.= $full_name.',';
                    $csv_out.= $advance_amount.',';
                    $csv_out.= $total_paid.',';
					$csv_out.= $ramount.',';
					$csv_out.= "\n"; 
                  }
                  
                    $advance_salary_csv = rand(11111,9999999).'.csv';
            	    $csv_handler = fopen ('user_backups/'.$advance_salary_csv,'w');
                    fwrite ($csv_handler,$csv_out);
                    fclose ($csv_handler);
            		
            		$file = 'user_backups/'.$advance_salary_csv;
                	$download_file = file_get_contents($file);
            		$zip->addFromString('Docs/'.basename($file), $download_file);
                    $zip->renameName('Docs/'.basename($file), 'Docs/Advance_Salary_Report.csv');
                    ///Close Advance Salary CSV
                    
                    
                    
                    
                    
                    
                    
                    
                    ///Loan Report List
                    $advance_salary = $this->Payroll_model->get_loan_report();
    	
        		    $csv_out = "Employee Name,Total Amount,Total Paid Amount,Remaining Amount \n ";
        
                  foreach($advance_salary->result() as $r) {
        
        			// get addd by > template
        			$user = $this->Xin_model->read_user_info($r->employee_id);
        			// user full name
        			if(!is_null($user)){
        				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
        			} else {
        				$full_name = '--';	
        			}
        			
        			$d = explode('-',$r->month_year);
        			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
        			$month_year = $get_month.', '.$d[0];
        			// get net salary
        			$advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
        			// get date > created at > and format
        			$cdate = $this->Xin_model->set_date_format($r->created_at);
        			// get monthly installment
        			$monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);
        			
        			$remainig_amount = $r->advance_amount - $r->total_paid;
        			$ramount = $this->Xin_model->currency_sign($remainig_amount);
        			
        			//total paid
        			$total_paid = $this->Xin_model->currency_sign($r->total_paid);
        			
        			$csv_out.= $full_name.',';
                    $csv_out.= $advance_amount.',';
                    $csv_out.= $total_paid.',';
					$csv_out.= $ramount.',';
					$csv_out.= "\n"; 
                  }
                  
                    $loan_report_csv = rand(11111,9999999).'.csv';
            	    $csv_handler = fopen ('user_backups/'.$loan_report_csv,'w');
                    fwrite ($csv_handler,$csv_out);
                    fclose ($csv_handler);
            		
            		$file = 'user_backups/'.$loan_report_csv;
                	$download_file = file_get_contents($file);
            		$zip->addFromString('Docs/'.basename($file), $download_file);
                    $zip->renameName('Docs/'.basename($file), 'Docs/Loan_Report.csv');
                    ///Close Loan Report CSV
                
                
                
                
		
        $zip->close();
        
        
        
                // Deleting Created CSV Files
                
                if(file_exists('user_backups/'.$employees_csv))
                {
                    unlink('user_backups/'.$employees_csv);
                }
                
                if(file_exists('user_backups/'.$payment_history_csv))
                {
                    unlink('user_backups/'.$payment_history_csv);
                }
                
                if(file_exists('user_backups/'.$advance_salary_csv))
                {
                    unlink('user_backups/'.$advance_salary_csv);
                }
                
                if(file_exists('user_backups/'.$loan_report_csv))
                {
                    unlink('user_backups/'.$loan_report_csv);
                }
        
        
        
        $file = 'user_backups/'.$zipname;
        $name = $zipname;
        
		 if(!is_readable($file)) die('File not found or inaccessible!');
		 $size = filesize($file);
		 $name = rawurldecode($name);
		 
		 //turn off output buffering to decrease cpu usage
		 @ob_end_clean(); 
		 
		 // required for IE, otherwise Content-Disposition may be ignored
		 if(ini_get('zlib.output_compression'))
		 ini_set('zlib.output_compression', 'Off');
		 header('Content-Type: application/zip');
		 header('Content-Disposition: attachment; filename="'.$name.'"');
		 header("Content-Transfer-Encoding: binary");
		 header('Accept-Ranges: bytes');
		 
		 // multipart-download and download resuming support
		 if(isset($_SERVER['HTTP_RANGE']))
		 {
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
			$range=intval($range);
			if(!$range_end) {
				$range_end=$size-1;
			} else {
				$range_end=intval($range_end);
			}
		
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		 } else {
			$new_length=$size;
			header("Content-Length: ".$size);
		 }
		 
		 /* Will output the file itself */
		 $chunksize = 1*(1024*1024); //you may want to change this
		 $bytes_send = 0;
		 if ($file = fopen($file, 'r'))
		 {
			if(isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
		 
			while(!feof($file) && 
				(!connection_aborted()) && 
				($bytes_send<$new_length)
				  )
			{
				$buffer = fread($file, $chunksize);
				echo($buffer); 
				flush();
				$bytes_send += strlen($buffer);
			}
		    fclose($file);
		 } 
		
        if(file_exists('user_backups/'.$zipname))
        {
            unlink('user_backups/'.$zipname);
        }
        
        
        
        
        
        
	}
	
}
