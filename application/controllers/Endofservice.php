<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Workable Zone License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.workablezone.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to workablezone@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please contact us at workablezone@gmail.com for more information.
 *
 * @author   Mian Abdullah Jan - Workable Zone
 * @package  Workable Zone - Employee Exit
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Endofservice extends MY_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Pdf');
        $this->load->library('../controllers/mypdf');

//load the model
        $this->load->model("Employee_exit_model");
        $this->load->model("Xin_model");
        $this->load->model("Designation_model");
        $this->load->model("Department_model");
        $this->load->model("Payroll_model");
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
        $data['all_exit_types'] = $this->Employee_exit_model->all_exit_types();
        $data['breadcrumbs'] = 'End Of Service';
        $data['path_url'] = 'employee_exit';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('108',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("exit/exit_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }
    public function get_employee_data(){
        $employee_id = $this->uri->segment(3);
        $user_info = $this->Xin_model->get_employee_details($employee_id);
        $data=array(
            'employee_id'=>$user_info[0]->employee_id,
            'name'=>$user_info[0]->first_name." ".$user_info[0]->last_name,
            'department'=>$user_info[0]->department_name,
            'designation'=>$user_info[0]->designation_name,
            'email'=>$user_info[0]->email,
            'doj'=>date('jS M,Y',strtotime($user_info[0]->date_of_joining)),
        );
        echo json_encode($data);

    }
    public function get_expenses(){
        $employee_id = $this->uri->segment(3);
        $date = $this->uri->segment(4);
        if(!$date){
            $newDate=date('Y-m');
        }else{
            $newDate = date("Y-m", strtotime($date));

        }
        $total_expenses =$this->Payroll_model->get_expenses_by_month_user($employee_id,$date);
        echo $total_expenses;

    }
    public function get_salary(){
        $employee_id = $this->uri->segment(3);
        $date = $this->uri->segment(4);
        if(!$date){
            $newDate=date('Y-m');

            $day =$this->Xin_model->get_number_of_days($newDate);
;

        }else{
            $newDate = date("Y-m", strtotime($date));
            $day = date('d', strtotime($date));

        }
        $no_of_days =$this->Xin_model->get_number_of_days($newDate);

        $deduct_leave_sal=0;
        $unpaid_off_per_month=0;
        $payment_check = $this->Payroll_model->read_make_payment_payslip_check($employee_id,$date);
        if($payment_check->num_rows() > 0) {
            echo "0.00";
        }else {
            $grade_template = $this->Payroll_model->read_salary_information_by_date($employee_id, $date);
            if (!is_null($grade_template)) {
                if ($grade_template[0]->basic_salary) {
                    $total_allowance = floatval($grade_template[0]->total_allowance);
                    $basic_salary = floatval($grade_template[0]->basic_salary);
                    $net_salary = floatval($grade_template[0]->net_salary);
                    $create_id = $grade_template[0]->salary_id;
//                    $deduct_salary = 0;
                    $payment_month = strtotime($date);
                    $p_month = date('F Y', $payment_month);
                    $all_leave_types = $this->Timesheet_model->all_leave_types();
                    $unpaid_leaves = 0;
                    $deduct_leave_sal = 0;
                    $unpaid_off = 0;
                    $leaves_per_yeartype = array();
                    foreach ($all_leave_types as $type) {
                        $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id, $employee_id, date('Y', $payment_month));
//                        echo $this->db->last_query();
//                        die;
                        if (($count_l > $type->days_per_year) && ($type->type_name != "Unpaid Leave")) {
                            $unpaid_leaves = $unpaid_leaves + ($count_l - $type->days_per_year);
                        }else if($type->type_name=="Unpaid Leave"){
                            $unpaid_leaves+=$count_l;
                            $unpaid_off+=$count_l;

                        } else {
                            $unpaid_leaves += 0;
                            $unpaid_off += $count_l;
                            $count_l=0;

                        }
                        $leaves_per_yeartype[$type->leave_type_id] = $count_l;
                    }

                    if ($unpaid_leaves > 0) {
                        $unpaid_leaves_count = $this->Timesheet_model->count_total_un_paid_leaves($employee_id);
                        if ($unpaid_leaves_count < $unpaid_leaves) {
                            $unpaid_leaves = $unpaid_leaves_count + $unpaid_off;
                        }
                    }
                    $unpaid_off_per_month = 0;
                    foreach ($all_leave_types as $type) {
//
                        $count_per_month = $this->Timesheet_model->get_leave_days_month($employee_id, $date, $type->leave_type_id);
                        if ($count_per_month) {
                            if ($type->type_name != "Unpaid Leave") {
                                if ($leaves_per_yeartype[$type->leave_type_id] < $count_per_month)
                                    $unpaid_off_per_month += $leaves_per_yeartype[$type->leave_type_id];
                                else
                                    $unpaid_off_per_month += $count_per_month;
                            } else {
                                $unpaid_off_per_month += $count_per_month;
                            }
                        }

                    }
                    $annual_leaves = $this->Timesheet_model->check_annual_leaves_for_employee($employee_id, $date);
                    if ($annual_leaves)
                        $unpaid_off_per_month = $unpaid_off_per_month + $annual_leaves;
// get advance salary

//
                    $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($employee_id);

                    $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($employee_id);

                    if (!is_null($advance_salary)) {
                        $monthly_installment = $advance_salary[0]->monthly_installment;
                        //check ifpaid
                        $em_advance_amount = floatval($emp_value[0]->advance_amount);
                        $em_total_paid = floatval($emp_value[0]->total_paid);
                        if ($em_advance_amount > $em_total_paid) {
                            $re_amount = $em_advance_amount - $em_total_paid;


                            $ntotal_paid = $emp_value[0]->total_paid;
                            $nadvance = $emp_value[0]->advance_amount;
                            $total_net_salary = $nadvance - $ntotal_paid;
                            $pay_amount = $net_salary - $total_net_salary;
                            $advance_amount = $re_amount;


                        } else {

                            $total_net_salary = $net_salary - 0;
                            $pay_amount = $net_salary - 0;
                            $advance_amount = 0;
                        }
                    } else {
                        $pay_amount = $net_salary -  0 ;
                        $total_net_salary = $net_salary - 0;
                        $advance_amount = 0;
                    }
                    $pay_amount = $pay_amount - 0;
                    $total_net_salary = $pay_amount - 0;
                    $loan_emi = 0;

                    $per_day_sal = ($basic_salary + $total_allowance) / $no_of_days;
                    if ($unpaid_off_per_month > 0) {
                        $unpaid_off_per_month > $no_of_days ? $unpaid_off_per_month = $no_of_days : $unpaid_off_per_month = $unpaid_off_per_month;
                        $deduct_leave_sal = round($per_day_sal * $unpaid_off_per_month);
                        $pay_amount = round($pay_amount - $deduct_leave_sal);
                    }
                    $total_deductions = $deduct_leave_sal + $advance_amount;
//                    if($day>=30)
//                        $total_net = $basic_salary + $total_allowance - $total_deductions ;
//                    else
//                    {

                        $basic_salary=($basic_salary+$total_allowance)/$no_of_days*$day;
                        $total_net=$basic_salary-$total_deductions;
//                    }
                    $net_sal = floatval($total_net );
                } else {

                    $total_deductions = '0.00';
                    $total_net = 0;
                    $net_sal = 0;
                }
            } else {
                $total_deductions = '0.00';
                $total_net = 0;
                $net_sal = 0;
            }
            echo round($net_sal,2);
        }
    }

    public function exit_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("exit/exit_list", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $exit = $this->Employee_exit_model->get_exit();

        $data = array();

        foreach($exit->result() as $r) {

            // get user > employee to exit
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            // get user > added by
            $user_by = $this->Xin_model->read_user_info($r->added_by);
            // user full name
            $added_by = $user_by[0]->first_name.' '.$user_by[0]->last_name;
            // get exit date
            $exit_date = $this->Xin_model->set_date_format($r->exit_date);
            $notice_date = $this->Xin_model->set_date_format($r->notice_date);

            // get exit type
            $exit_type = $this->Employee_exit_model->read_exit_type_information($r->exit_type_id);
//            if($r->exit_interview==0): $exit_interview = 'No'; else: $exit_interview = 'Yes'; endif;
//            if($r->is_inactivate_account==0): $account = 'No'; else: $account = 'Yes'; endif;
            $document ='  <a href="'.base_url().'endofservice/pdf_create/pdf/'.$r->exit_id.'" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create Pdf"><span <i="" class="fa fa-file-pdf-o"></span></a>
        <a target="blank" href="'.base_url().'endofservice/pdf_create/print/'.$r->exit_id.'" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print Document"><span <i="" class="fa fa-print"></span></a> 
     ';
            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-exit_id="'. $r->exit_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-exit_id="'. $r->exit_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->exit_id . '"><i class="fa fa-trash-o"></i></button></span>',
                $full_name,
                $exit_type[0]->type,
                $exit_date,
                $notice_date,
                "AED ".$r->net_amount,
                $added_by,
                $document
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $exit->num_rows(),
            "recordsFiltered" => $exit->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('exit_id');
        $result = $this->Employee_exit_model->read_exit_information($id);
        $data = array(
            'exit_id' => $result[0]->exit_id,
            'employee_id' => $result[0]->employee_id,
            'exit_date' => $result[0]->exit_date,
            'exit_type_id' => $result[0]->exit_type_id,
            'notice_date' => $result[0]->notice_date,
            'reason' => $result[0]->reason,
            'ticket_amount' => $result[0]->ticket_amount,
            'net_amount' => $result[0]->net_amount,
            'gratuity' => $result[0]->gratuity,
            'loan' => $result[0]->loan,
            'pending_salary' => $result[0]->pending_salary,
            'leave_salary' => $result[0]->leave_salary,
            'leave_balance' => $result[0]->leave_balance,
            'ticket_balance' => $result[0]->ticket_balance,
            'other_deductions' => $result[0]->other_deductions,
            'expenses' => $result[0]->expenses,
            'overtime_amount' => $result[0]->overtime_amount,
            'assets_returned' => $result[0]->assets_returned,
            'all_employees' => $this->Xin_model->all_employees(),
            'all_exit_types' => $this->Employee_exit_model->all_exit_types(),
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('exit/dialog_exit', $data);
        } else {
            redirect('');
        }
    }

    // Validate and add info in database
    public function add_exit() {

        if($this->input->post('add_type')=='exit') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);

            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            } else if($this->input->post('exit_date')==='') {
                $Return['error'] = "The exit date field is required.";
            } else if($this->input->post('type')==='') {
                $Return['error'] = "The exit type field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'exit_date' => $this->input->post('exit_date'),
                'notice_date' => $this->input->post('notice_date'),
                'leave_balance' => $this->input->post('leave_balance'),
                'leave_salary' => $this->input->post('leave_salary'),
                'pending_salary' => $this->input->post('pending_salary'),
                'expenses' => $this->input->post('expenses'),
                'gratuity' => $this->input->post('gratuity'),
                'ticket_balance' => $this->input->post('ticket_balance'),
                'ticket_amount' => $this->input->post('ticket_amount'),
                'loan' => $this->input->post('loan'),
                'other_deductions' => $this->input->post('other_deductions'),
                'overtime_amount' => $this->input->post('overtime'),
                'assets_returned' => $this->input->post('returned_assets'),
                'net_amount' => $this->input->post('net_amount'),
                'reason' => $qt_reason,
                'exit_type_id' => $this->input->post('type'),
                'added_by' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Employee_exit_model->add($data);
            $result1 = $this->Employees_model->basic_info(array('is_active'=>0,'date_of_leaving'=>$this->input->post('exit_date')),$this->input->post('employee_id'));

            if ($result == TRUE) {
                $Return['result'] = 'Employee Exit added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database
    public function update() {

        if($this->input->post('edit_type')=='exit') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);

            if($this->input->post('employee_id')==='') {
                $Return['error'] = "The employee field is required.";
            } else if($this->input->post('exit_date')==='') {
                $Return['error'] = "The exit date field is required.";
            } else if($this->input->post('type')==='') {
                $Return['error'] = "The exit type field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'exit_date' => $this->input->post('exit_date'),
                'reason' => $qt_reason,
                'exit_type_id' => $this->input->post('type'),
                'notice_date' => $this->input->post('notice_date'),
                'leave_balance' => $this->input->post('leave_balance'),
                'ticket_amount' => $this->input->post('ticket_amount'),
                'leave_salary' => $this->input->post('leave_salary'),
                'pending_salary' => $this->input->post('pending_salary'),
                'expenses' => $this->input->post('expenses'),
                'gratuity' => $this->input->post('gratuity'),
                'ticket_balance' => $this->input->post('ticket_balance'),
                'other_deductions' => $this->input->post('other_deductions'),
                'assets_returned' => $this->input->post('returned_assets'),
                'overtime_amount' => $this->input->post('overtime_amount'),
                'loan' => $this->input->post('loan'),
                'net_amount' => $this->input->post('net_amount'),


            );
            $exit = $this->Employee_exit_model->read_exit_information($id);

            $result1 = $this->Employees_model->basic_info(array('is_active'=>1),$exit[0]->employee_id);

            $result = $this->Employee_exit_model->update_record($data,$id);
            $result1 = $this->Employees_model->basic_info(array('is_active'=>0),$this->input->post('employee_id'));


            if ($result == TRUE) {
                $Return['result'] = 'Employee Exit updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
        }
    }

    public function delete() {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result'=>'', 'error'=>'');
        $id = $this->uri->segment(3);
        $exit = $this->Employee_exit_model->read_exit_information($id);

        $result1 = $this->Employees_model->basic_info(array('is_active'=>1),$exit[0]->employee_id);
        $result = $this->Employee_exit_model->delete_record($id);


        if(isset($id)) {
            $Return['result'] = 'Employee Exit deleted.';
        } else {
            $Return['error'] = 'Bug. Something went wrong, please try again.';
        }
        $this->output($Return);
    }
    public function pdf_create() {

        //$this->load->library('Pdf');
        $system = $this->Xin_model->read_setting_info(1);
        $re_paid_amount = 0;

        // create new PDF document
        $pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $mode = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $exit = $this->Employee_exit_model->read_exit_information($id);
        $user = $this->Xin_model->read_user_info($exit[0]->employee_id);

        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Xin_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Xin_model->read_company_setting_info(1);
        $salary_data = $this->Payroll_model->read_salary_information($exit[0]->employee_id);
        $unpaid_leaves =$this->Timesheet_model->count_all_unpaid_leaves($exit[0]->employee_id);

        $doj = $user[0]->date_of_joining;
        $current_date = new DateTime($exit[0]->exit_date);
        $date_of_joining = new DateTime($doj);

        $diff = $date_of_joining->diff($current_date);
        $years =$diff->y;
        $months=$diff->m;
        $days=$diff->d;
        $total_days = $diff->format('%a');

        $yearsOfService = $diff->y + ($diff->m / 12) + ($diff->d / 365);

        $p_method = '';

        //$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $company_name = $company[0]->company_name;
        // set default header data
        $c_info_email = $company[0]->email;
        $c_info_phone = $company[0]->phone;
        $country = $this->Xin_model->read_country_info($company[0]->country);
        $c_info_address = $company[0]->address_1.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
        $email_phone_address = "".$this->lang->line('dashboard_email')." : $c_info_email | ".$this->lang->line('xin_phone')." : $c_info_phone \n".$this->lang->line('xin_address').": $c_info_address";
//        $header_string="Payslip #".$payment[0]->make_payment_id."-".date('F Y', strtotime($payment[0]->payment_date));
        $header_string =
        $exit_type = $this->Employee_exit_model->read_exit_type_information($exit[0]->exit_type_id);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('Final Settlement');
        $pdf->SetSubject('Final Settlement');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'FINAL SETTLEMENT', 'RESIGNATION/TERMINATION ADVICE');

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 12));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 10);

// add a page
        $pdf->AddPage();



        $tbl=       '<table  cellpadding="10px">
                        <tr>
                        <td width="60%" style="border-bottom: solid 1px black;">
                        <table cellspacing="3px;">
                        <tr><td width="40%" align="left" style="font-weight: bold;">Employee Name:</td><td  align="left"  width="60%" >'.$user[0]->first_name.' '.$user[0]->last_name.'</td></tr>
                        <tr><td width="40%" align="left" style="font-weight: bold;">Department:</td><td  align="left"  width="60%">'.$department[0]->department_name.'</td></tr>
                        <tr><td width="40%" align="left" style="font-weight: bold;">Designation:</td><td  align="left"  width="60%">'.strtoupper($_des_name[0]->designation_name).'</td></tr>
                        <tr><td  width="40%" align="left" style="font-weight: bold;">Nationality:</td><td align="left"  width="60%">'.$user[0]->nationality.'</td></tr>
                        <tr><td  width="40%" align="left" style="font-weight: bold;">Date of Joining:</td><td align="left"  width="60%">'.date('jS M Y',strtotime($user[0]->date_of_joining)).'</td></tr>
                        <tr><td></td></tr>
                        <tr><td></td></tr>

                        </table>
                        </td>
                        <td width="40%" style="border-bottom: solid 1px black;">
                        <table cellspacing="3px;">
                        <tr><td align="right" ><b>Employee Number:</b>'.$user[0]->employee_id.'</td></tr>
                         <tr><td align="right"><b>Contract Type:</b>Unlimited</td></tr>

                        <tr><td align="right" ><b>Gratuity Entitled</b>:UAE Gratuity Law</td></tr>
                        <tr><td align="right"><b>Exit Type:</b>'.$exit_type[0]->type.'</td></tr>
                        <tr><td align="right" ><b>Notice Date:</b>'.date('M d,Y',strtotime($exit[0]->notice_date)).'</td></tr>
                        <tr><td align="right" ><b>Last Working Date:</b>'.date('M d,Y',strtotime($exit[0]->exit_date)).'</td></tr>
                                                                                                     <tr><td></td></tr>
                   
                        </table>
                        </td>
                        </tr>
                        <tr>
                        <td width="40%">
                        <table cellpadding="7px">
                        <tr><td align="left" style="border-bottom:1px solid black; font-weight: bold;">Salary Details</td><td align="right" style="border-bottom:1px solid black; font-weight: bold;">Amount</td></tr>
                        <tr><td align="left" style="border-bottom:1px solid gray;">Basic Salary</td><td align="right" style="border-bottom:1px solid gray;">'.number_format(trim($salary_data[0]->basic_salary,2)).'</td></tr>
                        <tr><td align="left" style="border-bottom:1px solid gray;">Total Allowances</td><td align="right" style="border-bottom:1px solid gray;">'.number_format(trim($salary_data[0]->total_allowance,2)).'</td></tr>
                        <tr><td align="right" style="font-weight: bold;">Net Salary</td><td align="right" style="font-weight: bold;">'.number_format(trim($salary_data[0]->net_salary,2)).'</td></tr>
                       
                        </table>
                        </td>
                        <td width="60%">
                         <table  cellpadding="7px;">
                        <tr><td style="border-bottom: 1px solid black;" colspan="2" align="right">'.$years.' Years '.$months.'months '.$days.' days (Abs :'.$unpaid_leaves.' days)(Total :'.$total_days.')</td></tr>
                             <tr><td width="60%" align="right" style=" border-bottom: 1px solid gray; font-weight: bold;">Accrued Annual Leave:</td><td width="40%" align="right" style="border-bottom: 1px solid gray;">'.$exit[0]->leave_balance.'</td></tr>

                        <tr><td width="60%" align="right" style=" border-bottom: 1px solid gray; font-weight: bold;">Air Ticket Accrued:</td><td width="40%" align="right" style="border-bottom: 1px solid gray;">'.$exit[0]->ticket_balance.'</td></tr>
                                                
                        </table>
                        </td>
                        </tr>
                         <table cellpadding="7px">

                        <tr>
                        <td align="left" width="12%" style="border-bottom: 1px solid black;font-weight: bold;">Reason:</td><td style="border-bottom:1px solid black;" align="left" width="88%">'.htmlspecialchars_decode(stripslashes($exit[0]->reason)).'</td>
                         </tr>
                         </table>
                                                 <table border="0" cellpadding="5px">

                        <tr><td width="100%" align="center" style=" font-size:12;font-weight: bold;text-decoration:underline ;"></td></tr>
                        <tr><td width="100%" align="center" style=" font-size:12;font-weight: bold;text-decoration:underline ;">Final Settlement Payments</td></tr>
                        
                        <tr>
                        <td align="left" width="100%" style="border-bottom: 1px solid black;font-weight: bold;">Settlements</td></tr>
                                                <tr><td width="80%" align="left">Leave Salary for '.$exit[0]->leave_balance.'</td> <td width="20%" align="right">'.number_format(trim($exit[0]->leave_salary),2).'</td></tr>
                                                <tr><td width="80%" align="left">Pending Salary </td> <td width="20%" align="right">'.number_format(trim($exit[0]->pending_salary),2).'</td></tr>
                                                <tr><td width="80%" align="left">Ticket Amount </td> <td width="20%" align="right">'.number_format(trim($exit[0]->ticket_amount),2).'</td></tr>
                                                <tr><td width="80%" align="left">Loan Amount Deduction </td> <td width="20%" align="right">'.number_format(trim($exit[0]->loan),2).'</td></tr>
                                                <tr><td width="80%" align="left">Overtime Amount </td> <td width="20%" align="right">'.number_format(trim($exit[0]->overtime_amount),2).'</td></tr>
                                                <tr><td width="80%" align="left">Pending Expenses </td> <td width="20%" align="right">'.number_format(trim($exit[0]->expenses),2).'</td></tr>
                                                <tr><td width="80%" align="left">Other Deductions </td> <td width="20%" align="right">'.number_format(trim($exit[0]->other_deductions),2).'</td></tr>
                                                <tr><td width="80%" align="left">Gratuity @ 21 Days basic salary per year ('.$total_days.') </td> <td width="20%" align="right">'.number_format(trim($exit[0]->gratuity,2)).'</td></tr>
                        <tr><td width="80%" style="border-bottom: 1px solid black;"></td><td width="20%" style="border-bottom: 1px solid black;"></td></tr>
                        <tr><td width="25%" style="font-weight: bold" align="left">Mode of Payment</td><td width="25%" style="font-weight: bold" align="left">Check</td><td  width="25%" style="font-weight: bold" align="left">Net Amount Payable</td><td width="25%" align="right">'.number_format(trim($exit[0]->net_amount,2)).'</td></tr>

                         </table>
                         <table border="0" cellpadding="5px">

                        <tr><td width="100%" align="center" style=" font-size:12;font-weight: bold;text-decoration:underline ;"></td></tr>
                        <tr><td width="100%" align="center" style=" border-bottom:1px solid black;font-size:12;font-weight: bold ;">Employee Acceptance</td></tr>
                        <tr><td width="100%" align="left" style=" border-bottom:1px solid black;font-size:10;">I confirm that I have Received the above in full and final settlement of all the dues from the company for my service.I also confirm that I do not have any financial or other claim on the company.</td></tr>
                         <tr><td width="50%" style="font-weight: bold" align="left">Date</td><td width="50%" style="font-weight: bold" align="right">Employee Signature</td></tr>
                         <tr><td></td></tr>
                         <tr><td></td></tr>
                                                  <table border="0" cellpadding="0px">

                                                  <tr><td width="40%" style="font-weight: bold" align="left">Actioned By Human Resource Manager :</td><td width="25%" style=" border-bottom:1px solid black;font-weight: bold" align="left"></td><td  width="10%" style="font-weight: bold" align="left">  Date:</td><td style="border-bottom: 1px solid black;" width="25%" align="right"></td></tr>
                                                   <tr><td></td></tr>
                                                   <tr><td></td></tr>
<tr><td width="40%" style="font-weight: bold" align="left">Actioned By Chief Accounts Manager :</td><td width="25%" style=" border-bottom:1px solid black;font-weight: bold" align="left"></td><td  width="10%" style="font-weight: bold" align="left">  Date:</td><td style="border-bottom: 1px solid black;" width="25%" align="right"></td></tr>
                                                  
  </table>
                       </table>
                        </table>
                        ';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        // This method has several options, check the source code documentation for more information.
//        $fname = strtolower($fname);
        //Close and output PDF document
//        $pdf->Output('payslip_'.$fname.'_.pdf', 'D');
        $footerhtml = '<table border="0" height="40px" cellpadding="10px" >
                        <tr ><td style="border-top: 1px solid grey;"align="left" width="30%">'.date("jS M Y").'</td>
                        <td style="border-top: 1px solid grey;"align="center" width="40%">EMSO-hrm</td>
                        <td align="right" style="border-top: 1px solid black;"  width="30%">     Page ' . $pdf->getAliasNumPage() . ' of ' . $pdf->getAliasNbPages() . '</td>
</tr>
                        </table>';
        $pdf->xfootertext =$footerhtml;
        $fname = strtolower($user[0]->first_name.'_'.$user[0]->last_name);
        //Close and output PDF document
        if($mode=="pdf") {
            $pdf->Output('final_settlement_' . $fname . '.pdf', 'D');
        }        else {

            $pdf->Output();
        }
    }

}
