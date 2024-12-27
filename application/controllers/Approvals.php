<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approvals extends MY_Controller {

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
        $data['breadcrumbs'] = 'Approvals';
        $data['path_url'] = 'approval';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('100',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("approval/approval_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    public function leave_list() {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("approval/approval_list", $data);
        } else {
            redirect('');
        }
        $user_details = $this->Xin_model->read_user_info($session['user_id']);
        
        $designation_id = $user_details[0]->designation_id;
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));



        $leave = $this->Timesheet_model->get_leaves_for_approval($designation_id);

        $data = array();
        foreach($leave->result() as $r) {

            // get start date and end date
            $user = $this->Xin_model->read_user_info($r->employee_id);
            if(!empty($user))
            {
                $full_name = $user[0]->first_name. ' '.$user[0]->last_name;

                // get leave type
                $leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);

                $applied_on = $this->Xin_model->set_date_format($r->applied_on);
                $duration = $this->Xin_model->set_date_format($r->from_date).' to '.$this->Xin_model->set_date_format($r->to_date);

                // get status
                if($r->status==1): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==2): $status = '<span class="tag tag-success">Accepted</span>'; elseif($r->status==3): $status = '<span class="tag tag-warning">Rejected</span>'; endif;
                if($r->approval==1): $approvalstatus = '<span class="tag tag-danger">Approved</span>'; elseif($r->approval==2): $approvalstatus = '<span class="tag tag-success">Accepted</span>'; elseif($r->approval==3): $approvalstatus = '<span class="tag tag-warning">Rejected</span>';else:$approvalstatus = '<span class="tag tag-warning">Not Available</span>'; endif;


                $data[] = array(
                    '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'approvals/approve_leave/id/'.$r->leave_id.'/"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span>',
                    $full_name,
                    $leave_type[0]->type_name,
                    $duration,
                    $applied_on,
                    $r->reason,
                    $approvalstatus,
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
    public function approve_leave() {
        $data['title'] = $this->Xin_model->site_title();
        $leave_id = $this->uri->segment(4);
        // leave applications
        $result = $this->Timesheet_model->read_leave_information($leave_id);
        // get leave types
        $type = $this->Timesheet_model->read_leave_type_information($result[0]->leave_type_id);
        // get employee
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);

        $data = array(
            'title' => $this->Xin_model->site_title(),
            'type' => $type[0]->type_name,
            'role_id' => $user[0]->user_role_id,
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'leave_id' => $result[0]->leave_id,
            'employee_id' => $result[0]->employee_id,
            'leave_type_id' => $result[0]->leave_type_id,
            'from_date' => $result[0]->from_date,
            'to_date' => $result[0]->to_date,
            'applied_on' => $result[0]->applied_on,
            'reason' => $result[0]->reason,
            'remarks' => $result[0]->remarks,
            'status' => $result[0]->status,
            'approval' => $result[0]->approval,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Xin_model->all_employees(),
            'all_leave_types' => $this->Timesheet_model->all_leave_types(),
        );
        $data['breadcrumbs'] = 'Approve Leave';
        $data['path_url'] = 'approve_leave';
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $data['subview'] = $this->load->view("approval/approve_leave", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }

    }
    public function update_leave_status() {

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

            $result = $this->Timesheet_model->update_leave_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Leave status updated.';

            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    public function annual_leave_list() {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("approval/approval_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $user_details = $this->Xin_model->read_user_info($session['user_id']);
        $designation_id = $user_details[0]->designation_id;


        $leave = $this->Employees_model->get_annual_leaves_for_approval($designation_id);
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
                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'approvals/annual_leave_details/id/'.$r->id.'/"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span>',
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
        $id = $this->input->get('ticket_id');
        $result = $this->Tickets_model->read_encashment_info($id);
        $balance=$this->check_gratuity_balance($result[0]->employee_id);
        $balance = floatval(str_replace(',', '', $balance))+$result[0]->amount;
        $data = array(
            'id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'amount' => $result[0]->amount,
            'paid_date' => $result[0]->paid_date,
            'description' => $result[0]->remarks,
            'balance'=>$balance,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('gratuity/dialog_gratuity', $data);
        } else {
            redirect('');
        }
    }
    public function check_loan_balance(){
        $employee_id = $this->input->post('employee_id');
        $loan_amount = $this->Payroll_model->get_loan_balance($employee_id);
        echo $loan_amount;
    }
    public function annual_leave_details() {
        $data['title'] = $this->Xin_model->site_title();
        $leave_id = $this->uri->segment(4);
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
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $data['breadcrumbs'] = 'Leave Detail';
        $data['path_url'] = 'annual_leave_approve';
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $data['subview'] = $this->load->view("approval/annual_leave_approve", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }

    }

    public function check_gratuity_balance($id=null){
        $employee_id = $this->input->post('employee_id')??$id;
        $user_info = $this->Xin_model->read_user_info($employee_id);
        $salary_data = $this->Payroll_model->read_salary_information($employee_id);
        $previous_encashments =$this->Payroll_model->get_previous_encashments($employee_id);
        if($salary_data) {
            $salary = $salary_data[0]->basic_salary;

            $doj = $user_info[0]->date_of_joining;
            $current_date = new DateTime();
            $date_of_joining = new DateTime($doj);

            $diff = $date_of_joining->diff($current_date);
            $diff = $date_of_joining->diff($current_date);

            $yearsOfService = $diff->y + ($diff->m / 12) + ($diff->d / 365);
            $dailyWage = $salary / 30;

            // Calculate 21 days' salary
            $twentyOneDaysSalary = $dailyWage * 21;

            // Calculate gratuity based on years of service
            if ($yearsOfService < 1) {
                // No gratuity for less than 1 year of service
                $gratuity = 0;
            } else {
                // Calculate gratuity for each year of service separately and add them together
                $gratuity = 0;
                if($yearsOfService<=1)
                {
                    $gratuity=0;
                }
                elseif ($yearsOfService>1&&$yearsOfService<=5){
                    $gratuity =$yearsOfService*$twentyOneDaysSalary;
                }else{

                    $gratuity=(5*$twentyOneDaysSalary)+($yearsOfService-5)*$salary;
                }
            }
            $final_gratuity =$gratuity -$previous_encashments;
            if($id)
                return number_format($final_gratuity, 2);
            else
                echo number_format($final_gratuity, 2);

            // Return gratuity amount
        }else{
            if($id)
                return 0;
            else
                echo "0.00";
        }


    }

    // Validate and add info in database
    public function add_gratuity() {

        if($this->input->post('add_type')=='gratuity') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }elseif($this->input->post('amount')==='') {
                $Return['error'] = "The amount field is required.";
            }elseif($this->input->post('date')==='') {
                $Return['error'] = "The date field is required.";
            }elseif($this->input->post('balance')==='') {
                $Return['error'] = "The Balance field is required.";
            }
            $description = $this->input->post('description');
            $balance = $this->input->post('balance');
            $balance = floatval(str_replace(',', '', $balance));

            if($balance<$this->input->post('amount')){
                $Return['error'] = "The employee doesn't have enough gratuity balance";

            }
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }


            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'amount' => $this->input->post('amount'),
                'paid_date' => $this->input->post('date'),
                'remarks ' => $qt_description,
                'added_by' => $this->input->post('user_id'),

                'created_date' => date('Y-m-d'),

            );
            $result = $this->Tickets_model->add_gratuity($data);
            if ($result == TRUE) {
                $Return['result'] = 'Gratuity  Encashment Added.';

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
                /*                if($setting[0]->enable_email_notification == 'yes') {
                                    //load email library
                                    $this->load->library('email');
                                    $this->email->set_mailtype("html");
                                    //get company info
                                    $cinfo = $this->Xin_model->read_company_setting_info(1);
                                    //get email template
                                    $template = $this->Xin_model->read_email_template(15);
                                    //get employee info
                                    $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

                                    $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

                                    $subject = str_replace('{var ticket_code}',$ticket_code,$template[0]->subject);
                                    $logo = base_url().'uploads/logo/'.$cinfo[0]->logo;

                                    $message = '
                            <div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
                            <img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var ticket_code}"),array($cinfo[0]->company_name,site_url(),$ticket_code),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

                                    /*
                                    $this->email->from($user_info[0]->email, $full_name);
                                    $this->email->to($cinfo[0]->email);

                                    $this->email->subject($subject);
                                    $this->email->message($message);

                                    $this->email->send();
                                    */

//                    require '../mail/gmail.php';
//                    $mail->addAddress($user_info[0]->email, $full_name);
//                    $mail->Subject = $subject;
//                    $mail->msgHTML($message);
//
//                    if (!$mail->send()) {
//                        //echo "Mailer Error: " . $mail->ErrorInfo;
//                    } else {
//                        //echo "Message sent!";
//                    }
//                }

            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database
    public function set_comment() {

        if($this->input->post('add_type')=='set_comment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('xin_comment')==='') {
                $Return['error'] = "The comment field is required.";
            }
            $xin_comment = $this->input->post('xin_comment');
            $qt_xin_comment = htmlspecialchars(addslashes($xin_comment), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'ticket_comments' => $qt_xin_comment,
                'ticket_id' => $this->input->post('comment_ticket_id'),
                'user_id' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y h:i:s')

            );
            $result = $this->Tickets_model->add_comment($data);
            if ($result == TRUE) {
                $Return['result'] = 'Ticket comment added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database
    public function add_attachment() {

        if($this->input->post('add_type')=='dfile_attachment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('file_name')==='') {
                $Return['error'] = "The file name field is required.";
            } else if($_FILES['attachment_file']['size'] == 0) {
                $Return['error'] = 'Select file.';
            } else if($this->input->post('file_description')==='') {
                $Return['error'] = 'The description field is required.';
            }
            $description = $this->input->post('file_description');
            $file_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }

            // is file upload
            if(is_uploaded_file($_FILES['attachment_file']['tmp_name'])) {
                //checking image type
                $allowed =  array('png','jpg','jpeg','pdf','doc','docx','xls','xlsx','txt');
                $filename = $_FILES['attachment_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                if(in_array($ext,$allowed)){
                    $tmp_name = $_FILES["attachment_file"]["tmp_name"];
                    $attachment_file = "uploads/ticket/";
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $name = basename($_FILES["attachment_file"]["name"]);
                    $newfilename = 'ticket_'.round(microtime(true)).'.'.$ext;
                    move_uploaded_file($tmp_name, $attachment_file.$newfilename);
                    $fname = $newfilename;
                } else {
                    $Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, pdf, doc, docx, xls, xlsx, txt";
                }
            }

            $data = array(
                'ticket_id' => $this->input->post('c_ticket_id'),
                'upload_by' => $this->input->post('user_file_id'),
                'file_title' => $this->input->post('file_name'),
                'file_description' => $file_description,
                'attachment_file' => $fname,
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Tickets_model->add_new_attachment($data);
            if ($result == TRUE) {
                $Return['result'] = 'Ticket attachment added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }
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

    public function details()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->uri->segment(3);
        $result = $this->Tickets_model->read_flight_ticket_information($id);
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);
        $data = array(
            'title' => $this->Xin_model->site_title(),
            'ticket_id' => $result[0]->id,
            'subject' => $result[0]->remarks,
            'ticket_code' => $result[0]->ticket_no,
            'employee_id' => $result[0]->employee_id,
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'ticket_priority' => "abcd",
            'created_at' => $result[0]->created_date,
            'description' => $result[0]->remarks,
            'assigned_to' => "abcd",
            'ticket_status' => $result[0]->status,
            'ticket_note' => "abcd",
            'ticket_remarks' => $result[0]->remarks,
            'message' => "ll",
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $data['breadcrumbs'] = 'Tickets Detail';
        $data['path_url'] = 'tickets_detail';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(!empty($session)){
            $data['subview'] = $this->load->view("tickets/ticket_details", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }
    }

    // Validate and update info in database // assign_ticket
    public function assign_ticket() {

        if($this->input->post('type')=='ticket_user') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            if(null!=$this->input->post('assigned_to')) {
                $assigned_ids = implode(',',$this->input->post('assigned_to'));
                $employee_ids = $assigned_ids;
            } else {
                $employee_ids = '';
            }

            $data = array(
                'assigned_to' => $employee_ids
            );
            $id = $this->input->post('ticket_id');
            $result = $this->Tickets_model->assign_ticket_user($data,$id);
            if ($result == TRUE) {
                $Return['result'] = 'Ticket employees has been updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

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

    // Validate and update info in database // add_note
    public function add_note() {

        if($this->input->post('type')=='add_note') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $data = array(
                'ticket_note' => $this->input->post('ticket_note')
            );
            $id = $this->input->post('token_note_id');
            $result = $this->Tickets_model->update_note($data,$id);
            if ($result == TRUE) {
                $Return['result'] = 'Ticket note updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    public function ticket_users() {

        $data['title'] = $this->Xin_model->site_title();
        $id = $this->uri->segment(3);

        $data = array(
            'ticket_id' => $id,
            'all_designations' => $this->Designation_model->all_designations(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("tickets/get_ticket_users", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function delete() {
        if($this->input->post('is_ajax') == 2) {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Tickets_model->delete_gratuity_record($id);
            if(isset($id)) {
                $Return['result'] = 'Entry deleted.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
        }
    }

    public function comment_delete() {
        if($this->input->post('data') == 'ticket_comment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Tickets_model->delete_comment_record($id);
            if(isset($id)) {
                $Return['result'] = 'Ticket comment deleted.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
        }
    }

    public function attachment_delete() {
        if($this->input->post('data') == 'ticket_attachment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $result = $this->Tickets_model->delete_attachment_record($id);
            if(isset($id)) {
                $Return['result'] = 'Ticket attachment deleted.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
        }
    }
}
