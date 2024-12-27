<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class payroll_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get payroll templates
    public function get_templates() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_salary_templates');
        $this->db->where($condition);
        return $this->db->get();
    }

    // get payroll templates
    public function get_employee_template($id,$dept=null) {
        $root_id   = $_SESSION['root_id'];
        if(!$dept || $dept=='undefined')
            return $query = $this->db->query("SELECT * from xin_employees where user_id='".$id."' and root_id = '".$root_id."' ");
        else
            return $query = $this->db->query("SELECT * from xin_employees where user_id='".$id."' and root_id = '".$root_id."'  and department_id = '".$dept."' ");
    }

    // get total hours work > hourly template > payroll generate
    public function total_hours_worked($id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date='".$attendance_date."' and root_id = '".$root_id."'");
    }

    // get payment history > all payslips
    public function all_payment_history() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' ORDER BY `make_payment_id` DESC";
        $this->db->select('*');
        $this->db->from('xin_make_payment');
        $this->db->where($condition);
        return $query = $this->db->get();
    }
    public function get_all_bank_transfers($date,$employee=null) {
        $root_id   = $_SESSION['root_id'];
        $condition = "xin_make_payment.root_id ='".$root_id."' ";
        $this->db->select('xin_make_payment.*,xin_employees.first_name,xin_employees.last_name,xin_employee_bankaccount.account_number,xin_employee_bankaccount.iban,xin_employee_bankaccount.bank_code,xin_employee_bankaccount.bank_name');
        $this->db->from('xin_make_payment')
            ->join('xin_employees','xin_employees.user_id=xin_make_payment.employee_id','left')
            ->join('xin_employee_bankaccount','xin_employee_bankaccount.employee_id=xin_employees.user_id');
        ;
        $this->db->where('xin_make_payment.payment_date',$date);
        $this->db->where('xin_make_payment.payment_method',4);
        if($employee) {
            $this->db->where_in('xin_make_payment.employee_id',$employee);
        }
        $this->db->where($condition)->group_by('xin_make_payment.make_payment_id');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_all_salary_transfers($date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "xin_make_payment.root_id ='".$root_id."' ";
        $this->db->select('xin_make_payment.*,xin_employees.first_name,xin_employees.last_name,xin_employee_bankaccount.account_number,xin_employee_bankaccount.bank_code,xin_employee_bankaccount.bank_name');
        $this->db->from('xin_make_payment')
            ->join('xin_employees','xin_employees.user_id=xin_make_payment.employee_id','left')
            ->join('xin_employee_bankaccount','xin_employee_bankaccount.employee_id=xin_employees.user_id');
        ;
        $this->db->where('xin_make_payment.payment_date',$date);
        $this->db->where($condition)->group_by('xin_make_payment.make_payment_id');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function get_payment_report($employee_id=[],$dept=null,$dates=[],$min_date='',$max_date='') {
        $root_id   = $_SESSION['root_id'];
        $condition = "xin_make_payment.root_id ='".$root_id."' ";
        $this->db->select('xin_make_payment.*,xin_employees.first_name,xin_employees.last_name');
        $this->db->from('xin_make_payment')
            ->join('xin_employees','xin_employees.user_id=xin_make_payment.employee_id','left')
        ;
        if (!empty($dates) && is_array($dates)) {
            // Use where_in to filter by an array of dates
            $this->db->where_in('xin_make_payment.payment_date', $dates);
        }
        if (!empty($employee_id) && is_array($employee_id)) {
            // Use where_in to filter by an array of dates
            $this->db->where_in('xin_make_payment.employee_id', $employee_id);
        }
        if (!empty($dept) && $dept) {
            // Use where_in to filter by an array of dates
            $this->db->where('xin_employees.department_id', $dept);
        }
        if ($min_date && $max_date) {
            // Assuming your created_at field is in 'Y-m-d H:i:s' format
            $this->db->where('STR_TO_DATE(xin_make_payment.created_at, "%d-%m-%Y %H:%i:%s") BETWEEN "' . $min_date . '" AND "' . $max_date . '"', null, false);
        }
        $this->db->where($condition)->group_by('xin_make_payment.make_payment_id')->order_by('xin_make_payment.payment_date','DESC')
        ;

        $query = $this->db->get();
        return $query;

    }
    public function get_all_payment_months() {
        $root_id   = $_SESSION['root_id'];
        $condition = "xin_make_payment.root_id ='".$root_id."' ";
        $this->db->select('xin_make_payment.payment_date');
        $this->db->from('xin_make_payment')->group_by('xin_make_payment.payment_date')->order_by('xin_make_payment.payment_date','DESC');
        $query = $this->db->get();
        return $query;

    }

    // get payslips of single employee
    public function get_payroll_slip($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_make_payment where employee_id='".$id."' and root_id='".$root_id."' ");
    }

    // get hourly wages
    public function get_hourly_wages()
    {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_hourly_templates');
        $this->db->where($condition);
        return $query = $this->db->get();
    }

    public function read_template_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "salary_template_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_salary_templates');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_salary_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "emp_id =" . "'" . $id . "' and root_id='".$root_id."' ORDER BY ABS(DATEDIFF(NOW(), active_date)), salary_id DESC";
        $this->db->select('*');
        $this->db->from('xin_employee_salary');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function get_previous_encashments($employee_id){
        $this->db->select('COALESCE(SUM(amount), 0) as total_payments');
        $this->db->from('gratuity_encashment_details');
        $this->db->where('employee_id', $employee_id);
        $query = $this->db->get();
        return $query->row()->total_payments;
    }
    public function read_salary_information_by_date($id, $p_date) {
        if (empty($p_date)) {
            $p_date = date('Y-m-d');
        } else {
            $p_date .= '-01'; // Add day "01" to the end of the date
        }
        $root_id = $_SESSION['root_id'];

        $this->db->select('*');
        $this->db->from('xin_employee_salary');
        $this->db->where('emp_id', $id);
        $this->db->where('root_id', $root_id);
        $this->db->where('active_date <=', $p_date);
        $this->db->order_by('active_date', 'DESC'); // Order by nearest active date in past
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            $this->db->select('*');
            $this->db->from('xin_employee_salary');
            $this->db->where('emp_id', $id);
            $this->db->where('root_id', $root_id);
            $this->db->order_by('active_date', 'DESC'); // Order by nearest active date in past
            $this->db->limit(1);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->result();
            } else {

                return null;
            }
        }
    }


    public function read_hourly_wage_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "hourly_rate_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_hourly_templates');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_make_payment_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "make_payment_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_make_payment');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }


    // Function to add record in table
    public function add_template($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_salary_templates', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_hourly_wages($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_hourly_templates', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_monthly_payment_payslip($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_make_payment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_hourly_payment_payslip($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_make_payment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_template_record($id){
        $this->db->where('salary_template_id', $id);
        $this->db->delete('xin_salary_templates');

    }
    public function delete_payment_record($id){
        $this->db->where('make_payment_id', $id);
        $this->db->delete('xin_make_payment');

    }

    // Function to Delete selected record from table
    public function delete_hourly_wage_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "hourly_rate_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_hourly_templates');

    }

    // Function to update record in table
    public function update_template_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "salary_template_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_salary_templates',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get all hourly templates
    public function all_hourly_templates()
    {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_hourly_templates where root_id = '".$root_id."' ");
        return $query->result();
    }

    // get all salary tempaltes > payroll templates
    public function all_salary_templates()
    {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_salary_templates where root_id = '".$root_id."' ");
        return $query->result();
    }

    // Function to update record in table
    public function update_hourly_wages_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "hourly_rate_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_hourly_templates',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > manage salary
    public function update_salary_template($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > empty grade status
    public function update_empty_salary_template($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > set hourly grade
    public function update_hourlygrade_salary_template($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > set monthly grade
    public function update_monthlygrade_salary_template($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > zero hourly grade
    public function update_hourlygrade_zero($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > zero monthly grade
    public function update_monthlygrade_zero($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "user_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    public function read_make_payment_payslip_check($employee_id,$p_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $employee_id . "' and payment_date =" . "'" . $p_date . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_make_payment');
        $this->db->where($condition);
        $this->db->limit(10000);
        return $query = $this->db->get();

        //return $query->result();
    }

    public function read_make_payment_payslip($employee_id,$p_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $employee_id . "' and payment_date =" . "'" . $p_date . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_make_payment');
        $this->db->where($condition);
        $this->db->limit(10000);
        $query = $this->db->get();

        return $query->result();
    }

    public function total_hours_worked_payslip($id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date like '%".$attendance_date."%' and root_id = '".$root_id."' ");
    }

    // Function to add record in table > advance salary
    public function add_advance_salary_payroll($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_advance_salaries', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > loan
    public function add_loan_payroll($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_loans', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get advance salaries > all employee
    public function get_advance_salaries() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' order by created_at desc";
        $this->db->select('*');
        $this->db->from('xin_advance_salaries');
        $this->db->where($condition);
        return $this->db->get();
    }

    // get loans > all employee
    public function get_loans() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' order by created_at desc";
        $this->db->select('*');
        $this->db->from('xin_loans');
        $this->db->where($condition);
        return $this->db->get();
    }

    public function read_advance_salary_info($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "advance_salary_id =" . "'" . $id . "' and root_id ='" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_advance_salaries');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_loan_info($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "loan_id =" . "'" . $id . "' and root_id ='" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_loans');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function updated_advance_salary_payroll($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and advance_salary_id='".$id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_advance_salaries',$data)) {
            return true;
        } else {
            return false;
        }
    }

    public function updated_loan_payroll($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and loan_id='".$id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_loans',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > deduction of loan
    public function updated_loan_paid_amount($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' and employee_id='".$id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_loans',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > deduction of advance salary
    public function updated_advance_salary_paid_amount($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' and employee_id='".$id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_advance_salaries',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_monthly_payment_payslip($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='".$root_id."' and make_payment_id='".$id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_make_payment',$data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_advance_salary_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and advance_salary_id='".$id."'";
        $this->db->where($condition);
        $this->db->delete('xin_advance_salaries');

    }

    public function delete_loan_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id ='" . $root_id . "' and loan_id='".$id."'";
        $this->db->where($condition);
        $this->db->delete('xin_loans');

    }

    public function advance_salary_by_employee_id($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and status = '1' and root_id ='".$root_id."' order by advance_salary_id desc";
        $this->db->select('*');
        $this->db->from('xin_advance_salaries');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function loan_by_employee_id($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and status = '1' and root_id ='".$root_id."' order by loan_id desc";
        $this->db->select('*');
        $this->db->from('xin_loans');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function get_loan_balance($employee_id)
    {
        $root_id = $_SESSION['root_id'];

        $this->db->select_sum('advance_amount');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('root_id', $root_id);
        $this->db->where('status', 1);
        $advance_amount = $this->db->get('xin_loans')->row()->advance_amount;

        $this->db->select_sum('total_paid');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('root_id', $root_id);
        $this->db->where('status', 1);
        $paid_amount = $this->db->get('xin_loans')->row()->total_paid;

        if ($advance_amount !== null && $paid_amount !== null) {
            return $advance_amount - $paid_amount;
        } else {
            return 0;
        }
    }

    public function get_paid_salary_by_employee_id($id) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT advance_salary_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and root_id='".$root_id."' and employee_id='".$id."' group by employee_id ");
        return $query->result();
    }

    public function get_paid_loan_by_employee_id($id) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT loan_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_loans`.advance_amount) AS advance_amount FROM `xin_loans` where status=1 and root_id='".$root_id."' and employee_id='".$id."' group by employee_id ");
        return $query->result();
    }

    // get advance salaries report > view all
    public function advance_salaries_report_view($id) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT advance_salary_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id='".$id."' and root_id='".$root_id."' group by employee_id");
        return $query->result();
    }

    // get advance salaries report > view all
    public function loan_report_view($id) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT loan_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_loans`.advance_amount) AS advance_amount FROM `xin_loans` where status=1 and employee_id='".$id."' and root_id='".$root_id."' group by employee_id");
        return $query->result();
    }

    // get request date details > advance salary
    public function requested_date_details($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from `xin_advance_salaries` where employee_id='".$id."' and status=1 and root_id='".$root_id."'");
    }

    // get request loan date details > loan
    public function requested_loan_date_details($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from `xin_loans` where employee_id='".$id."' and status=1 and root_id='".$root_id."'");
    }

    // get advance salaries > single employee
    public function get_advance_salaries_single($id) {
        $root_id   = $_SESSION['root_id'];
        return $this->db->query("SELECT * from xin_advance_salaries where employee_id='".$id."' and root_id = '".$root_id."' ");
    }

    // get advance salaries > single employee
    public function get_loan_single($id) {
        $root_id   = $_SESSION['root_id'];
        return $this->db->query("SELECT * from xin_loans where employee_id='".$id."' and root_id = '".$root_id."' ");
    }

    // get advance salaries report
    public function get_advance_salaries_report() {
        $root_id   = $_SESSION['root_id'];
        return $this->db->query("SELECT advance_salary_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and root_id = '".$root_id."' group by employee_id");
    }

    // get loan report
    public function get_loan_report() {
        $root_id   = $_SESSION['root_id'];
        return $this->db->query("SELECT loan_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_loans`.advance_amount) AS advance_amount FROM `xin_loans` where status=1 and root_id = '".$root_id."' group by employee_id");
    }

    // get advance salaries report >> single employee > current user
    public function advance_salaries_report_single($id) {
        $root_id   = $_SESSION['root_id'];
        return $this->db->query("SELECT advance_salary_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id='".$id."' and root_id = '".$root_id."' group by employee_id");
    }

    // get advance salaries report >> single employee > current user
    public function loan_report_single($id) {
        $root_id   = $_SESSION['root_id'];
        return $this->db->query("SELECT loan_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_loans`.advance_amount) AS advance_amount FROM `xin_loans` where status=1 and employee_id='".$id."' and root_id = '".$root_id."' group by employee_id");
    }
    public function get_expenses_by_month_user($employee,$year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id   = $_SESSION['root_id'];

        $this->db->select_sum('amount')
            ->where("purchase_date >= '$start_date' AND purchase_date <= '$end_date'")
            ->where('root_id', $root_id)
            ->group_start()

            ->where('status',1)
//            ->or_where('status', 2)
            ->group_end()
            ->where('employee_id',$employee);

        $result = $this->db->get('xin_expenses')->row();

        if ($result->amount !== null) {
            return $result->amount;
        } else {
            return 0;
        }
    }
    public function get_tickets_by_month_user($employee,$year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id   = $_SESSION['root_id'];

        $this->db->select_sum('amount')
            ->where("ticket_date >= '$start_date' AND ticket_date <= '$end_date'")
            ->where('root_id', $root_id)
            ->group_start()

            ->where('status',1)
//            ->or_where('status', 2)
            ->group_end()
            ->where('employee_id',$employee);

        $result = $this->db->get('flight_tickets')->row();

        if ($result->amount !== null) {
            return $result->amount;
        } else {
            return 0;
        }
    }
    public function get_leave_salary_by_month_user($employee,$year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id   = $_SESSION['root_id'];

        $this->db->select_sum('amount')
            ->where("paid_date >= '$start_date' AND paid_date <= '$end_date'")
            ->where('root_id', $root_id)
            ->group_start()

            ->where('status',2)
            ->group_end()
            ->where('employee_id',$employee);

        $result = $this->db->get('leave_salary')->row();

        if ($result->amount !== null) {
            return $result->amount;
        } else {
            return 0;
        }
    }
    public function get_leave_salarydays_by_month_user($employee,$year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id   = $_SESSION['root_id'];

        $this->db->select_sum('leave_days')
            ->where("paid_date >= '$start_date' AND paid_date <= '$end_date'")
            ->where('root_id', $root_id)
            ->group_start()

            ->where('status',2)
            ->group_end()
            ->where('employee_id',$employee);

        $result = $this->db->get('leave_salary')->row();

        if ($result->leave_days !== null) {
            return $result->leave_days;
        } else {
            return 0;
        }
    }
    public function read_bank_account_information_user($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $id . "' and root_id='".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_employee_bankaccount');
        $this->db->where($condition);
        $this->db->where('is_primary',1);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }


    public function get_all_expenses_by_month_user($employee,$year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id   = $_SESSION['root_id'];

        $this->db->select('xin_expenses.*,xin_expense_type.name as type_name')
            ->where("purchase_date >= '$start_date' AND purchase_date <= '$end_date'")
            ->where('xin_expenses.root_id', $root_id)
            ->where('xin_expenses.status',1)
            ->where('xin_expenses.employee_id',$employee)
            ->from('xin_expenses')
            ->join('xin_expense_type','xin_expense_type.expense_type_id=xin_expenses.expense_type_id','left');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

}
?>