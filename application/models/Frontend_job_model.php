<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
class frontend_job_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    
    // get jobs
	public function get_jobs() {
        $this->db->select('*');
		$this->db->from('xin_jobs');
		return $query = $this->db->get();
	}
	
	// get all job > frontend
	public function all_jobs() {
	  $query = $this->db->query("SELECT * from xin_jobs");
  	  return $query->result();
	}
	
	// get all designations
	public function all_designations()
	{
	  $query = $this->db->query("SELECT * from xin_designations");
  	  return $query->result();
	}
	
	// get all job types
	public function all_job_types() {
	  $query = $this->db->query("SELECT * from xin_job_type");
  	  return $query->result();
	}
	
	// get all jobs by designation
	 public function read_all_jobs_by_designation() {
		$condition = "designation_id !='' group by designation_id";
		$this->db->select('*');
		$this->db->from('xin_jobs');
		$this->db->where($condition);
		$this->db->limit(1000);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// read job type info
	 public function read_job_type_information($id) {
		$condition = "job_type_id ='".$id."'";
		$this->db->select('*');
		$this->db->from('xin_job_type');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// check apply jobs > remove duplicate
	 public function check_apply_job($job_id,$email) {
		$condition = "job_id='".$job_id."' and email='".$email."'";
		$this->db->select('*');
		$this->db->from('xin_job_applications');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
		
		// $query->result();
	}
	
	// Function to add record in table
	public function add_resume($data){
		$this->db->insert('xin_job_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// read job info
	 public function read_job_information($id) { 
		$condition = "job_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_jobs');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	public function read_designation_information($id) {
		$condition = "designation_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		$a=[];
		$a[] = (object) array('designation_name' => 'admin');
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return $a;
		}

	}
	
	public function read_department_information($id) {
		$condition = "department_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_departments');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
}
?>