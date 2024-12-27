<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller
{

   /*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	public function __construct()
     {
          parent::__construct();
          $this->load->library('session');
          $this->load->helper('form');
          $this->load->helper('url');
          $this->load->helper('html');
          $this->load->database();
          $this->load->library('form_validation');
          //load the login model
          $this->load->model('Login_model');
          $this->load->model('Xin_model');
		  $this->load->model('Employees_model');
     }
	 
	public function login() {
	    
	    $this->main_db = $this->load->database('maindb', TRUE);
    	
    	$subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
        $subdomain_name; // Print the sub domain
        $subdomain_name = 'testcor';
        
        $sql="SELECT * FROM root_accounts WHERE corbuz_name='$subdomain_name'";
        $result=$this->main_db->query($sql);
        $main_db_row = $result->result();
        $main_root_id = $main_db_row[0]->id;
	    
		$this->form_validation->set_rules('iusername', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('ipassword', 'Password', 'trim|required|xss_clean');
		$Return = array('result'=>'', 'error'=>'');
		
		$username = trim($this->input->post('iusername',TRUE));
		$password = trim($this->input->post('ipassword',TRUE));
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		
		/* Server side PHP input validation */
		if($username==='') {
			$Return['error'] = "Username field is required.";
		} elseif($password===''){
			$Return['error'] = "Password field is required.";
		}
		if($Return['error']!=''){
			$this->output($Return);
		}
		
		$query = $this->Xin_model->read_user_info_byemail($username,$main_root_id);
    	$q_result = $query->result();
    	if($q_result){
    		$salt = $q_result[0]->pslt;
    		
    		$pw_hash = sha1($salt.$password);
    		
    		$data = array(
    			'username' => $username,
    			'sec_pass' => $pw_hash,
    			'root_id' => $main_root_id,
    			);
    		$result = $this->Login_model->login($data);	
		}else{
		    $result = 0;
		}
		
		function randomPassword() {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 200; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass); //turn the array into a string
        }
		
		if ($result == TRUE) {
			
			    $device_id = randomPassword();
				$result = $this->Login_model->read_user_information_login($username,$pw_hash);
				$session_data = array(
				'user_id' => $result[0]->user_id,
				'username' => $result[0]->username,
				'email' => $result[0]->email,
				'root_id' => $result[0]->root_id,
				'device_id' => $device_id,
				);
				
				
				// Add user data in session
				$this->session->set_userdata('username', $session_data);
				$Return['result'] = 'Logged In Successfully.';
				
				
				/////////////////////////////////////////////////////////////////////////
       
               
        
                function getOS() {
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    $os_platform  = "Unknown OS Platform";
                
                    $os_array     = array(
                                          '/windows nt 10/i'      =>  'Windows 10',
                                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                                          '/windows nt 6.2/i'     =>  'Windows 8',
                                          '/windows nt 6.1/i'     =>  'Windows 7',
                                          '/windows nt 6.0/i'     =>  'Windows Vista',
                                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                                          '/windows nt 5.1/i'     =>  'Windows XP',
                                          '/windows xp/i'         =>  'Windows XP',
                                          '/windows nt 5.0/i'     =>  'Windows 2000',
                                          '/windows me/i'         =>  'Windows ME',
                                          '/win98/i'              =>  'Windows 98',
                                          '/win95/i'              =>  'Windows 95',
                                          '/win16/i'              =>  'Windows 3.11',
                                          '/macintosh|mac os x/i' =>  'Mac OS X',
                                          '/mac_powerpc/i'        =>  'Mac OS 9',
                                          '/linux/i'              =>  'Linux',
                                          '/ubuntu/i'             =>  'Ubuntu',
                                          '/iphone/i'             =>  'iPhone',
                                          '/ipod/i'               =>  'iPod',
                                          '/ipad/i'               =>  'iPad',
                                          '/android/i'            =>  'Android',
                                          '/blackberry/i'         =>  'BlackBerry',
                                          '/webos/i'              =>  'Mobile'
                                    );
                
                    foreach ($os_array as $regex => $value)
                        if (preg_match($regex, $user_agent))
                            $os_platform = $value;
                
                    return $os_platform;
                }
                
                function getBrowser() {
                
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                
                    $browser        = "Unknown Browser";
                
                    $browser_array = array(
                                            '/msie/i'      => 'Internet Explorer',
                                            '/firefox/i'   => 'Firefox',
                                            '/safari/i'    => 'Safari',
                                            '/chrome/i'    => 'Google Chrome',
                                            '/edge/i'      => 'Edge',
                                            '/opera/i'     => 'Opera',
                                            '/netscape/i'  => 'Netscape',
                                            '/maxthon/i'   => 'Maxthon',
                                            '/konqueror/i' => 'Konqueror',
                                            '/mobile/i'    => 'Mobile Browser'
                                     );
                
                    foreach ($browser_array as $regex => $value)
                        if (preg_match($regex, $user_agent))
                            $browser = $value;
                
                    return $browser;
                }
                
                
                $user_os        = getOS();
                $user_browser   = getBrowser();
                $user_ip        = $this->input->ip_address();
                
                $active_device_data = array(
                    'user_id'   =>  $result[0]->user_id,
                    'root_id'   =>  $result[0]->root_id,
                    'ip'        =>  $user_ip,
                    'date'      =>  date('Y-m-d H:i:s'),
                    'time'      =>  time(),
                    'browser'   =>  $user_browser,
                    'os'        =>  $user_os,
                    'device_id' =>  $device_id
                    );
                    
                //$this->Employees_model->add_user_device($active_device_data);
               
               /////////////////////////////////////////////////////////////////////////
				
				
				// update last login info
				$ipaddress = $this->input->ip_address();
				  
				 $last_data = array(
					'last_login_date' => date('d-m-Y H:i:s'),
					'last_login_ip' => $ipaddress,
					'is_logged_in' => '1'
				); 
				
				
				$id = $result[0]->user_id; // user id 
				
				$cookie_name  = "myhrmusername";
                $cookie_value = $session_data;
                
                setcookie($cookie_name, serialize($cookie_value), time() + (86400 * 30), "/"); // 86400 = 1 day
				  
				$this->Employees_model->update_record($last_data, $id);
				$this->output($Return);
				
			} else if(isset($q_result[0]->is_active) && $q_result[0]->is_active==0){
			    $Return['error'] = "Your account is deactivated by your admin.";
			}else {
				$Return['error'] = "Invalid Login Credential.";
				/*Return*/
				$this->output($Return);
			}
    }
} 
?>