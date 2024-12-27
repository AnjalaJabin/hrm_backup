<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class expense_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
 	// get all expenses
	public function get_expenses() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_expenses');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	// get total number of expenses
	public function get_total_expenses() {
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT SUM(amount) as exp_amount FROM xin_expenses where root_id = '".$root_id."' ");
  	  return $query->result();
	}
	 
	 public function read_expense_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "expense_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_expenses');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	public function read_expense_type_information($id) {
	
		$root_id   = $_SESSION['root_id'];
		$condition = "expense_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_expense_type');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// get all expense types
	public function all_expense_types()
	{
	  $root_id   = $_SESSION['root_id'];
	  $query = $this->db->query("SELECT * from xin_expense_type where root_id = '".$root_id."'");
  	  return $query->result();
	}
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_expenses', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "expense_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		$this->db->delete('xin_expenses');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$root_id   = $_SESSION['root_id'];
		$condition = "expense_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_expenses',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id){
		$root_id   = $_SESSION['root_id'];
		$condition = "expense_id =" . "'" . $id . "' and root_id = '".$root_id."'";
		$this->db->where($condition);
		if( $this->db->update('xin_expenses',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>