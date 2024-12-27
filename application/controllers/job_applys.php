<?php  
defined('BASEPATH') OR exit('No direct script access allowed');  
  
class job_applys extends CI_Controller {  
      
    public function index()  
    {  
        $this->load->view('job_post/job_apply');  
    }  
}  
?>  