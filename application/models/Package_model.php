<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
class package_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_packages()
	{
	    $this->db = $this->load->database('maindb', TRUE);
	    $condition = "active =" . "'1'";
        $this->db->select('*');
		$this->db->from('packages');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function read_package_information($id) {
	    $this->db = $this->load->database('maindb', TRUE);
		$condition = "id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('packages');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	
	
	public function read_payment_information($id) {
	    $this->db = $this->load->database('maindb', TRUE);
	    $root_id   = $_SESSION['root_id'];
		$condition = "order_number =" . "'" . $id . "' and root_id='".$root_id."'";
		$this->db->select('*');
		$this->db->from('payment_data');
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
	public function add_payment_data($data){
	    $this->db = $this->load->database('maindb', TRUE);
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('payment_data', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function add_re_payment_data($data){
	    $this->db = $this->load->database('maindb', TRUE);
		$this->db->insert('payment_data', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	// Function to add record in table
	public function add_custom_price($data){
	    $this->db = $this->load->database('maindb', TRUE);
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('custom_price_request', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	} 
	
	
	//All Payment List
	public function get_payments()
	{
	    $this->db = $this->load->database('maindb', TRUE);
	    $root_id   = $_SESSION['root_id'];
	    $condition = "status ='1' and root_id='".$root_id."' ORDER BY `id` DESC";
        $this->db->select('*');
		$this->db->from('payment_data');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query;
	}

	
	// Function to update record in table
	public function update_record($data){
	    $this->db = $this->load->database('maindb', TRUE);
		$root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $root_id."'";
		$this->db->where($condition);
		if( $this->db->update('root_accounts',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_re_root_record($data){
	    $this->db = $this->load->database('maindb', TRUE);
		$root_id   = $data['root_id'];
		$condition = "id =" . "'" . $root_id."'";
		$this->db->where($condition);
		if( $this->db->update('root_accounts',$data)) {
			return true;
		} else {
			return false;
		}		
	}

}
?>