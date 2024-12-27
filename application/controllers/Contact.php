<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->library('curl');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the models
		$this->load->model("Contact_model");
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
		
		$user_id = $this->session->userdata('user_id');
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Contacts';
		$data['path_url'] = 'contacts';
		$data['get_contact_group'] = $this->Contact_model->get_contact_group($user_id);
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("contact/contact_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
     }
     
    public function import_google()
     {
         
            function curl($url, $post = "") {
            	$curl = curl_init();
            	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
            	curl_setopt($curl, CURLOPT_URL, $url);
            	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            	if ($post != "") {
            		curl_setopt($curl, CURLOPT_POST, 5);
            		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            	}
            	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
            	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
            	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
            	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            	$contents = curl_exec($curl);
            	curl_close($curl);
            	return $contents;
            }
         
            require_once '../contacts/google-api-php-client/src/Google/autoload.php';// or wherever autoload.php is located
            $google_client_id = '290198589134-p1kvbrpmh25q9tnl0qmpl2okoa2vervn.apps.googleusercontent.com';
            $google_client_secret = 'd3AOMcq3qfUEXpxI5XhMlAi1';
            $google_redirect_uri = site_url().'contact/import_google';
            
            $client = new Google_Client();
            $client -> setApplicationName('Corbuz');
            $client -> setClientid($google_client_id);
            $client -> setClientSecret($google_client_secret);
            $client -> setRedirectUri($google_redirect_uri);
            $client -> setAccessType('online');
            $client -> setScopes('https://www.googleapis.com/auth/contacts.readonly');
            $googleImportUrl = $client -> createAuthUrl();
            
            if (isset($_GET['code'])) {
            	$auth_code = $_GET["code"];
            	$_SESSION['google_code'] = $auth_code;
            }
            else
            {
                header('location:'.$googleImportUrl);
            }

            if(isset($_SESSION['google_code'])) {
            	$auth_code = $_SESSION['google_code'];
            	$max_results = 1000;
                $fields=array(
                    'code'=>  urlencode($auth_code),
                    'client_id'=>  urlencode($google_client_id),
                    'client_secret'=>  urlencode($google_client_secret),
                    'redirect_uri'=>  urlencode($google_redirect_uri),
                    'grant_type'=>  urlencode('authorization_code')
                );
                $post = '';
                foreach($fields as $key=>$value)
                {
                    $post .= $key.'='.$value.'&';
                }	
                $post = rtrim($post,'&');
            	
            	
                $result = curl('https://accounts.google.com/o/oauth2/token',$post);
                $response =  json_decode($result);
                $accesstoken = $response->access_token;
                $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
                $xmlresponse =  curl($url);
                $contacts = json_decode($xmlresponse,true);
            	
            	//echo "<pre>";
            	//print_r($contacts);
            	//deg ($contacts['feed']['entry']);
            	//exit();
            	
            	$contact_count = 0;
            	$c_count       = 0;
            	
            	if(!empty($contacts)) {
            	    foreach ($contacts['feed']['entry'] as $contact) {
            	        
            	        if(!empty($contact['title']['$t']) && ( !empty($contact['gd$phoneNumber'][0]['$t']) || !empty($contact['gd$email'][0]['address']) ))
            	        {
            	            $contact_count++;
            	        }
            	    }
            	}
            	
                //echo $contact_count;
                $bigQuery = '';
                
                if (!empty($contacts['feed']['entry'])) {
            		foreach($contacts['feed']['entry'] as $contact) {
            		    
            		    $image = '';
            		    
            		 if(!empty($contact['title']['$t']) && ( !empty($contact['gd$phoneNumber'][0]['$t']) || !empty($contact['gd$email'][0]['address']) ))
                     {
                         $email = '';
                         $phone = '';
                         
                         if(!empty($contact['gd$email'][0]['address']))
                         {
                             $email = $contact['gd$email'][0]['address'];
                         }
                         
                         if(!empty($contact['gd$phoneNumber'][0]['$t']))
                         {
                             $phone = $contact['gd$phoneNumber'][0]['$t'];
                         }
            		    
            		    $c_num = $this->Contact_model->check_contact_exist($contact['title']['$t'],$phone,$email);
                		if($c_num==0)
                		{
            		    
                    			if (isset($contact['link'][0]['href'])) {
                    				$url =   $contact['link'][0]['href'];
                    				$url = $url . '&access_token=' . urlencode($accesstoken);
                    				$curl = curl_init($url);
                    		        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    		        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    		        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
                    				curl_setopt($curl, CURLOPT_VERBOSE, true);
                    		        $image = curl_exec($curl);
                    		        curl_close($curl);
                    			}
                    			
                    			$photo = '';
                    			$name  = '';
                    			$phone = '';
                    			$email = '';
                    			
                    			if(!empty($contact['title']['$t']))
                    			{
                    			    $name = $contact['title']['$t'];
                    			}
                    			
                    			if(!empty($contact['gd$phoneNumber'][0]['$t']))
                    			{
                    			    $phone = $contact['gd$phoneNumber'][0]['$t'];
                    			}
                    			
                    			if(!empty($contact['gd$email'][0]['address']))
                    			{
                    			    $email = $contact['gd$email'][0]['address'];
                    			}
                    			
                    			if(!empty($name) && ( !empty($phone) || !empty($email)))
                    			{
                    			    if(!empty($image) && $image!='Photo not found')
                        			{
                            			$data = $image;
                                        $uxi = rand(111, 999).round(microtime(true)).'.jpg';
                                        $photo = "contact_" . $uxi;
                            			$filename = "uploads/contacts/" . $photo;
                            			file_put_contents($filename,$data);
                        			}
                        			
                    			    $user_id = $this->session->userdata('user_id');
                        			$data = array(
                            		'name' => $name,
                            		'email' => $email,
                            		'phone1' => $phone,
                            		'user_id' => $user_id,
                            		'photo' => $photo,
                            		'created' => date('Y-m-d H:i:s'),
                            		'time' => time(),
                            		);
                            		$root_id   = $_SESSION['root_id'];
                            		$result = $this->Contact_model->add($data);
                            		
                            		/*
                            		if(empty($bigQuery))
                            		{
                            		    $bigQuery.="INSERT INTO xin_contacts (root_id,name,email,phone1,user_id,photo,created,time)
                                                    VALUES ('".$root_id."','".$name."','".$email."','".$phone."','".$user_id."','".$photo."','".date('Y-m-d H:i:s')."','".time()."')";
                            		}
                            		else
                            		{
                            		    $bigQuery.=", ('".$root_id."','".$name."','".$email."','".$phone."','".$user_id."','".$photo."','".date('Y-m-d H:i:s')."','".time()."')";
                            		}
                            		
                            		$result = $this->Contact_model->multi_add($bigQuery);
                            		*/
                    		
            			}

            			}
            			
            			$c_count++;
            			
                     }
            			
            		}				
            	}
                
                
            }
            
            if($contact_count==$c_count)
            {
               header('location:'.site_url('contact'));
            }
         
     }
 
    public function contact_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("contact/contact_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$user_id = $this->session->userdata('user_id');
		$contact = $this->Contact_model->get_contacts($user_id);
		
		$data = array();

          foreach($contact->result() as $r) {
			  
			  // get user
			  $user = $this->Xin_model->read_user_info($r->user_id);
			  // user full name
			  if(!is_null($user)){
			  	$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  } else {
				  $full_name = '--';	
			  }
			  
			  $contact_group_name = '';
			  if(!empty($r->contact_group))
			  {
			      $group_data = $this->Contact_model->read_contact_group_information($r->contact_group);
			      $contact_group_name = $group_data[0]->name;
			  }
			   
			   if(!empty($r->photo))
                  {
                      $photo_url = site_url().'uploads/contacts/'.$r->photo;
                  }
                  else
                  {
                      $photo_url = site_url().'uploads/profile/default_male.jpg';
                  }
                  
                  if($r->user_id!=$session['user_id'])
                  {
                      $act_btn = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-contact_id="'. $r->id . '"><i class="fa fa-eye"></i></button></span>';
                  }
                  else
                  {
                      $act_btn = '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-contact_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span>
			   		 <span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-contact_id="'. $r->id . '"><i class="fa fa-eye"></i></button></span>
			   		 <span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>';
                  }
			  
               $data[] = array(
			   		$act_btn,
                    '<img src="'.$photo_url.'" width="50" style="border-radius: 25px; margin-right:15px"/>'.
                    $r->name,
                    $r->phone1,
                    $contact_group_name,
                    $r->email,
                    $r->company,
					$full_name
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $contact->num_rows(),
                 "recordsFiltered" => $contact->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$contact_id = $this->input->get('contact_id');
		$result = $this->Contact_model->read_contact_information($contact_id);
		
		$contact_group_name = '';
		if(!empty($result[0]->contact_group))
    	  {
    	      $group_data = $this->Contact_model->read_contact_group_information($result[0]->contact_group);
    	      $contact_group_name = $group_data[0]->name;
    	  }
			  
		$data = array(
				'contact_id' => $result[0]->id,
				'name' => $result[0]->name,
				'company' => $result[0]->company,
				'job_title' => $result[0]->job_title,
				'contact_group' => $result[0]->contact_group,
				'phone1' => $result[0]->phone1,
				'label1' => $result[0]->label1,
				'phone2' => $result[0]->phone2,
				'label2' => $result[0]->label2,
				'phone3' => $result[0]->phone3,
				'label3' => $result[0]->label3,
				'phone4' => $result[0]->phone4,
				'label4' => $result[0]->label4,
				'phone5' => $result[0]->phone5,
				'label5' => $result[0]->label5,
				'phone6' => $result[0]->phone6,
				'label6' => $result[0]->label6,
				'address' => $result[0]->address,
				'email' => $result[0]->email,
				'share_public' => $result[0]->share_public,
				'note' => $result[0]->note,
				'contact_group_name' => $contact_group_name,
				'photo' => $result[0]->photo
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('contact/dialog_contact', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_contact() {
	
		if($this->input->post('add_type')=='contact') {
		// Check validation for user input
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phone1', 'Phone', 'trim|required|xss_clean');
		
		$name = $this->input->post('name');
		$phone = $this->input->post('phone1');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($name==='') {
			$Return['error'] = $this->lang->line('xin_error_name_field');
		} else if( $phone==='') {
			$Return['error'] = 'Atleast one phone number is required';
		} 
		
		/* Check if file uploaded..*/
		else if($_FILES['photo']['size'] == 0) {
			$photo = '';
			//$Return['error'] = $this->lang->line('xin_error_logo_field');
		} else {
			if(is_uploaded_file($_FILES['photo']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['photo']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					
					$uploadedfile = $_FILES['photo']['tmp_name'];
                    
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
        			$newwidth = 200;
        			$newheight = ($height / $width) * $newwidth;
        			$tmp = imagecreatetruecolor($newwidth, $newheight);
        			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        			$uxi = rand(111, 999).round(microtime(true)).'.'.$ext;
                    $photo = "contact_" . $uxi;
        			$filename = "uploads/contacts/" . $photo;
        			imagejpeg($tmp, $filename, 100);
        			imagedestroy($src);
        			imagedestroy($tmp);
					
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'name' => $this->input->post('name'),
		'company' => $this->input->post('company'),
		'job_title' => $this->input->post('job_title'),
		'contact_group' => $this->input->post('contact_group'),
		'email' => $this->input->post('email'),
		'phone1' => $this->input->post('phone1'),
		'label1' => $this->input->post('label1'),
		'phone2' => $this->input->post('phone2'),
		'label2' => $this->input->post('label2'),
		'phone3' => $this->input->post('phone3'),
		'label3' => $this->input->post('label3'),
		'phone4' => $this->input->post('phone4'),
		'label4' => $this->input->post('label4'),
		'phone5' => $this->input->post('phone5'),
		'label5' => $this->input->post('label5'),
		'phone6' => $this->input->post('phone6'),
		'label6' => $this->input->post('label6'),
		'share_public' => $this->input->post('share_public'),
		'address' => $this->input->post('address'),
		'note' => $this->input->post('note'),
		'user_id' => $this->input->post('user_id'),
		'photo' => $photo,
		'created' => date('Y-m-d H:i:s'),
		'time' => time(),
		);
		
		
		$result = $this->Contact_model->add($data);
		
		if ($result == TRUE) {
			$Return['result'] = 'Contact Added';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function add_group() {
	    if($this->input->post('add_type')=='group') {
	        $Return = array('result'=>'', 'error'=>'');
	        if(!empty($this->input->post('name')))
	        {
	            $data = array(
        		'name' => $this->input->post('name'),
        		'user_id' => $this->session->userdata('user_id'),
        		'created_at' => date('Y-m-d H:i:s'),
        		);
        		
        		$result = $this->Contact_model->add_group($data);
		
        		if ($result == TRUE) {
        			$Return['result'] = 'Group Added';
        		} else {
        			$Return['error'] = $this->lang->line('xin_error_msg');
        		}
        		$this->output($Return);
        		exit;
	        }
	    }
	}
	
	public function group_list() {

		$data['title'] = $this->Xin_model->site_title();
		
		$user_id = $this->session->userdata('user_id');
		
		$data = array();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("contact/get_group", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	 }
	
	// Validate and update info in database
	public function update() {
		if($this->input->post('edit_type')=='contact') {
		$id = $this->uri->segment(3);
		// Check validation for user input
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phone1', 'Phone', 'trim|required|xss_clean');
				
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		$name = $this->input->post('name');
		$phone = $this->input->post('phone1');
			
		/* Server side PHP input validation */
		if($name==='') {
			$Return['error'] = $this->lang->line('xin_error_name_field');
		} else if( $phone==='') {
			$Return['error'] = 'Atleast one phone number is required';
		} 
		else if($_FILES['photo']['size'] == 0) {
		    
		    if(empty($this->input->post('share_public')))
    		{
    		    $share_public = 0;
    		}
    		else
    		{
    		    $share_public = 1;
    		}
			 $photo = '';
			 $no_photo_data = array(
    		'name' => $this->input->post('name'),
    		'company' => $this->input->post('company'),
    		'job_title' => $this->input->post('job_title'),
    		'contact_group' => $this->input->post('contact_group'),
    		'email' => $this->input->post('email'),
    		'phone1' => $this->input->post('phone1'),
    		'label1' => $this->input->post('label1'),
    		'phone2' => $this->input->post('phone2'),
    		'label2' => $this->input->post('label2'),
    		'phone3' => $this->input->post('phone3'),
    		'label3' => $this->input->post('label3'),
    		'phone4' => $this->input->post('phone4'),
    		'label4' => $this->input->post('label4'),
    		'phone5' => $this->input->post('phone5'),
    		'label5' => $this->input->post('label5'),
    		'phone6' => $this->input->post('phone6'),
    		'label6' => $this->input->post('label6'),
    		'share_public' => $share_public,
    		'address' => $this->input->post('address'),
    		'note' => $this->input->post('note'),
			);
			 $result = $this->Contact_model->update_record_no_photo($no_photo_data,$id);
		} else {
			if(is_uploaded_file($_FILES['photo']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['logo']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					
					$uploadedfile = $_FILES['photo']['tmp_name'];
                    
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
        			$newwidth = 200;
        			$newheight = ($height / $width) * $newwidth;
        			$tmp = imagecreatetruecolor($newwidth, $newheight);
        			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        			$uxi = rand(111, 999).round(microtime(true)).'.'.$ext;
                    $photo = "contact_" . $uxi;
        			$filename = "uploads/contacts/" . $photo;
        			imagejpeg($tmp, $filename, 100);
        			imagedestroy($src);
        			imagedestroy($tmp);
					
					// update record > model
					
					$result2     = $this->Contact_model->read_contact_information($id);
            		if(!empty($result2[0]->photo))
            		{
                		$delete_file = "uploads/contacts/".$result2[0]->photo;
                		unlink($delete_file);
            		}
            		
            		if(empty($this->input->post('share_public')))
            		{
            		    $share_public = 0;
            		}
            		else
            		{
            		    $share_public = 1;
            		}
            		
            		$data = array(
            		'name' => $this->input->post('name'),
            		'company' => $this->input->post('company'),
            		'job_title' => $this->input->post('job_title'),
            		'contact_group' => $this->input->post('contact_group'),
            		'email' => $this->input->post('email'),
            		'phone1' => $this->input->post('phone1'),
            		'label1' => $this->input->post('label1'),
            		'phone2' => $this->input->post('phone2'),
            		'label2' => $this->input->post('label2'),
            		'phone3' => $this->input->post('phone3'),
            		'label3' => $this->input->post('label3'),
            		'phone4' => $this->input->post('phone4'),
            		'label4' => $this->input->post('label4'),
            		'phone5' => $this->input->post('phone5'),
            		'label5' => $this->input->post('label5'),
            		'phone6' => $this->input->post('phone6'),
            		'label6' => $this->input->post('label6'),
            		'share_public' => $share_public,
            		'address' => $this->input->post('address'),
            		'note' => $this->input->post('note'),
            		'photo' => $photo,
            		);
					
					$result = $this->Contact_model->update_record($data,$id);
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		
		if ($result == TRUE) {
			$Return['result'] = 'Contact Updated';
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
		
		$result2     = $this->Contact_model->read_contact_information($id);
		if(!empty($result2[0]->photo))
		{
    		$delete_file = "uploads/contacts/".$result2[0]->photo;
    		unlink($delete_file);
		}
		
		$result = $this->Contact_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Contact deleted';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		
    	$this->output($Return);
		
	}
}
