<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class contact_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
  
	public function get_contacts($user_id) {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "' and (user_id='".$user_id."' OR share_public='1') order by id desc";
        $this->db->select('*');
		$this->db->from('xin_contacts');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	
	// contact groups
	public function get_contact_group($user_id) {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "' and user_id = '".$user_id."'";
        $this->db->select('*');
		$this->db->from('xin_contact_group');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function check_contact_exist($name,$phone,$email) {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id ='".$root_id."' and name ='".$name."' and (phone1='".$phone."' or email='".$email."')";
        $this->db->select('*');
		$this->db->from('xin_contacts');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function get_all_contacts() {
	  $root_id   = $_SESSION['root_id'];
	  $condition = "root_id =" . "'" . $root_id . "'";
      $this->db->select('*');
	  $this->db->from('xin_contacts');
	  $this->db->where($condition);
	  $query = $this->db->get();
	  return $query->result();
	}
	 
	public function read_contact_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->select('*');
		$this->db->from('xin_contacts');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_contact_group_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->select('*');
		$this->db->from('xin_contact_group');
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
		$this->db->insert('xin_contacts', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function multi_add($data){
		$this->db->query($data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_group($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_contact_group', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		$this->db->delete('xin_contacts');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		if( $this->db->update('xin_contacts',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_photo($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		if( $this->db->update('xin_contacts',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>