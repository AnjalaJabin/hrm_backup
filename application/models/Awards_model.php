<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class awards_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_awards()
	{
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_awards');
		$this->db->where($condition);
		return $this->db->get();
	}
	 
	 public function read_award_type_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "award_type_id =" . "'" . $id . "' and root_id='".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_award_type');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function all_award_types()
	{
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_award_type where root_id='".$root_id."'");
  	  return $query->result();
	}
	
	public function get_employee_awards($id) {
	    $root_id   = $_SESSION['root_id'];
	 	return $query = $this->db->query("SELECT * from xin_awards where employee_id = '".$id."' and root_id='".$root_id."'");
	}
	
	public function read_award_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "award_id =" . "'" . $id . "' and root_id='".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_awards');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
		$data['root_id'] = $root_id;
		$this->db->insert('xin_awards', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "award_id =" . "'" . $id . "' and root_id='".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_awards');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "award_id =" . "'" . $id . "' and root_id='".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_awards',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>