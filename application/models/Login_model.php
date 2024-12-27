<?php
	
	class login_model extends CI_Model
	{
     function __construct()
     {
          // Call the Model constructor
          parent::__construct();
     }

	// Read data using username and password
	public function login($data) {
	
		$username = $this->db->escape($data['username']);
	    $sec_pass = $this->db->escape($data['sec_pass']);
	    $root_id = $this->db->escape($data['root_id']);
		$condition = "(username =".$username." OR email=".$username.") AND sec_pass = ".$sec_pass." and root_id = ".$root_id." and is_active='1' and deleted=0";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		$return_val = $query->result();
		foreach($return_val as $return_vals){
          $root_id = $return_vals->root_id;
          $user_id = $return_vals->user_id;
		}
		
		if(isset($user_id))
		{
		   $_SESSION['root_id'] = $root_id;
		   $_SESSION['user_id'] = $user_id;
		$MainDb = $this->load->database('maindb', TRUE);
		$queryz = $MainDb->query("UPDATE `root_accounts` SET `status`='1' WHERE id='".$root_id."'");
		}
	
		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}
	

	// Read data from database to show data in admin page
	public function read_user_information($username) {
	    $root_id = $_SESSION['root_id'];
		$condition = "username =" . "'" . $username . "' and root_id='".$root_id."'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// Read data from database to show data in admin page
	public function read_user_information_login($username,$sec_pass) {
	    $root_id = $_SESSION['root_id'];
		$condition = "(username ='".$username."' OR email='".$username."') AND sec_pass = '".$sec_pass."'  and root_id='".$root_id."' and deleted=0";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

}
?>