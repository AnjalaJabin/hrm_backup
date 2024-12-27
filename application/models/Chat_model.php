<?php
	
	class Chat_model extends CI_Model
	{
     function __construct()
     {
          // Call the Model constructor
          parent::__construct();
     }
	// get all messages
	public function get_messages()
	{
	 $query = $this->db->query("SELECT * FROM xin_chat_messages ORDER BY message_id asc");
	 // $query = $this->db->query("SELECT * from xin_chat_messages where ((from_id = '".$fid."' and to_id = '".$tid."') OR (from_id = '".$tid."' and to_id = '".$fid."')) order by message_id DESC");
  	  return $query->result();
	}
	
	public function timeAgo($timestamp){
		//$time_now = mktime(date('h')+0,date('i')+30,date('s'));
		$datetime1=new DateTime("now");
		$datetime2=date_create($timestamp);
		$diff=date_diff($datetime1, $datetime2);
		$timemsg='';
		if($diff->y > 0){
			$timemsg = $diff->y .' year'. ($diff->y > 1?"'s":'');
	
		}
		else if($diff->m > 0){
			$timemsg = $diff->m . ' month'. ($diff->m > 1?"'s":'');
		}
		else if($diff->d > 0){
			$timemsg = $diff->d .' day'. ($diff->d > 1?"'s":'');
		}
		else if($diff->h > 0){
			$timemsg = $diff->h .' hour'.($diff->h > 1 ? "'s":'');
		}
		else if($diff->i > 0){
			$timemsg = $diff->i .' minute'. ($diff->i > 1?"'s":'');
		}
		else if($diff->s > 0){
			$timemsg = $diff->s .' second'. ($diff->s > 1?"'s":'');
		}
	
		$timemsg = $timemsg.' ago';
		return $timemsg;
	}
	
	// Function to add record in table
	public function add_chat($data){
		$this->db->insert('xin_chat_messages', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// get chat list
	public function read_chat_from_info($from_id) {
	
		$condition = "from_id =" . "'" . $from_id . "'";
		$this->db->select('*');
		$this->db->from('xin_chat_messages');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// get chat list
	public function read_chat_to_info($to_id) {
	
		$condition = "to_id =" . "'" . $to_id . "'";
		$this->db->select('*');
		$this->db->from('xin_chat_messages');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}

}
?>