<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends MY_Controller {

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
        $data['breadcrumbs'] = 'Requests';
        $data['path_url'] = 'requests';
        $data['my_request']=0;

        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('107',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("requests/request_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }


    public function my_requests()
    {
        $session = $this->session->userdata('username');
        if(!empty($session)){

        } else {
            redirect('');
        }

        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees();
        $data['breadcrumbs'] = 'Requests';
        $data['path_url'] = 'requests';
        $data['my_request']=1;
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
            if(!empty($session)){
                $data['subview'] = $this->load->view("requests/request_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }

    }


    public function request_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("requests/request_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        if($this->input->get("my_request")==1) {
            $ticket = $this->Tickets_model->get_employee_requests($_SESSION['user_id']);
        }else{
            $ticket = $this->Tickets_model->get_employee_requests();

        }

        $data = array();

        foreach($ticket->result() as $r) {

            if($r->status==1): $status = '<span class="tag tag-warning">Pending</span>'; elseif($r->status==2): $status = '<span class="tag tag-success">Approved</span>';elseif($r->status==3): $status = '<span class="tag tag-danger">Rejected</span>';elseif($r->status==4): $status = '<span class="tag tag-primary">Issued</span>'; else:$status="--";endif;
            $created_at = date('jS M Y', strtotime($r->created_at));

// Remove HTML tags
            $desc = htmlspecialchars_decode(stripslashes($r->description));

// Limit the string to 200 characters
            if (strlen($desc) > 200) {
                $desc = substr($desc, 0, 200) . '...';
            }
            $action='';
            if($this->input->get("my_request")==0) {
                $action = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-request_id="' . $r->id . '"><i class="fa fa-eye"></i></button></span>';
            }
            $action.='<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>';

            // status
            $data[] = array(
//                '<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'flights/details/'.$r->id.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span><span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-ticket_id="'. $r->id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '"><i class="fa fa-trash-o"></i></button></span>',
                $action,
                $r->user_id,
                $r->employee_id,
                $r->first_name." ".$r->last_name,
                $r->department_name,
                $desc,
                $status,
                $created_at,
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
            $created_at = date('jS M Y', strtotime($r->created_at));
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
        $result = $this->Tickets_model->read_request_information($id);
        $my_request=$this->input->get("my_request");


        $data = array(
            'ticket_id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'status' => $result[0]->status,
            'description' => $result[0]->description,
            'my_request'=>$my_request,
            'all_employees' => $this->Xin_model->all_employees(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('requests/dialog_request', $data);
        } else {
            redirect('');
        }
    }
    public function read_view()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('request_id');
        $result = $this->Tickets_model->read_request_information($id);
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);

        $data = array(
            'request_id' => $result[0]->id,
            'employee_id' => $result[0]->employee_id,
            'status' => $result[0]->status,
            'created_at' => $result[0]->created_at,
            'description' => $result[0]->description,
            'all_employees' => $this->Xin_model->all_employees(),
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,

        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('requests/view_request', $data);
        } else {
            redirect('');
        }
    }
    public function check_ticket_balance(){
        $employee_id = $this->input->post('employee_id');
        $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

        $result = $this->Xin_model->check_ticket_balance($employee_id);
        if($result->ticket_balance && $result->ticket_balance!=0.0000) {
            $ticket_balance = $result->ticket_balance +$result->remaining_balance;
        }
        else{
            $doj= $user_info[0]->date_of_joining;
            $current_date = new DateTime();
            $date_of_joining = new DateTime($doj);
            $interval = date_diff($current_date, $date_of_joining);
            $days_since_doj = $interval->days;
            $ticket_balance = $days_since_doj / 365;
        }
        echo $ticket_balance;


    }

    // Validate and add info in database
    public function add_request() {

        if($this->input->post('add_type')=='request') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }else if(($this->input->post('description')==='') ||(!html_entity_decode(strip_tags($this->input->post('description'))))){
                $Return['error'] = "The Description Field is required.";
            }
            $description = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }


            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'description' => $qt_description,
                'status' => $this->input->post('status')??1,
                'created_at' => date('Y-m-d'),

            );
            if($this->input->post("employee_id")){
                $data['employee_id']=  $this->input->post('employee_id');

            }
            else{
                $data['employee_id']=$_SESSION['user_id'];
            }

            $result = $this->Tickets_model->add_request($data);
            if ($result == TRUE) {
                $Return['result'] = 'Request Entry created.';

                //get setting info

            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }
    public function update() {

        if($this->input->post('edit_type')=='ticket') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            }else if(($this->input->post('description')==='') ||(!html_entity_decode(strip_tags($this->input->post('description'))))){
                $Return['error'] = "The Description Field is required.";
            }

            $description = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($Return['error']!=''){
                $this->output($Return);
            }


            $data = array(
                'description' => $qt_description,
                'status' => $this->input->post('status')??1,

            );
            if($this->input->post("employee_id")){
                $data['employee_id']=  $this->input->post('employee_id');

            }
            $result = $this->Tickets_model->update_request($data,$id);
            if ($result == TRUE) {
                $Return['result'] = 'Request Entry Updated.';

                //get setting info

            }
            else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }
    public function update_status() {

        if($this->input->post('edit_type')=='ticket') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            $data = array(
                'status' => $this->input->post('status'),

            );
            $result = $this->Tickets_model->update_request($data,$id);
            if ($result == TRUE) {
                $Return['result'] = 'Request Status Updated.';

                //get setting info

            }

            else {
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
            $result = $this->Tickets_model->delete_request_record($id);
            if(isset($id)) {
                $Return['result'] = 'Request deleted.';
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
