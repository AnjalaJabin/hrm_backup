<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class termination_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_terminations()
	{
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_employee_terminations');
		$this->db->where($condition);
		return $query = $this->db->get();
	}
	 
	 public function read_termination_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "termination_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_employee_terminations');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_termination_type_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "termination_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_termination_type');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function all_termination_types() {
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_termination_type where root_id = '".$root_id."' ");
  	  return $query->result();
	}
	
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_employee_terminations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "termination_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_employee_terminations');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "termination_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_employee_terminations',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>