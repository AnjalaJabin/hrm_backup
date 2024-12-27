<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compoff extends MY_Controller {

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
        $data['breadcrumbs'] = 'Compensatory Leave Details';
        $data['path_url'] = 'compoff';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('105',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("compoff/compoff_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    public function compoff_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("compoff/compoff_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $ticket = $this->Tickets_model->get_total_compoff_employees();

        $data = array();

        foreach($ticket->result() as $r) {
            // get user > employee_
            $employee = $this->Xin_model->read_user_info($r->added_by);
            $employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;

            if($r->status==1): $status = '<span class="tag tag-success">Open</span>'; else: $status = '<span class="tag tag-primary">Closed</span>';  endif;
            $date = date('jS M Y', strtotime($r->created_date));

            $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a target="_blank" href="'.base_url().'compoff/read_entry/'.$r->employee_id.'" ><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-ticket_id="'. $r->employee_id . '"><i class="fa fa-arrow-circle-right"></i></button></a></span>',
                $r->emp_id,
                $r->first_name ." ".$r->last_name,
                $r->department_name,
                $r->total_off,
                $status,
                $employee_name,
                $date

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
    public function compoff_list_employee()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("compoff/compoff_list", $data);
        } else {
            redirect('');
        }
        $id = $this->input->get('emp_id');
        $compoffs = $this->Tickets_model->read_compoff_info_employee($id);

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $data = array();

        foreach($compoffs->result() as $r) {
            // get user > employee_
            $employee = $this->Xin_model->read_user_info($r->added_by);
            $employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;

            if($r->status==1): $status = '<span class="tag tag-success">Open</span>'; else: $status = '<span class="tag tag-primary">Closed</span>';  endif;
            $date = date('jS M Y', strtotime($r->created_date));
            $desc = htmlspecialchars_decode(stripslashes($r->remarks));

// Limit the string to 200 characters
            if (strlen($desc) > 200) {
                $desc = substr($desc, 0, 200) . '...';
            }
            $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                $r->emp_id,
                $r->leave_no,
                $desc,
                $status,
                $employee_name,
                $date

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $compoffs->num_rows(),
            "recordsFiltered" => $compoffs->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('ticket_id');
        $result = $this->Tickets_model->read_compoff_info($id);
        $data = array(
            'id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'leave_no' => $result[0]->leave_no,
            'status' => $result[0]->status,
            'remarks' => $result[0]->remarks,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('compoff/dialog_compoff', $data);
        } else {
            redirect('');
        }
    }


    public function read_entry()

    {
        $data['title'] = $this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Compensatory Leave Details For Employees';
        $data['path_url'] = 'compoff_detail';
        $id = $this->uri->segment(3);
        $result = $this->Tickets_model->read_compoff_info_employee($id);
        $data['compoff_data']=$result->result();
        $data['all_employees'] = $this->Xin_model->all_employees();

        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();

        if(in_array('105',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("compoff/compoff_details", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
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
    public function add_leave() {

        if($this->input->post('add_type')=='compoff') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }elseif($this->input->post('days')==='') {
                $Return['error'] = "The Days field is required.";
            }
            $description = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }


            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'leave_no' => $this->input->post('days'),
                'remarks ' => $qt_description,
                'added_by' => $this->input->post('user_id'),
                'status'=>1,
                'created_date' => date('Y-m-d'),

            );
            $result = $this->Tickets_model->add_compoff($data);
            if ($result == TRUE) {
                $Return['result'] = 'Compensatory Leave Added.';

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

    // Validate and update info in database
    public function update() {

        if($this->input->post('edit_type')=='compoff') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }elseif($this->input->post('days')==='') {
                $Return['error'] = "The leave days field is required.";
            }
            $description = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }


            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'leave_no' => $this->input->post('days'),
                'remarks ' => $qt_description,
                'status'=>$this->input->post('status'),

            );
            $result = $this->Tickets_model->update_compoff_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Entry updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

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
            $result = $this->Tickets_model->delete_compoff_record($id);
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
