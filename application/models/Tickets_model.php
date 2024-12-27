<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tickets_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_tickets() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_support_tickets');
        $this->db->where($condition);
        return $query = $this->db->get();
    }
    public function get_flight_tickets() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('flight_tickets');
        $this->db->where($condition)
        ->order_by('id','desc');
        return $query = $this->db->get();
    }
    public function get_flight_tickets_employee($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('flight_tickets');
        $this->db->where($condition);
        $this->db->where('employee_id',$employee_id);
        return $query = $this->db->get();
    }

    public function read_ticket_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_support_tickets');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function read_compoff_info($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('comp_off');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function read_encashment_info($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('gratuity_encashment_details');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function read_flight_ticket_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('flight_tickets');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function read_asset_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('employee_asset');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
 public function read_request_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('employee_requests');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }


    // Function to add record in table
    public function add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_support_tickets', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_flight($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('flight_tickets', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_asset($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('employee_asset', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_request($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('employee_requests', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_compoff($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('comp_off', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_gratuity($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('gratuity_encashment_details', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_comment($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_tickets_comments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_new_attachment($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_tickets_attachment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_support_tickets');

    }
    public function delete_flight_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('flight_tickets');

    }
    public function delete_asset_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('employee_asset');
    }
    public function delete_request_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('employee_requests');
    }
    public function delete_gratuity_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('gratuity_encashment_details');

    }

    public function delete_compoff_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('comp_off');

    }

    // Function to Delete selected record from table
    public function delete_comment_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "comment_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_tickets_comments');

    }

    // Function to Delete selected record from table
    public function delete_attachment_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_attachment_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_tickets_attachment');

    }

    public function get_employee_tickets($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_support_tickets where employee_id = '".$id."' and root_id = '".$root_id."' ");
    }

    // Function to update record in table
    public function update_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_support_tickets',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_asset($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('employee_asset',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_request($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('employee_requests',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_compoff_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('comp_off',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_gratuity_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('gratuity_encashment_details',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_flight_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('flight_tickets',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function assign_ticket_user($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_support_tickets',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_status($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_support_tickets',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_note($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_support_tickets',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get comments
    public function get_comments($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."' ";

        $this->db->select('*');
        $this->db->from('xin_tickets_comments');
        $this->db->where($condition);
        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }
    public function get_gratuity_details_employees() {
        $root_id   = $_SESSION['root_id'];
        $condition = "gratuity_encashment_details.root_id = '".$root_id."' ";

        $this->db->select('gratuity_encashment_details.*,xin_departments.department_name')->order_by('gratuity_encashment_details.created_date','DESC');
        $this->db->from('gratuity_encashment_details');
        $this->db->where($condition);
        $this->db->join('xin_employees','xin_employees.user_id=gratuity_encashment_details.employee_id','left');
        $this->db->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }
    public function get_compoff_details_employees() {
        $root_id   = $_SESSION['root_id'];
        $condition = "comp_off.root_id = '".$root_id."' ";

        $this->db->select('comp_off.*,xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.last_name,xin_departments.department_name');
        $this->db->from('comp_off');
        $this->db->join('xin_employees','xin_employees.user_id=comp_off.employee_id','left');
        $this->db->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->where($condition);
        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }
    public function get_total_compoff_employees() {
        $root_id   = $_SESSION['root_id'];

        $this->db->select('comp_off.*,sum(comp_off.leave_no) as total_off,xin_employees.user_id,xin_employees.employee_id  as emp_id,xin_employees.first_name,xin_employees.last_name,xin_departments.department_name')
            ->from('comp_off')
            ->join('xin_employees','xin_employees.user_id=comp_off.employee_id','left')
            ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left')
            ->where('comp_off.root_id',$root_id)
            ->where('comp_off.status',1)
            ->group_by('comp_off.employee_id')
            ->limit(100);
        $query = $this->db->get();

        return $query;
    }
    public function read_compoff_info_employee($employee_id) {
        $root_id = $_SESSION['root_id'];

        $this->db->select('comp_off.*, xin_employees.user_id, xin_employees.employee_id as emp_id, xin_employees.first_name, xin_employees.last_name, xin_departments.department_name')
            ->from('comp_off')
            ->join('xin_employees', 'xin_employees.user_id = comp_off.employee_id', 'left')
            ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left')
            ->where('comp_off.root_id', $root_id)
            ->where('comp_off.employee_id', $employee_id)
            ->limit(100);
        $query = $this->db->get();

        return $query;
    }
    public function  get_employee_assets() {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_asset.root_id = '".$root_id."' ";

        $this->db->select('employee_asset.*,xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.last_name,xin_departments.department_name');
        $this->db->from('employee_asset');
        $this->db->join('xin_employees','xin_employees.user_id=employee_asset.employee_id','left')
        ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->where($condition);
        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }

    public function  get_employee_requests($employee_id=null) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_requests.root_id = '".$root_id."' ";

        $this->db->select('employee_requests.*,xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.last_name,xin_departments.department_name');
        $this->db->from('employee_requests');
        $this->db->join('xin_employees','xin_employees.user_id=employee_requests.employee_id','left')
        ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->where($condition);
        if($employee_id){
            $this->db->where('employee_requests.employee_id',$employee_id);
        }
        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }


    public function get_gratuity_employees() {
        $root_id   = $_SESSION['root_id'];
        $condition = "xin_employees.root_id = '".$root_id."' ";
        $this->db->order_by("xin_employees.user_id","desc");

        $this->db->select('xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.gratuity_eligibilty,xin_employees.ticket_eligibilty,xin_employees.last_name,xin_employees.date_of_joining,xin_departments.department_name');
        $this->db->from('xin_employees');
        $this->db->where('xin_employees.is_active',1);
        $this->db->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
//        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }

    // get attachments
    public function get_attachments($id) {

        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_tickets_attachment');
        $this->db->where($condition);
        $this->db->limit(100);
        $query = $this->db->get();

        return $query;
    }

    // get all ticket users
    public function read_ticket_users_information($id) {

        $root_id   = $_SESSION['root_id'];
        $condition = "ticket_id =" . "'" . $id . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_support_tickets');
        $this->db->where($condition);
        $this->db->limit(100);
        $query = $this->db->get();

        return $query->result();
    }
}
?>