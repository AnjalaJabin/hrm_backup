<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class job_post_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 	
	// get jobs
	public function get_jobs() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_jobs');
		$this->db->where($condition);
		return $query = $this->db->get();
	}
			
	// get all job candidates
	public function get_jobs_candidates() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_job_applications');
		$this->db->where($condition);
		return $query = $this->db->get();
	}
	
	// get all employee applied jobs
	public function get_employee_jobs_applied($id) {
	    $root_id   = $_SESSION['root_id'];
		return $query = $this->db->query("SELECT * from xin_job_applications where user_id = '".$id."' and root_id='".$root_id."' ");
	}
	 // read job info
	 public function read_job_information($id) { 
	    $root_id   = $_SESSION['root_id'];
		$condition = "job_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_jobs');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// get all jobtype jobs
	 public function read_all_jobs_by_type() {
	    $root_id   = $_SESSION['root_id'];
		$condition = "job_type !='' and root_id = '".$root_id."' group by job_type";
		$this->db->select('*');
		$this->db->from('xin_jobs');
		$this->db->where($condition);
		$this->db->limit(1000);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// get all job types
	public function all_job_types() {
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_job_type where root_id = '".$root_id."' ");
  	  return $query->result();
	}
	
	// get all jobs by designation
	 public function read_all_jobs_by_designation() {
	    $root_id   = $_SESSION['root_id'];
		$condition = "designation_id !='' and root_id = '".$root_id."' group by designation_id";
		$this->db->select('*');
		$this->db->from('xin_jobs');
		$this->db->where($condition);
		$this->db->limit(1000);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// check apply jobs > remove duplicate
	 public function check_apply_job($job_id,$user_id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "job_id='".$job_id."' and user_id='".$user_id."' and root_id = '".$root_id."' ";
		$this->db->select('*');
		$this->db->from('xin_job_applications');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
		
		// $query->result();
	}
	
	// get all interview jobs > 
	public function all_interview_jobs()
	{
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT j.*, jap.* FROM xin_jobs as j, xin_job_applications as jap where j.job_id = jap.job_id and j.root_id= '".$root_id."' group by j.job_id");
  	  return $query->result();
	}
	
	// read job type info
	 public function read_job_type_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "job_type_id ='".$id."' and root_id = '".$root_id."' ";
		$this->db->select('*');
		$this->db->from('xin_job_type');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// get all interviews
	public function all_interviews() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_job_interviews');
		$this->db->where($condition);
		return $query = $this->db->get();
	}
	
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_jobs', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_resume($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_job_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// get all job > frontend
	public function all_jobs() {
	  $query = $this->db->query("SELECT * from xin_jobs");
  	  return $query->result();
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('job_id', $id);
		$this->db->delete('xin_jobs');
		
	}
	
	// Function to Delete selected record from table
	public function delete_application_record($id){
		$this->db->where('application_id', $id);
		$this->db->delete('xin_job_applications');
		
	}
	
	// Function to Delete selected record from table
	public function delete_interview_record($id){
		$this->db->where('job_interview_id', $id);
		$this->db->delete('xin_job_interviews');
		
	}
	
	// get department > designations
	public function ajax_job_user_information($id) {
	
		$condition = "job_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_job_applications');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_interview($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_job_interviews', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('job_id', $id);
		if( $this->db->update('xin_jobs',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>