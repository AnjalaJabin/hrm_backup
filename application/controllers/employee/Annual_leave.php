<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Annual_leave extends MY_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        //load the model
        $this->load->model("Timesheet_model");
        $this->load->model("Employees_model");
        $this->load->model("Xin_model");
        $this->load->model("Department_model");
        $this->load->model("Designation_model");
        $this->load->model("Roles_model");
        $this->load->model("Tickets_model");
        $this->load->model("Location_model");
        $this->load->model("Payroll_model");
    }

    /*Function to set JSON output*/
    public function output($Return=array()){
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }

    // leave>index
    public function index()
    {
        $session = $this->session->userdata('username');
        if(!empty($session)){

        } else {
            redirect('');
        }

        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees();
        $data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
        $session = $this->session->userdata('username');
        $data['breadcrumbs'] = 'Annual Leave';
        $data['path_url'] = 'user/annual_leave';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('100',$role_resources_ids)) {

            if (!empty($session)) {

                $data['subview'] = $this->load->view("user/annual_leave", $data, TRUE);
                $this->load->view('layout_main', $data); //page load

            } else {

                redirect('');

            }
        }else{
            redirect('');

        }

    }

    // leave list
    public function leave_list() {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("user/annual_leave", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));



        $leave = $this->Employees_model->get_all_employee_annual_leaves();
        $data = array();


        foreach($leave->result() as $r) {

            // get start date and end date
            $user = $this->Xin_model->read_user_info($r->employee_id);
            $full_name = $user[0]->first_name. ' '.$user[0]->last_name;

            // get leave type
//            $leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);

            $applied_on = $this->Xin_model->set_date_format($r->applied_on);
            $duration = $this->Xin_model->set_date_format($r->start_date).' to '.$this->Xin_model->set_date_format($r->end_date);

            // get status
            if($r->status==1): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==2): $status = '<span class="tag tag-success">Approved</span>'; elseif($r->status==3): $status = '<span class="tag tag-warning">Rejected</span>'; endif;
            if($r->approval==1): $approvalstatus = '<span class="tag tag-danger">Approved</span>'; elseif($r->approval==2): $approvalstatus = '<span class="tag tag-success">Accepted</span>'; elseif($r->approval==3): $approvalstatus = '<span class="tag tag-warning">Rejected</span>';else:$approvalstatus = '<span class="tag tag-warning">Not Available</span>'; endif;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'employee/annual_leave/leave_details/id/'.$r->id.'/"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-leave_id="'. $r->id.'" title="Edit"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '" title="Delete"><i class="fa fa-trash-o"></i></button></span>',
                $user[0]->employee_id,
                $full_name,
                $r->department_name,
                $duration,
                $applied_on,
                $approvalstatus,
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
    public function read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $leave_id = $this->input->post('leave_id');
        $result = $this->Timesheet_model->read_annual_leave_information($leave_id);

        $data = array(
            'leave_id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'from_date' => $result[0]->start_date,
            'to_date' => $result[0]->end_date,
            'applied_on' => $result[0]->applied_on,
            'remarks' => $result[0]->remarks,
            'status' => $result[0]->status,
            'leave_salary' => $result[0]->leave_salary,
            'all_employees' => $this->Xin_model->all_employees(),
            'all_leave_types' => $this->Timesheet_model->all_leave_types(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('timesheet/dialog_annual_leave', $data);
        } else {
            redirect('');
        }
    }
    public function edit_leave() {

        if($this->input->post('edit_type')=='leave') {

            $id = $this->uri->segment(4);
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $remarks = $this->input->post('remarks');

            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);

            /* Server side PHP input validation */
            if($this->input->post('leave_type')==='') {
                $Return['error'] = "The leave type field is required.";
            } else if($this->input->post('start_date')==='') {
                $Return['error'] = "The start date field is required.";
            } else if($this->input->post('end_date')==='') {
                $Return['error'] = "The end date field is required.";
            } else if($st_date > $ed_date) {
                $Return['error'] = "Start Date should be less than or equal to End Date.";
            } else if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }
            $days = round((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24))+1;

            $count_leaves = $this->Timesheet_model->count_total_annual_leaves();
            $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

            $count_l=$count_leaves?$count_leaves[0]->annual_leave_count:0;
            $monthly_leave =30/12;


            $last_leave = $this->Timesheet_model->getLatestAnnualLeaveForEmployee($this->input->post('employee_id'));
            $currentDate = date('Y-m-d',$st_date);

            // Get the start of the year
//            $startOfYear = date('Y-01-01');
            $cuurentannualleave = $this->Timesheet_model->read_annual_leave_information($id);
            $lastdays = round((strtotime($cuurentannualleave[0]->end_date) - strtotime($cuurentannualleave[0]->start_date)) / (60 * 60 * 24))+1;
            if($last_leave) {

                if ($last_leave[0]->id == $id) {
                    $last_doj = $last_leave[1]->end_date;
                    $last_rem = $last_leave[1]->remaining_balance;
                }
                else{
                    $last_doj = $last_leave[0]->end_date;

                    $last_rem = $last_leave[0]->remaining_balance;
                }


            }
            else {

                $last_doj = $user_info[0]->date_of_joining;
                $last_rem=0;
            }
            $daysSinceStartOfYear = intval((strtotime($currentDate) - strtotime($last_doj)) / (60 * 60 * 24));
            $months=$daysSinceStartOfYear/30;
            $balance = ($monthly_leave*$months);
            $remaining= $balance-$days+$last_rem;

            //
            if($Return['error']!=''){
                $this->output($Return);
            }

            if($this->input->post('leave_salary'))
                $leave_sal=1;
            else
                $leave_sal=0;

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'remarks' => $qt_remarks,
                'remaining_balance'=>$remaining,
                'leave_salary'=>$leave_sal,

            );

            $result = $this->Timesheet_model->update_annual_leave_record($data,$id);
            $leave = $this->Timesheet_model->read_annual_leave_information($id);

            if($leave[0]->status==2) {
                $grade_template = $this->Payroll_model->read_salary_information($leave[0]->employee_id);
                if ($grade_template) {
                $per_day_sal = (
                        floatval($grade_template[0]->basic_salary) +
                        floatval($grade_template[0]->house_rent_allowance) +
                        floatval($grade_template[0]->medical_allowance) +
                        floatval($grade_template[0]->travelling_allowance) +
                        floatval($grade_template[0]->other_allowance) +
                        floatval($grade_template[0]->telephone_allowance)
                    ) / 30;
                    //updated according to new law
                    $tot_amount = $per_day_sal * $days;
                    $amount = $tot_amount;

                } else {
                    $amount = 0;
                }

                $leave_salary_data = array(
                    'root_id' => $_SESSION['root_id'],
                    'employee_id' => $this->input->post('employee_id'),
                    'leave_days' => $days,
                    'is_editable' => 0,
                    'amount' => $amount,
                    'status' => 1,
                    'created_at' => date('Y-m-d'),
                    'annual_leave_id' => $id


                );
                $leave_salary = $this->Timesheet_model->check_leave_salarywith_annual_leave($id);
                if($this->input->post('leave_salary')) {
                    if (!$leave_salary)
                        $this->db->insert('leave_salary', $leave_salary_data);
                    else
                        $this->db->where('annual_leave_id', $id)->update('leave_salary', $leave_salary_data);
                }else{
                    if ($leave_salary)
                        $this->db->where('id',$leave_salary[0]->id)->delete('leave_salary');

                }

            }
            if ($result == TRUE) {
                $Return['result'] = 'Leave updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    public function leave_list_employee(){
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("user/annual_leave", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $ticket = $this->Tickets_model->get_gratuity_employees();

        $data = array();

        foreach($ticket->result() as $r) {

            // get user > employee_
            // priority
            $last_leave = $this->Timesheet_model->getLatestAnnualLeaveForEmployee($r->user_id);

            $currentDate = date('Y-m-d');

            // Get the start of the year
//            $startOfYear = date('Y-01-01');
            if($last_leave) {
                $last_doj = $last_leave[0]->end_date;
                $remaining=$last_leave[0]->remaining_balance;
            }
            else {
                $last_doj = $r->date_of_joining;
                $remaining=0;
            }
            $daysSinceStartOfYear = intval((strtotime($currentDate) - strtotime($last_doj)) / (60 * 60 * 24));
            $months=$daysSinceStartOfYear/30;
            $balance = 2.5*$months;
            $leave_salary_entries = $this->Xin_model->get_leave_salary_entries_all($r->user_id);
            if($leave_salary_entries){
                foreach ($leave_salary_entries as $entry){
                    if($entry->annual_leave_id==0)
                        $balance=$balance-$entry->leave_days;


                }
            }
            $balance= $balance+$remaining;

            if($balance>30) {
                $avlbalance = 30 .'('.round($balance,2).')';
            }
            else{
                $avlbalance=round($balance,2);
            }

            $ticket_date = date('jS M Y, h:i A', strtotime($last_doj));

            // status
            $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
//                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                $r->user_id,
                $r->employee_id,
                $r->first_name." ".$r->last_name,
                $r->department_name,
                $last_doj,
                $months,
                $avlbalance
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
    public function add_leave() {

        if($this->input->post('add_type')=='leave') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $remarks = $this->input->post('remarks');
            $employee_id =$this->input->post('employee_id');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);

            /* Server side PHP input validation */
            if($this->input->post('start_date')==='') {
                $Return['error'] = "The start date field is required.";
            } else if($this->input->post('end_date')==='') {
                $Return['error'] = "The end date field is required.";
            } else if($st_date > $ed_date) {
                $Return['error'] = "Start Date should be less than or equal to End Date.";
            } else if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }
            $days = round((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24))+1;
            $count_leaves = $this->Timesheet_model->count_total_annual_leaves();
            $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

            $count_l=$count_leaves?(30-$count_leaves[0]->annual_leave_count):30;
            $monthly_leave =30/12;


            $last_leave = $this->Timesheet_model->getLatestAnnualLeaveForEmployee($employee_id);

            $currentDate = date('Y-m-d',$st_date);

            // Get the start of the year
//            $startOfYear = date('Y-01-01');
            if(isset($last_leave[0])) {
                $last_doj = $last_leave[0]->end_date;
                $last_bal=$last_leave[0]->remaining_balance;
            }            else {
                $last_doj = $user_info[0]->date_of_joining;
                $last_bal=0;
            }
            $daysSinceStartOfYear = intval((strtotime($currentDate) - strtotime($last_doj)) / (60 * 60 * 24));
            $months=$daysSinceStartOfYear/30;
            $balance = $monthly_leave*$months;
            $remaining= floatval($balance-$days+$last_bal);



//            if($days >$balance){
//                $Return['error'] = "You have ".round($balance,0,PHP_ROUND_HALF_DOWN). " days remaining in your annual Leave!";
//
//            }
            $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_year($employee_id);
//            if($annual_leaves &&$annual_leaves>=30)
//                $Return['error']="Employee has already taken 30 days annual leave this year.";

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(

                'employee_id' => $this->input->post('employee_id'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'applied_on' => date('Y-m-d h:i:s'),
                'remaining_balance'=>$remaining,
                'remarks' => $qt_remarks,
                'status' => '1',
            );
            $result = $this->Employees_model->add_annual_leave($data);

            if ($result == TRUE) {
                $Return['result'] = 'Leave added.';

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
                if($setting[0]->enable_email_notification == 'yes') {

                    //load email library
                    $this->load->library('email');
                    $this->email->set_mailtype("html");
                    //get company info
                    $cinfo = $this->Xin_model->read_company_setting_info(1);
                    //get email template
                    $template = $this->Xin_model->read_email_template(5);
                    //get employee info
                    $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));
                    $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

                    $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
                    //$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
                    $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
                    $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
                    $subdomain_name; // Print the sub domain

                    $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';

                    $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;

                    $message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}"),array($cinfo[0]->company_name,site_url(),$full_name),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

                    /*
                    $this->email->from($user_info[0]->email, $full_name);
                    $this->email->to($cinfo[0]->email);

                    $this->email->subject($subject);
                    $this->email->message($message);

                    $this->email->send();
                    */

                    require './mail/gmail.php';
                    //echo '<pre>';
                    $allemails = $this->Xin_model->get_manager_emails(32);
                    foreach($allemails as $email_data)
                    {
                        $mail->AddBCC($email_data['email'], $email_data['name']);
                    }

                    //print_r($mail);


                    //$mail->addAddress($cinfo[0]->email, $cinfo[0]->company_name);
                    $mail->Subject = $subject;
                    $mail->msgHTML($message);

//                    if (!$mail->send()) {
//                        //echo "Mailer Error: " . $mail->ErrorInfo;
//                    } else {
//                        //echo "Message sent!";
//                    }


                }
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }
    public function leave_details() {
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
            'approval' => $result[0]->approval,
            'leave_salary' => $result[0]->leave_salary,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $data['breadcrumbs'] = 'Leave Detail';
        $data['path_url'] = 'annual_leave_details';
        $session = $this->session->userdata('username');

        if(!empty($session)) {
            $role_resources_ids = $this->Xin_model->user_role_resource();
            if (in_array('102', $role_resources_ids)) {

                $data['subview'] = $this->load->view("timesheet/annual_leave_details", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        }

    }
    public function update_leave_status() {

        if($this->input->post('update_type')=='leave') {
            $id = $this->uri->segment(4);
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $remarks = $this->input->post('remarks');
            $qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
            if($this->input->post('leave_salary'))
                $leave_sal=1;
            else
                $leave_sal=0;

            $data = array(
                'status' => $this->input->post('status'),
                'remarks' => $qt_remarks,
                'leave_salary'=>$leave_sal,
            );
            //Generate Leave Salary
            if($this->input->post('status')==2){
                $leave = $this->Timesheet_model->read_annual_leave_information($id);
                $days = round((strtotime($leave[0]->end_date) - strtotime($leave[0]->start_date)) / (60 * 60 * 24)) + 1;
                $grade_template = $this->Payroll_model->read_salary_information($leave[0]->employee_id);
                if($grade_template) {
                    $per_day_sal = (
                        floatval($grade_template[0]->basic_salary) +
                        floatval($grade_template[0]->house_rent_allowance) +
                        floatval($grade_template[0]->medical_allowance) +
                        floatval($grade_template[0]->travelling_allowance) +
                        floatval($grade_template[0]->other_allowance) +
                        floatval($grade_template[0]->telephone_allowance)
                    ) / 30;
                    $tot_amount=$per_day_sal*$days;
                    $amount =$tot_amount;

                }                else {
                    $amount = 0;
                }

                $leave_salary_data   =array(
                    'root_id'=>$_SESSION['root_id'],
                    'employee_id'=>$leave[0]->employee_id,
                    'leave_days'=>$days,
                    'is_editable'=>0,
                    'amount'=>$amount,
                    'status'=>1,
                    'created_at'=>date('Y-m-d'),
                    'paid_date' => date('Y-m-d'),
                    'annual_leave_id'=>$id


                );

                $leave_salary=$this->Timesheet_model->check_leave_salarywith_annual_leave($id);
                if($this->input->post('leave_salary')) {
                    if (!$leave_salary)
                        $this->db->insert('leave_salary', $leave_salary_data);
                    else
                        $this->db->where('annual_leave_id', $id)->update('leave_salary', $leave_salary_data);
                }else{
                    if ($leave_salary)
                        $this->db->where('id',$leave_salary[0]->id)->delete('leave_salary');

                }
            }
            $result = $this->Timesheet_model->update_annual_leave_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Leave status updated.';

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
//                if($setting[0]->enable_email_notification == 'yes') {
//
//                    if($this->input->post('status') == 2){
//                        $this->load->library('email');
//                        $this->email->set_mailtype("html");
//
//                        //get leave info
//                        $timesheet = $this->Timesheet_model->read_annual_leave_information($id);
//                        //get company info
//                        $cinfo = $this->Xin_model->read_company_setting_info(1);
//                        //get email template
//                        $template = $this->Xin_model->read_email_template(6);
//                        //get employee info
//                        $user_info = $this->Xin_model->read_user_info($timesheet[0]->employee_id);
//
//                        $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
//
//                        $from_date = $this->Xin_model->set_date_format($timesheet[0]->start_date);
//                        $to_date = $this->Xin_model->set_date_format($timesheet[0]->end_date);
//
//                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
//                        //$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
//                        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
//                        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
//                        $subdomain_name; // Print the sub domain
//
////                        $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';
//
//                        $logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
//
//                        $message = '
//			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
//			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var leave_start_date}","{var leave_end_date}"),array($cinfo[0]->company_name,site_url(),$from_date,$to_date),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
//
//                        /*
//                        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
//                        $this->email->to($user_info[0]->email);
//
//                        $this->email->subject($subject);
//                        $this->email->message($message);
//
//                        $this->email->send();
//                        */
//
//                        require './mail/gmail.php';
//                        $mail->addAddress($user_info[0]->email, $full_name);
//                        $mail->Subject = $subject;
//                        $mail->msgHTML($message);
//
//                        if (!$mail->send()) {
//                            //echo "Mailer Error: " . $mail->ErrorInfo;
//                        } else {
//                            //echo "Message sent!";
//                        }
//
//                    } else if($this->input->post('status') == 3){ // rejected
//
//                        $this->load->library('email');
//                        $this->email->set_mailtype("html");
//
//                        //get leave info
//                        $timesheet = $this->Timesheet_model->read_annual_leave_information($id);
//                        //get company info
//                        $cinfo = $this->Xin_model->read_company_setting_info(1);
//                        //get email template
//                        $template = $this->Xin_model->read_email_template(7);
//                        //get employee info
//                        $user_info = $this->Xin_model->read_user_info($timesheet[0]->employee_id);
//
//                        $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
//
//                        $from_date = $this->Xin_model->set_date_format($timesheet[0]->start_date);
//                        $to_date = $this->Xin_model->set_date_format($timesheet[0]->end_date);
//
//                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
//                        //$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
//                        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
//                        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
//                        $subdomain_name; // Print the sub domain
//
//                        $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';
//
//                        $logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
//
//                        $message = '
//			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
//			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var leave_start_date}","{var leave_end_date}"),array($cinfo[0]->company_name,site_url(),$from_date,$to_date),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
//
//                        /*
//                        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
//                        $this->email->to($user_info[0]->email);
//
//                        $this->email->subject($subject);
//                        $this->email->message($message);
//
//                        $this->email->send();
//                        */
//
//                        require '../mail/gmail.php';
//                        $mail->addAddress($user_info[0]->email, $full_name);
//                        $mail->Subject = $subject;
//                        $mail->msgHTML($message);
//
//                        if (!$mail->send()) {
//                            //echo "Mailer Error: " . $mail->ErrorInfo;
//                        } else {
//                            //echo "Message sent!";
//                        }
//
//                    }
//                }
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }
    public function check_leave_balance($id=null){
        $employee_id = $this->input->post('employee_id')??$id;
        $date = $this->input->post('date');
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
        $leave_salary_entries = $this->Xin_model->get_leave_salary_entries_all($employee_id);
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


//        if($date) {
//            $leave_balance = $this->Xin_model->get_leave_balance($employee_id,$date);
//        }
//        else {
//            $leave_balance = $this->Xin_model->get_leave_balance($employee_id);
//        }

        if($id)
            return $avlbalance;
        else
            echo $avlbalance;



    }
    public function delete_leave() {
        if($this->input->post('type')=='delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(4);
            $result = $this->Timesheet_model->delete_annual_leave_record($id);
            $this->db->where('annual_leave_id',$id);
            $this->db->delete('leave_salary');

            if($result) {
                $Return['result'] = 'Leave deleted.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
        }
    }




}
