<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class document_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
  
	public function get_documents() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_documents');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	public function get_all_files($user_id) {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "document_id='' and root_id='".$root_id."' and uid='".$user_id."' order by id DESC";
        $this->db->select('*');
		$this->db->from('xin_document_files');
		$this->db->where($condition);
		$query = $this->db->get();
	    return $query->result();
	}
	
	public function get_document_files($id) {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "document_id='".$id."' and root_id='".$root_id."' order by id DESC";
        $this->db->select('*');
		$this->db->from('xin_document_files');
		$this->db->where($condition);
		$query = $this->db->get();
	    return $query->result();
	}
	
	public function get_all_files_count($user_id) {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "document_id='' and root_id='".$root_id."' and uid='".$user_id."' order by id DESC";
        $this->db->select('*');
		$this->db->from('xin_document_files');
		$this->db->where($condition);
		$query = $this->db->get();
	    return $query->num_rows();
	}
	
	public function get_total_documents() {
	    $root_id   = $_SESSION['root_id'];
	    $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
		$this->db->from('xin_documents');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function get_all_documents() {
	  $root_id   = $_SESSION['root_id'];
	  $condition = "root_id =" . "'" . $root_id . "'";
      $this->db->select('*');
	  $this->db->from('xin_documents');
	  $query = $this->db->get();
	  return $query->result();
	}
	 
	public function read_document_information($id) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "document_id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->select('*');
		$this->db->from('xin_documents');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_document_title_list($keyword) {
	    $root_id   = $_SESSION['root_id'];
		$condition = "root_id =".$root_id." and title like '%".$keyword."%' group by title";
		$this->db->select('*');
		$this->db->from('xin_documents');
		$this->db->where($condition);
		return $this->db->get();
	}
	
	// Function to add record in table
	public function add($data){
	    $root_id   = $_SESSION['root_id'];
	    $data['root_id'] = $root_id;
		$this->db->insert('xin_documents', $data);
		$document_id = $this->db->insert_id();
		
		$this->db->query("update xin_document_files set document_id='".$document_id."' where document_id='' and root_id='".$root_id."' and uid='".$data['added_by']."'");
		
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "document_id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		$this->db->delete('xin_documents');
		
	}
	
	// Function to Delete selected record from table
	public function delete_all_file($id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "document_id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		$this->db->delete('xin_document_files');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "document_id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		if( $this->db->update('xin_documents',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id){
	    $root_id   = $_SESSION['root_id'];
		$condition = "document_id =" . "'" . $id . "' and root_id =" . "'" . $root_id . "'";
		$this->db->where($condition);
		if( $this->db->update('xin_documents',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>