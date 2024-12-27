<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class leaves_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_leaves()
	{
	    
        $this->db->select('*');
		$this->db->from('xin_leaves');
		return $this->db->get();
	}
	 public function read_leave_type_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_leaves');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	public function add($data){
	   $this->db->insert('xin_leaves', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}