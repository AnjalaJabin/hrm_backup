<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class employees_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get all employes
    public function get_employees() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' and deleted=0 and is_active=1";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        return $this->db->get();
    }
   public function get_employees_d($dep=null,$p_date=null) {

        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' and deleted=0";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        if($dep&&$dep!='undefined'){
            $this->db->where('department_id',$dep);
        }
        if (!empty($p_date)) {
            $p_date_y_m_d = date('Y-m-d', strtotime($p_date));
            $p_date_d_m_y = date('d-m-Y', strtotime($p_date));
            $lastp_date_y_m_d = date('Y-m-t', strtotime($p_date));

            $this->db->where("(DATE_FORMAT(STR_TO_DATE(date_of_joining, '%Y-%m-%d'), '%Y-%m-%d') <= '$lastp_date_y_m_d')", null, false);
            $this->db->group_start();
            $this->db->or_where('date_of_leaving' ,'');
            $this->db->or_where("(DATE_FORMAT(STR_TO_DATE(date_of_leaving, '%Y-%m-%d'), '%Y-%m-%d') >= '$p_date_y_m_d')", null, false);
            $this->db->group_end();
        }

        return $this->db->get();
    }
    
    // get total number of employees
    public function get_total_employees() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' and deleted=0 and is_active=1";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function read_employee_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
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

    // Function to add record in table
    public function add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employees', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_record($id){
        $data=array('deleted'=>1 , 'is_active'=>0);
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    /*  Update Employee Record */

    // Function to update record in table
    public function update_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > basic_info
    public function basic_info($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > change_password
    public function change_password($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > social_info
    public function social_info($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > profile picture
    public function profile_picture($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > contact_info
    public function contact_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_contacts', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > contact_info
    public function contact_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "contact_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_contacts',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > document_info_update
    public function document_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "document_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_documents',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > document_info_update
    public function img_document_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "immigration_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_immigration',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > document info
    public function document_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_documents', $data);
        $document_id = $this->db->insert_id();

        $this->db->query("UPDATE xin_employee_document_files SET document_id='".$document_id."' WHERE (document_id IS NULL OR document_id = '') AND root_id='".$root_id."' AND employee_id='".$data['employee_id']."'");

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > immigration info
    public function immigration_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_immigration', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    // Function to add record in table > qualification_info_add
    public function qualification_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_qualification', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > qualification_info_update
    public function qualification_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "qualification_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_qualification',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > work_experience_info_add
    public function work_experience_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_work_experience', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > work_experience_info_update
    public function work_experience_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "work_experience_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_work_experience',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > bank_account_info_add
    public function bank_account_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_bankaccount', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > bank_account_info_update
    public function bank_account_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "bankaccount_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_bankaccount',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > contract_info_add
    public function contract_info_add($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id']=$root_id;
        $this->db->insert('xin_employee_contract', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //for current contact > employee
    public function check_employee_contact_current($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and contact_type ='current' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(1);
        return $query = $this->db->get();
    }

    //for permanent contact > employee
    public function check_employee_contact_permanent($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and contact_type ='permanent' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(1);
        return $query = $this->db->get();
    }

    // get current contacts by id
    public function read_contact_info_current($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "contact_id =" . "'" . $id . "' and contact_type ='current' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->where($condition);
        $this->db->limit(1);// only apply if you have more than same id in your table othre wise comment this line
        $query = $this->db->get('xin_employee_contacts');
        $row = $query->row();
        return $row;
    }

    // get permanent contacts by id
    public function read_contact_info_permanent($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "contact_id =" . "'" . $id . "' and contact_type ='permanent' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->where($condition);
        $this->db->limit(1);// only apply if you have more than same id in your table othre wise comment this line
        $query = $this->db->get('xin_employee_contacts');
        $row = $query->row();
        return $row;
    }

    // Function to update record in table > contract_info_update
    public function contract_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "contact_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_contract',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > leave_info_add
    public function leave_info_add($data){
        $root_id = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_employee_leave', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > leave_info_update
    public function leave_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_leave',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > shift_info_add
    public function shift_info_add($data){
        $root_id = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_employee_shift', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > shift_info_update
    public function shift_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "emp_shift_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_shift',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > location_info_add
    public function location_info_add($data){
        $root_id = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_employee_location', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > location_info_update
    public function location_info_update($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "office_location_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_location',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get all office shifts
    public function all_office_shifts() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_office_shift where root_id='".$root_id."'");
        return $query->result();
    }

    // get contacts
    public function set_employee_contacts($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get salary
    public function set_employee_salary($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "emp_id =" . "'" . $id . "' and root_id = '".$root_id."' order by salary_id desc";
        $this->db->select('*');
        $this->db->from('xin_employee_salary');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get documents
    public function set_employee_documents($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_documents');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get immigration
    public function set_employee_immigration($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_immigration');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get employee qualification
    public function set_employee_qualification($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_qualification');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get employee work experience
    public function set_employee_experience($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_work_experience');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get employee bank account
    public function set_employee_bank_account($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_bankaccount');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }
    // get employee contract
    public function set_employee_contract($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_contract');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get employee office shift
    public function set_employee_shift($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_shift');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get employee leave
    public function set_employee_leave($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_leave');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }
    public function get_employee_annual_leave($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('annual_leave');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }
    public function get_all_employee_annual_leaves() {
        $root_id   = $_SESSION['root_id'];
        $condition = "annual_leave.root_id='".$root_id."'";
        $this->db->select('annual_leave.*,xin_departments.department_name');
        $this->db->from('annual_leave');
        $this->db->where($condition);
        $this->db->join('xin_employees','xin_employees.user_id=annual_leave.employee_id','left');
        $this->db->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->order_by('annual_leave.id','DESC');
        $this->db->limit(500);
        return $this->db->get();
    }
    public function get_annual_leaves_for_approval($designation) {
        $root_id   = $_SESSION['root_id'];
        $condition = "annual_leave.root_id='".$root_id."'";
        $this->db->select('annual_leave.*,xin_departments.department_name');
        $this->db->from('annual_leave');
        $this->db->where($condition);
        $this->db->where('xin_employees.reporting_to',$designation);
        $this->db->join('xin_employees','xin_employees.user_id=annual_leave.employee_id','left');
        $this->db->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->order_by('annual_leave.id','DESC');
        $this->db->limit(500);
        return $this->db->get();
    }

    // get employee location
    public function set_employee_location($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_location');
        $this->db->where($condition);
        $this->db->limit(500);
        return $this->db->get();
    }

    // get document type by id
    public function read_document_type_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "document_type_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_document_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // contract type
    public function read_contract_type_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "contract_type_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_contract_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // contract employee
    public function read_contract_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "contract_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_contract');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // office shift
    public function read_shift_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "office_shift_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_office_shift');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }



    // get all contract types
    public function all_contract_types() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_contract_type where root_id='".$root_id."'");
        return $query->result();
    }

    // get all contracts
    public function all_contracts() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_employee_contract where root_id='".$root_id."'");
        return $query->result();
    }

    // get all document types
    public function all_document_types() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_document_type where root_id='".$root_id."'");
        return $query->result();
    }

    // get all education level
    public function all_education_level() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_qualification_education_level where root_id='".$root_id."'");
        return $query->result();
    }

    // get education level by id
    public function read_education_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "education_level_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_qualification_education_level');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get all qualification languages
    public function all_qualification_language() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_qualification_language where root_id='".$root_id."'");
        return $query->result();
    }

    // get languages by id
    public function read_qualification_language_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_qualification_language');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get all qualification skills
    public function all_qualification_skill() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_qualification_skill where root_id='".$root_id."'");
        return $query->result();
    }

    // get qualification by id
    public function read_qualification_skill_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "skill_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_qualification_skill');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get contacts by id
    public function read_contact_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "contact_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get documents by id
    public function read_document_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "document_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_documents');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get documents by id
    public function read_imgdocument_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "immigration_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_immigration');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get qualifications by id
    public function read_qualification_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "qualification_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_qualification');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get qualifications by id
    public function read_work_experience_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "work_experience_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_work_experience');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get bank account by id
    public function read_bank_account_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "bankaccount_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_bankaccount');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get leave by id
    public function read_leave_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_leave');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get shift by id
    public function read_emp_shift_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "emp_shift_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_shift');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_contact_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "contact_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_contacts');

    }

    public function delete_annual_leave($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('annual_leave');

    }

    // Function to Delete selected record from table
    public function delete_document_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "document_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_documents');

    }

    // Function to Delete selected record from table
    public function delete_imgdocument_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "immigration_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_immigration');

    }

    // Function to Delete selected record from table
    public function delete_qualification_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "qualification_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_qualification');

    }

    // Function to Delete selected record from table
    public function delete_work_experience_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "work_experience_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_work_experience');

    }

    // Function to Delete selected record from table
    public function delete_bank_account_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "bankaccount_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_bankaccount');

    }

    // Function to Delete selected record from table
    public function delete_contract_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "contract_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_contract');

    }

    // Function to Delete selected record from table
    public function delete_salary_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "salary_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_salary');

    }

    // Function to Delete selected record from table
    public function delete_leave_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_leave');

    }

    // Function to Delete selected record from table
    public function delete_shift_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "emp_shift_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_shift');

    }

    // Function to Delete selected record from table
    public function delete_location_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "office_location_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_location');

    }

    // Function to add record in table
    public function add_salary($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_employee_salary', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_annual_leave($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('annual_leave', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get location by id
    public function read_location_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "office_location_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_location');
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