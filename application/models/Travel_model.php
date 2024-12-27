<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class travel_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_travel() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_employee_travels');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	public function get_employee_travel($id) {
	    $root_id   = $_SESSION['root_id'];
	 	return $query = $this->db->query("SELECT * from xin_employee_travels where employee_id = '".$id."' and root_id='".$root_id."' ");
	}
	 
	 public function read_travel_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "travel_id =" . "'" . $id . "' and root_id='".$root_id."' ";
		$this->db->select('*');
		$this->db->from('xin_employee_travels');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// get all travel arrangement types
	public function travel_arrangement_types()
	{
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_travel_arrangement_type where root_id='".$root_id."' ");
  	  return $query->result();
	}
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_employee_travels', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "travel_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_employee_travels');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "travel_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_employee_travels',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>