<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class timesheet_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get office shifts
    public function get_office_shifts() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_office_shift');
        $this->db->where($condition);
        return $this->db->get();
    }

    // get all tasks
    public function get_tasks() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_tasks');
        $this->db->where($condition);
        return $this->db->get();
    }

    // check if check-in available
    public function attendance_first_in_check($employee_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $employee_id . "' and attendance_date =" . "'" . $attendance_date . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    // get user attendance
    public function attendance_time_check($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "employee_id =" . "'" . $employee_id . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // check if check-in available
    public function attendance_first_in($employee_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        //$condition = "employee_id =" . "'" . $employee_id . "' and attendance_date =" . "'" . $attendance_date . "'";
        $condition = array('employee_id' => $employee_id, 'attendance_date' => $attendance_date, 'root_id' => $root_id);
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // check if check-out available
    public function attendance_first_out_check($employee_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $this->db->order_by("time_attendance_id","desc");
        $condition = "employee_id =" . "'" . $employee_id . "' and attendance_date =" . "'" . $attendance_date . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);

        $this->db->limit(1);
        return $query = $this->db->get();
    }

    // get leave types
    public function all_leave_types() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_leave_type');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->result();
    }

    // check if check-out available
    public function attendance_first_out($employee_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $this->db->order_by("time_attendance_id","desc");
        $condition = array('employee_id' => $employee_id, 'attendance_date' => $attendance_date, 'root_id' => $root_id);
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);

        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get total hours work > attendance
    public function total_hours_worked_attendance($id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date='".$attendance_date."' and total_work!='' and root_id = '".$root_id."'");
    }

    // get total rest > attendance
    public function total_rest_attendance($id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date='".$attendance_date."' and total_rest!='' and root_id = '".$root_id."' ");
    }

    // check if holiday available
    public function holiday_date_check($attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id = '".$root_id."' and (start_date between start_date and end_date) or (start_date = '".$attendance_date."' or end_date = '".$attendance_date."')";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);

        $this->db->limit(1);
        return $query = $this->db->get();
    }

    // get all leaves
    public function get_leaves() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "' order by created_at desc";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);
        return $this->db->get();
    }
    public function get_all_leave_salaries() {
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_salary.root_id =" . "'" . $root_id . "' order by leave_salary.created_at desc";
        $this->db->select('leave_salary.*,annual_leave.start_date,annual_leave.end_date,xin_departments.department_name');
        $this->db->from('leave_salary')
            ->join('annual_leave','annual_leave.id=leave_salary.annual_leave_id','left');
        ;
        $this->db->join('xin_employees','xin_employees.user_id=leave_salary.employee_id','left');
        $this->db->join('xin_departments','xin_departments.department_id=xin_employees.department_id','left');

        $this->db->where($condition);
        return $this->db->get();
    }
    public function get_leaves_for_approval($designation_id) {
        $root_id   = $_SESSION['root_id'];
        $this->db->select('xin_leave_applications.*');
        $this->db->from('xin_leave_applications')
            ->join('xin_employees','xin_employees.user_id=xin_leave_applications.employee_id','left');
        $this->db->where('xin_leave_applications.root_id',$root_id);
        $this->db->where('xin_employees.reporting_to',$designation_id);
        $this->db->order_by('xin_leave_applications.leave_id','DESC');
        ;
        return $this->db->get();

    }

    // get all employee leaves
    public function get_employee_leaves($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_leave_applications where employee_id='".$id."' and root_id='".$root_id."' ");
    }

    // check if holiday available
    public function holiday_date($attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id = '".$root_id."' and (start_date between start_date and end_date) or (start_date = '".$attendance_date."' or end_date = '".$attendance_date."')";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);

        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get all holidays
    public function get_holidays() {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id =" . "'" . $root_id . "'";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);
        return $this->db->get();
    }

    // check if leave available
    public function leave_date_check($emp_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id = '".$root_id."' and (from_date between from_date and to_date) and employee_id = '".$emp_id."' or from_date = '".$attendance_date."' and to_date = '".$attendance_date."'";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);

        $this->db->limit(1);
        return $query = $this->db->get();
    }

    // check if leave available
    public function leave_date($emp_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id = '".$root_id."' and (from_date between from_date and to_date) and employee_id = '".$emp_id."' or from_date = '".$attendance_date."' and to_date = '".$attendance_date."'";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);

        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get total number of leave > employee
    public function count_total_leaves($leave_type_id,$employee_id,$year) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_leave_applications where employee_id = '".$employee_id."' and leave_type_id='".$leave_type_id."' and root_id='".$root_id."' and YEAR(from_date)='".$year."' and status='2'");
        $result = $query->result();
        $date_diff = 0;
        foreach( $result as $row )
        {
            //access columns as $row->column_name
            $date1 = $row->from_date;
            $date2 = $row->to_date;

            $diff = abs(strtotime($date2) - strtotime($date1));
            $date_diff+= ($diff/ (3600*24))+1;
        }
        return $date_diff;
    }
    public function count_total_annual_leaves_user($employee_id,$year) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from annual_leave where employee_id = '".$employee_id."' and root_id='".$root_id."' and YEAR(start_date)='".$year."' and status='2'");
        $result = $query->result();
        $date_diff = 0;
        foreach( $result as $row )
        {
            //access columns as $row->column_name
            $date1 = $row->start_date;
            $date2 = $row->end_date;

            $diff = abs(strtotime($date2) - strtotime($date1));
            $date_diff+= ($diff/ (3600*24))+1;
        }
        return $date_diff;
    }
    public function getLatestAnnualLeaveForEmployee($employee_id)
    {        $root_id   = $_SESSION['root_id'];

        $this->db->select('*');

        $this->db->from('annual_leave');
        $this->db->where('root_id', $root_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('status', 2);
//        $this->db->where('end_date', "(SELECT end_date FROM annual_leave ORDER BY end_date DESC LIMIT 1)", FALSE);
        $this->db->order_by('end_date','DESC');
        $query = $this->db->get();
        return $query->result();
    }


    // get total number of leave > employee
    public function count_total_un_paid_leaves($employee_id) {
        $root_id   = $_SESSION['root_id'];
        $query = $this->db->query("SELECT * from xin_leave_applications where employee_id = '".$employee_id."' and root_id='".$root_id."' and status='2'  ");
        $result = $query->result();
        $date_diff = 0;
        foreach( $result as $row )
        {
            //access columns as $row->column_name
            $date1 = $row->from_date;
            $date2 = $row->to_date;

            $diff = abs(strtotime($date2) - strtotime($date1));
            $date_diff+= ($diff/ (3600*24))+1;

        }
        return $date_diff;
    }

    public function count_all_unpaid_leaves($employee_id){
        $root_id   = $_SESSION['root_id'];

        $this->db->select('DATEDIFF(xin_leave_applications.to_date, xin_leave_applications.from_date) AS leave_days');
        $this->db->from('xin_leave_applications')
            ->join('xin_leave_type','xin_leave_type.leave_type_id=xin_leave_applications.leave_type_id');
        $this->db->where('xin_leave_applications.employee_id', $employee_id);
        $this->db->where('xin_leave_applications.root_id', $root_id);
        $this->db->where('xin_leave_applications.status', 2);
        $this->db->where('xin_leave_type.type_name',"Unpaid Leave");
        $query = $this->db->get();

        $result = $query->row();
        if($result) {
            $leave_days = $result->leave_days;
            return $leave_days;
        }
        else{
            return 0;
        }
    }
    public function get_leave_days_month($employee_id, $year_month, $type_id) {
        $root_id = $_SESSION['root_id'];

        $query = $this->db->query("
        SELECT SUM(
            DATEDIFF(
                LEAST(LAST_DAY('$year_month-01'), to_date),
                GREATEST('$year_month-01', from_date)
            ) + 1
        ) AS leave_days
        FROM xin_leave_applications
        WHERE (
            (YEAR(from_date) = YEAR('$year_month-01') AND MONTH(from_date) = MONTH('$year_month-01'))
            OR
            (YEAR(to_date) = YEAR('$year_month-01') AND MONTH(to_date) = MONTH('$year_month-01'))
            OR
            (
                from_date < '$year_month-01' AND to_date > LAST_DAY('$year_month-01')
            )
        )
        AND employee_id = $employee_id AND status = 2 AND root_id = $root_id AND leave_type_id = $type_id
    ");
        return $query->row()->leave_days;
    }

    public function check_annual_leaves_for_employee($employee_id, $year_month)
    {
        $root_id = $_SESSION['root_id'];

        $query = $this->db->query("
    SELECT SUM(
        DATEDIFF(
            LEAST(LAST_DAY('$year_month-01'), end_date),
            GREATEST('$year_month-01', start_date)
        ) + 1
    ) AS leave_days
    FROM annual_leave
    WHERE (YEAR(start_date) = YEAR('$year_month-01') AND MONTH(start_date) = MONTH('$year_month-01')
        OR YEAR(end_date) = YEAR('$year_month-01') AND MONTH(end_date) = MONTH('$year_month-01'))
        AND employee_id = $employee_id AND root_id = $root_id
        AND status = 2
");

        return $query->row()->leave_days;
    }
 public function check_annual_leaves_for_year($employee_id)
    {
        $root_id = $_SESSION['root_id'];

        $year = date('Y'); // get the current year
        $query = $this->db->query("
    SELECT SUM(
        DATEDIFF(
            LEAST(LAST_DAY(CONCAT('$year','-12-01')), end_date),
            GREATEST(CONCAT('$year','-01-01'), start_date)
        ) + 1
    ) AS leave_days
    FROM annual_leave
    WHERE (YEAR(start_date) = $year OR YEAR(end_date) = $year)
        AND employee_id = $employee_id 
        AND status = 2 
        AND root_id = $root_id
");
        return $query->row()->leave_days;

    }


        public function count_total_annual_leaves() {
        $root_id   = $_SESSION['root_id'];
        //$condition = "setting_id =" . "'" . $id . "' and root_id='".$root_id."' ";
        $condition = "root_id='".$root_id."' ";
        $this->db->select('annual_leave_count');
        $this->db->from('xin_system_setting');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
 public function check_leave_salarywith_annual_leave($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "root_id='".$root_id."' ";
        $this->db->select('*');
        $this->db->from('leave_salary');
        $this->db->where($condition);
        $this->db->where('annual_leave_id',$id);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // update unpaid leaves
    public function update_total_un_paid_leaves($id,$limit=NULL){
        $root_id   = $_SESSION['root_id'];
        $data = array('pay_check' => '1');
        $condition = "employee_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if( $this->db->update('xin_leave_applications',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_previous_un_paid_leaves($id,$limit=NULL){
        $root_id   = $_SESSION['root_id'];
        $data = array('pay_check' => '0');
        $condition = "employee_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if( $this->db->update('xin_leave_applications',$data)) {
            return true;
        } else {
            return false;
        }
    }


    // get payroll templates > NOT USED
    public function attendance_employee_with_date($emp_id,$attendance_date) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_attendance_time where attendance_date = '".$attendance_date."' and employee_id = '".$emp_id."' ");
    }

    // get record of office shift > by id
    public function read_office_shift_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "(office_shift_id ='".$id."' || default_shift=1) and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_office_shift');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get record of leave > by id
    public function read_leave_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_id =" . "'" . $id . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function read_leave_salary_info($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_salary.id =" . "'" . $id . "' and leave_salary.root_id = '".$root_id."' ";
        $this->db->select('leave_salary.*,annual_leave.start_date,annual_leave.end_date');
        $this->db->from('leave_salary')
            ->join('annual_leave','annual_leave.id=leave_salary.annual_leave_id','left');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function read_annual_leave_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('annual_leave');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }
    public function reduce_compoffs($employee_id,$daysToReduce){
        $this->db->where('employee_id', $employee_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('comp_off');
        $rows = $query->result();

// Reduce the days for each row
        foreach ($rows as $row) {
            if ($daysToReduce <= 0) {
                break;
            }

            $currentDays = $row->leave_no;
            $reduction = min($currentDays, $daysToReduce);
            $daysToReduce -= $reduction;

            // Update the days for the current row
            $this->db->set('leave_no', $currentDays - $reduction)
                ->where('id', $row->id)
                ->update('comp_off');
        }
        return true;
    }
    // get leave type by id
    public function read_leave_type_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_type_id =" . "'" . $id . "' and root_id = '".$root_id."' ";
        $this->db->select('*');
        $this->db->from('xin_leave_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // Function to add record in table
    public function add_employee_attendance($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_attendance_time', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_leave_record($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_leave_applications', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_task_record($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_tasks', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_office_shift_record($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_office_shift', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_holiday_record($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_holidays', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get record of task by id
    public function read_task_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "task_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_tasks');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get record of holiday by id
    public function read_holiday_information($id) {
        $root_id   = $_SESSION['root_id'];
        $condition = "holiday_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // get record of attendance by id
    public function read_attendance_information($id) {

        $root_id   = $_SESSION['root_id'];
        $condition = "time_attendance_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }

    // Function to Delete selected record from table
    public function delete_attendance_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "time_attendance_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_attendance_time');

    }

    // Function to Delete selected record from table
    public function delete_task_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "task_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_tasks');

    }

    // Function to Delete selected record from table
    public function delete_holiday_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "holiday_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_holidays');

    }

    // Function to Delete selected record from table
    public function delete_shift_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "office_shift_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_office_shift');

    }

    // Function to Delete selected record from table
    public function delete_leave_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_leave_applications');

    }
    public function delete_annual_leave_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('annual_leave');
        $this->db->where('annual_leave_id',$id)->delete('leave_salary');
        return true;
    }

    // Function to update record in table
    public function update_task_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "task_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_tasks',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_leave_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "leave_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_leave_applications',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_leave_salary_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('leave_salary',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_annual_leave_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('annual_leave',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_holiday_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "holiday_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_holidays',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_attendance_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "time_attendance_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_attendance_time',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_shift_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "office_shift_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_office_shift',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_default_shift_record($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "office_shift_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_office_shift',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_default_shift_zero($data){
        $root_id   = $_SESSION['root_id'];
        $condition = "office_shift_id!='' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_office_shift',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function assign_task_user($data, $id){
        $root_id   = $_SESSION['root_id'];
        $condition = "task_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_tasks',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get comments
    public function get_comments($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_tasks_comments where task_id = '".$id."' and root_id='".$root_id."' ");
    }

    // get comments
    public function get_attachments($id) {
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * from xin_tasks_attachment where task_id = '".$id."' and root_id='".$root_id."' ");
    }

    // Function to add record in table > add comment
    public function add_comment($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_tasks_comments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_comment_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "comment_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_tasks_comments');

    }

    // Function to Delete selected record from table
    public function delete_attachment_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "task_attachment_id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('xin_tasks_attachment');

    }
    public function delete_leave_salary_record($id){
        $root_id   = $_SESSION['root_id'];
        $condition = "id =" . "'" . $id . "' and root_id = '".$root_id."'";
        $this->db->where($condition);
        $this->db->delete('leave_salary');
        return true;

    }

    // Function to add record in table > add attachment
    public function add_new_attachment($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_tasks_attachment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // check user attendance
    public function check_user_attendance() {
        $today_date = date('Y-m-d');
        $session = $this->session->userdata('username');
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$session['user_id']."' and `attendance_date` = '".$today_date."' and root_id='".$root_id."' order by time_attendance_id desc limit 1");
    }

    // check user attendance
    public function check_user_attendance_clockout() {
        $today_date = date('Y-m-d');
        $session = $this->session->userdata('username');
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$session['user_id']."' and `attendance_date` = '".$today_date."' and clock_out='' and root_id='".$root_id."' order by time_attendance_id desc limit 1");
    }

    //  set clock in- attendance > user
    public function add_new_attendance($data){
        $root_id   = $_SESSION['root_id'];
        $data['root_id'] = $root_id;
        $this->db->insert('xin_attendance_time', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get last user attendance
    public function get_last_user_attendance() {
        $root_id   = $_SESSION['root_id'];
        $session = $this->session->userdata('username');
        $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$session['user_id']."' and root_id='".$root_id."' order by time_attendance_id desc limit 1");
        return $query->result();
    }

    // get last user attendance > check if loged in-
    public function attendance_time_checks($id) {

        $session = $this->session->userdata('username');
        $root_id   = $_SESSION['root_id'];
        return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$id."' and clock_out = '' and root_id='".$root_id."' order by time_attendance_id desc limit 1");
    }

    // Function to update record in table > update attendace.
    public function update_attendance_clockedout($data,$id){
        $root_id   = $_SESSION['root_id'];
        $condition = "time_attendance_id!='' and root_id = '".$root_id."' and time_attendance_id='".$id."'";
        $this->db->where($condition);
        if( $this->db->update('xin_attendance_time',$data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_all_expenses_user($employee, $year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id = $_SESSION['root_id'];

        $this->db->set('status', 2)
            ->where("purchase_date >= '$start_date' AND purchase_date <= '$end_date'")
            ->where('root_id', $root_id)
            ->where('status', 1)
            ->where('employee_id', $employee);

        if ($this->db->update('xin_expenses')) {
            return true;
        } else {
            return false;
        }
    }
    public function update_all_prev_expenses_user($employee, $year_month) {
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        $root_id = $_SESSION['root_id'];

        $this->db->set('status', 1)
            ->where("purchase_date >= '$start_date' AND purchase_date <= '$end_date'")
            ->where('root_id', $root_id)
            ->where('status', 2)
            ->where('employee_id', $employee);

        if ($this->db->update('xin_expenses')) {
            return true;
        } else {
            return false;
        }
    }
}
?>