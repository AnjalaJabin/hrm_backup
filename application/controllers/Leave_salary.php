<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_salary extends MY_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        //load the model
        $this->load->model("Tickets_model");
        $this->load->model("Payroll_model");
        $this->load->model("Xin_model");
        $this->load->model("Designation_model");
        $this->load->model("Timesheet_model");
        $this->load->model("Employees_model");
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
        $data['all_employees'] = $this->Xin_model->all_employees();
        $data['breadcrumbs'] = 'Leave salary';
        $data['path_url'] = 'leave_salary';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('103',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("user/leave_salary_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }
    public function check_salary($id=null){
        $annual_leaves='0';
        $employee_id = $this->input->post('employee_id')??$id;
        $grade_template = $this->Payroll_model->read_salary_information($employee_id);
        $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_year($employee_id);
        $date = $this->input->post('date')??date('Y-m-d');
        $newDate = date("Y-m", strtotime($date));
        $no_of_days =$this->Xin_model->get_number_of_days($newDate);
        $days = $this->input->post('days')??$no_of_days;


//        if($date) {
//            $leave_balance = $this->Xin_model->get_leave_balance($employee_id,$date);
//        }
//        else {
//            $leave_balance = $this->Xin_model->get_leave_balance($employee_id);
//        }
//

//        if($annual_leaves&&$annual_leaves>=30){
//            $days=0;
//        }else {
//            if ($annual_leaves) {
//                $days = 30 - $annual_leaves;
//
//            } else {
//                $days = 30;

//                $annual_leaves = 0;
//            }
//        }
        if($grade_template) {
//            if($days>=30){
//                $tot_amount=$grade_template[0]->basic_salary+$grade_template[0]->house_rent_allowance;
//
//            }else{
                $per_day_sal = (
                        floatval($grade_template[0]->basic_salary) +
                        floatval($grade_template[0]->house_rent_allowance) +
                        floatval($grade_template[0]->medical_allowance) +
                        floatval($grade_template[0]->travelling_allowance) +
                        floatval($grade_template[0]->other_allowance) +
                        floatval($grade_template[0]->telephone_allowance)
                    ) / 30;   
                    $tot_amount=$per_day_sal*$days;

//            }

            $amount = $tot_amount;
        }
        else {
            $amount = 0;
        }
        $amount=round($amount,2);
        if($id)
            return $amount;
        else
        {
            $Return=array('amount'=>$amount,
                'result'=>"Employee has taken ".$annual_leaves." annual leaves this year!") ;
            $this->output($Return);
        };



    }
    public function check_salary_on_exit($id=null){
        $annual_leaves='0';
        $employee_id = $this->input->post('employee_id')??$id;
        $grade_template = $this->Payroll_model->read_salary_information($employee_id);
        $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_year($employee_id);
        $date = $this->input->post('date')??date('Y-m-d');
        $newDate = date("Y-m", strtotime($date));
        $no_of_days =$this->Xin_model->get_number_of_days($newDate);
        $days = $this->input->post('days')??$no_of_days;
        $user_info = $this->Xin_model->read_user_info($employee_id);

        $last_leave = $this->Timesheet_model->getLatestAnnualLeaveForEmployee($employee_id);

        $currentDate = date('Y-m-d');
        if ($this->input->post('date')){
            $currentDate=$date;
        }

        // Get the start of the year
//            $startOfYear = date('Y-01-01');
        if($last_leave) {
            $last_doj = $last_leave[0]->end_date;
            $remaining=$last_leave[0]->remaining_balance;
        }
        else {
            $last_doj = $user_info[0]->date_of_joining;
            $remaining=0;
        }
        $daysSinceStartOfYear = intval((strtotime($currentDate) - strtotime($last_doj)) / (60 * 60 * 24));
        $months=$daysSinceStartOfYear/30;
        $balance = 2.5*$months;
        $leave_salary_entries = $this->Xin_model->get_leave_salary_entries($employee_id);
        if($leave_salary_entries){
            foreach ($leave_salary_entries as $entry){
                if($entry->annual_leave_id==0)
                    $balance=$balance-$entry->leave_days;


            }
        }
        $balance= $balance+$remaining;

        if($balance>30) {
            $avlbalance =30;
        }
        else{
            $avlbalance=round($balance,2);
        }

        if($grade_template) {
//            if($days>=30){
//                $tot_amount=$grade_template[0]->basic_salary+$grade_template[0]->house_rent_allowance;
//
//            }else{
                $per_day_sal = (
                        floatval($grade_template[0]->basic_salary) +
                        floatval($grade_template[0]->house_rent_allowance) +
                        floatval($grade_template[0]->medical_allowance) +
                        floatval($grade_template[0]->travelling_allowance) +
                        floatval($grade_template[0]->other_allowance) +
                        floatval($grade_template[0]->telephone_allowance)
                    ) / $no_of_days;             
                    $tot_amount=$per_day_sal*$avlbalance;

//            }

            $amount = $tot_amount;
        }
        else {
            $amount = 0;
        }
        $amount=round($amount,2);
        if($id)
            return $amount;
        else
        {
            $Return=array('amount'=>$amount,
                'result'=>"Employee has taken ".$annual_leaves." annual leaves this year!") ;
            $this->output($Return);
        };



    }
    public function leave_salary_list() {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("user/leave_salary_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));



        $leave = $this->Timesheet_model->get_all_leave_salaries();

        $data = array();
        foreach($leave->result() as $r) {

            // get start date and end date
            $user = $this->Xin_model->read_user_info($r->employee_id);
            if(!empty($user))
            {
                $full_name = $user[0]->first_name. ' '.$user[0]->last_name;
                $applied_on = $this->Xin_model->set_date_format($r->created_at);
                $days = round((strtotime($r->end_date) - strtotime($r->start_date)) / (60 * 60 * 24)) +1;

                if($r->annual_leave_id)
                    $duration = $this->Xin_model->set_date_format($r->start_date).' to '.$this->Xin_model->set_date_format($r->end_date)."-".$days." days";
                else
                    $duration="Annual Leave Not Taken";


                // get status
                if($r->status==1): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==2): $status = '<span class="tag tag-success">Accepted</span>'; elseif($r->status==3): $status = '<span class="tag tag-warning">Rejected</span>';  endif;
                $action='<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'leave_salary/leave_details/id/'.$r->id.'/"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span>';
                if($r->is_editable)
                    $action.='<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-leave_id="'. $r->id.'" title="Edit"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '" title="Delete"><i class="fa fa-trash-o"></i></button></span>';

                $data[] = array(
                    $action,
                    $user[0]->employee_id,
                    $full_name,
                    $r->department_name,
                    $duration,
                    $this->Xin_model->currency_sign(number_format($r->amount,2)),
                    $applied_on,
                    $status
                );
            }

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
    public function update_leave_status() {

        if($this->input->post('update_type')=='leave') {

            $id = $this->uri->segment(3);
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $remarks = $this->input->post('remarks');
            $qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);

            $data = array(
                'status' => $this->input->post('status'),
                'paid_date' => $this->input->post('paid_date'),
                'payment_method' => $this->input->post('payment_method'),
                'amount' => $this->input->post('amount'),
            );

            $result = $this->Timesheet_model->update_leave_salary_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Salary status updated.';

            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }



    public function read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('leave_id');
        $result = $this->Timesheet_model->read_leave_salary_info($id);
        $data = array(
            'leave_id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'amount' => $result[0]->amount,
            'paid_date' => $result[0]->paid_date,
            'leave_days' => $result[0]->leave_days,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('user/dialog_leave_salary', $data);
        } else {
            redirect('');
        }
    }


    // Validate and add info in database
    public function update_annual_leave_status() {

        if($this->input->post('update_type')=='leave') {

            $id = $this->uri->segment(3);
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $remarks = $this->input->post('remarks');
            $qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);

            $data = array(
                'approval' => $this->input->post('status'),
                'remarks' => $qt_remarks
            );
            $result = $this->Timesheet_model->update_annual_leave_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Leave status updated.';

                //get setting info
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database

    public function leave_details() {
        $data['title'] = $this->Xin_model->site_title();
        $leave_id = $this->uri->segment(4);
        // leave applications
        $result = $this->Timesheet_model->read_leave_salary_info($leave_id);
        // get leave types
        // get employee
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);

        $data = array(
            'title' => $this->Xin_model->site_title(),
            'role_id' => $user[0]->user_role_id,
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'leave_id' => $result[0]->id,
            'leave_days' => $result[0]->leave_days,
            'employee_id' => $result[0]->employee_id,
            'from_date' => $result[0]->start_date,
            'annual_leave_id' => $result[0]->annual_leave_id,
            'end_date' => $result[0]->end_date,
            'applied_on' => $result[0]->created_at,
            'status' => $result[0]->status,
            'amount' => $result[0]->amount,
            'date' => $result[0]->paid_date,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $data['breadcrumbs'] = 'Leave Salary Detail';
        $data['path_url'] = 'leave_salary_detail';
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $data['subview'] = $this->load->view("user/leave_salary_detail", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }

    }

    // Validate and update info in database // assign_ticket

    // Validate and update info in database // update_status
    public function update_status() {

        if($this->input->post('type')=='update_status') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $data = array(
                'ticket_status' => $this->input->post('status'),
                'ticket_remarks' => $this->input->post('remarks'),
            );
            $id = $this->input->post('status_ticket_id');
            $result = $this->Tickets_model->update_status($data,$id);
            if ($result == TRUE) {
                $Return['result'] = 'Ticket status updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }
    public function add_leave() {

        if($this->input->post('add_type')=='leave') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $date = $this->input->post('date');
            $end_date = $this->input->post('end_date');

            /* Server side PHP input validation */
            if(!$this->input->post('date')) {
                $Return['error'] = "The  date field is required.";
            } else if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }
//            else {
//                $annual_leaves = $this->Timesheet_model->check_annual_leaves_for_year($this->input->post('employee_id'));
//                if($annual_leaves &&$annual_leaves>=30)
//                    $Return['error']="Employee has already taken 30 days annual leave this year.";
//            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'root_id'=>$_SESSION['root_id'],
                'leave_days'=>$this->input->post('days'),
                'is_editable'=>1,
                'employee_id' => $this->input->post('employee_id'),
                'amount' => $this->input->post('amount'),
                'paid_date' => $this->input->post('date'),
                'created_at'=>date('Y-m-d'),
                'status' => '1',
                'annual_leave_id'=>0

            );
            $this->db->insert('leave_salary', $data);

            $Return['result'] = 'Leave Salary added.';

            //get setting info
        } else {
            $Return['error'] = 'Bug. Something went wrong, please try again.';
        }
        $this->output($Return);
        exit;
    }
    public function edit_leave() {

        if($this->input->post('edit_type')=='leave') {

            $id = $this->uri->segment(3);
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $remarks = $this->input->post('remarks');

            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);

            /* Server side PHP input validation */
            if($this->input->post('date')==='') {
                $Return['error'] = "The date field is required.";
            }  else if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }
            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'amount' => $this->input->post('amount'),
                'paid_date' => $this->input->post('date'),
                'leave_days' => $this->input->post('days'),


            );

            $result = $this->Timesheet_model->update_leave_salary_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Leave Salary updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }


// Validate and update info in database // add_note

    public function delete_leave() {
        if($this->input->post('type')=='delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Timesheet_model->delete_leave_salary_record($id);

            if($result) {
                $Return['result'] = 'Leave deleted.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
        }
    }



}
