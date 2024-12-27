<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends MY_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        //load the models
        $this->load->model("Employees_model");
        $this->load->model("Xin_model");
        $this->load->model("Department_model");
        $this->load->model("Designation_model");
        $this->load->model("Roles_model");
        $this->load->model("Location_model");
        $this->load->model("Payroll_model");
        $this->load->model("Package_model");
        $this->load->model("Tickets_model");
    }

    /*Function to set JSON output*/
    public function output($Return=array()){
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }

    public function index()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/employee_sheet.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/employee_sheet.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {
            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
            $date_str = trim($data['doj']);//'17-July-23'; // or '22-03-23'

// Define regular expressions to match the input date string against
            $dmy_regex = '/^(0?[1-9]|[1-2][0-9]|3[0-1])-(0?[1-9]|1[0-2])-\d{2}$/';
            $jfy_regex = '/^(0?[1-9]|[1-2][0-9]|3[0-1])-[a-zA-Z]+-\d{2}$/';

// Check if the input date string matches the "d-m-y" format
            if (preg_match('/[a-zA-Z]/', $date_str)) {
                $date = DateTime::createFromFormat("j-F-y", $date_str);
            } else  {
                // Check if the input date string matches the "j-F-y" format
                $date = DateTime::createFromFormat("d-m-y", $date_str);
            }

// Create a DateTime object from the string

// Format the date in the desired format
            if($date)
                $doj = $date->format('Y-m-d');
            else {
                var_dump($date_str);
                die;
            }
//            echo $doj;
//            continue;
// Output the formatted date
            // Insert employee data into the employees table
            $employee_data = array(
                'employee_id' => $data['employee_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'username' => $data['email'],
                'date_of_joining' => $doj,
                'passport_no' => $data['passport#'],
                'visa_no' => $data['visa#'],
                'emirates_id' => $data['emirates_id'],
                'root_id'=>$root_id,
                'user_role_id'=>'1033',
                'is_active'=>1,
                'created_at' => date('d-m-Y'),
                'passport_expiry'=>$data['passport_expiry_date'],
                'visa_expiry'=>$data['visa_expiry_date'],
                'emirates_id_expiry'=>$data['emirates_id_expiry'],
            );
            $this->db->insert('xin_employees', $employee_data);
            $emp_id=$this->db->insert_id();
            // Insert department data into the departments table if it doesn't exist
            $department_data = array(
                'root_id'=>$root_id,
                'employee_id'=>$emp_id,
                'added_by' => $this->session->userdata('user_id'),
                'created_at'=>date('d-m-Y'),
                'status'=>1,
                'location_id'=>170,
                'department_name' => $data['department']
            );
            $dep_exists=$this->Department_model->check_dep_exist($data['department']);
            if($dep_exists){
                $department_id =$dep_exists[0]->department_id;
            }else{
                $this->db->insert('xin_departments', $department_data);
                $department_id = $this->db->insert_id();

            }

            // Insert designation data into the designations table if it doesn't exist
            $designation_data = array(
                'root_id'=>$root_id,
                'department_id'=>$department_id,
                'added_by' => $this->session->userdata('user_id'),
                'created_at'=>date('d-m-Y'),
                'status'=>1,
                'designation_name' => $data['designation']
            );
            $desi_exists=$this->Designation_model->check_designation($department_id,$data['designation']);
            if($desi_exists){
                $designation_id =$desi_exists[0]->designation_id;
            }
            else
            {
                $this->db->insert('xin_designations', $designation_data);
                $designation_id = $this->db->insert_id();
            }
            $company_data = $this->Xin_model->get_company_by_department($department_id);
            $company_id = $company_data[0]->company_id;

            // Update the employee record with the department and designation ids
            $this->db->set('department_id', $department_id);
            $this->db->set('designation_id', $designation_id);
            $this->db->set('company_id', $company_id);
            $this->db->where('employee_id', $data['employee_id']);
            $this->db->update('xin_employees');
            $basic_sal =floatval(str_replace(',', '', $data['basic_salary']));
            $room_rent =floatval(str_replace(',', '', $data['room_rent']));
            $telephone =floatval(str_replace(',', '', $data['telephone']));
            $transport =floatval(str_replace(',', '', $data['transport']));
            $others =floatval(str_replace(',', '', $data['other']));
            $overtime =floatval(str_replace(',', '', $data['OVERTIME CALCULATION']));

            $salary_data=array(
                'emp_id' => $emp_id,
                'active_date' => date('y-m-d'),
                'basic_salary' =>$basic_sal,
                'overtime_rate' => $overtime,
                'house_rent_allowance' => $room_rent,
                'medical_allowance' => '',
                'travelling_allowance' => $transport,
                'telephone_allowance' => $telephone,
                'other_allowance' => $others,
                'security_deposit' => 0,
                'gross_salary' => $basic_sal,
                'total_allowance' => $room_rent+$telephone+$others+$transport,
                'total_deduction' => 0,
                'net_salary' =>floatval(str_replace(',', '',  $data['Grand Total'])),
                'added_by' => $session['user_id'],
                'created_at' => date('d-m-Y h:i:s'),


            );
            $result = $this->Employees_model->add_salary($salary_data);

        }
        echo "Import Finished";

    }
    public function import_tickets()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/ticket_sheet.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/ticket_sheet.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {
            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
//            $date = date_create_from_format('d-m-y', $data['doj']);
//            $formatted_date = date_format($date, 'Y-m-d');
            // Insert employee data into the employees table
            $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);
            if(!$user) {
                echo $data['Emp ID'];
                die;

            }
            $date_string = trim($data['Last Ticket Paid']);
            if($date_string&& $date_string!='NA') {
                $date = DateTime::createFromFormat("j-F-y", $date_string);
                if($date)
                    $formatted_date=$date->format('Y-m-d');
                else{
                    $date = DateTime::createFromFormat("d-m-y", $date_string);
                    $formatted_date=$date->format('Y-m-d');
                }

            }else{
                continue;
            }

            $ticket_data = array(
                'employee_id' => $user[0]->user_id,
                'ticket_no' => '',
                'remarks ' => $data['Remarks'],
                'status' => '1',
                'airlines' => '',
                'destination' => '',
                'ticket_date' => $formatted_date,
                'added_by' => $session['user_id'],
                'remaining_balance' => '',
                'created_date' => date('Y-m-d'),

            );

            $result = $this->Tickets_model->add_flight($ticket_data);
        }
        echo "Import Finished";

    }
    public function import_leave()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/leave_sheet1.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/leave_sheet1.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);
// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {
            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
            echo "<pre>";
            var_dump($data);
//            $date = date_create_from_format('d-m-y', $data['doj']);
//            $formatted_date = date_format($date, 'Y-m-d');
            // Insert employee data into the employees table
            $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);
            if(!$user) {
                echo $data['Emp ID'];
                die;

            }
            $date_string = str_replace(' ', '', $data['DOJ from Last Vaction']);
            if($date_string&& $date_string!='NA') {
// Convert date string to a DateTime object
                $date_obj = date_create_from_format('d-M-y', $date_string);

//               atted date
                if($date_obj) {
                    $formatted_date = $date_obj->format('Y-m-d');
                    $thirty_days_before = $date_obj->modify('-30 days')->format('Y-m-d');
                }else{
                    $date_obj = date_create_from_format('d-m-y', $date_string);
                    $formatted_date = $date_obj->format('Y-m-d');
                    $thirty_days_before = $date_obj->modify('-30 days')->format('Y-m-d');

                }
                echo $date_string;
            }else{
                continue;
            }

            $data = array(

                'employee_id' => $user[0]->user_id,
                'start_date' => $thirty_days_before,
                'end_date' => $formatted_date,
                'applied_on' => date('Y-m-d h:i:s'),
                'remarks' => '',
                'status' => '2',
            );
            $result = $this->Employees_model->add_annual_leave($data);
        }
        echo "Import Finished";

    }
    public function gratuity_import()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/gratuity_sheet.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/gratuity_sheet.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {

            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
            $date = date_create_from_format('d-m-y', $data['Paid Date']);
//            $formatted_date = date_format($date, 'Y-m-d');
            // Insert employee data into the employees table
            $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);
            if(!$user) {
                echo $data['Emp ID'];
                die;

            }
            $date_string = $data['Paid Date'];
            if($date_string&& $date_string!='LOCAL') {
                $date_obj = date_create_from_format('j-M-y', $date_string);
                if($date_obj)
                    $formatted_date = date_format($date_obj, 'Y-m-d');
                else
                    $formatted_date='';
            }else{
                $formatted_date='';
            }
            if($data[" previous_encashment "]!="LOCAL") {
                $amount = floatval(str_replace(',', '', $data[" previous_encashment "]));
                if($amount) {
                    $gratuitydata = array(
                        'employee_id' => $user[0]->user_id,
                        'amount' => $amount,
                        'paid_date' => $formatted_date,
                        'remarks ' => '',
                        'added_by' => $this->session->userdata('user_id'),

                        'created_date' => date('Y-m-d'),

                    );
                    $result = $this->Tickets_model->add_gratuity($gratuitydata);
                }
            }

        }
        echo "Import Finished";

    }
    public function bank_import()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/gratuity_sheet.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/bank_sheet.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {

            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
            // Insert employee data into the employees table
            $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);
            if(!$user) {
                echo $data['Emp ID'];
                die;

            }

            $gratuitydata = array(
                'root_id'=>$_SESSION['root_id'],
                'employee_id' => $user[0]->user_id,
                'account_number' => $data['Bank Account'],
                'bank_code' => $data['Bank Swift Code'],
                'bank_name' => $data['Bank Name'],
                'is_primary ' => 1,
                'created_at' => date('Y-m-d'),

            );
            $this->db->insert('xin_employee_bankaccount', $gratuitydata);
        }



        echo "Import Finished";

    }
    public function compoff_import()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/gratuity_sheet.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/compoff_sheet.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {

            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
            // Insert employee data into the employees table
            $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);
            if (!$user) {
                echo $data['Emp ID'];
                die;

            }
            if ($data['Comp off balance as on 31.03.2023']) {
                $gratuitydata = array(
                    'root_id' => $_SESSION['root_id'],
                    'employee_id' => $user[0]->user_id,
                    'leave_no' => $data['Comp off balance as on 31.03.2023'],
                    'status' => 1,
                    'created_date' => date('Y-m-d'),
                    'added_by'=>$_SESSION['user_id'],

                );
                $this->db->insert('comp_off', $gratuitydata);
            }
        }


        echo "Import Finished";

    }public function loan_import()
{
    $session = $this->session->userdata('username');
    if (!empty($session)) {

    } else {
        redirect('');
    }
    $csv_file = base_url() . 'uploads/excel/gratuity_sheet.csv';
    // Load CSV file into an array
    $rows = array_map('str_getcsv', file('uploads/excel/loan_sheet.csv'));

// Remove the headers from the array
    $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
    $this->load->database();
    $root_id = $_SESSION['root_id'];

// Loop through each row of data
    foreach ($rows as $row) {

        // Map the row to an associative array with column headers as keys
        $data = array_combine($headers, $row);
        // Insert employee data into the employees table
        $user = $this->Xin_model->read_user_info_by_emp_id($data['STAFF ID']);
        if (!$user) {
            echo $data['STAFF ID'];
            die;

        }
        $date_string=$data['Loan Date'];
        $date_obj = date_create_from_format('j-M-y', $date_string);
        $year_month = date_format($date_obj, 'Y-m');
        $created_at= date_format($date_obj, 'Y-m-d h:i:s');

        $convertedNumber = str_replace(',', '', $data['Loan Amount']);
        echo $convertedNumber;
        $balance = str_replace(',', '', $data['Balance as on 31.03.2023']);
        $paid=intval($convertedNumber)-intval($balance);
        $pattern = '/\d+/';  // Regular expression pattern to match one or more digits
        $matches = [];
        $string=$data['EMI '];
        preg_match($pattern, $string, $matches);

        if (!empty($matches)) {
            $number = $matches[0];
            $monthly_installment= $number; // Output: 500
        } else {
            $monthly_installment=0;
        }

        $gratuitydata = array(
            'root_id' => $_SESSION['root_id'],
            'employee_id' => $user[0]->user_id,
            'month_year' => $year_month,
            'advance_amount' => $convertedNumber,
            'created_at' =>$created_at,
            'one_time_deduct'=>0,
            'total_paid'=>$paid,
            'status'=>1,
            'monthly_installment'=>$monthly_installment,
//            'added_by' => $_SESSION['user_id'],

        );
        $this->db->insert('xin_loans', $gratuitydata);

    }


    echo "Import Finished";
}

    public function dob_import()
    {
        $session = $this->session->userdata('username');
        if (!empty($session)) {

        } else {
            redirect('');
        }
        $csv_file = base_url() . 'uploads/excel/gratuity_sheet.csv';
        // Load CSV file into an array
        $rows = array_map('str_getcsv', file('uploads/excel/dob.csv'));

// Remove the headers from the array
        $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
        $this->load->database();
        $root_id   = $_SESSION['root_id'];

// Loop through each row of data
        foreach ($rows as $row) {

            // Map the row to an associative array with column headers as keys
            $data = array_combine($headers, $row);
            // Insert employee data into the employees table
            $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);
            if (!$user) {
                echo $data['Emp ID'];
                die;

            } else {
                $date_string=$data['DOB'];
                $date_obj = date_create_from_format('j-M-y', $date_string);
                if($date_obj)
                    $formatted_date = date_format($date_obj, 'Y-m-d');
                $this->db->set('date_of_birth', $formatted_date);
                $this->db->where('xin_employees.user_id', $user[0]->user_id);
                $this->db->update('xin_employees');
                echo $this->db->last_query();
            }
        }



        echo "Import Finished";

    }
    public function create_password(){
        $employee = $this->Employees_model->get_employees();


        foreach($employee->result() as $r) {
            $data = array();
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $password = implode($pass); //turn the array into a string
            $salt = implode($pass);
            $pw_hash = sha1($salt . $password);
            $data = array();
            $data = array(
                'password' => $password,
                'sec_pass' => $pw_hash,
                'pslt' => $salt

            );

            $result = $this->Employees_model->basic_info($data,$r->user_id);
            echo $this->db->last_query();

        }


    }
 public function add_reporting(){
     $rows = array_map('str_getcsv', file('uploads/excel/employee.csv'));

// Remove the headers from the array
     $headers = array_shift($rows);

// Connect to the database using CodeIgniter's database library
     $this->load->database();
     $root_id   = $_SESSION['root_id'];


// Loop through each row of data
     foreach ($rows as $row) {
         $data = array_combine($headers, $row);
         $user = $this->Xin_model->read_user_info_by_emp_id($data['Emp ID']);

         $desi_exists=$this->Designation_model->check_designation_name($data['Department Head - 1']);
         if($desi_exists){

             $designation_id =$desi_exists[0]->designation_id;

         }
         else
         {
             echo $this->db->last_query();
             echo "This designation doesn't exist ".$data['Department Head - 1'];
             die;
         }

        $emp_data=array(

            'reporting_to' => $designation_id,

        );

         $result = $this->Employees_model->basic_info($emp_data, $user[0]->user_id);
         echo $this->db->last_query();
     }
    }
// Close the database connection
}