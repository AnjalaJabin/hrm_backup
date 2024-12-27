<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Customers_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_customers()
	{
	  return $this->db->get("xin_customers");
	}
	 
	 public function read_customer_information($id) {
	
		$condition = "customer_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_customers');
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
		$this->db->insert('xin_customers', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('customer_id', $id);
		$this->db->delete('xin_customers');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('customer_id', $id);
		if( $this->db->update('xin_customers',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>