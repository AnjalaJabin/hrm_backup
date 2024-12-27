<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gratuity extends MY_Controller {

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
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('104',$role_resources_ids)) {

            if (!empty($session)) {

            } else {
                redirect('');
            }
        }

        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees();
        $data['breadcrumbs'] = 'Gratuity Details';
        $data['path_url'] = 'gratuity';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('104',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("gratuity/gratuity_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    public function gratuity_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("gratuity/gratuity_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $ticket = $this->Tickets_model->get_gratuity_details_employees();

        $data = array();

        foreach($ticket->result() as $r) {
            $user =$this->Xin_model->read_user_info($r->employee_id);
            $user_name = $user[0]->first_name.' '.$user[0]->last_name;

            // get user > employee_
            if($r->added_by) {
                $employee = $this->Xin_model->read_user_info($r->added_by);
                $employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;

            }
            else {
                $employee_name = '';
            }            // employee full

            // ticket date and time
            if($r->paid_date&&$r->paid_date!='0000-00-00')
                $paid_date = date('jS M Y', strtotime($r->paid_date));
            else
                $paid_date='Not Available';

            $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                $r->employee_id,
                $user_name,
                $r->department_name,
                $r->amount,
                $paid_date,
                $employee_name,
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

    public function gratuity_list_employees()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("gratuity/gratuity_list", $data);
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
            $current_balance = $this->check_gratuity_balance($r->user_id);
            $doj = $r->date_of_joining;
            $joined_date = date('jS M Y', strtotime($r->date_of_joining));

            $current_date = new DateTime();
            $date_of_joining = new DateTime($doj);

            $diff = $date_of_joining->diff($current_date);
            $unpaid_leaves =$this->Timesheet_model->count_all_unpaid_leaves($r->user_id);
            $yearsOfService = $diff->y + ($diff->m / 12) + ($diff->d / 365);
            if($unpaid_leaves)
                $yearsOfService = $yearsOfService -($unpaid_leaves / 365); // add unpaid leaves to years of service

            $previous_encashments =$this->Payroll_model->get_previous_encashments($r->user_id);

            $total_grat= $current_balance+$previous_encashments;
            if(!$r->gratuity_eligibilty){
                $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
//                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->user_id,
                    $r->employee_id,
                    $r->first_name." ".$r->last_name,
                    $r->department_name,
                    $joined_date,
                    number_format($yearsOfService,3),
                    "Not Eligible",
                    "Not Eligible",
                    "Not Eligible",
                );
            }else{
                $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
//                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->user_id,
                    $r->employee_id,
                    $r->first_name." ".$r->last_name,
                    $r->department_name,
                    $joined_date,
                    number_format($yearsOfService,3),
                    number_format($total_grat,2),
                    number_format($previous_encashments,0),
                    number_format($current_balance,2),
                );
            }
            // status$total_grat

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

    public function comments_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        //$id = $this->input->get('ticket_id');
        $id = $this->uri->segment(3);
        $session = $this->session->userdata('username');
        $ses_user = $this->Xin_model->read_user_info($session['user_id']);
        if(!empty($session)){
            $this->load->view("tickets/ticket_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $comments = $this->Tickets_model->get_comments($id);

        $data = array();

        foreach($comments->result() as $r) {

            // get user > employee_
            $employee = $this->Xin_model->read_user_info($r->user_id);
            // employee full name
            $employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
            // get designation
            $_designation = $this->Designation_model->read_designation_information($employee[0]->designation_id);
            // created at
            $created_at = date('h:i A', strtotime($r->created_at));
            $_date = explode(' ',$r->created_at);
            $date = $this->Xin_model->set_date_format($_date[0]);
            // profile picture
            if($employee[0]->profile_picture!='' && $employee[0]->profile_picture!='no file') {
                $u_file = base_url().'uploads/profile/'.$employee[0]->profile_picture;
            } else {
                if($employee[0]->gender=='Male') {
                    $u_file = base_url().'uploads/profile/default_male.jpg';
                } else {
                    $u_file = base_url().'uploads/profile/default_female.jpg';
                }
            }
            ///
            if($ses_user[0]->user_role_id==1){
                $link = '<a class="c-user text-black" href="'.site_url().'employees/detail/'.$r->user_id.'"><span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span></a>';
            } else{
                $link = '<span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span>';
            }

            if($ses_user[0]->user_role_id==1 || $ses_user[0]->user_id==$r->user_id){
                $dlink = '<div class="media-right">
							<div class="c-rating">
							<span data-toggle="tooltip" data-placement="top" title="Delete">
								<a class="btn btn-danger btn-sm delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'.$r->comment_id.'">
			  <i class="ti-trash m-r-0-5"></i>Delete</a></span>
							</div>
						</div>';
            } else {
                $dlink = '';
            }

            $function = '<div class="c-item">
					<div class="media">
						<div class="media-left">
							<div class="avatar box-48">
							<img class="b-a-radius-circle" src="'.$u_file.'">
							</div>
						</div>
						<div class="media-body">
							<div class="mb-0-5">
								'.$link.'
								<span class="font-90 text-muted">'.$date.' '.$created_at.'</span>
							</div>
							<div class="c-text">'.$r->ticket_comments.'</div>
						</div>
						'.$dlink.'
					</div>
				</div>';

            $data[] = array(
                $function
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $comments->num_rows(),
            "recordsFiltered" => $comments->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // attachment list
    public function attachment_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        //$id = $this->input->get('ticket_id');
        $id = $this->uri->segment(3);
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("tickets/ticket_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $attachments = $this->Tickets_model->get_attachments($id);
        if($attachments->num_rows() > 0) {
            $data = array();

            foreach($attachments->result() as $r) {

                $data[] = array('<span data-toggle="tooltip" data-placement="top" title="Download"><a href="'.site_url().'download?type=ticket&filename='.$r->attachment_file.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="Download"><i class="fa fa-download"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete-file" data-toggle="modal" data-target=".delete-modal-file" data-record-id="'. $r->ticket_attachment_id . '" title="Delete"><i class="fa fa-trash-o"></i></button></span>',
                    $r->file_title,
                    $r->file_description,
                    $r->created_at
                );
            }

            $output = array(
                "draw" => $draw,
                "recordsTotal" => $attachments->num_rows(),
                "recordsFiltered" => $attachments->num_rows(),
                "data" => $data
            );
        } else {
            $data[] = array('','','','');


            $output = array(
                "draw" => $draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => $data
            );
        }
        echo json_encode($output);
        exit();
    }

    public function read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('ticket_id');
        $result = $this->Tickets_model->read_encashment_info($id);
        $balance=$this->check_gratuity_balance($result[0]->employee_id);
        $loan_balance=$this->check_loan_balance($result[0]->employee_id);
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
    public function check_loan_balance($emp_id=null){
        $employee_id = $this->input->post('employee_id')??$emp_id;
        $loan_amount = $this->Payroll_model->get_loan_balance($employee_id);
        if($emp_id)
            return $loan_amount;
        else
            echo $loan_amount;
    }
    public function check_gratuity_balance($id=null){
        $employee_id = $this->input->post('employee_id')??$id;
        $date = $this->input->post('date');

        $user_info = $this->Xin_model->read_user_info($employee_id);
        $salary_data = $this->Payroll_model->read_salary_information($employee_id);
        $previous_encashments =$this->Payroll_model->get_previous_encashments($employee_id);
        if($salary_data&&$user_info[0]->gratuity_eligibilty) {
            $salary = $salary_data[0]->basic_salary;

            $doj = $user_info[0]->date_of_joining;
            if($date) {
                $current_date = new DateTime($date);
            }else{
                $current_date = new DateTime();

            }
            $date_of_joining = new DateTime($doj);

            $diff = $date_of_joining->diff($current_date);

            $yearsOfService = $diff->y + ($diff->m / 12) + ($diff->d / 365);
            $unpaid_leaves =$this->Timesheet_model->count_all_unpaid_leaves($employee_id);

            if($unpaid_leaves)
                $yearsOfService = $yearsOfService - ($unpaid_leaves / 365); // add unpaid leaves to years of service

            $dailyWage = $salary /30;

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
                return round(floatval($final_gratuity),2);
            else
                echo round(floatval($final_gratuity),2);

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

//            if($balance<$this->input->post('amount')){
//                $Return['error'] = "The employee doesn't have enough gratuity balance";
//
//            }
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

    // Validate and update info in database
    public function update() {

        if($this->input->post('edit_type')=='gratuity') {

            $id = $this->uri->segment(3);

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

//            if($balance<$this->input->post('amount')){
//                $Return['error'] = "The employee doesn't have enough gratuity balance";
//
//            }
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }


            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'amount' => $this->input->post('amount'),
                'paid_date' => $this->input->post('date'),
                'remarks ' => $qt_description,


            );

            $result = $this->Tickets_model->update_gratuity_record($data,$id);

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
