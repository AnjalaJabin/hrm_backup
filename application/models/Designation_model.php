<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
class designation_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_designations()
	{
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where($condition);
		return $this->db->get();
	}
	 
	 public function read_designation_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "designation_id =" . "'" . $id . "' and root_id = '".$root_id."'";
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
	
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_designations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "designation_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_designations');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$root_id   = $_SESSION['root_id'];
		$condition = "designation_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_designations',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all designations
	public function all_designations()
	{
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_designations where root_id = '".$root_id."' ");
  	  return $query->result();
	}
	
	// get department > designations
	public function ajax_designation_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "department_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
    public function check_designation($dept,$name) {
	    $root_id   = $_SESSION['root_id'];
		$this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where('department_id',$dept);
		$this->db->where('designation_name',$name);
		$this->db->where('root_id',$root_id);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
    public function check_designation_name($name) {
	    $root_id   = $_SESSION['root_id'];
		$this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where('designation_name',$name);
		$this->db->where('root_id',$root_id);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}
?>