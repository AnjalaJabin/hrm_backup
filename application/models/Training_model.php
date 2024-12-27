<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class training_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	// get training
	public function get_training() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_training');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	// get training type
	public function get_training_type()
	{
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_training_types');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	// all training_types
	public function all_training_types() {
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_training_types where root_id='".$root_id."' ");
  	  return $query->result();
	}
	 
	 public function read_training_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_training');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	// get training type by id
	public function read_training_type_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_training_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_training', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_type($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_training_types', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_training');
		
	}
	
	// Function to Delete selected record from table
	public function delete_type_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_training_types');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_training',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_status($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_training',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_type_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "training_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_training_types',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>