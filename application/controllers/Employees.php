<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends MY_Controller {

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
        $this->load->model("Timesheet_model");
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
        if(!empty($session)){

        } else {
            redirect('');
        }

        $data['title'] = $this->Xin_model->site_title();
        $data['all_departments'] = $this->Department_model->all_departments();
        $data['all_designations'] = $this->Designation_model->all_designations();
        $data['all_user_roles'] = $this->Roles_model->all_user_roles();
        $data['all_office_shifts'] = $this->Employees_model->all_office_shifts();
        $data['breadcrumbs'] = $this->lang->line('xin_employees');
        $data['path_url'] = 'employees';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('13',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("employees/employees_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    // employees directory
    public function directory() {

        $session = $this->session->userdata('username');
        if(!empty($session)){

        } else {
            redirect('');
        }

        $data['title'] = $this->Xin_model->site_title();
        $data['all_departments'] = $this->Department_model->all_departments();
        $data['all_designations'] = $this->Designation_model->all_designations();
        $data['all_user_roles'] = $this->Roles_model->all_user_roles();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = $this->lang->line('xin_employees_directory');
        $data['path_url'] = 'employees_directory';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('52',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("employees/directory", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    public function employees_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employees_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $employee = $this->Employees_model->get_employees();

        $data = array();

        foreach($employee->result() as $r) {

            // user full name
            $full_name = $r->first_name.' '.$r->last_name;
            // get status
            if($r->is_active==0): $status = '<span class="tag tag-danger">In-Active</span>';
            elseif($r->is_active==1): $status = '<span class="tag tag-success">Active</span>'; endif;
            // user role
            $role = $this->Xin_model->read_user_role_info($r->user_role_id);
            // get designation
            $designation = $this->Designation_model->read_designation_information($r->designation_id);

            // department
            $department = $this->Department_model->read_department_information($r->department_id);

            $department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
            if($department[0]->location_id) {
                $location = $this->Xin_model->read_location_info($department[0]->location_id);
                // company info
                $company = $this->Xin_model->read_company_info($location[0]->company_id);
                $company_name=$company[0]->name;
            }
            else{
                $location='';
                $company_name='';
            }

            /*
            if($r->user_role_id != '1' && $r->user_id!=23) {
                $option = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a href="employees/detail/'.$r->user_id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '"><i class="fa fa-trash-o"></i></button></span>';
            } else {
                $option = '';
            }
            */
            $option = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a href="employees/detail/'.$r->user_id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" ><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '"><i class="fa fa-trash-o"></i></button></span>';
            $data[] = array(
                $option,
                $r->employee_id,
                $full_name,
                $r->email,
                $role[0]->role_name,
                $department_designation,
                $status
            );

        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function detail() {

        $id = $this->uri->segment(3);
        $session = $this->session->userdata('username');
        $result = $this->Employees_model->read_employee_information($id);
        $salary_data = $this->Payroll_model->read_salary_information($id);
        $role_resources_ids = $this->Xin_model->user_role_resource();
        $data['breadcrumbs'] = $this->lang->line('xin_employee_details');
        $data['path_url'] = 'employees_detail';

        if(in_array('13',$role_resources_ids)) {
            if(!empty($session)){
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }

        $company_details = $this->Xin_model->get_employee_company($id);
        $ticket_balance = $this->check_ticket_balance($id);
        $data = array(
            'breadcrumbs' => $this->lang->line('xin_employee_detail').' ('.$result[0]->first_name.' '.$result[0]->last_name.')',
            'path_url' => 'employees_detail',
            'first_name' => $result[0]->first_name,
            'last_name' => $result[0]->last_name,
            'user_id' => $result[0]->user_id,
            'employee_id' => $result[0]->employee_id,
            'username' => $result[0]->username,
            'email' => $result[0]->email,
            'department_id' => $result[0]->department_id,
            'department_id' => $result[0]->department_id,
            'designation_id' => $result[0]->designation_id,
            'reporting_to' => $result[0]->reporting_to,
            'nationality' => $result[0]->nationality,
            'user_role_id' => $result[0]->user_role_id,
            'date_of_birth' => $result[0]->date_of_birth,
            'date_of_leaving' => $result[0]->date_of_leaving,
            'gender' => $result[0]->gender,
            'marital_status' => $result[0]->marital_status,
            'contact_no' => $result[0]->contact_no,
            'address' => $result[0]->address,
            'is_active' => $result[0]->is_active,
            'date_of_joining' => $result[0]->date_of_joining,
            'all_departments' => $this->Department_model->all_departments(),
            'all_designations' => $this->Designation_model->all_designations(),
            'all_user_roles' => $this->Roles_model->all_user_roles(),
            'title' => $this->Xin_model->site_title(),
            'profile_picture' => $result[0]->profile_picture,
            'facebook_link' => $result[0]->facebook_link,
            'twitter_link' => $result[0]->twitter_link,
            'blogger_link' => $result[0]->blogger_link,
            'linkdedin_link' => $result[0]->linkdedin_link,
            'google_plus_link' => $result[0]->google_plus_link,
            'instagram_link' => $result[0]->instagram_link,
            'pinterest_link' => $result[0]->pinterest_link,
            'youtube_link' => $result[0]->youtube_link,
            'emirates_id' => $result[0]->emirates_id,
            'labour_id' => $result[0]->labour_id,
            'work_permit' => $result[0]->work_permit,
            'passport_no' => $result[0]->passport_no,
            'visa_no' => $result[0]->visa_no,
            'ticket_eligibilty' => $result[0]->ticket_eligibilty,
            'gratuity_eligibilty' => $result[0]->gratuity_eligibilty,
            'overtime_eligibilty' => $result[0]->overtime_eligibilty,
            'working_hours' => $result[0]->working_hours,
            'company_country' => "UAE",
            'all_countries' => $this->Xin_model->get_countries(),
            'all_document_types' => $this->Employees_model->all_document_types(),
            'all_files' => $this->Xin_model->get_pending_employee_document_files($result[0]->user_id),
            'all_education_level' => $this->Employees_model->all_education_level(),
            'all_qualification_language' => $this->Employees_model->all_qualification_language(),
            'all_qualification_skill' => $this->Employees_model->all_qualification_skill(),
            'all_contract_types' => $this->Employees_model->all_contract_types(),
            'all_contracts' => $this->Employees_model->all_contracts(),
            'all_office_shifts' => $this->Employees_model->all_office_shifts(),
            'all_office_locations' => $this->Location_model->all_office_locations(),
            'ticket_balance'=>$ticket_balance,
            'all_employees' => $this->Xin_model->all_employees(),

        );

        if(!empty($salary_data))
        {
            $data+=array('basic_salary' => $salary_data[0]->basic_salary,
                'overtime_rate' => $salary_data[0]->overtime_rate,
                'house_rent_allowance' => $salary_data[0]->house_rent_allowance,
                'medical_allowance' => $salary_data[0]->medical_allowance,
                'travelling_allowance' => $salary_data[0]->travelling_allowance,
                'telephone_allowance' => $salary_data[0]->telephone_allowance,
                'other_allowance' => $salary_data[0]->other_allowance,
                'gross_salary' => $salary_data[0]->gross_salary,
                'total_allowance' => $salary_data[0]->total_allowance,
                'net_salary' => $salary_data[0]->net_salary,
                'active_date' => $salary_data[0]->active_date);
        }
        else
        {
            $data+=array('basic_salary' => '',
                'overtime_rate' => '',
                'house_rent_allowance' => '',
                'medical_allowance' => '',
                'travelling_allowance' => '',
                'telephone_allowance' => '',
                'other_allowance' => '',
                'gross_salary' => '',
                'total_allowance' => '',
                'net_salary' => '',
                'active_date' => date('Y-m-d'));
        }
        $data['subview'] = $this->load->view("employees/employee_detail", $data, TRUE);
        $this->load->view('layout_main', $data); //page load

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function dialog_contact() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_contact_information($id);
        $data = array(
            'contact_id' => $result[0]->contact_id,
            'employee_id' => $result[0]->employee_id,
            'relation' => $result[0]->relation,
            'is_primary' => $result[0]->is_primary,
            'is_dependent' => $result[0]->is_dependent,
            'contact_name' => $result[0]->contact_name,
            'work_phone' => $result[0]->work_phone,
            'work_phone_extension' => $result[0]->work_phone_extension,
            'mobile_phone' => $result[0]->mobile_phone,
            'home_phone' => $result[0]->home_phone,
            'work_email' => $result[0]->work_email,
            'personal_email' => $result[0]->personal_email,
            'address_1' => $result[0]->address_1,
            'address_2' => $result[0]->address_2,
            'city' => $result[0]->city,
            'state' => $result[0]->state,
            'zipcode' => $result[0]->zipcode,
            'country' => $result[0]->country,
            'all_countries' => $this->Xin_model->get_countries()
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }
    public function dialog_flight() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Tickets_model->read_flight_ticket_information($id);
        $data = array(
            'ticket_id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'destination' => $result[0]->destination,
            'ticket_date' => $result[0]->ticket_date,
            'airlines' => $result[0]->airlines,
            'ticket_no' => $result[0]->ticket_no,
            'description' => $result[0]->remarks,
            'balance' => $result[0]->remaining_balance,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('tickets/dialog_flight_ticket', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_document() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $document = $this->Employees_model->read_document_information($id);
        $data = array(
            'document_id' => $document[0]->document_id,
            'document_type_id' => $document[0]->document_type_id,
            'd_employee_id' => $document[0]->employee_id,
            'all_document_types' => $this->Employees_model->all_document_types(),
            'date_of_expiry' => $document[0]->date_of_expiry,
            'all_files' => $this->Xin_model->get_employee_document_files($document[0]->document_id),
            'title' => $document[0]->title,
            'is_alert' => $document[0]->is_alert,
            'description' => $document[0]->description,
            'notification_email' => $document[0]->notification_email,
            'document_file' => $document[0]->document_file
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function read_document() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('document_id');
        $document = $this->Employees_model->read_document_information($id);
        $data = array(
            'document_id' => $document[0]->document_id,
            'document_type_id' => $document[0]->document_type_id,
            'd_employee_id' => $document[0]->employee_id,
            'all_document_types' => $this->Employees_model->all_document_types(),
            'date_of_expiry' => $document[0]->date_of_expiry,
            'all_files' => $this->Xin_model->get_employee_document_files($document[0]->document_id),
            'title' => $document[0]->title,
            'is_alert' => $document[0]->is_alert,
            'description' => $document[0]->description
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_imgdocument() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $document = $this->Employees_model->read_imgdocument_information($id);
        $data = array(
            'immigration_id' => $document[0]->immigration_id,
            'document_type_id' => $document[0]->document_type_id,
            'd_employee_id' => $document[0]->employee_id,
            'all_document_types' => $this->Employees_model->all_document_types(),
            'all_countries' => $this->Xin_model->get_countries(),
            'document_number' => $document[0]->document_number,
            'document_file' => $document[0]->document_file,
            'issue_date' => $document[0]->issue_date,
            'expiry_date' => $document[0]->expiry_date,
            'country_id' => $document[0]->country_id,
            'eligible_review_date' => $document[0]->eligible_review_date,
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_qualification() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_qualification_information($id);
        $data = array(
            'qualification_id' => $result[0]->qualification_id,
            'employee_id' => $result[0]->employee_id,
            'name' => $result[0]->name,
            'education_level_id' => $result[0]->education_level_id,
            'from_year' => $result[0]->from_year,
            'language_id' => $result[0]->language_id,
            'to_year' => $result[0]->to_year,
            'skill_id' => $result[0]->skill_id,
            'description' => $result[0]->description,
            'all_education_level' => $this->Employees_model->all_education_level(),
            'all_qualification_language' => $this->Employees_model->all_qualification_language(),
            'all_qualification_skill' => $this->Employees_model->all_qualification_skill()
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }
    public function dialog_work_experience() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_work_experience_information($id);
        $data = array(
            'work_experience_id' => $result[0]->work_experience_id,
            'employee_id' => $result[0]->employee_id,
            'company_name' => $result[0]->company_name,
            'from_date' => $result[0]->from_date,
            'to_date' => $result[0]->to_date,
            'post' => $result[0]->post,
            'description' => $result[0]->description
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }
    public function check_ticket_balance($id=null)
    {
        $employee_id = $this->input->post('employee_id') ?? $id;
        $date = $this->input->post('date');

        $user_info = $this->Xin_model->read_user_info($employee_id);
        if($user_info[0]->ticket_eligibilty){
            if ($date)
                $result = $this->Xin_model->check_ticket_balance($employee_id, $date);
            else
                $result = $this->Xin_model->check_ticket_balance($employee_id);

            if ($result && isset($result->ticket_date) && $result->ticket_date != '0000-00-00') {
                $last_date =$result->ticket_date;

            } else {
                $last_date = $user_info[0]->date_of_joining;
            }
            if ($date)
                $current_date = new DateTime($date);
            else
                $current_date = new DateTime();
            $last_date_time = new DateTime($last_date);
            $interval = date_diff($current_date, $last_date_time);
            $days_since_doj = $interval->days;

            $unpaid_leaves = $this->Timesheet_model->count_all_unpaid_leaves($employee_id);

            if ($unpaid_leaves)
                $days_since_doj = $days_since_doj - ($unpaid_leaves); // add unpaid leaves to years of service

            $ticket_balance = $days_since_doj / 365;
            if ($result && $result->remaining_balance) {
                $ticket_balance+=$result->remaining_balance;
            }

        }else
        {
            $ticket_balance = 0;
        }
        if($id)
            return round($ticket_balance,3);
        else
            echo round($ticket_balance,3);


    }

    public function dialog_bank_account() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_bank_account_information($id);
        $data = array(
            'bankaccount_id' => $result[0]->bankaccount_id,
            'employee_id' => $result[0]->employee_id,
            'is_primary' => $result[0]->is_primary,
            'account_title' => $result[0]->account_title,
            'account_number' => $result[0]->account_number,
            'bank_name' => $result[0]->bank_name,
            'iban' => $result[0]->iban,
            'bank_code' => $result[0]->bank_code,
            'bank_branch' => $result[0]->bank_branch
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_contract() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_contract_information($id);
        $data = array(
            'contract_id' => $result[0]->contract_id,
            'employee_id' => $result[0]->employee_id,
            'contract_type_id' => $result[0]->contract_type_id,
            'from_date' => $result[0]->from_date,
            'designation_id' => $result[0]->designation_id,
            'title' => $result[0]->title,
            'to_date' => $result[0]->to_date,
            'description' => $result[0]->description,
            'all_contract_types' => $this->Employees_model->all_contract_types(),
            'all_designations' => $this->Designation_model->all_designations(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_leave() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_leave_information($id);
        $data = array(
            'leave_id' => $result[0]->leave_id,
            'employee_id' => $result[0]->employee_id,
            'contract_id' => $result[0]->contract_id,
            'casual_leave' => $result[0]->casual_leave,
            'medical_leave' => $result[0]->medical_leave
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_shift() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_emp_shift_information($id);
        $data = array(
            'emp_shift_id' => $result[0]->emp_shift_id,
            'employee_id' => $result[0]->employee_id,
            'shift_id' => $result[0]->shift_id,
            'from_date' => $result[0]->from_date,
            'to_date' => $result[0]->to_date
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    public function dialog_location() {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_location_information($id);
        $data = array(
            'office_location_id' => $result[0]->office_location_id,
            'employee_id' => $result[0]->employee_id,
            'location_id' => $result[0]->location_id,
            'from_date' => $result[0]->from_date,
            'to_date' => $result[0]->to_date
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('employees/dialog_employee_details', $data);
        } else {
            redirect('');
        }
    }

    // get departmens > designations
    public function designation() {

        $data['title'] = $this->Xin_model->site_title();
        $id = $this->uri->segment(3);

        $data = array(
            'department_id' => $id,
            'all_designations' => $this->Designation_model->all_designations(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/get_designations_2", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }
    public function reportingdesignation() {

        $data['title'] = $this->Xin_model->site_title();
        $id = $this->uri->segment(3);

        $data = array(
            'department_id' => $id,
            'all_designations' => $this->Designation_model->all_designations(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/get_reporting", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('warning_id');
        $result = $this->Warning_model->read_warning_information($id);
        $data = array(
            'warning_id' => $result[0]->warning_id,
            'warning_to' => $result[0]->warning_to,
            'warning_by' => $result[0]->warning_by,
            'warning_date' => $result[0]->warning_date,
            'warning_type_id' => $result[0]->warning_type_id,
            'subject' => $result[0]->subject,
            'description' => $result[0]->description,
            'status' => $result[0]->status,
            'all_employees' => $this->Xin_model->all_employees(),
            'all_warning_types' => $this->Warning_model->all_warning_types(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('warning/dialog_warning', $data);
        } else {
            redirect('');
        }
    }

    // Validate and add info in database
    public function add_employee() {

        if($this->input->post('add_type')=='employee') {

            $total_employees = $this->Employees_model->get_total_employees();
            $root_account    = $this->Xin_model->get_root_account();
            $package_info    = $this->Package_model->read_package_information($root_account[0]->package_id);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($total_employees>=$package_info[0]->employees) {
                $Return['error'] = "You can't add more employees because your package is full <br>Please upgrade your package for more employees.<br>Contact us: <a href='mailto:info@corbuz.com'>info@corbuz.com</a>";
            }
            else if($this->input->post('first_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_first_name');
            } else if($this->input->post('last_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_last_name');
            } else if($this->input->post('employee_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_employee_id');
            } else if(empty($this->input->post('date_of_joining'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_joining_date');
            } else if(empty($this->input->post('department_id'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_department');
            } else if(empty($this->input->post('designation_id'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_designation');
            } else if(empty($this->input->post('role'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_user_role');
            }  else if(empty($this->input->post('email'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_email');
            } else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
                $Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
            } else if(empty($this->input->post('contact_no'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_contact_number');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $password = implode($pass); //turn the array into a string
            $salt = $password;
            $pw_hash = sha1($salt.$password);


            $company_data = $this->Xin_model->get_company_by_department($this->input->post('department_id'));
            $company_id = $company_data[0]->company_id;

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'office_shift_id' => $this->input->post('office_shift_id'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'username' => $this->input->post('email'),
                'email' => $this->input->post('email'),
                'password' => $password,
                'date_of_birth' => $this->input->post('date_of_birth'),
                'gender' => $this->input->post('gender'),
                'user_role_id' => $this->input->post('role'),
                'company_id' => $company_id,
                'department_id' => $this->input->post('department_id'),
                'reporting_to' => $this->input->post('reporting_to'),
                'nationality' => $this->input->post('nationality'),
                'designation_id' => $this->input->post('designation_id'),
                'date_of_joining' => $this->input->post('date_of_joining'),
                'contact_no' => $this->input->post('contact_no'),
                'passport_no' => $this->input->post('passport_no'),
                'passport_expiry' => $this->input->post('passport_expiry'),
                'emirates_id' => $this->input->post('emirates_id'),
                'emirates_id_expiry' => $this->input->post('emirates_id_expiry'),
                'visa_no' => $this->input->post('visa_no'),
                'visa_expiry' => $this->input->post('visa_expiry'),
                'address' => $this->input->post('address'),
                'created_at' => date('d-m-Y'),
                'sec_pass' => $pw_hash,
                'pslt' => $salt

            );
            
            $result = $this->Employees_model->add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_add_employee');

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
                if($setting[0]->enable_email_notification == 'yes') {

                    // load email library
                    $this->load->library('email');
                    $this->email->set_mailtype("html");

                    //get company info
                    $cinfo = $this->Xin_model->read_company_setting_info(1);
                    //get email template
                    $template = $this->Xin_model->read_email_template(8);

                    $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
                    $logo = base_url().'uploads/logo/'.$cinfo[0]->logo;

                    // get user full name
                    $full_name = $this->input->post('first_name').' '.$this->input->post('last_name');

                    $message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var username}","{var employee_id}","{var employee_name}","{var email}","{var password}"),array($cinfo[0]->company_name,site_url(),$this->input->post('email'),$this->input->post('employee_id'),$full_name,$this->input->post('email'),$password),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

                    /*
                    $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
                    $this->email->to($this->input->post('email'));

                    $this->email->subject($subject);
                    $this->email->message($message);

                    $this->email->send();
                    */

                    require './mail/gmail.php';
                    $mail->addAddress($this->input->post('email'), $full_name);
                    $mail->Subject = $subject;
                    $mail->msgHTML($message);

                    if (!$mail->send()) {
                        //echo "Mailer Error: " . $mail->ErrorInfo;
                    } else {
                        //echo "Message sent!";
                    }
                }
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    /*  add and update employee details info */

    // Validate and update info in database // basic info
    public function basic_info() {
        if($this->input->post('type')=='basic_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('first_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_first_name');
            } else if($this->input->post('last_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_last_name');
            } else if($this->input->post('employee_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_employee_id');
            } else if(empty($this->input->post('date_of_joining'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_joining_date');
            } else if(empty($this->input->post('department_id'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_department');
            } else if(empty($this->input->post('designation_id'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_designation');
            } else if(empty($this->input->post('role'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_user_role');
            } else if(empty($this->input->post('username'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_username');
            } else if(empty($this->input->post('email'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_email');
            } else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
                $Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $company_data = $this->Xin_model->get_company_by_department($this->input->post('department_id'));
            $company_id = $company_data[0]->company_id;

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'gender' => $this->input->post('gender'),
                'user_role_id' => $this->input->post('role'),
                'marital_status' => $this->input->post('marital_status'),
                'is_active' => $this->input->post('status'),
                'company_id' => $company_id,
                'department_id' => $this->input->post('department_id'),
                'designation_id' => $this->input->post('designation_id'),
                'date_of_joining' => $this->input->post('date_of_joining'),
                'reporting_to' => $this->input->post('reporting_to'),
                'nationality' => $this->input->post('nationality'),
                'contact_no' => $this->input->post('contact_no'),
                'emirates_id' => $this->input->post('emirates_id'),
                'labour_id' => $this->input->post('labour_id'),
                'work_permit' => $this->input->post('work_permit'),
                'visa_no' => $this->input->post('visa_no'),
                'passport_no' => $this->input->post('passport_no'),
                'address' => $this->input->post('address'),
                'gratuity_eligibilty' => $this->input->post('gratuity_eligibility')??0,
                'ticket_eligibilty' => $this->input->post('ticket_eligibilty')??0,
                'overtime_eligibilty' => $this->input->post('overtime_eligibilty')??0,
                'working_hours' => $this->input->post('working_hours')??8,

            );
            $id = $this->input->post('user_id');
            $result = $this->Employees_model->basic_info($data,$id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_basic_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database // social info
    public function profile_picture() {
        if($this->input->post('type')=='profile_picture') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->input->post('user_id');

            /* Check if file uploaded..*/
            if($_FILES['p_file']['size'] == 0 && null ==$this->input->post('remove_profile_picture')) {
                $Return['error'] = $this->lang->line('xin_employee_select_picture');
            } else {
                if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {

                    //checking image type
                    $allowed =  array('png','jpg','jpeg','gif');
                    $filename = $_FILES['p_file']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                    if(in_array($ext,$allowed)){

                        $uploadedfile = $_FILES['p_file']['tmp_name'];

                        if ($ext == "jpg" || $ext == "jpeg")
                        {
                            $src = imagecreatefromjpeg($uploadedfile);
                        }
                        else if ($extension == "png")
                        {
                            $src = imagecreatefrompng($uploadedfile);
                        }
                        else
                        {
                            $src = imagecreatefromgif($uploadedfile);
                        }

                        list($width, $height) = getimagesize($uploadedfile);
                        $newwidth = 250;
                        $newheight = ($height / $width) * $newwidth;
                        $tmp = imagecreatetruecolor($newwidth, $newheight);
                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                        $uxi = rand(111, 999).round(microtime(true)).'.'.$ext;
                        $fname = $photo = "profile_" . $uxi;
                        $filename = "uploads/profile/" . $photo;
                        imagejpeg($tmp, $filename, 100);
                        imagedestroy($src);
                        imagedestroy($tmp);

                        $result2     = $this->Employees_model->read_employee_information($this->input->post('user_id'));
                        $delete_file = $result2[0]->profile_picture;

                        //UPDATE Employee info in DB
                        $data = array('profile_picture' => $fname);
                        $result = $this->Employees_model->profile_picture($data,$id);
                        if ($result == TRUE) {
                            $Return['result'] = $this->lang->line('xin_employee_picture_updated');
                            $Return['img'] = site_url('uploads/profile/'.$fname);
                            if($delete_file) {
                                if (file_exists('uploads/profile/' . $delete_file)) {
                                    unlink('uploads/profile/' . $delete_file);
                                }
                            }

                        } else {
                            $Return['error'] = $this->lang->line('xin_error_msg');
                        }
                        $this->output($Return);
                        exit;

                    } else {
                        $Return['error'] = $this->lang->line('xin_employee_picture_type');
                    }
                }
            }

            if(null!=$this->input->post('remove_profile_picture')) {
                //UPDATE Employee info in DB

                $result2     = $this->Employees_model->read_employee_information($this->input->post('user_id'));
                $delete_file = $result2[0]->profile_picture;

                if(file_exists('uploads/profile/'.$delete_file))
                {
                    unlink('uploads/profile/'.$delete_file);
                }

                $data = array('profile_picture' => 'no file');
                $row = $this->Employees_model->read_employee_information($id);
                $profile = base_url()."uploads/profile/";
                $result = $this->Employees_model->profile_picture($data,$id);
                if ($result == TRUE) {
                    $Return['result'] = $this->lang->line('xin_employee_picture_updated');
                    if($row[0]->gender=='Male') {
                        $Return['img'] = $profile.'default_male.jpg';
                    } else {
                        $Return['img'] = $profile.'default_female.jpg';
                    }
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
                $this->output($Return);
                exit;

            }

            if($Return['error']!=''){
                $this->output($Return);
            }
        }
    }

    // Validate and update info in database // basic info
    public function social_info() {

        if($this->input->post('type')=='social_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $data = array(
                'facebook_link' => $this->input->post('facebook_link'),
                'twitter_link' => $this->input->post('twitter_link'),
                'blogger_link' => $this->input->post('blogger_link'),
                'linkdedin_link' => $this->input->post('linkdedin_link'),
                'google_plus_link' => $this->input->post('google_plus_link'),
                'instagram_link' => $this->input->post('instagram_link'),
                'pinterest_link' => $this->input->post('pinterest_link'),
                'youtube_link' => $this->input->post('youtube_link')
            );
            $id = $this->input->post('user_id');
            $result = $this->Employees_model->social_info($data,$id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_update_social_info');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }


    // Validate and update info in database // contact info
    public function update_contacts_info() {

        if($this->input->post('type')=='contact_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            /* Server side PHP input validation */
            if($this->input->post('salutation')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_salutation');
            } else if($this->input->post('contact_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contact_name');
            } else if($this->input->post('relation')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_grp');
            } else if($this->input->post('primary_email')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_pemail');
            } else if($this->input->post('mobile_phone')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_mobile');
            } else if($this->input->post('city')==='') {
                $Return['error'] = $this->lang->line('xin_error_city_field');
            } else if($this->input->post('country')==='') {
                $Return['error'] = $this->lang->line('xin_error_country_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'salutation' => $this->input->post('salutation'),
                'contact_name' => $this->input->post('contact_name'),
                'relation' => $this->input->post('relation'),
                'company' => $this->input->post('company'),
                'job_title' => $this->input->post('job_title'),
                'primary_email' => $this->input->post('primary_email'),
                'mobile_phone' => $this->input->post('mobile_phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zipcode' => $this->input->post('zipcode'),
                'country' => $this->input->post('country'),
                'employee_id' => $this->input->post('user_id'),
                'contact_type' => 'permanent'
            );

            $query = $this->Employees_model->check_employee_contact_permanent($this->input->post('user_id'));
            if ($query->num_rows() > 0 ) {
                $res = $query->result();
                $e_field_id = $res[0]->contact_id;
                $result = $this->Employees_model->contact_info_update($data,$e_field_id);
            } else {
                $result = $this->Employees_model->contact_info_add($data);
            }

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database //  econtact info
    public function update_contact_info() {

        if($this->input->post('type')=='contact_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            /* Server side PHP input validation */
            if($this->input->post('salutation')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_salutation');
            } else if($this->input->post('contact_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contact_name');
            } else if($this->input->post('relation')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_grp');
            } else if($this->input->post('primary_email')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_pemail');
            } else if($this->input->post('mobile_phone')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_mobile');
            } else if($this->input->post('city')==='') {
                $Return['error'] = $this->lang->line('xin_error_city_field');
            } else if($this->input->post('country')==='') {
                $Return['error'] = $this->lang->line('xin_error_country_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'salutation' => $this->input->post('salutation'),
                'contact_name' => $this->input->post('contact_name'),
                'relation' => $this->input->post('relation'),
                'company' => $this->input->post('company'),
                'job_title' => $this->input->post('job_title'),
                'primary_email' => $this->input->post('primary_email'),
                'mobile_phone' => $this->input->post('mobile_phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zipcode' => $this->input->post('zipcode'),
                'country' => $this->input->post('country'),
                'employee_id' => $this->input->post('user_id'),
                'contact_type' => 'current'
            );

            $query = $this->Employees_model->check_employee_contact_current($this->input->post('user_id'));
            if ($query->num_rows() > 0 ) {
                $res = $query->result();
                $e_field_id = $res[0]->contact_id;
                $result = $this->Employees_model->contact_info_update($data,$e_field_id);
            } else {
                $result = $this->Employees_model->contact_info_add($data);
            }
            //$e_field_id = 1;

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database // contact info
    public function contact_info() {

        if($this->input->post('type')=='contact_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('relation')==='') {
                $Return['error'] = "The relation field is required.";
            } else if($this->input->post('contact_name')==='') {
                $Return['error'] = "The contact name field is required.";
            } else if($this->input->post('mobile_phone')==='') {
                $Return['error'] = "The mobile field is required.";
            }

            if(null!=$this->input->post('is_primary')) {
                $is_primary = $this->input->post('is_primary');
            } else {
                $is_primary = '';
            }
            if(null!=$this->input->post('is_dependent')) {
                $is_dependent = $this->input->post('is_dependent');
            } else {
                $is_dependent = '';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'relation' => $this->input->post('relation'),
                'work_email' => $this->input->post('work_email'),
                'is_primary' => $is_primary,
                'is_dependent' => $is_dependent,
                'personal_email' => $this->input->post('personal_email'),
                'contact_name' => $this->input->post('contact_name'),
                'address_1' => $this->input->post('address_1'),
                'work_phone' => $this->input->post('work_phone'),
                'work_phone_extension' => $this->input->post('work_phone_extension'),
                'address_2' => $this->input->post('address_2'),
                'mobile_phone' => $this->input->post('mobile_phone'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zipcode' => $this->input->post('zipcode'),
                'home_phone' => $this->input->post('home_phone'),
                'country' => $this->input->post('country'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->contact_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = 'Contact Information added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database //  econtact info
    public function e_contact_info() {

        if($this->input->post('type')=='e_contact_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('relation')==='') {
                $Return['error'] = "The relation field is required.";
            } else if($this->input->post('contact_name')==='') {
                $Return['error'] = "The contact name field is required.";
            } else if($this->input->post('mobile_phone')==='') {
                $Return['error'] = "The mobile field is required.";
            }

            if(null!=$this->input->post('is_primary')) {
                $is_primary = $this->input->post('is_primary');
            } else {
                $is_primary = '';
            }
            if(null!=$this->input->post('is_dependent')) {
                $is_dependent = $this->input->post('is_dependent');
            } else {
                $is_dependent = '';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'relation' => $this->input->post('relation'),
                'work_email' => $this->input->post('work_email'),
                'is_primary' => $is_primary,
                'is_dependent' => $is_dependent,
                'personal_email' => $this->input->post('personal_email'),
                'contact_name' => $this->input->post('contact_name'),
                'address_1' => $this->input->post('address_1'),
                'work_phone' => $this->input->post('work_phone'),
                'work_phone_extension' => $this->input->post('work_phone_extension'),
                'address_2' => $this->input->post('address_2'),
                'mobile_phone' => $this->input->post('mobile_phone'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zipcode' => $this->input->post('zipcode'),
                'home_phone' => $this->input->post('home_phone'),
                'country' => $this->input->post('country')
            );

            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->contact_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Contact Information updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // document info
    public function document_info() {

        if($this->input->post('type')=='document_info' && $this->input->post('data')=='document_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $all_files_count = $this->Xin_model->get_employee_files_count($this->input->post('user_id'));

            /* Server side PHP input validation */
            if($this->input->post('document_type_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_type');
            } else if($this->input->post('title')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_document_title');
            }

            /* Check if file uploaded..*/
            else if($all_files_count == 0) {
                $fname = 'no file';
                $Return['error'] = 'Document is required.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'document_type_id' => $this->input->post('document_type_id'),
                'date_of_expiry' => $this->input->post('date_of_expiry'),
                'title' => $this->input->post('title'),
                'is_alert' => $this->input->post('send_mail'),
                'description' => $this->input->post('description'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->document_info_add($data);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_d_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // document info
    public function immigration_info() {

        if($this->input->post('type')=='immigration_info' && $this->input->post('data')=='immigration_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('document_type_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_type');
            } else if($this->input->post('document_number')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_number');
            } else if($this->input->post('issue_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_issue');
            } else if($this->input->post('expiry_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_expiry_date');
            }

            /* Check if file uploaded..*/
            else if($_FILES['document_file']['size'] == 0) {
                $Return['error'] = $this->lang->line('xin_employee_select_d_file');
            } else {
                if(is_uploaded_file($_FILES['document_file']['tmp_name'])) {
                    //checking image type
                    $allowed =  array('png','jpg','jpeg','pdf','gif','txt','pdf','xls','xlsx','doc','docx');
                    $filename = $_FILES['document_file']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                    if(in_array($ext,$allowed)){
                        $tmp_name = $_FILES["document_file"]["tmp_name"];
                        $documentd = "uploads/document/immigration/";
                        // basename() may prevent filesystem traversal attacks;
                        // further validation/sanitation of the filename may be appropriate
                        $name = basename($_FILES["document_file"]["name"]);
                        $newfilename = 'document_'.round(microtime(true)).'.'.$ext;
                        move_uploaded_file($tmp_name, $documentd.$newfilename);
                        $fname = $newfilename;
                    } else {
                        $Return['error'] = $this->lang->line('xin_employee_document_file_type');
                    }
                }
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'document_type_id' => $this->input->post('document_type_id'),
                'document_number' => $this->input->post('document_number'),
                'document_file' => $fname,
                'issue_date' => $this->input->post('issue_date'),
                'expiry_date' => $this->input->post('expiry_date'),
                'country_id' => $this->input->post('country'),
                'eligible_review_date' => $this->input->post('eligible_review_date'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y h:i:s'),
            );
            $result = $this->Employees_model->immigration_info_add($data);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_img_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // document info
    public function e_immigration_info() {

        if($this->input->post('type')=='e_immigration_info' && $this->input->post('data')=='e_immigration_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('document_type_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_type');
            } else if($this->input->post('document_number')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_number');
            } else if($this->input->post('issue_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_issue');
            } else if($this->input->post('expiry_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_expiry_date');
            }

            /* Check if file uploaded..*/
            else if($_FILES['document_file']['size'] == 0) {
                $data = array(
                    'document_type_id' => $this->input->post('document_type_id'),
                    'document_number' => $this->input->post('document_number'),
                    'issue_date' => $this->input->post('issue_date'),
                    'expiry_date' => $this->input->post('expiry_date'),
                    'country_id' => $this->input->post('country'),
                    'eligible_review_date' => $this->input->post('eligible_review_date'),
                );
                $e_field_id = $this->input->post('e_field_id');
                $result = $this->Employees_model->img_document_info_update($data,$e_field_id);
                if ($result == TRUE) {
                    $Return['result'] = $this->lang->line('xin_employee_img_info_updated');
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
                $this->output($Return);
                exit;
            } else {
                if(is_uploaded_file($_FILES['document_file']['tmp_name'])) {
                    //checking image type
                    $allowed =  array('png','jpg','jpeg','pdf','gif','txt','pdf','xls','xlsx','doc','docx');
                    $filename = $_FILES['document_file']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                    if(in_array($ext,$allowed)){
                        $tmp_name = $_FILES["document_file"]["tmp_name"];
                        $documentd = "uploads/document/immigration/";
                        // basename() may prevent filesystem traversal attacks;
                        // further validation/sanitation of the filename may be appropriate
                        $name = basename($_FILES["document_file"]["name"]);
                        $newfilename = 'document_'.round(microtime(true)).'.'.$ext;
                        move_uploaded_file($tmp_name, $documentd.$newfilename);
                        $fname = $newfilename;
                        $data = array(
                            'document_type_id' => $this->input->post('document_type_id'),
                            'document_number' => $this->input->post('document_number'),
                            'document_file' => $fname,
                            'issue_date' => $this->input->post('issue_date'),
                            'expiry_date' => $this->input->post('expiry_date'),
                            'country_id' => $this->input->post('country'),
                            'eligible_review_date' => $this->input->post('eligible_review_date'),
                        );
                        $e_field_id = $this->input->post('e_field_id');
                        $result = $this->Employees_model->img_document_info_update($data,$e_field_id);
                        if ($result == TRUE) {
                            $Return['result'] = $this->lang->line('xin_employee_d_info_updated');
                        } else {
                            $Return['error'] = $this->lang->line('xin_error_msg');
                        }
                        $this->output($Return);
                        exit;
                    } else {
                        $Return['error'] = $this->lang->line('xin_employee_document_file_type');
                    }
                }
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_img_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // e_document info
    public function e_document_info() {

        if($this->input->post('type')=='e_document_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('document_type_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_type');
            } else if($this->input->post('title')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_document_title');
            }
            else{
                $data = array(
                    'document_type_id' => $this->input->post('document_type_id'),
                    'date_of_expiry' => $this->input->post('date_of_expiry'),
                    'title' => $this->input->post('title'),
                    'is_alert' => $this->input->post('send_mail'),
                    'description' => $this->input->post('description')
                );
                $e_field_id = $this->input->post('e_field_id');
                $result = $this->Employees_model->document_info_update($data,$e_field_id);
                if ($result == TRUE) {
                    $Return['result'] = $this->lang->line('xin_employee_d_info_updated');
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
                $this->output($Return);
                exit;
            }

            if($Return['error']!=''){
                $this->output($Return);
            }


        }
    }

    // Validate and add info in database // qualification info
    public function qualification_info() {

        if($this->input->post('type')=='qualification_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            $from_year = $this->input->post('from_year');
            $to_year = $this->input->post('to_year');
            $st_date = strtotime($from_year);
            $ed_date = strtotime($to_year);

            if($this->input->post('name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
            } else if($this->input->post('from_year')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('to_year')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_to_date');
            } else if($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'name' => $this->input->post('name'),
                'education_level_id' => $this->input->post('education_level'),
                'from_year' => $this->input->post('from_year'),
                'language_id' => $this->input->post('language'),
                'to_year' => $this->input->post('to_year'),
                'skill_id' => $this->input->post('skill'),
                'description' => $this->input->post('description'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->qualification_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_error_q_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // qualification info
    public function e_qualification_info() {

        if($this->input->post('type')=='e_qualification_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            $from_year = $this->input->post('from_year');
            $to_year = $this->input->post('to_year');
            $st_date = strtotime($from_year);
            $ed_date = strtotime($to_year);

            if($this->input->post('name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
            } else if($this->input->post('from_year')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('to_year')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_to_date');
            } else if($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'name' => $this->input->post('name'),
                'education_level_id' => $this->input->post('education_level'),
                'from_year' => $this->input->post('from_year'),
                'language_id' => $this->input->post('language'),
                'to_year' => $this->input->post('to_year'),
                'skill_id' => $this->input->post('skill'),
                'description' => $this->input->post('description')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->qualification_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_error_q_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // work experience info
    public function work_experience_info() {

        if($this->input->post('type')=='work_experience_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $frm_date = strtotime($this->input->post('from_date'));
            $to_date = strtotime($this->input->post('to_date'));
            /* Server side PHP input validation */
            if($this->input->post('company_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_company_name');
            } else if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('to_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_to_date');
            } else if($frm_date > $to_date) {
                $Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
            } else if($this->input->post('post')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_post');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'company_name' => $this->input->post('company_name'),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'post' => $this->input->post('post'),
                'description' => $this->input->post('description'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->work_experience_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_error_w_exp_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    public function e_work_experience_info() {

        if($this->input->post('type')=='e_work_experience_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $frm_date = strtotime($this->input->post('from_date'));
            $to_date = strtotime($this->input->post('to_date'));
            /* Server side PHP input validation */
            if($this->input->post('company_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_company_name');
            } else if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('to_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_to_date');
            } else if($frm_date > $to_date) {
                $Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
            } else if($this->input->post('post')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_post');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'company_name' => $this->input->post('company_name'),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'post' => $this->input->post('post'),
                'description' => $this->input->post('description')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->work_experience_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_error_w_exp_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }


    // Validate and add info in database // bank account info
    public function bank_account_info() {

        if($this->input->post('type')=='bank_account_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            if($this->input->post('account_title')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_acc_title');
            } else if($this->input->post('account_number')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_acc_number');
            } else if($this->input->post('bank_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_bank_name');
            } else if($this->input->post('bank_code')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_bank_code');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }
            if($this->input->post('is_primary'))
                $is_primary =1;
            else
                $is_primary=0;
            if($is_primary)
                $this->db->where('root_id',$_SESSION['root_id'])->where('employee_id',$this->input->post('user_id'))->update('xin_employee_bankaccount',array('is_primary'=>0));


            $data = array(
                'account_title' => $this->input->post('account_title'),
                'account_number' => $this->input->post('account_number'),
                'bank_name' => $this->input->post('bank_name'),
                'bank_code' => $this->input->post('bank_code'),
                'bank_branch' => $this->input->post('bank_branch'),
                'iban' => $this->input->post('iban'),
                'is_primary'=>$is_primary,
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->bank_account_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_error_bank_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // ebank account info
    public function e_bank_account_info() {

        if($this->input->post('type')=='e_bank_account_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            if($this->input->post('account_title')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_acc_title');
            } else if($this->input->post('account_number')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_acc_number');
            } else if($this->input->post('bank_name')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_bank_name');
            } else if($this->input->post('bank_code')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_bank_code');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }
            if($this->input->post('is_primary'))
                $is_primary =1;
            else
                $is_primary=0;
            if($is_primary)
                $this->db->where('root_id',$_SESSION['root_id'])->where('employee_id',$this->input->post('user_id'))->update('xin_employee_bankaccount',array('is_primary'=>0));

            $data = array(
                'account_title' => $this->input->post('account_title'),
                'account_number' => $this->input->post('account_number'),
                'bank_name' => $this->input->post('bank_name'),
                'bank_code' => $this->input->post('bank_code'),
                'iban' => $this->input->post('iban'),
                'is_primary'=>$is_primary,
                'bank_branch' => $this->input->post('bank_branch')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->bank_account_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_error_bank_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database //contract info
    public function contract_info() {

        if($this->input->post('type')=='contract_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $frm_date = strtotime($this->input->post('from_date'));
            $to_date = strtotime($this->input->post('to_date'));
            /* Server side PHP input validation */
            if($this->input->post('contract_type_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_type');
            } else if($this->input->post('title')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_title');
            } else if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('to_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_to_date');
            } else if($frm_date > $to_date) {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
            } else if($this->input->post('designation_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_designation');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'contract_type_id' => $this->input->post('contract_type_id'),
                'title' => $this->input->post('title'),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'designation_id' => $this->input->post('designation_id'),
                'description' => $this->input->post('description'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->contract_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_contract_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database //e contract info
    public function e_contract_info() {

        if($this->input->post('type')=='e_contract_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $frm_date = strtotime($this->input->post('from_date'));
            $to_date = strtotime($this->input->post('to_date'));
            /* Server side PHP input validation */
            if($this->input->post('contract_type_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_type');
            } else if($this->input->post('title')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_title');
            } else if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('to_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_to_date');
            } else if($frm_date > $to_date) {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
            } else if($this->input->post('designation_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_designation');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'contract_type_id' => $this->input->post('contract_type_id'),
                'title' => $this->input->post('title'),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'designation_id' => $this->input->post('designation_id'),
                'description' => $this->input->post('description')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->contract_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_contract_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database //leave_info
    public function leave_info() {

        if($this->input->post('type')=='leave_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            if($this->input->post('contract_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_f');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'contract_id' => $this->input->post('contract_id'),
                'casual_leave' => $this->input->post('casual_leave'),
                'medical_leave' => $this->input->post('medical_leave'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->leave_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_leave_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database //Eleave_info
    public function e_leave_info() {

        if($this->input->post('type')=='e_leave_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'casual_leave' => $this->input->post('casual_leave'),
                'medical_leave' => $this->input->post('medical_leave')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->leave_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_leave_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // shift info
    public function shift_info() {

        if($this->input->post('type')=='shift_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('shift_id')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_shift_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'shift_id' => $this->input->post('shift_id'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->shift_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_shift_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // eshift info
    public function e_shift_info() {

        if($this->input->post('type')=='e_shift_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            }

            $data = array(
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->shift_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_shift_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // location info
    public function location_info() {

        if($this->input->post('type')=='location_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('location_id')==='') {
                $Return['error'] = $this->lang->line('error_location_dept_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'location_id' => $this->input->post('location_id'),
                'employee_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employees_model->location_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_location_info_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database // elocation info
    public function e_location_info() {

        if($this->input->post('type')=='e_location_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('from_date')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_frm_date');
            } else if($this->input->post('location_id')==='') {
                $Return['error'] = $this->lang->line('error_location_dept_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->location_info_update($data,$e_field_id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_location_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database // change password
    public function change_password() {

        if($this->input->post('type')=='change_password') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if(trim($this->input->post('new_password'))==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_newpassword');
            } else if(strlen($this->input->post('new_password')) < 6) {
                $Return['error'] = $this->lang->line('xin_employee_error_password_least');
            } else if(trim($this->input->post('new_password_confirm'))==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_new_cpassword');
            } else if($this->input->post('new_password')!=$this->input->post('new_password_confirm')) {
                $Return['error'] = $this->lang->line('xin_employee_error_old_new_cpassword');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $password = $this->input->post('new_password'); //turn the array into a string
            $salt = implode($pass);
            $pw_hash = sha1($salt.$password);

            $data = array(
                'sec_pass' => $pw_hash,
                'pslt' => $salt,
                 'password'=>$password,

            );
            $id = $this->input->post('user_id');
            $result = $this->Employees_model->change_password($data,$id);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_password_update');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    /*  get all employee details lisitng *//////////////////

    // employee contacts - listing
    public function contacts()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $contacts = $this->Employees_model->set_employee_contacts($id);

        $data = array();

        foreach($contacts->result() as $r) {

            if($r->is_primary==1){
                $primary = '<span class="tag tag-success">'.$this->lang->line('xin_employee_primary').'</span>';
            } else {
                $primary = '';
            }
            if($r->is_dependent==2){
                $dependent = '<span class="tag tag-danger">'.$this->lang->line('xin_employee_dependent').'</span>';
            } else {
                $dependent = '';
            }

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contact_id . '" data-field_type="contact"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contact_id . '" data-token_type="contact"><i class="fa fa-trash-o"></i></button></span>',
                $r->contact_name . ' ' .$primary . ' '.$dependent,
                $r->relation,
                $r->work_email,
                $r->mobile_phone
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $contacts->num_rows(),
            "recordsFiltered" => $contacts->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee documents - listing
    public function documents() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $documents = $this->Employees_model->set_employee_documents($id);

        $data = array();

        foreach($documents->result() as $r) {

            $d_type = $this->Employees_model->read_document_type_information($r->document_type_id);
            if(!empty($r->date_of_expiry))
            {
                $date_of_expiry = $this->Xin_model->set_date_format($r->date_of_expiry);
            }
            else
            {
                $date_of_expiry = "--";
            }

            if($r->is_alert==1){
                $alert = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_e_details_alert_notifyemail').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"><i class="fa fa-bell"></i></button></span>';
                $alert = '';
            } else {
                $alert = '';
            }

            $filename = $r->document_file;
            $file_url = site_url().'uploads/document/'.$filename;

            $ext = substr(strrchr($filename, '.'), 1);

            if($ext=="doc" || $ext=="docx")
            {
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            }
            else if($ext=="csv" || $ext=="xlsx" || $ext=="xls")
            {
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            }

            $data[] = array(
                $functions = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-field_id="'. $r->document_id . '" data-field_type="document"><i class="fa fa-eye"></i></button></span>
			                   <span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->document_id . '" data-field_type="document"><i class="fa fa-pencil-square-o"></i></button></span>
			<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->document_id . '" data-token_type="document"><i class="fa fa-trash-o"></i></button></span>',
                $d_type[0]->document_type,
                $r->title,
                $date_of_expiry
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $documents->num_rows(),
            "recordsFiltered" => $documents->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function employee_ticket_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        $id = $this->uri->segment(3);

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $ticket = $this->Tickets_model->get_flight_tickets_employee($id);

        $data = array();

        foreach($ticket->result() as $r) {


            // status
            if($r->status==1): $status = 'Created'; elseif($r->ticket_status==2): $status = 'Paid'; endif;
            // ticket date and time
            $created_at = date('jS M Y', strtotime($r->created_date));
            $ticket_date = date('jS M Y', strtotime($r->ticket_date));
            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-field_id="'. $r->id . '" data-field_type="flight" data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-token_type="flight"data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                $ticket_date,
                $r->destination,
                $r->airlines,
                $r->amount,
                $r->ticket_no,
                $created_at
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $ticket->num_rows(),
            "recordsFiltered" => $ticket->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee immigration - listing
    public function immigration() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $immigration = $this->Employees_model->set_employee_immigration($id);

        $data = array();

        foreach($immigration->result() as $r) {

            $issue_date = $this->Xin_model->set_date_format($r->issue_date);
            $expiry_date = $this->Xin_model->set_date_format($r->expiry_date);
            $eligible_review_date = $this->Xin_model->set_date_format($r->eligible_review_date);
            $d_type = $this->Employees_model->read_document_type_information($r->document_type_id);
            $document_d = $d_type[0]->document_type.'<br>'.$r->document_number;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="imgdocument"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->immigration_id . '" data-token_type="imgdocument"><i class="fa fa-trash-o"></i></button></span>',
                $document_d,
                $issue_date,
                $expiry_date,
                $r->country_id,
                $eligible_review_date,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $immigration->num_rows(),
            "recordsFiltered" => $immigration->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee qualification - listing
    public function qualification() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $qualification = $this->Employees_model->set_employee_qualification($id);

        $data = array();

        foreach($qualification->result() as $r) {

            $education = $this->Employees_model->read_education_information($r->education_level_id);
            $language = $this->Employees_model->read_qualification_language_information($r->language_id);

            /*if($r->skill_id == 'no course') {
                $ol = 'No Course';
            } else {
                $ol = '<ol class="nl">';
                foreach(explode(',',$r->skill_id) as $desig_id) {
                    $skill = $this->Employees_model->read_qualification_skill_information($desig_id);
                    $ol .= '<li>'.$skill[0]->name.'</li>';
                 }
                 $ol .= '</ol>';
            }*/
            $sdate = $this->Xin_model->set_date_format($r->from_year);
            $edate = $this->Xin_model->set_date_format($r->to_year);

            $time_period = $sdate.' - '.$edate;
            // get date
            $pdate = $time_period;
            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->qualification_id . '" data-field_type="qualification"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->qualification_id . '" data-token_type="qualification"><i class="fa fa-trash-o"></i></button></span>',
                $r->name,
                $pdate,
                $education[0]->name
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $qualification->num_rows(),
            "recordsFiltered" => $qualification->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee work experience - listing
    public function experience() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $experience = $this->Employees_model->set_employee_experience($id);

        $data = array();

        foreach($experience->result() as $r) {

            $from_date = $this->Xin_model->set_date_format($r->from_date);
            $to_date = $this->Xin_model->set_date_format($r->to_date);


            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->work_experience_id . '" data-field_type="work_experience"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->work_experience_id . '" data-token_type="work_experience"><i class="fa fa-trash-o"></i></button></span>',
                $r->company_name,
                $from_date,
                $to_date,
                $r->post,
                $r->description
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $experience->num_rows(),
            "recordsFiltered" => $experience->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee bank account - listing
    public function bank_account() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $bank_account = $this->Employees_model->set_employee_bank_account($id);

        $data = array();

        foreach($bank_account->result() as $r) {
            if($r->is_primary==0): $status = '<span class="tag tag-danger">Inactive</span>'; elseif($r->is_primary==1): $status = '<span class="tag tag-success">Primary</span>'; endif;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->bankaccount_id . '" data-field_type="bank_account"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->bankaccount_id . '" data-token_type="bank_account"><i class="fa fa-trash-o"></i></button></span>',
                $r->account_title,
                $r->account_number,
                $r->bank_name,
                $r->bank_code,
                $r->bank_branch,
                $r->bank_branch,
                $status
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $bank_account->num_rows(),
            "recordsFiltered" => $bank_account->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee contract - listing
    public function contract() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $contract = $this->Employees_model->set_employee_contract($id);

        $data = array();

        foreach($contract->result() as $r) {
            // designation
            $designation = $this->Designation_model->read_designation_information($r->designation_id);
            //contract type
            $contract_type = $this->Employees_model->read_contract_type_information($r->contract_type_id);
            // date
            $duration = $this->Xin_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($r->to_date);

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contract_id . '" data-token_type="contract"><i class="fa fa-trash-o"></i></button></span>',
                $duration,
                $designation[0]->designation_name,
                $contract_type[0]->name,
                $r->title
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $contract->num_rows(),
            "recordsFiltered" => $contract->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }




    // employee contract - listing
    public function salary() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $contract = $this->Employees_model->set_employee_salary($id);

        $data = array();

        foreach($contract->result() as $r) {

            $salary_details='Basic Salary : '.$r->basic_salary.'<br>';
            if(!empty($r->house_rent_allowance))
            {
                $salary_details.='House Rent Allowance : '.$r->house_rent_allowance.'<br>';
            }
            if(!empty($r->medical_allowance))
            {
                $salary_details.='Medical Allowance : '.$r->medical_allowance.'<br>';
            }
            if(!empty($r->travelling_allowance))
            {
                $salary_details.='Travelling Allowance : '.$r->travelling_allowance.'<br>';
            }
            if(!empty($r->dearness_allowance))
            {
                $salary_details.='Dearness Allowance : '.$r->dearness_allowance;
            }
            if(!empty($r->telephone_allowance))
            {
                $salary_details.='Telephone Allowance : '.$r->telephone_allowance;
            }
            if(!empty($r->other_allowance))
            {
                $salary_details.='Other Allowance : '.$r->other_allowance;
            }
            $user = $this->Xin_model->read_user_info($r->added_by);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name.'<br>'.date("d D M Y", strtotime($r->created_at));
            } else {
                $full_name = '--';
            }

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->salary_id . '" data-token_type="salary"><i class="fa fa-trash-o"></i></button></span>',
                $newDate = date("M Y", strtotime($r->active_date)),
                $salary_details,
                $r->net_salary,
                $full_name
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $contract->num_rows(),
            "recordsFiltered" => $contract->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }




    // employee leave - listing
    public function leave() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $leave = $this->Employees_model->set_employee_leave($id);

        $data = array();

        foreach($leave->result() as $r) {
            // contract
            $contract = $this->Employees_model->read_contract_information($r->contract_id);
            // contract duration
            $duration = $this->Xin_model->set_date_format($contract[0]->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($contract[0]->to_date);
            $contracti = $contract[0]->title.' '.$duration;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->leave_id . '" data-field_type="leave"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '" data-token_type="leave"><i class="fa fa-trash-o"></i></button></span>',
                $contracti,
                $r->casual_leave,
                $r->medical_leave
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $leave->num_rows(),
            "recordsFiltered" => $leave->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function annual_leave() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $leave = $this->Employees_model->set_employee_leave($id);

        $data = array();

        foreach($leave->result() as $r) {
            // contract
            $contract = $this->Employees_model->read_contract_information($r->contract_id);
            // contract duration
            $duration = $this->Xin_model->set_date_format($contract[0]->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($contract[0]->to_date);
            $contracti = $contract[0]->title.' '.$duration;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->id . '" data-field_type="annual_leave"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '" data-token_type="leave"><i class="fa fa-trash-o"></i></button></span>',
                $contracti,
                $r->casual_leave,
                $r->medical_leave
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $leave->num_rows(),
            "recordsFiltered" => $leave->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function annual_leave_user() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $leave = $this->Employees_model->get_employee_annual_leave($id);

        $data = array();

        foreach($leave->result() as $r) {
            // contract
            $applied_on = $this->Xin_model->set_date_format($r->applied_on);
            $duration = $this->Xin_model->set_date_format($r->start_date).' to '.$this->Xin_model->set_date_format($r->end_date);

            // get status
            if($r->status==1): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==2): $status = '<span class="tag tag-success">Accepted</span>'; elseif($r->status==3): $status = '<span class="tag tag-warning">Rejected</span>'; endif;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->id . '" data-field_type="annual_leave"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'employee/annual_leave/leave_details/id/'.$r->id.'/"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '" data-token_type="annual_leave"><i class="fa fa-trash-o"></i></button></span>',
                $duration,
                $applied_on,
                $status
            );
        }


        $output = array(
            "draw" => $draw,
            "recordsTotal" => $leave->num_rows(),
            "recordsFiltered" => $leave->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // employee office shift - listing
    public function shift() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $shift = $this->Employees_model->set_employee_shift($id);

        $data = array();

        foreach($shift->result() as $r) {
            // contract
            $shift_info = $this->Employees_model->read_shift_information($r->shift_id);
            // contract duration
            $duration = $this->Xin_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($r->to_date);

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->emp_shift_id . '" data-field_type="shift"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->emp_shift_id . '" data-token_type="shift"><i class="fa fa-trash-o"></i></button></span>',
                $duration,
                $shift_info[0]->shift_name
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $shift->num_rows(),
            "recordsFiltered" => $shift->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function dialog_annual_leave() {
        $data['title'] = $this->Xin_model->site_title();
        $leave_id = $this->uri->segment(5);
        // leave applications
        $result = $this->Timesheet_model->read_annual_leave_information($leave_id);
        // get leave types
        // get employee
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);

        $data = array(
            'title' => $this->Xin_model->site_title(),
            'role_id' => $user[0]->user_role_id,
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'leave_id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'from_date' => $result[0]->start_date,
            'end_date' => $result[0]->end_date,
            'applied_on' => $result[0]->applied_on,
            'remarks' => $result[0]->remarks,
            'status' => $result[0]->status,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $data['breadcrumbs'] = 'Leave Detail';
        $data['path_url'] = 'annual_leave_details';
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $data['subview'] = $this->load->view("timesheet/annual_leave_edit", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }

    }
    // employee location - listing
    public function location() {
        //set data
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("employees/employee_detail", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(3);
        $location = $this->Employees_model->set_employee_location($id);

        $data = array();

        foreach($location->result() as $r) {
            // contract
            $of_location = $this->Location_model->read_location_information($r->location_id);
            // contract duration
            $duration = $this->Xin_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($r->to_date);

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->office_location_id . '" data-field_type="location"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->office_location_id . '" data-token_type="location"><i class="fa fa-trash-o"></i></button></span>',
                $duration,
                $of_location[0]->location_name
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $location->num_rows(),
            "recordsFiltered" => $location->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // Validate and update info in database
    public function update() {

        if($this->input->post('edit_type')=='warning') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            $description = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($this->input->post('warning_to')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_warning');
            } else if($this->input->post('type')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_warning_type');
            } else if($this->input->post('subject')==='') {
                $Return['error'] = $this->lang->line('xin_employee_error_subject');
            } else if(empty($this->input->post('warning_by'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_warning_by');
            } else if(empty($this->input->post('warning_date'))) {
                $Return['error'] = $this->lang->line('xin_employee_error_warning_date');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'warning_to' => $this->input->post('warning_to'),
                'warning_type_id' => $this->input->post('type'),
                'description' => $qt_description,
                'subject' => $this->input->post('subject'),
                'warning_by' => $this->input->post('warning_by'),
                'warning_date' => $this->input->post('warning_date'),
                'status' => $this->input->post('status'),
            );

            $result = $this->Warning_model->update_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_employee_warning_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // delete contact record
    public function delete_contact() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_contact_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_contact_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }
    public function delete_annual_leave() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_annual_leave($id);
            if(isset($id)) {
                $Return['result'] = "Leave Entry Deleted";
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete document record
    public function delete_document() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);

            $all_files = $this->Xin_model->get_employee_document_files($id);
            foreach($all_files as $row) {
                $delete_file = "uploads/document/".$row->img_name;
                unlink($delete_file);
            }

            $result = $this->Employees_model->delete_document_record($id);
            if(isset($id)) {
                $Return['result'] = 'Document deleted.';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete document record
    public function delete_imgdocument() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_imgdocument_record($id);
            if(isset($id)) {
                $Return['result'] = 'Immigration deleted.';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete qualification record
    public function delete_qualification() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_qualification_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_qualification_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete work_experience record
    public function delete_work_experience() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_work_experience_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_work_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete bank_account record
    public function delete_bank_account() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_bank_account_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_bankaccount_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete contract record
    public function delete_contract() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_contract_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_contract_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete contract record
    public function delete_salary() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_salary_record($id);
            if(isset($id)) {
                $Return['result'] = 'Salary History Deleted';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete leave record
    // delete shift record

    public function delete_leave() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_leave_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_leave_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }
    public function delete_flight() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Tickets_model->delete_flight_record($id);
            if(isset($id)) {
                $Return['result'] = "Ticket entry Deleted";
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }
    public function delete_shift() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_shift_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_shift_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete location record
    public function delete_location() {

        if($this->input->post('data')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_location_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_location_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete employee record
    public function delete() {

        if($this->input->post('is_ajax')=='2') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Employees_model->delete_record($id);
            if(isset($id)) {
                $Return['result'] = $this->lang->line('xin_employee_current_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }


    public function add_designation() {

        if($this->input->post('add_type')=='designation') {
            // Check validation for user input
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            $this->form_validation->set_rules('designation_name', 'Designation', 'trim|required|xss_clean');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('department_id')==='') {
                $Return['error'] = $this->lang->line('error_department_field');
            } else if($this->input->post('designation_name')==='') {
                $Return['error'] = $this->lang->line('error_designation_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'department_id' => $this->input->post('department_id'),
                'designation_name' => $this->input->post('designation_name'),
                'added_by' => $this->session->userdata('user_id'),
                'created_at' => date('d-m-Y'),

            );
            $result = $this->Designation_model->add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_add_designation');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }


    // Validate and add info in database
    public function add_salary() {

        if($this->input->post('add_type')=='salary') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('active_date')==='') {
                $Return['error'] = 'Active From Date is important';
            } else if($this->input->post('basic_salary')==='') {
                $Return['error'] = $this->lang->line('xin_error_basic_salary');
            }
            $session = $this->session->userdata('username');
            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'emp_id' => $this->input->post('user_id'),
                'active_date' => $this->input->post('active_date'),
                'basic_salary' => $this->input->post('basic_salary'),
                'overtime_rate' => $this->input->post('overtime_rate'),
                'house_rent_allowance' => $this->input->post('house_rent_allowance'),
                'medical_allowance' => $this->input->post('medical_allowance'),
                'travelling_allowance' => $this->input->post('travelling_allowance'),
                'telephone_allowance' => $this->input->post('telephone_allowance'),
                'other_allowance' => $this->input->post('other_allowance'),
                'provident_fund' => $this->input->post('provident_fund'),
                'tax_deduction' => $this->input->post('tax_deduction'),
                'security_deposit' => 0,
                'gross_salary' => $this->input->post('gross_salary'),
                'total_allowance' => $this->input->post('total_allowance'),
                'total_deduction' => 0,
                'net_salary' => $this->input->post('net_salary'),
                'added_by' => $session['user_id'],
                'created_at' => date('d-m-Y h:i:s'),

            );
            $result = $this->Employees_model->add_salary($data);
            if ($result == TRUE) {
                $Return['result'] = 'Salary successfully updated';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }


}
