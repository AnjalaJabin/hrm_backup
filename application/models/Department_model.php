<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class department_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_departments()
	{
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_departments');
		$this->db->where($condition);
		return $this->db->get();
	}
	 
	 public function read_department_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "department_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_departments');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_departments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "department_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_departments');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "department_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_departments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
    public function check_dep_exist($name){
        $root_id   = $_SESSION['root_id'];
        $this->db->select('*');
        $this->db->from('xin_departments');
        $this->db->where('department_name',$name);
        $this->db->where('root_id',$root_id);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
	
	// get all departments
	public function all_departments()
	{
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_departments where root_id = '".$root_id."'");
  	  return $query->result();
	}
	
	public function ajax_department_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "location_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_departments');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}
?>