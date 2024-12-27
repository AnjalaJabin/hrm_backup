<?php

class xin_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    // get single location
    public function read_location_info($id) {

        $condition = "location_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_office_location');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // contact groups
    public function get_contact_group($user_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "' and user_id = '".$user_id."'";
        $this->db->select('*');
        $this->db->from('xin_contact_group');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_contacts($user_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "' and (user_id='".$user_id."' OR share_public='1') order by id desc";
        $this->db->select('*');
        $this->db->from('xin_contacts');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // add contact groups
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

    //get root_id
    public function get_root_id()
    {
        return $_SESSION['root_id'];
    }

    public function get_root_account()
    {
        $MainDb = $this->load->database('maindb', TRUE);
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $root_id."'";
        $MainDb->select('*');
        $MainDb->from('root_accounts');
        $MainDb->where($condition);
        $query = $MainDb->get();
        return $query->result();
    }

    public function read_package_information($id) {
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

    // is logged in to system
    public function is_logged_in($id)
    {
        $CI =& get_instance();
        $is_logged_in = $CI->session->userdata($id);
        return $is_logged_in;
    }

    // generate random string
    public function generate_random_string($length = 7) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function get_countries()
    {
        $query = $this->db->query("SELECT * from xin_countries");
        return $query->result();
    }

    // get single country
    public function read_country_info($id) {
        $MainDb = $this->load->database('maindb', TRUE);
        $condition = "country_id ='".$id."'";
        $MainDb->select('*');
        $MainDb->from('xin_countries');
        $MainDb->where($condition);
        $MainDb->limit(1);
        $query = $MainDb->get();

        return $query->result();
    }

    // get single user
    public function read_user_info($id) {
        $root_id   = $_SESSION['root_id'];
        //$condition = "root_id='".$root_id."' and user_id =" . "'" . $id . "' or user_id = ''";
        $condition = "user_id ='".$id."'";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }
    public function get_leave_salary_entries($empid)
    {
        // Define the criteria for the query
        $this->db->where('annual_leave_id', 0);
        $this->db->where('paid_date >=', date('Y-m-d', strtotime('-1 year')));
        $this->db->where('paid_date <=', date('Y-m-d'))->where('employee_id',$empid)->where('status',2);

        // Execute the query
        $query = $this->db->get('leave_salary');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
     public function get_leave_salary_entries_all($empid)
    {
        // Define the criteria for the query
        $this->db->where('annual_leave_id', 0);
        // $this->db->where('paid_date >=', date('Y-m-d', strtotime('-1 year')));
        $this->db->where('paid_date <=', date('Y-m-d'))->where('employee_id',$empid)->where('status',2);

        // Execute the query
        $query = $this->db->get('leave_salary');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function get_leave_balance($employee_id,$date=null) {
        $root_id   = $_SESSION['root_id'];
        $current_year = date('Y');

        $this->db->select('end_date');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('root_id', $root_id);
        $this->db->order_by('end_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('annual_leave');
        $result = $query->row_array();

        if (!empty($result)) {
            if($date)
                $cur_date =date_create_from_format('Y-m-d', $date);
            else
                $cur_date=date_create();
                
            $end_date = date_create_from_format('Y-m-d', $result['end_date']);
            $interval = date_diff($cur_date, $end_date);
            $days = $interval->format('%a');
            $this->db->select('DATEDIFF(xin_leave_applications.to_date, xin_leave_applications.from_date) AS leave_days');
            $this->db->from('xin_leave_applications')
                ->join('xin_leave_type','xin_leave_type.leave_type_id=xin_leave_applications.leave_type_id');
            $this->db->where('xin_leave_applications.employee_id', $employee_id);
            $this->db->where('xin_leave_applications.root_id', $root_id);
            $this->db->where('xin_leave_applications.status', 2);
            $this->db->where("YEAR(xin_leave_applications.from_date) = $current_year");

            $this->db->where('xin_leave_type.type_name',"Unpaid Leave");
            $query = $this->db->get();

            $result = $query->row();
            if($result) {
                $leave_days = $result->leave_days;
            }
            else{
                $leave_days= 0;
            }
            $days=$days-$leave_days;
            $leave_balance = round($days / 30 * 2.5, 2);
            $leave_salary_entries = $this->get_leave_salary_entries($employee_id);
            if($leave_salary_entries){
                foreach ($leave_salary_entries as $entry){
                    if($entry->annual_leave_id==0)
                        $leave_balance=$leave_balance-$entry->leave_days;

                }
            }

//            $leave_balance>30?$leave_balance=30:$leave_balance=$leave_balance;
        } else {
            $leave_balance = 30;
        }

        return $leave_balance;
    }

    public function read_user_info_by_emp_id($id) {
        $root_id   = $_SESSION['root_id'];
        //$condition = "root_id='".$root_id."' and user_id =" . "'" . $id . "' or user_id = ''";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where('employee_id',$id);
        $this->db->where('root_id',$root_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    // get single user
    public function read_candidate_info($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id='".$root_id."' and application_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_job_applications');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    // get single user > by email
    public function read_user_info_byemail($email) {
        $condition = "email ='".$email."' or username ='".$email."'  and deleted=0 and is_active='1'";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        $this->db->limit(1);
        return $query = $this->db->get();

        //return $query->num_rows();
    }

    // get last user attendance > check if loged in-
    public function attendance_time_checks($id) {

        $session = $this->session->userdata('username');
        return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$id."' and clock_out = '' order by time_attendance_id desc limit 1");
    }

    // get single user > by designation
    public function read_user_info_bydesignation($email) {
        $root_id   = $_SESSION['root_id'];
        $condition = "designation_id =" . "'" . $email . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function employee_count() {
        $root_id   = $_SESSION['root_id'];
        $this->db->select('count(*) as count');
        $this->db->from('xin_employees');
        $this->db->where('root_id',$root_id);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result()[0]->count;
    }
    public function department_count() {
        $root_id   = $_SESSION['root_id'];
        $this->db->select('count(*) as count');
        $this->db->from('xin_departments');
        $this->db->where('root_id',$root_id);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result()[0]->count;
    }
    public function countExpiredDocuments($user=null)
    {

        $today = date('Y-m-d'); // Get today's date
        $root_id   = $_SESSION['root_id'];

        // Query to count the number of expired documents
        $this->db->where('date_of_expiry <', $today)
            ->where('root_id',$root_id);
        if($user)
            $this->db->where('employee_id',$user);
        $count = $this->db->count_all_results('xin_employee_documents');

        return $count; // Output the count of expired documents
    }

    public function getUpcomingBirthdays()
    {
        $root_id   = $_SESSION['root_id'];

        $query = $this->db->query("SELECT * FROM `xin_employees` WHERE DATE_FORMAT(date_of_birth, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d') AND `root_id` = '".$root_id."' ORDER BY DATE_FORMAT(CONCAT(YEAR(CURDATE()), '-' ,MONTH(date_of_birth), '-', DAY(date_of_birth)), '%Y-%m-%d') ASC LIMIT 4");
        return $query->result();

    }
    public function  get_employee_requests_count($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_requests.root_id = '".$root_id."' ";

        $this->db->select('count(*) as count');
        $this->db->from('employee_requests');
        $this->db->where($condition);
        $this->db->where('employee_id',$employee_id);
        $query = $this->db->get();

        return $query->result()[0]->count;
    }


    public function  get_employee_requests_dashboard($employee_id=null) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_requests.root_id = '".$root_id."' ";

        $this->db->select('employee_requests.*,xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.last_name,xin_departments.department_name');
        $this->db->from('employee_requests');
        $this->db->join('xin_employees','xin_employees.user_id=employee_requests.employee_id','left')
            ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->where($condition);
        $this->db->where('employee_requests.status',1);
        if($employee_id){
            $this->db->where('employee_requests.employee_id',$employee_id);
        }
        $this->db->limit(5);
        $query = $this->db->get();

        $results = $query->result(); // Get the query results

        return $results;
    }
    public function get_number_of_days($p_date){
        $targetDateStr = $p_date;
        list($year, $month) = explode('-', $targetDateStr);
        $numberOfDays = date('t', strtotime($year . '-' . $month . '-01'));
        return $numberOfDays;
    }
    public function  get_employee_details($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "xin_employees.root_id = '".$root_id."' ";

        $this->db->select('xin_employees.*,xin_departments.department_name,xin_designations.designation_name');
        $this->db->from('xin_employees');
        $this->db->join('xin_designations','xin_designations.designation_id=xin_employees.designation_id','left')
            ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->where($condition);
        $this->db->where('xin_employees.user_id',$employee_id);
        $this->db->limit(1);
        $query = $this->db->get();

        $results = $query->result(); // Get the query results

        return $results;
    }
    public function  get_employee_requests_dashboard_user($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_requests.root_id = '".$root_id."' ";

        $this->db->select('employee_requests.*,xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.last_name,xin_departments.department_name');
        $this->db->from('employee_requests');
        $this->db->join('xin_employees','xin_employees.user_id=employee_requests.employee_id','left')
            ->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');
        $this->db->where($condition);
        $this->db->where('employee_requests.employee_id',$employee_id);

        $this->db->limit(5);
        $query = $this->db->get();

        $results = $query->result(); // Get the query results

        return $results;
    }

    public function request_count() {
        $root_id   = $_SESSION['root_id'];
        $this->db->select('count(*) as count');
        $this->db->from('employee_requests');
        $this->db->where('root_id',$root_id);
        $this->db->where('status',1);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result()[0]->count;
    }
    public function asset_count() {
        $root_id   = $_SESSION['root_id'];
        $this->db->select('count(*) as count');
        $this->db->from('employee_asset');
        $this->db->where('root_id',$root_id);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result()[0]->count;
    }
    public function expense_count($user=null) {
        $year_month = date('Y-m');

        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id   = $_SESSION['root_id'];

        $this->db->select_sum('amount')
            ->where("purchase_date >= '$start_date' AND purchase_date <= '$end_date'")
            ->where('root_id', $root_id);
        if($user)
            $this->db->where('employee_id',$user);
//            ->where('status',1)

        $result = $this->db->get('xin_expenses')->row();

        if ($result->amount !== null) {
            return $result->amount;
        } else {
            return 0;
        }
    }


    // get single company
    public function read_company_info($id) {

        $condition = "root_id =" . "'" . $_SESSION['root_id'] . "'";
        $this->db->select('*');
        $this->db->from('xin_companies');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_employee_officeshift($id) {
        return $query = $this->db->query("SELECT * from xin_employee_shift where employee_id = '".$id."'");
    }

    // get single user role info
    public function read_user_role_info($id) {

        $condition = "role_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_user_roles');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get setting info
    public function read_setting_info($id) {
        $root_id   = $id;
        //$condition = "setting_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_system_setting');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function check_ticket_balance($employee_id, $date = null) {
        if ($date) {
            $given_date = $date;
        } else {
            $given_date = date('Y-m-d');
        }

        $given_date = $this->db->escape($given_date);

        $query = "SELECT * 
              FROM flight_tickets 
              WHERE employee_id = $employee_id 
              AND ticket_date = (SELECT MAX(ticket_date) FROM flight_tickets WHERE employee_id = $employee_id)
              LIMIT 1";

        $result = $this->db->query($query)->row();
        return $result;
    }

    // get setting layout
    public function system_layout() {

        // get details of layout
        $system = $this->read_setting_info(1);

        if($system[0]->compact_sidebar!=''){
            // if compact sidebar
            $compact_sidebar = 'compact-sidebar';
        } else {
            $compact_sidebar = '';
        }
        if($system[0]->fixed_header!=''){
            // if fixed header
            $fixed_header = 'fixed-header';
        } else {
            $fixed_header = '';
        }
        if($system[0]->fixed_sidebar!=''){
            // if fixed sidebar
            $fixed_sidebar = 'fixed-sidebar';
        } else {
            $fixed_sidebar = '';
        }
        if($system[0]->boxed_wrapper!=''){
            // if boxed wrapper
            $boxed_wrapper = 'boxed-wrapper';
        } else {
            $boxed_wrapper = '';
        }
        if($system[0]->layout_static!=''){
            // if static layout
            $static = 'static';
        } else {
            $static = '';
        }
        return $layout = $compact_sidebar.' '.$fixed_header.' '.$fixed_sidebar.' '.$boxed_wrapper.' '.$static;
    }
    public function getPaymentsByMonths($months,$employeeid) {
        // Query the database to get records with payment dates within the specified months
        $this->db->select('*');
        $this->db->from('xin_make_payment')->where('employee_id',$employeeid);
        $this->db->where_in('payment_date', $months);
        $query = $this->db->get();

        // Return the results as an array
        return $query->num_rows();

    }
    // get company setting info
    public function read_company_setting_info($id) {
 if(isset($_SESSION['root_id'])){
             $root_id =  $_SESSION['root_id'];
            
        }
         else{
             $root_id="217";
        }
                $condition = "root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_company_info');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get title
    public function site_title() {
        return 'HRM | Corbuz';
    }

    // get all companies
    public function get_companies()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_companies where root_id='".$root_id."'");
        return $query->result();
    }

    // get all leave applications
    public function get_leave_applications()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_leave_applications where root_id='".$root_id."' ");
        return $query->result();
    }

    // get last 5 applications
    public function get_last_leave_applications()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_leave_applications where root_id='".$root_id."' order by leave_id desc limit 5");
        return $query->result();
    }

    //set currency sign
    public function currency_sign($number) {

        // get details
        $system_setting = $this->read_setting_info(1);
        // currency code/symbol
        if($system_setting[0]->show_currency=='code'){
            $ar_sc = explode(' -',$system_setting[0]->default_currency_symbol);
            $sc_show = $ar_sc[0];
        } else {
            $ar_sc = explode('- ',$system_setting[0]->default_currency_symbol);
            $sc_show = $ar_sc[1];
        }
        if($system_setting[0]->currency_position=='Prefix'){
            $sign_value = $sc_show.''.$number;
        } else {
            $sign_value = $number.''.$sc_show;
        }
        return $sign_value;
    }

    // get all locations
    public function all_locations()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_office_location where root_id='".$root_id."'");
        return $query->result();
    }

    // get all company locations
    public function all_company_locations($id)
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_office_location where root_id='".$root_id."'  and company_id='".$id."' ");
        return $query->result();
    }

    // get all company locations
    public function get_all_department_designations($id)
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_designations where root_id='".$root_id."'  and department_id='".$id."' ");
        return $query->result();
    }

    //set currency sign
    public function set_date_format_js() {

        // get details
        $system_setting = $this->read_setting_info(1);
        // date format
        if($system_setting[0]->date_format_xi=='d-m-Y'){
            $d_format = 'dd-mm-yy';
        } else if($system_setting[0]->date_format_xi=='m-d-Y'){
            $d_format = 'mm-dd-yy';
        } else if($system_setting[0]->date_format_xi=='d-M-Y'){
            $d_format = 'dd-M-yy';
        } else if($system_setting[0]->date_format_xi=='M-d-Y'){
            $d_format = 'M-dd-yy';;
        }

        return $d_format;
    }

    public function read_designation_info($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "designation_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_designations');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // get all employees
    public function all_employees()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_employees where root_id='".$root_id."'");
        return $query->result();
    }

    public function all_employees_2()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_employees where root_id ='".$root_id."' and deleted=0 and is_active=1");
        return $query->result();
    }

    public function all_active_employees()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_employees where root_id ='".$root_id."' and deleted=0 and is_active=1");
        return $query->result();
    }

    public function get_total_task_hours_by_employee($user_id,$date){
        //echo "SELECT * FROM `task_timers` WHERE `staff_id`='".$user_id."' and end_time between UNIX_TIMESTAMP('".date("Y-m-d",strtotime($date))."') and UNIX_TIMESTAMP('".date("Y-m-d",strtotime($date))." 23:59:59')";
        $check_query = $this->db->query("SELECT * FROM `task_timers` WHERE `staff_id`='".$user_id."' and end_time between UNIX_TIMESTAMP('".date("Y-m-d",strtotime($date))."') and UNIX_TIMESTAMP('".date("Y-m-d",strtotime($date))." 23:59:59')");

        if($check_query->num_rows()>=1){
            $g_total_time = 0;
            foreach($check_query->result() as $row ) {
                $total_time = $row->end_time-$row->start_time;
                $g_total_time = $g_total_time+$total_time;
            }
            $g_total_time = $this->seconds_to_hour($g_total_time);
            return $g_total_time;
        }else{
            return '';
        }
    }

    public function seconds_to_hour($init){
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;

        return "$hours:$minutes:$seconds";
    }

    //set currency sign
    public function set_date_format($date) {

        // get details
        $system_setting = $this->read_setting_info(1);
        // date formate
        if($system_setting[0]->date_format_xi=='d-m-Y'){
            $d_format = date("d-m-Y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='m-d-Y'){
            $d_format = date("m-d-Y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='d-M-Y'){
            $d_format = date("d-M-Y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='M-d-Y'){
            $d_format = date("M-d-Y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='F-j-Y'){
            $d_format = date("F-j-Y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='j-F-Y'){
            $d_format = date("j-F-Y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='m.d.y'){
            $d_format = date("m.d.y", strtotime($date));
        } else if($system_setting[0]->date_format_xi=='d.m.y'){
            $d_format = date("d.m.y", strtotime($date));
        } else {
            $d_format = $system_setting[0]->date_format_xi;
        }

        return $d_format;
    }

    // get all table rows
    public function all_policies() {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_company_policy where root_id='".$root_id."'");
        return $query->result();
    }

    // Function to update record in table > company information
    public function update_company_info_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_company_info',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > company information
    public function update_setting_info_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        //$condition = "setting_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $condition = "root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_system_setting',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_backup($data){
        $this->db->insert('xin_database_backup', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get all db backup/s
    public function all_db_backup() {
        return  $query = $this->db->query("SELECT * from xin_database_backup");
    }

    // Function to Delete selected record from table
    public function delete_single_backup_record($id){
        $this->db->where('backup_id', $id);
        $this->db->delete('xin_database_backup');

    }
    // Function to Delete selected record from table
    public function delete_all_backup_record(){
        $this->db->empty_table('xin_database_backup');

    }

    // get all email templates
    public function get_email_templates() {
        return  $query = $this->db->query("SELECT * from xin_email_template");
    }

    // get email template info
    public function read_email_template_info($id) {

        $condition = "template_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_email_template');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // Function to update record in table > email template
    public function update_email_template_record($data, $id){
        $this->db->where('template_id', $id);
        if( $this->db->update('xin_email_template',$data)) {
            return true;
        } else {
            return false;
        }
    }

    /*  ALL CONSTATNS */

    // get all table rows
    public function get_contract_types() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_contract_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_qualification_education() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_qualification_education_level where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_qualification_language() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_qualification_language where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_qualification_skill() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_qualification_skill where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_document_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_document_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_award_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_award_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_leave_type() {
        $root_id = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_leave_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_warning_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_warning_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_termination_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_termination_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_expense_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_expense_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_job_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_job_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_exit_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_employee_exit_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_travel_type() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_travel_arrangement_type where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_payment_method() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_payment_method where root_id='".$root_id."' ");
    }

    // get all table rows
    public function get_currency_types() {
        $root_id   = $_SESSION['root_id'];
        return  $query = $this->db->query("SELECT * from xin_currencies where root_id='".$root_id."' ");
    }

    /*  ADD CONSTANTS */

    // Function to add record in table
    public function add_contract_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_contract_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_document_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_document_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_edu_level($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_qualification_education_level', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_edu_language($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_qualification_language', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_edu_skill($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_qualification_skill', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_payment_method($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_payment_method', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_award_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_award_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_leave_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_leave_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_warning_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_warning_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_termination_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_termination_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_expense_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_expense_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_job_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_job_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_exit_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_employee_exit_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_travel_arr_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_travel_arrangement_type', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_currency_type($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_currencies', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*  DELETE CONSTANTS */
    // Function to Delete selected record from table
    public function delete_contract_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "contract_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_contract_type');

    }
    // Function to Delete selected record from table
    public function delete_document_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "document_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_document_type');

    }
    // Function to Delete selected record from table
    public function delete_payment_method_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "payment_method_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_payment_method');

    }
    // Function to Delete selected record from table
    public function delete_education_level_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "education_level_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_qualification_education_level');

    }
    // Function to Delete selected record from table
    public function delete_qualification_language_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_qualification_language');

    }
    // Function to Delete selected record from table
    public function delete_qualification_skill_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "skill_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_qualification_skill');

    }
    // Function to Delete selected record from table
    public function delete_award_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "award_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_award_type');

    }
    // Function to Delete selected record from table
    public function delete_leave_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_leave_type');

    }
    // Function to Delete selected record from table
    public function delete_warning_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "warning_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_warning_type');

    }
    // Function to Delete selected record from table
    public function delete_termination_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "termination_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_termination_type');

    }
    // Function to Delete selected record from table
    public function delete_expense_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "expense_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_expense_type');

    }
    // Function to Delete selected record from table
    public function delete_job_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "job_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_job_type');

    }
    // Function to Delete selected record from table
    public function delete_exit_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "exit_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_employee_exit_type');

    }
    // Function to Delete selected record from table
    public function delete_travel_arr_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "arrangement_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_travel_arrangement_type');

    }

    // Function to Delete selected record from table
    public function delete_currency_type_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "currency_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_currencies');

    }

    // get all last 5 employees
    public function last_four_employees()
    {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_employees where root_id='".$root_id."'  and is_admin=0 order by user_id desc limit 4");
        return $query->result();
    }

    // get all last jobs
    public function last_jobs()
    {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * FROM xin_job_applications where root_id='".$root_id."' order by application_id desc limit 4");
        return $query->result();
    }

    // get total number of salaries paid
    public function get_total_salaries_paid() {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT SUM(payment_amount) as paid_amount FROM xin_make_payment where root_id = '".$root_id."' ");
        return $query->result();
    }

    // get company wise salary > chart
    public function all_companies_chart()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT m.*, c.* FROM xin_make_payment as m, xin_companies as c where m.company_id = c.company_id and c.root_id='".$root_id."' group by m.company_id");
        return $query->result();
    }

    // get company wise salary > chart > make payment
    public function get_company_make_payment($id) {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where company_id='".$id."' and root_id='".$root_id."' ");

        return $query->result();
    }

    // get all currencies
    public function get_currencies() {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_currencies where root_id='".$root_id."' ");

        return $query->result();
    }

    // get location wise salary > chart
    public function all_location_chart()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT m.*, l.* FROM xin_make_payment as m, xin_office_location as l where m.location_id = l.location_id and l.root_id='".$root_id."' group by m.location_id");
        return $query->result();
    }

    // get location wise salary > chart > make payment
    public function get_location_make_payment($id) {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where location_id='".$id."' and root_id='".$root_id."' ");
        return $query->result();
    }

    // get location wise salary > chart
    public function all_departments_chart()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT m.*, d.* FROM xin_make_payment as m, xin_departments as d where m.department_id = d.department_id and d.root_id='".$root_id."' group by m.department_id");
        return $query->result();
    }

    // get department wise salary > chart > make payment
    public function get_department_make_payment($id) {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where department_id='".$id."' and root_id='".$root_id."' ");
        return $query->result();
    }

    // get designation wise salary > chart
    public function all_designations_chart()
    {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT m.*, d.* FROM xin_make_payment as m, xin_designations as d where m.designation_id = d.designation_id and d.root_id='".$root_id."' group by m.designation_id");
        return $query->result();
    }

    // get designation wise salary > chart > make payment
    public function get_designation_make_payment($id) {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where designation_id='".$id."' and root_id='".$root_id."' ");
        return $query->result();
    }

    // get all jobs
    public function get_all_jobs() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_jobs');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get all departments
    public function get_all_departments() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_departments');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get all designations
    public function get_all_designations() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_designations');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get all projects
    public function get_all_projects() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_projects');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get all locations
    public function get_all_locations() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_office_location');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get all companies
    public function get_all_companies() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_companies');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get single record > db table > constant
    public function read_contract_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "contract_type_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_contract_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    // get single record > db table > constant
    public function read_document_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "document_type_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_document_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_payment_method($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "payment_method_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_payment_method');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_education_level($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "education_level_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_qualification_education_level');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_qualification_language($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_qualification_language');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_qualification_skill($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "skill_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_qualification_skill');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_award_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "award_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_award_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_leave_type($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_type_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_leave_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_warning_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "warning_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_warning_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_termination_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "termination_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_termination_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_expense_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "expense_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_expense_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_job_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "job_type_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_job_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_exit_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "exit_type_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_employee_exit_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_travel_arr_type($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "arrangement_type_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_travel_arrangement_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get single record > db table > constant
    public function read_currency_types($id) {
        $root_id = $_SESSION['root_id'];
        $condition = "currency_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_currencies');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    /* UPDATE CONSTANTS */
    // Function to update record in table
    public function update_document_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "document_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_document_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_contract_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "contract_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_contract_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_payment_method_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "payment_method_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_payment_method',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_education_level_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "education_level_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_qualification_education_level',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_qualification_language_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "language_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_qualification_language',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_qualification_skill_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "skill_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_qualification_skill',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_award_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "award_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_award_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_leave_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_leave_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_warning_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "warning_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_warning_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_termination_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "termination_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_termination_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_expense_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "expense_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_expense_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_currency_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "currency_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_currencies',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get email template
    public function single_email_template($id){

        $query = $this->db->query("SELECT * from xin_email_template where template_id = '".$id."'");
        return $query->result();
    }

    // Function to update record in table
    public function update_job_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "job_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_job_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get single record > db table > email template
    public function read_email_template($id) {

        $condition = "template_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_email_template');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // Function to update record in table
    public function update_exit_type_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "exit_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employee_exit_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_travel_arr_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "arrangement_type_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_travel_arrangement_type',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get current month attendance
    public function current_month_attendance() {
        $current_month = date('Y-m');
        $session = $this->session->userdata('username');
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_attendance_time where root_id='".$root_id."' and attendance_date like '%".$current_month."%' and `employee_id` = '".$session['user_id']."'  group by attendance_date");
        return $query->num_rows();
    }

    // get total employee awards
    public function total_employee_awards() {
        $session = $this->session->userdata('username');
        $id = $session['user_id'];
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * FROM xin_awards where employee_id IN($id) and root_id='".$root_id."' order by award_id desc");
        return $query->num_rows();
    }

    // get current employee awards
    public function get_employee_awards() {
        $session = $this->session->userdata('username');
        $id = $session['user_id'];
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * FROM xin_awards where employee_id IN($id) and root_id='".$root_id."' order by award_id desc");
        return $query->result();
    }

    // get user role > links > all
    public function user_role_resource(){

        // get session
        $session = $this->session->userdata('username');
        // get userinfo and role
        $user = $this->read_user_info($session['user_id']);
        $role_user = $this->read_user_role_info($user[0]->user_role_id);

        $role_resources_ids = explode(',',$role_user[0]->role_resources);
        return $role_resources_ids;
    }

    // get all opened tickets
    public function all_open_tickets() {
        $root_id = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * FROM xin_support_tickets WHERE ticket_status ='1' and root_id='".$root_id."'");
        return $query->num_rows();
    }

    // get all closed tickets
    public function all_closed_tickets() {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * FROM xin_support_tickets WHERE ticket_status ='2' and root_id='".$root_id."' ");
        return $query->num_rows();
    }

    // delete checking

    public function company_delete_check($id) {
        $condition = "company_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_office_location');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function location_delete_check($id) {
        $condition = "location_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_departments');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function department_delete_check($id) {
        $condition = "department_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_designations');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function designation_delete_check($id) {
        $condition = "designation_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_salary_employees() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "' and is_admin=0 and (`hourly_grade_id`!=0 OR `monthly_grade_id`!=0)";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        return $this->db->get();
    }

    // Get all pending leave count
    public function get_all_pending_leaves() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and status = 1 ";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get all pending advance salary request
    public function get_all_pending_advance_salary_request() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and status = 0 ";
        $this->db->select('*');
        $this->db->from('xin_advance_salaries');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get Company Head Emails

    public function get_manager_emails($role_id){

        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and FIND_IN_SET('".$role_id."', role_resources)";
        $this->db->select('*');
        $this->db->from('xin_user_roles');
        $this->db->where($condition);
        $query = $this->db->get();

        $email_datas = array();

        foreach($query->result() as $arow)
        {

            $xaquery  = "SELECT * FROM xin_employees where root_id='$root_id' and user_role_id='".$arow->role_id."'";
            $xaresult = $this->db->query($xaquery);
            foreach ($xaresult->result() as $xarow)
            {
                $email_datas[] = ['email' => $xarow->email, 'name' => $xarow->first_name];
            }

        }

        $xaquery  = "SELECT * FROM xin_employees where root_id='$root_id' and user_role_id='1'";
        $xaresult = $this->db->query($xaquery);
        foreach ($xaresult->result() as $xarow)
        {
            $email_datas[] = ['email' => $xarow->email, 'name' => $xarow->first_name];
        }

        return $email_datas;
    }
    public function get_payment_history($employee_id,$date,$amount,$emi,$one_time_deduct){
        $limit=0;
        if(($one_time_deduct)||($emi==0))
            $limit=1;
        // else
        //     $limit= ceil(floatval($amount)/floatval($emi));

        $date = date('d-m-Y h:i:s',strtotime($date));
        $this->db->select('*')
                 ->from('xin_make_payment')
                 ->where('employee_id',$employee_id)
                 ->where("STR_TO_DATE(created_at, '%d-%m-%Y %H:%i:%s') >=", $date)
                 ->where('loan_emi >',0);
        if($limit)
                $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();

    }
    // Get all pending advance salary request
    public function get_all_pending_loan_request() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and status = 0 ";
        $this->db->select('*');
        $this->db->from('xin_loans');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // get current employee awards
    public function get_employee_company($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $xaquery  = "SELECT * FROM xin_employees where root_id='$root_id' and user_id='".$employee_id."'";
        $xaresult = $this->db->query($xaquery);
        $xaresult = $xaresult->result();
        $department_id = $xaresult[0]->department_id;

        $xaquery  = "SELECT * FROM xin_departments where department_id='$department_id'";
        $xaresult = $this->db->query($xaquery);
        $xaresult = $xaresult->result();
        $location_id = $xaresult[0]->location_id;
        if($location_id){
            $xaquery  = "SELECT * FROM xin_office_location where location_id='$location_id'";
            $xaresult = $this->db->query($xaquery);
            $xaresult = $xaresult->result();
            $company_id = $xaresult[0]->company_id;

            $xaquery  = "SELECT * FROM xin_companies where company_id='$company_id'";
            $xaresult = $this->db->query($xaquery);

            return $xaresult = $xaresult->result();
        }
        else
            return '';
    }


    // get company by department
    public function get_company_by_department($id) {
        $root_id   = $_SESSION['root_id'];

        $xaquery  = "SELECT * FROM xin_departments where department_id='$id'";
        $xaresult = $this->db->query($xaquery);
        $xaresult = $xaresult->result();
        $location_id = $xaresult[0]->location_id;
        if($location_id) {
            $xaquery = "SELECT * FROM xin_office_location where location_id='$location_id'";
            $xaresult = $this->db->query($xaquery);
            $xaresult = $xaresult->result();
            $company_id = $xaresult[0]->company_id;

            $xaquery = "SELECT * FROM xin_companies where company_id='$company_id'";
            $xaresult = $this->db->query($xaquery);
            return $xaresult = $xaresult->result();
        }else{
            return null;
        }
    }

    // get company by location
    public function get_company_by_location($id) {
        $root_id   = $_SESSION['root_id'];

        $xaquery  = "SELECT * FROM xin_office_location where location_id='$id'";
        $xaresult = $this->db->query($xaquery);
        $xaresult = $xaresult->result();
        $company_id = $xaresult[0]->company_id;

        $xaquery  = "SELECT * FROM xin_companies where company_id='$company_id'";
        $xaresult = $this->db->query($xaquery);
        return $xaresult = $xaresult->result();
    }


    public function get_employee_document_files($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "document_id=".$id." and root_id='".$root_id."' order by id DESC";
        $this->db->select('*');
        $this->db->from('xin_employee_document_files');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_pending_employee_document_files($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id='".$id."' and document_id='' and root_id='".$root_id."' order by id DESC";
        $this->db->select('*');
        $this->db->from('xin_employee_document_files');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_employee_files_count($user_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "(document_id IS NULL OR document_id = '') AND root_id='".$root_id."' AND employee_id='".$user_id."' ORDER BY id DESC";
        $this->db->select('*');
        $this->db->from('xin_employee_document_files');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function insertgal($smlimg,$actimg,$filesize,$root_id,$user_id,$employee_id)
    {
        $data = array(
            'img_name' => $actimg,
            'img_list_name' => $smlimg,
            'root_id' => $root_id,
            'uid' => $user_id,
            'employee_id' => $employee_id,
            'time' => time(),
            'filesize' => $filesize
        );

        $this->db->insert('xin_employee_document_files', $data);

    }

    public function delimg($img_id, $root_id)
    {
        $query = $this->db->get_where('xin_employee_document_files', array('id' => $img_id, 'root_id' => $root_id));
        $row=  $query->row();
        $act_img = "../../uploads/document/" . $row['img_name'];
        if (file_exists($act_img)) {
            unlink($act_img);
        }

        $lst_img = "../../uploads/document/" . $row['img_list_name'];
        if (file_exists($lst_img)) {
            unlink($lst_img);
        }

        $this->db->where('id', $img_id);
        $this->db->where('root_id', $root_id);
        $this->db->delete('xin_employee_document_files');

    }

    public function editcapt($img_id,$caption,$root_id)
    {
        $data = array(
            'img_title' => $caption
        );

        $this->db->where('id', $img_id);
        $this->db->where('root_id', $root_id);
        $this->db->update('xin_employee_document_files', $data);
    }

    public function img_upload($source_path,$filename)
    {

        $i = strrpos($filename,".");
        if (!$i) { return ""; }
        $l = strlen($filename) - $i;
        $ext = substr($filename,$i+1,$l);

        $extension = $ext;
        $extension = strtolower($extension);

        $ua=rand(1111111,9999999999);
        $ub=time();

        $ud=$ua."_".$ub;

        $act_img=$ud.".".$extension;;
        $targetfile="uploads/document/".$act_img;
        move_uploaded_file($source_path,$targetfile);
        return $act_img;
    }

}
?>