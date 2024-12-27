<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends MY_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        // load email library

        $this->load->database();
        $this->load->library('Pdf');
        //$this->load->library('email');
        $this->load->library('form_validation');
        //load the model
        $this->load->model("Payroll_model");
        $this->load->model("Xin_model");
        $this->load->model("Employees_model");
        $this->load->model("Designation_model");
        $this->load->model("Department_model");
        $this->load->model("Location_model");
        $this->load->model("Timesheet_model");
        $this->load->library('../controllers/mypdf');

    }

    /*Function to set JSON output*/
    public function output($Return=array()){
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }

    // payroll templates
    public function templates()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['breadcrumbs'] = $this->lang->line('left_payroll_templates');
        $data['path_url'] = 'payroll_templates';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('38',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("payroll/templates", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    public function pdf_create() {

        //$this->load->library('Pdf');
        $system = $this->Xin_model->read_setting_info(1);
        $re_paid_amount = 0;

        // create new PDF document
        $pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $id = $this->uri->segment(4);
        $payment = $this->Payroll_model->read_make_payment_information($id);
        $user = $this->Xin_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        if($system[0]->is_payslip_password_generate==1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if($system[0]->payslip_password_format=='dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if($system[0]->payslip_password_format=='contact_no') {
                $password_val = $user[0]->contact_no;
            } else if($system[0]->payslip_password_format=='full_name') {
                $password_val = $user[0]->first_name.$user[0]->last_name;
            } else if($system[0]->payslip_password_format=='email') {
                $password_val = $user[0]->email;
            } else if($system[0]->payslip_password_format=='password') {
                $password_val = $user[0]->password;
            } else if($system[0]->payslip_password_format=='user_password') {
                $password_val = $user[0]->username.$user[0]->password;
            } else if($system[0]->payslip_password_format=='employee_id') {
                $password_val = $user[0]->employee_id;
            } else if($system[0]->payslip_password_format=='employee_id_password') {
                $password_val = $user[0]->employee_id.$user[0]->password;
            } else if($system[0]->payslip_password_format=='dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob.$fname[0].$lname[0];
            }
            $pdf->SetProtection(array('print', 'copy','modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Xin_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Xin_model->read_company_setting_info($location[0]->company_id);


        $p_method = '';
        if($payment[0]->payment_method==1){
            $p_method = 'Online';
        } else if($payment[0]->payment_method==2){
            $p_method = 'PayPal';
        } else if($payment[0]->payment_method==3) {
            $p_method = 'Payoneer';
        } else if($payment[0]->payment_method==4){
            $p_method = 'Bank Transfer';
        } else if($payment[0]->payment_method==5) {
            $p_method = 'Cheque';
        } else {
            $p_method = 'Cash';
        }

        //$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $company_name = $company[0]->company_name;
        // set default header data
        $c_info_email = $company[0]->email;
        $c_info_phone = $company[0]->phone;
        $country = $this->Xin_model->read_country_info($company[0]->country);
        $c_info_address = trim($company[0]->address_1).' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
        $email_phone_address = "".$this->lang->line('dashboard_email')." : $c_info_email | ".$this->lang->line('xin_phone')." : $c_info_phone \n".$this->lang->line('xin_address').": $c_info_address";
        $header_string = $email_phone_address;


        // set document information
        $pdf->SetCreator('Workable-Zone');
        $pdf->SetAuthor('Workable-Zone');
        //$pdf->SetTitle('Workable-Zone - Payslip');
        //$pdf->SetSubject('TCPDF Tutorial');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->SetHeaderData('../../../uploads/logo/payroll/'.$company[0]->logo, 60, $company_name, $header_string);

        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array('helvetica', '', 11.5));
        $pdf->setFooterFont(Array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);
        $pdf->SetAuthor($company_name);
        $pdf->SetTitle($company_name.' - '.$this->lang->line('xin_print_payslip'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));
        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        // -----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="center"><h1>'.$this->lang->line('xin_payslip').'</h1></td>
			</tr>
			<tr>
				<td align="center"><strong>'.$this->lang->line('xin_payslip_number').':</strong> #'.$payment[0]->make_payment_id.'</td>
			</tr>
			<tr>
				<td align="center"><strong>'.$this->lang->line('xin_e_details_date').':</strong> '.date("d F, Y").'</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        $fname = $user[0]->first_name.' '.$user[0]->last_name;
        $tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr>
				<td>'.$this->lang->line('xin_name').'</td>
				<td>'.$fname.'</td>
				<td>'.$this->lang->line('dashboard_employee_id').'</td>
				<td>'.$user[0]->employee_id.'</td>
			</tr>
			<tr>
				<td>'.$this->lang->line('left_department').'</td>
				<td>'.$department[0]->department_name.'</td>
				<td>'.$this->lang->line('left_designation').'</td>
				<td>'.$_des_name[0]->designation_name.'</td>
			</tr>
			<tr>
				<td>'.$this->lang->line('xin_salary_month').'</td>
				<td>'.date("F Y", strtotime($payment[0]->payment_date)).'</td>
				<td>'.$this->lang->line('xin_payslip_number').'</td>
				<td>'.$payment[0]->make_payment_id.'</td>
			</tr>
		
		</table>
		';

        $pdf->writeHTML($tbl, true, false, true, false, '');

        $company_details = $this->Xin_model->get_employee_company($user[0]->user_id);
        $company_country = $company_details[0]->country;
        if($company_country==228)
        {

            $tbl = '
    		<table cellpadding="5" cellspacing="0" border="1">
    			<tr>
    				<td width="38%"><b>Emirates ID : </b>'.$user[0]->emirates_id.'</td>
    				<td width="33%"><b>Labour ID : </b>'.$user[0]->labour_id.'</td>
    				<td width="29%"><b>Work Permit : </b>'.$user[0]->work_permit.'</td>
    			</tr>
    		
    		</table>
    		';

            $pdf->writeHTML($tbl, true, false, true, false, '');

        }

        if(null!=$this->uri->segment(3) && $this->uri->segment(3)=='sl') {
            // -----------------------------------------------------------------------------

            // Allowances
            if($payment[0]->house_rent_allowance!='' || $payment[0]->house_rent_allowance!=0){
                $hra = $this->Xin_model->currency_sign($payment[0]->house_rent_allowance);
            } else { $hra = '0';}
            if($payment[0]->medical_allowance!='' || $payment[0]->medical_allowance!=0){
                $ma = $this->Xin_model->currency_sign($payment[0]->medical_allowance);
            } else { $ma = '0';}
            if($payment[0]->travelling_allowance!='' || $payment[0]->travelling_allowance!=0){
                $ta = $this->Xin_model->currency_sign($payment[0]->travelling_allowance);
            } else { $ta = '0';}
            if($payment[0]->telephone_allowance!='' || $payment[0]->telephone_allowance!=0){
                $da = $this->Xin_model->currency_sign($payment[0]->telephone_allowance);
            } else { $da = '0';}
            if($payment[0]->other_allowance!='' || $payment[0]->other_allowance!=0){
                $othera = $this->Xin_model->currency_sign($payment[0]->other_allowance);
            } else { $othera = '0';}
            if($payment[0]->allowance!='' || $payment[0]->allowance!=0){
                $allo = $this->Xin_model->currency_sign($payment[0]->allowance);
            } else { $allo = '0';}

            // Deductions
            if($payment[0]->provident_fund!='' || $payment[0]->provident_fund!=0){
                $pf = $this->Xin_model->currency_sign($payment[0]->provident_fund);
            } else { $pf = '0';}
            if($payment[0]->tax_deduction!='' || $payment[0]->tax_deduction!=0){
                $td = $this->Xin_model->currency_sign($payment[0]->tax_deduction);
            } else { $td = '0';}
            if($payment[0]->security_deposit!='' || $payment[0]->security_deposit!=0){
                $sd = $this->Xin_model->currency_sign($payment[0]->security_deposit);
            } else { $sd = '0';}

            // get advance salary
            if($payment[0]->is_advance_salary_deduct==1){
                $re_paid_amount = $payment[0]->net_salary - $payment[0]->advance_salary_amount;
                $ad_sl = '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_advance_deducted_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->advance_salary_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_paid_amount').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			';
            }
            else {
                $ad_sl = '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_paid_amount').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>';
            }

            if($payment[0]->leave_salary_deduct_amount>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->leave_salary_deduct_amount;
                $ad_lv_sl = '<tr>
				<td>Leave Deducted Salary ('.$payment[0]->leave_days.' Days)</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->leave_salary_deduct_amount).'</td>
			</tr>';
            }
            else
            {
                $ad_lv_sl ='';
            }

            if($payment[0]->loan_emi>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->loan_emi;
                $loan_emi = '<tr>
				<td>Loan EMI</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->loan_emi).'</td>
			</tr>';
            }
            else
            {
                $loan_emi ='';
            }

            if($payment[0]->allowance>0){
                $re_paid_amount = $re_paid_amount + $payment[0]->allowance;
                $ext_alv = '<tr>
				<td>Extra Allowance</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->allowance).'</td>
			</tr>';
            }
            else
            {
                $ext_alv ='';
            }

            if($payment[0]->extra_deductions>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->extra_deductions;
                $ext_did = '<tr>
				<td>Extra Deductions</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->extra_deductions).'</td>
			</tr>';
            }
            else
            {
                $ext_did ='';
            }

            $tbl = '
		<table cellpadding="4" cellspacing="0" border="0">
			<tr>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#9F9;">
				<td><strong>'.$this->lang->line('xin_earning_salary').'</strong></td>
				<td align="right"><strong>'.$this->lang->line('xin_amount').'</strong></td>
			</tr>';

            if($payment[0]->house_rent_allowance>0)
            {
                $tbl .= '<tr>
    				<td>'.$this->lang->line('xin_Payroll_house_rent_allowance').'</td>
    				<td align="right">'.$hra.'</td>
    			</tr>';
            }
            if($payment[0]->overtime_amount>0)
            {
                $tbl .= '<tr>
    				<td>Overtime Amount</td>
    				<td align="right">'.$payment[0]->overtime_amount.'</td>
    			</tr>';
            }
            if($payment[0]->employee_expenses>0)
            {
                $tbl .= '<tr>
    				<td>Employee Expenses</td>
    				<td align="right">'.$payment[0]->employee_expenses.'</td>
    			</tr>';
            }
            if($payment[0]->medical_allowance>0)
            {
                $tbl .= '<tr>
            				<td>'.$this->lang->line('xin_payroll_medical_allowance').'</td>
            				<td align="right">'.$ma.'</td>
            			</tr>';
            }
            if($payment[0]->travelling_allowance>0)
            {
                $tbl .= '<tr>
            				<td>'.$this->lang->line('xin_payroll_travel_allowance').'</td>
            				<td align="right">'.$ta.'</td>
            			</tr>';
            }
            if($payment[0]->telephone_allowance>0)
            {
                $tbl .= '<tr>
            				<td>Telephone Allowance</td>
            				<td align="right">'.$da.'</td>
            			</tr>';
            }
            if($payment[0]->other_allowance>0)
            {
                $tbl .= '<tr>
            				<td>Other Allowance</td>
            				<td align="right">'.$othera.'</td>
            			</tr>';
            }
            if($payment[0]->allowance>0)
            {
                $tbl .= '<tr>
            				<td>Extra Allowance</td>
            				<td align="right">'.$allo.'</td>
            			</tr>';
            }

            $tbl .= $ext_alv.'
		</table></td>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#ff7575;">
				<td><strong>Salary Deduction</strong></td>
				<td align="right"><strong>'.$this->lang->line('xin_amount').'</strong></td>
			</tr>';

            if($payment[0]->provident_fund>0)
            {
                $tbl .= '<tr>
            				<td>'.$this->lang->line('xin_payroll_provident_fund_de').'</td>
            				<td align="right">'.$pf.'</td>
            			</tr>';
            }
            if($payment[0]->tax_deduction>0)
            {
                $tbl .= '<tr>
            				<td>'.$this->lang->line('xin_payroll_tax_deduction_de').'</td>
            				<td align="right">'.$td.'</td>
            			</tr>';
            }
            if($payment[0]->security_deposit>0)
            {
                $tbl .= '<tr>
            				<td>'.$this->lang->line('xin_payroll_security_deposit').'</td>
            				<td align="right">'.$sd.'</td>
            			</tr>';
            }
            $tbl .= $loan_emi.$ext_did.$ad_lv_sl.'
		</table></td>
			</tr>
		</table>
		';

            $pdf->writeHTML($tbl, true, false, false, false, '');

            $total_allowance  = $payment[0]->house_rent_allowance+$payment[0]->medical_allowance+$payment[0]->travelling_allowance+$payment[0]->other_allowance+$payment[0]->telephone_allowance+$payment[0]->allowance;
            $total_deductions = $payment[0]->provident_fund+$payment[0]->tax_deduction+$payment[0]->security_deposit+$payment[0]->extra_deductions+$payment[0]->leave_salary_deduct_amount;

            // -----------------------------------------------------------------------------

            $tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#c4e5fd;">
			  <th colspan="4" align="center"><strong>'.$this->lang->line('xin_payment_details').'</strong></th>
			 </tr>
			 <tr>
				<td colspan="2">'.$this->lang->line('xin_payroll_basic_salary').'</td>
				<td colspan="2" align="right">'.$this->Xin_model->currency_sign($payment[0]->basic_salary).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_gross_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->gross_salary).'</td>
			</tr>';

            if($total_allowance>0)
            {
                $tbl .= '<tr>
            				<td colspan="2">&nbsp;</td>
            				<td>'.$this->lang->line('xin_payroll_total_allowance').'</td>
            				<td align="right">'.$this->Xin_model->currency_sign($total_allowance).'</td>
            			</tr>';
            }

            if($total_deductions>0)
            {
                $tbl .= '<tr>
    				<td colspan="2">&nbsp;</td>
    				<td>'.$this->lang->line('xin_payroll_total_deduction').'</td>
    				<td align="right">'.$this->Xin_model->currency_sign($total_deductions).'</td>
    			</tr>';
            }

            $tbl .= '<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_net_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->net_salary).'</td>
			</tr>
			'.$ad_sl.'
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payment_method').'</td>
				<td align="right">'.$p_method.'</td>
			</tr>
		</table>
		';

            $pdf->writeHTML($tbl, true, false, false, false, '');
        }
        if(null!=$this->uri->segment(3) && $this->uri->segment(3)=='hr') {
            // -----------------------------------------------------------------------------
            $tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#c4e5fd;">
			  <th colspan="4" align="center"><strong>'.$this->lang->line('xin_payment_details').'</strong></th>
			 </tr>
			<tr>
				<td colspan="2">'.$this->lang->line('xin_payroll_hourly_rate').'</td>
				<td colspan="2" align="right">'.$this->Xin_model->currency_sign($payment[0]->hourly_rate).'</td>
			</tr>
			<tr>
				<td colspan="2">'.$this->lang->line('xin_total_hours_worked').'</td>
				<td colspan="2" align="right">'.$payment[0]->total_hours_work.'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_gross_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payroll_net_salary').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_paid_amount').'</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$this->lang->line('xin_payment_method').'</td>
				<td align="right">'.$p_method.'</td>
			</tr>
		</table>
		';

            $pdf->writeHTML($tbl, true, false, false, false, '');
        }
        // -----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
			<td>This is a saystem generated payroll slip which does</td>
			</tr>
		</table>
		';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
        //Close and output PDF document
        $pdf->Output('payslip_'.$fname.'_'.$pay_month.'.pdf', 'D');

    }
    public function pdf_create_new() {

        //$this->load->library('Pdf');
        $system = $this->Xin_model->read_setting_info(1);
        $re_paid_amount = 0;

        // create new PDF document
        $pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $id = $this->uri->segment(4);
        $payment = $this->Payroll_model->read_make_payment_information($id);
        $user = $this->Xin_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        if($system[0]->is_payslip_password_generate==1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if($system[0]->payslip_password_format=='dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if($system[0]->payslip_password_format=='contact_no') {
                $password_val = $user[0]->contact_no;
            } else if($system[0]->payslip_password_format=='full_name') {
                $password_val = $user[0]->first_name.$user[0]->last_name;
            } else if($system[0]->payslip_password_format=='email') {
                $password_val = $user[0]->email;
            } else if($system[0]->payslip_password_format=='password') {
                $password_val = $user[0]->password;
            } else if($system[0]->payslip_password_format=='user_password') {
                $password_val = $user[0]->username.$user[0]->password;
            } else if($system[0]->payslip_password_format=='employee_id') {
                $password_val = $user[0]->employee_id;
            } else if($system[0]->payslip_password_format=='employee_id_password') {
                $password_val = $user[0]->employee_id.$user[0]->password;
            } else if($system[0]->payslip_password_format=='dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob.$fname[0].$lname[0];
            }
            $pdf->SetProtection(array('print', 'copy','modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Xin_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Xin_model->read_company_setting_info($location[0]->company_id);


        $p_method = '';
        if($payment[0]->payment_method==1){
            $p_method = 'Online';
        } else if($payment[0]->payment_method==2){
            $p_method = 'PayPal';
        } else if($payment[0]->payment_method==3) {
            $p_method = 'Payoneer';
        } else if($payment[0]->payment_method==4){
            $p_method = 'Bank Transfer';
        } else if($payment[0]->payment_method==5) {
            $p_method = 'Cheque';
        } else {
            $p_method = 'Cash';
        }

        //$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $company_name = $company[0]->company_name;
        // set default header data
        $c_info_email = $company[0]->email;
        $c_info_phone = $company[0]->phone;
        $country = $this->Xin_model->read_country_info($company[0]->country);
        $c_info_address = $company[0]->address_1??''.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
        $c_info_address = trim($company[0]->address_1).' '.$company[0]->address_2.', '.$company[0]->city.', '.$country[0]->country_name;
        $email_phone_address = "".$this->lang->line('dashboard_email')." : $c_info_email | ".$this->lang->line('xin_phone')." : $c_info_phone \n".$this->lang->line('xin_address').": $c_info_address";
        $header_string = $email_phone_address;

//        $header_string="Payslip #".$payment[0]->make_payment_id."-".date('F Y', strtotime($payment[0]->payment_date));



        // set document information
        $pdf->SetCreator('Workable-Zone');
        $pdf->SetAuthor('Workable-Zone');
        //$pdf->SetTitle('Workable-Zone - Payslip');
        //$pdf->SetSubject('TCPDF Tutorial');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $header_string = preg_replace('/[ \t]+/', ' ', $header_string);

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $company_name, $header_string);

        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array('helvetica', '', 11.5));
        $pdf->setFooterFont(Array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);
        $pdf->SetAuthor($company_name);
        $pdf->SetTitle($company_name.' - '.$this->lang->line('xin_print_payslip'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));
        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pay_date = strtolower(date("Y-m", strtotime($payment[0]->payment_date)));

        // -----------------------------------------------------------------------------

        $tbl = '
		<table style="font-family: Verdana;font-size: 10px"; cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="right"><h2>'.$this->lang->line('xin_payslip').' </h2></td>
			</tr>
			<tr>
				<td align="right"><strong>'.$this->lang->line('xin_e_details_date').':</strong> '.date("d F, Y").'</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        $fname = $user[0]->first_name.' '.$user[0]->last_name;
        $tbl = '
<table border="1">
<tr><td><table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
			<tr>
				<td><b>'.$this->lang->line('xin_name').'</b></td>
				<td align="left" colspan="2">'.$fname.'</td>
				<td><b>'.$this->lang->line('dashboard_employee_id').'</b></td>
				<td align="left" colspan="2">'.$user[0]->employee_id.'</td>
			</tr>
			<tr>
				<td><b>'.$this->lang->line('left_department').'</b></td>
				<td align="left" colspan="2">'.$department[0]->department_name.'</td>
				<td><b>'.$this->lang->line('left_designation').'</b></td>
				<td align="left" colspan="2">'.$_des_name[0]->designation_name.'</td>
			</tr>
			<tr>
				<td><b>Pay Period</b></td>
				<td align="left" colspan="2">'.date("F Y", strtotime($payment[0]->payment_date)).'</td>
				<td><b>Emirates ID :</b></td>
				<td align="left" colspan="2">'.$user[0]->emirates_id.'</td>
			</tr>
			<tr>
			<td><b>Date of Joining</b></td>
			<td align="left" colspan="2">'.date('jS M Y', strtotime($user[0]->date_of_joining)).'</td>
</tr>
		
		</table></td></tr>
</table>';

        $pdf->writeHTML($tbl, true, false, true, false, '');

        $company_details = $this->Xin_model->get_employee_company($user[0]->user_id);
        $company_country = $company_details[0]->country;

        if(null!=$this->uri->segment(3) && $this->uri->segment(3)=='sl') {
            // -----------------------------------------------------------------------------

            // Allowances
            if($payment[0]->house_rent_allowance!='' || $payment[0]->house_rent_allowance!=0){
                $hra = $payment[0]->house_rent_allowance;
            } else { $hra = '0';}
            if($payment[0]->medical_allowance!='' || $payment[0]->medical_allowance!=0){
                $ma = $payment[0]->medical_allowance;
            } else { $ma = '0';}
            if($payment[0]->travelling_allowance!='' || $payment[0]->travelling_allowance!=0){
                $ta = $payment[0]->travelling_allowance;
            } else { $ta = '0';}
            if($payment[0]->telephone_allowance!='' || $payment[0]->telephone_allowance!=0){
                $da = $payment[0]->telephone_allowance;
            } else { $da = '0';}
            if($payment[0]->other_allowance!='' || $payment[0]->other_allowance!=0){
                $othera = $payment[0]->other_allowance;
            } else { $othera = '0';}
            if($payment[0]->allowance!='' || $payment[0]->allowance!=0){
                $allo = $payment[0]->allowance;
            } else { $allo = '0';}

            // Deductions
            // get advance salary
            if($payment[0]->is_advance_salary_deduct==1){
                $re_paid_amount = $payment[0]->net_salary - $payment[0]->advance_salary_amount;
                $ad_sl = '<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_advance_deducted_salary').'</td>
				<td  width="30%" align="right">'.number_format($payment[0]->advance_salary_amount,2).'</td>
			</tr>
			<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_paid_amount').'</td>
				<td width="30%"  align="right">'.number_format($payment[0]->payment_amount,2).'</td>
			</tr>
			';
            }
            else {
                $ad_sl = '<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_paid_amount').'</td>
				<td width="30%" align="right">'.number_format($payment[0]->payment_amount,2).'</td>
			</tr>';
            }

            if($payment[0]->leave_salary_deduct_amount>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->leave_salary_deduct_amount;
                $ad_lv_sl = '<tr>
				<td align="left" width="70%">Leave Deducted Salary ('.$payment[0]->leave_days.' Days)</td>
				<td width="30%" align="right">'.number_format($payment[0]->leave_salary_deduct_amount,2).'</td>
			</tr>';
            }
            else
            {
                $ad_lv_sl ='';
            }

            if($payment[0]->loan_emi>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->loan_emi;
                $loan_emi = '<tr>
				<td align="left" width="70%">Loan EMI</td>
				<td width="30%" align="right">'.number_format($payment[0]->loan_emi,2).'</td>
			</tr>';
            }
            else
            {
                $loan_emi ='';
            }

            if($payment[0]->allowance>0){
                $re_paid_amount = $re_paid_amount + $payment[0]->allowance;
                $ext_alv = '<tr>
				<td align="left" width="70%">Extra Allowance</td>
				<td width="30%" align="right">'.number_format($payment[0]->allowance,2).'</td>
			</tr>';
            }
            else
            {
                $ext_alv ='';
            }

            if($payment[0]->extra_deductions>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->extra_deductions;
                $ext_did = '<tr>
				<td align="left" width="70%">Extra Deductions</td>
				<td width="30%" align="right">'.number_format($payment[0]->extra_deductions,2).'</td>
			</tr>';
            }
            else
            {
                $ext_did ='';
            }
            $table_details='<table border="1" cellpadding="12" cellspacing="0" style="font-size: 10px;font-family: Verdana"><tr>
<td align="left" width="70%">Currency</td><td align="right" width="30%" >AED</td></tr>
<tr><td align="left" width="70%">Basic Salary</td><td align="right" width="30%">'.number_format($payment[0]->basic_salary,2).'</td></tr>';
            if($payment[0]->house_rent_allowance>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">'.$this->lang->line('xin_Payroll_house_rent_allowance').'</td>
    				<td width="30%" align="right">'.number_format($hra,2).'</td>
    			</tr>';
            }
            if($payment[0]->medical_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">'.$this->lang->line('xin_payroll_medical_allowance').'</td>
            				<td width="30%" align="right">'.number_format($ma,2).'</td>
            			</tr>';
            }
            if($payment[0]->travelling_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">'.$this->lang->line('xin_payroll_travel_allowance').'</td>
            				<td width="30%" align="right">'.number_format($ta,2).'</td>
            			</tr>';
            }
            if($payment[0]->telephone_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">Telephone Allowance</td>
            				<td width="30%" align="right">'.number_format($da,2).'</td>
            			</tr>';
            }
            if($payment[0]->other_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">Other Allowance</td>
            				<td width="30%" align="right">'.number_format($othera,2).'</td>
            			</tr>';
            }
            if($payment[0]->allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">Extra Allowance</td>
            				<td width="30%" align="right">'.number_format($allo,2).'</td>
            			</tr>';
            }
            $table_details .= '<tr>
            				<td align="right" width="70%"><b>Gross Salary</b></td>
            				<td width="30%" align="right">'.number_format(($payment[0]->basic_salary+$payment[0]->total_allowances),2).'</td>
            			</tr>';
            $table_details .= '<tr>
            				<td align="right" width="70%"><b>Total Net Salary</b></td>
            				<td width="30%" align="right">'.number_format(($payment[0]->basic_salary+$payment[0]->total_allowances-$payment[0]->total_deductions),2).'</td>
            			</tr>';

            if($payment[0]->overtime_amount>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">Overtime Amount</td>
    				<td width="30%" align="right">'.number_format($payment[0]->overtime_amount,2).'</td>
    			</tr>';
            }
            if($payment[0]->ticket_amount>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">Ticket Encashment</td>
    				<td width="30%" align="right">'.number_format($payment[0]->ticket_amount,2).'</td>
    			</tr>';
            }
            if($payment[0]->leave_salary>0)
            {
                $leave_salarydays =$this->Payroll_model->get_leave_salarydays_by_month_user($payment[0]->employee_id,$pay_date);


                $table_details .= '<tr>
    				<td align="left" width="70%">Leave Salary ('.$leave_salarydays.' days) </td>
    				<td width="30%" align="right">'.number_format($payment[0]->leave_salary,2).' </td>
    			</tr>';
            }
            if($payment[0]->employee_expenses>0)
            {
                $employee_expenses =$this->Payroll_model->get_all_expenses_by_month_user($user[0]->user_id,$pay_date);
                if($employee_expenses){
                    foreach($employee_expenses as $expense){
                        $desc = htmlspecialchars_decode(stripslashes($expense->remarks));

// Limit the string to 200 characters
                        if (strlen($desc) > 100) {
                            $desc = substr($desc, 0, 100) . '...';
                        }

                        $table_details .= '<tr>
    				<td align="left" width="70%">'.$expense->type_name.'-'.$desc.' ON '.$expense->date.'</td>
    				<td width="30%" align="right">'.number_format($expense->amount,2).'</td>
    			</tr>';
                    }
                }
                $table_details .= '<tr>
    				<td align="right" width="70%"><b>Total Expenses</b></td>
    				<td width="30%" align="right">'.number_format($payment[0]->employee_expenses,2).'</td>
    			</tr>';
            }

//		</table></td>
//				<td><table cellpadding="5" cellspacing="0" border="1">
//			<tr style="background-color:#ff7575;">
//				<td><strong>Salary Deduction</strong></td>
//				<td align="right"><strong>'.$this->lang->line('xin_amount').'</strong></td>
//			</tr>';
            $total_allowance = floatval($payment[0]->house_rent_allowance) + floatval($payment[0]->medical_allowance) + floatval($payment[0]->travelling_allowance) + floatval($payment[0]->other_allowance) + floatval($payment[0]->telephone_allowance);

            $total_deductions = floatval($payment[0]->loan_emi)+floatval($payment[0]->advance_salary_amount)+floatval($payment[0]->extra_deductions)+floatval($payment[0]->leave_salary_deduct_amount);


            $table_details .= $loan_emi.$ext_did.$ad_sl;
            if($total_deductions>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">Total Dedcutions</td>
    				<td width="30%" align="right">'.number_format($total_deductions,2).'</td>
    			</tr>';
            }
            if($total_allowance>0)
            {
                $table_details .= '<tr>
            				    				<td align="left" width="70%">Total Allowances</td>
<td width="30%" align="right">'.number_format($total_allowance,2).'</td>
            			</tr>';
            }


            $table_details.='<tr style="background-color: lightgrey;"><td align="right" width ="70%"><b>Net Salary</b></td><td align="right" width="30%"><b>'.$this->Xin_model->currency_sign(number_format($payment[0]->payment_amount,2)).'</b></td>
</tr>
		</table>
		';
            $pdf->writeHTML($table_details, true, false, false, false, '');


        }
        $bank_details =$this->Payroll_model->read_bank_account_information_user($user[0]->user_id);
        if($bank_details) {
            $table_bank = '<table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
<tr height="50px;"><td></td></tr>
<tr style="background-color: lightgrey;"><td width="60%" align="left"><b>Payment</b></td><td width="40%" align="right"><b>Payment Method : ' . $p_method . '</b></td></tr>
<tr ><td></td></tr>
<tr><td width="25%"><strong>Bank</strong><br>'.$bank_details[0]->bank_name.'</td><td width="25%"><strong>Account Number</strong><br>'.$bank_details[0]->account_number.'</td><td width="25%"><strong>IBAN Number</strong><br>'.$bank_details[0]->iban.'</td><td align="right" width="25%"><strong>Amount</strong><br>'.$payment[0]->payment_amount.'</td></tr>

</table>';
            $pdf->writeHTML($table_bank, true, false, false, false, '');

        }
        $table_comments = '<table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
<tr style="background-color: lightgrey;"><td width="60%" align="left"><b>Comments:</b></td><td width="40%" align="right"><i>' . $payment[0]->comments . '</i></td></tr>


</table>';
        $pdf->writeHTML($table_comments, true, false, false, false, '');



        // -----------------------------------------------------------------------------

        $tbl = '
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td align="center"> This is  system generated payroll slip which does not require a signature or company stamp.
</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0">
			<tr>
			<td width="30%" style="border-top: 1px solid black;"align="left">Printed on :'.date('d-m-Y g.i a').'</td>
			<td width="70%" style="border-top: 1px solid black;"></td>
</tr>
<tr><td></td></tr>
		</table>';
        $pdf->xfootertext =$tbl;

//        $pdf->SetY(-20);
////        $this->SetY(-10);
//        $this->SetX(10);
//        // Set font
//        $this->SetFont('helvetica', 'I', 8);
//
//        $pdf->writeHTML($tbl, true, false, false, false, '');

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
        //Close and output PDF document
        $pdf->Output('payslip_'.$fname.'_'.$pay_month.'.pdf', 'D');

    }

    public function print_payslip()
    {

        $re_paid_amount = 0;
        //$this->load->library('Pdf');
        $system = $this->Xin_model->read_setting_info(1);


        // create new PDF document
        $pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $id = $this->uri->segment(4);
        $payment = $this->Payroll_model->read_make_payment_information($id);
        $user = $this->Xin_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        // -----------------------------------------------------------------------------
        if($system[0]->is_payslip_password_generate==1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if($system[0]->payslip_password_format=='dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if($system[0]->payslip_password_format=='contact_no') {
                $password_val = $user[0]->contact_no;
            } else if($system[0]->payslip_password_format=='full_name') {
                $password_val = $user[0]->first_name.$user[0]->last_name;
            } else if($system[0]->payslip_password_format=='email') {
                $password_val = $user[0]->email;
            } else if($system[0]->payslip_password_format=='password') {
                $password_val = $user[0]->password;
            } else if($system[0]->payslip_password_format=='user_password') {
                $password_val = $user[0]->username.$user[0]->password;
            } else if($system[0]->payslip_password_format=='employee_id') {
                $password_val = $user[0]->employee_id;
            } else if($system[0]->payslip_password_format=='employee_id_password') {
                $password_val = $user[0]->employee_id.$user[0]->password;
            } else if($system[0]->payslip_password_format=='dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob.$fname[0].$lname[0];
            }
            $pdf->SetProtection(array('print', 'copy','modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Xin_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Xin_model->read_company_setting_info($location[0]->company_id);


        $p_method = '';
        if($payment[0]->payment_method==1){
            $p_method = 'Online';
        } else if($payment[0]->payment_method==2){
            $p_method = 'PayPal';
        } else if($payment[0]->payment_method==3) {
            $p_method = 'Payoneer';
        } else if($payment[0]->payment_method==4){
            $p_method = 'Bank Transfer';
        } else if($payment[0]->payment_method==5) {
            $p_method = 'Cheque';
        } else {
            $p_method = 'Cash';
        }

        //$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $company_name = $company[0]->company_name;
        // set default header data
        $c_info_email = $company[0]->email;
        $c_info_phone = $company[0]->phone;
        $country = $this->Xin_model->read_country_info($company[0]->country);
        $c_info_address = trim($company[0]->address_1).' '.$company[0]->address_2.', '.$company[0]->city.', '.$country[0]->country_name;
        $email_phone_address = "".$this->lang->line('dashboard_email')." : $c_info_email | ".$this->lang->line('xin_phone')." : $c_info_phone \n".$this->lang->line('xin_address').": $c_info_address";
        $header_string = $email_phone_address;
//        $header_string="Payslip #".$payment[0]->make_payment_id."-".date('F Y', strtotime($payment[0]->payment_date));



        // set document information
        $pdf->SetCreator('Workable-Zone');
        $pdf->SetAuthor('Workable-Zone');
        //$pdf->SetTitle('Workable-Zone - Payslip');
        //$pdf->SetSubject('TCPDF Tutorial');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $header_string = preg_replace('/[ \t]+/', ' ', $header_string);

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $company_name, $header_string);

        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array('helvetica', '', 11.5));
        $pdf->setFooterFont(Array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);
        $pdf->SetAuthor($company_name);
        $pdf->SetTitle($company_name.' - '.$this->lang->line('xin_print_payslip'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));
        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pay_date = strtolower(date("Y-m", strtotime($payment[0]->payment_date)));

        // -----------------------------------------------------------------------------

        $tbl = '
		<table style="font-family: Verdana;font-size: 10px"; cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="right"><h2>'.$this->lang->line('xin_payslip').'</h2></td>
			</tr>
			<tr>
				<td align="right"><strong>'.$this->lang->line('xin_e_details_date').':</strong> '.date("d F, Y").'</td>
			</tr>
		</table>
		';
        //				<td align="right"><h2>'.$this->lang->line('xin_payslip').' #'.$payment[0]->make_payment_id.'</h2></td>

        $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        $fname = $user[0]->first_name.' '.$user[0]->last_name;
        $tbl = '
<table border="1">
<tr><td><table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
			<tr>
				<td><b>'.$this->lang->line('xin_name').'</b></td>
				<td align="left" colspan="2">'.$fname.'</td>
				<td><b>'.$this->lang->line('dashboard_employee_id').'</b></td>
				<td align="left" colspan="2">'.$user[0]->employee_id.'</td>
			</tr>
			<tr>
				<td><b>'.$this->lang->line('left_department').'</b></td>
				<td align="left" colspan="2">'.$department[0]->department_name.'</td>
				<td><b>'.$this->lang->line('left_designation').'</b></td>
				<td align="left" colspan="2">'.$_des_name[0]->designation_name.'</td>
			</tr>
			<tr>
				<td><b>Pay Period</b></td>
				<td align="left" colspan="2">'.date("F Y", strtotime($payment[0]->payment_date)).'</td>
				<td><b>Emirates ID :</b></td>
				<td align="left" colspan="2">'.$user[0]->emirates_id.'</td>
			</tr>
			<tr>
			<td><b>Date of Joining</b></td>
			<td align="left" colspan="2">'.date('jS M Y', strtotime($user[0]->date_of_joining)).'</td>
</tr>
		
		</table></td></tr>
</table>';

        $pdf->writeHTML($tbl, true, false, true, false, '');

        $company_details = $this->Xin_model->get_employee_company($user[0]->user_id);
        $company_country = $company_details[0]->country;
        if(null!=$this->uri->segment(3) && $this->uri->segment(3)=='sl') {
            // -----------------------------------------------------------------------------

            // Allowances
            if($payment[0]->house_rent_allowance!='' || $payment[0]->house_rent_allowance!=0){
                $hra = $payment[0]->house_rent_allowance;
            } else { $hra = '0';}
            if($payment[0]->medical_allowance!='' || $payment[0]->medical_allowance!=0){
                $ma = $payment[0]->medical_allowance;
            } else { $ma = '0';}
            if($payment[0]->travelling_allowance!='' || $payment[0]->travelling_allowance!=0){
                $ta = $payment[0]->travelling_allowance;
            } else { $ta = '0';}
            if($payment[0]->telephone_allowance!='' || $payment[0]->telephone_allowance!=0){
                $da = $payment[0]->telephone_allowance;
            } else { $da = '0';}
            if($payment[0]->other_allowance!='' || $payment[0]->other_allowance!=0){
                $othera = $payment[0]->other_allowance;
            } else { $othera = '0';}
            if($payment[0]->allowance!='' || $payment[0]->allowance!=0){
                $allo = $payment[0]->allowance;
            } else { $allo = '0';}

            // Deductions
            // get advance salary
            if($payment[0]->is_advance_salary_deduct==1){
                $re_paid_amount = $payment[0]->net_salary - $payment[0]->advance_salary_amount;
                $ad_sl = '<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_advance_deducted_salary').'</td>
				<td  width="30%" align="right">'.number_format($payment[0]->advance_salary_amount,2).'</td>
			</tr>
			<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_paid_amount').'</td>
				<td width="30%"  align="right">'.number_format($payment[0]->payment_amount,2).'</td>
			</tr>
			';
            }
            else {
                $ad_sl = '<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_paid_amount').'</td>
				<td width="30%" align="right">'.number_format($payment[0]->payment_amount,2).'</td>
			</tr>';
            }

            if($payment[0]->leave_salary_deduct_amount>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->leave_salary_deduct_amount;
                $ad_lv_sl = '<tr>
				<td align="left" width="70%">Leave Deducted Salary ('.$payment[0]->leave_days.' Days)</td>
				<td width="30%" align="right">'.number_format($payment[0]->leave_salary_deduct_amount,2).'</td>
			</tr>';
            }
            else
            {
                $ad_lv_sl ='';
            }

            if($payment[0]->loan_emi>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->loan_emi;
                $loan_emi = '<tr>
				<td align="left" width="70%">Loan EMI</td>
				<td width="30%" align="right">'.number_format($payment[0]->loan_emi,2).'</td>
			</tr>';
            }
            else
            {
                $loan_emi ='';
            }

            if($payment[0]->allowance>0){
                $re_paid_amount = $re_paid_amount + $payment[0]->allowance;
                $ext_alv = '<tr>
				<td align="left" width="70%">Extra Allowance</td>
				<td width="30%" align="right">'.number_format($payment[0]->allowance,2).'</td>
			</tr>';
            }
            else
            {
                $ext_alv ='';
            }

            if($payment[0]->extra_deductions>0){
                $re_paid_amount = $re_paid_amount - $payment[0]->extra_deductions;
                $ext_did = '<tr>
				<td align="left" width="70%">Extra Deductions</td>
				<td width="30%" align="right">'.number_format($payment[0]->extra_deductions,2).'</td>
			</tr>';
            }
            else
            {
                $ext_did ='';
            }
            $table_details='<table border="1" cellpadding="12" cellspacing="0" style="font-size: 10px;font-family: Verdana"><tr>
<td align="left" width="70%">Currency</td><td align="right" width="30%" >AED</td></tr>
<tr><td align="left" width="70%">Basic Salary</td><td align="right" width="30%">'.number_format($payment[0]->basic_salary,2).'</td></tr>';
            if($payment[0]->house_rent_allowance>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">'.$this->lang->line('xin_Payroll_house_rent_allowance').'</td>
    				<td width="30%" align="right">'.number_format($hra,2).'</td>
    			</tr>';
            }
            if($payment[0]->medical_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">'.$this->lang->line('xin_payroll_medical_allowance').'</td>
            				<td width="30%" align="right">'.number_format($ma,2).'</td>
            			</tr>';
            }
            if($payment[0]->travelling_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">'.$this->lang->line('xin_payroll_travel_allowance').'</td>
            				<td width="30%" align="right">'.number_format($ta,2).'</td>
            			</tr>';
            }
            if($payment[0]->telephone_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">Telephone Allowance</td>
            				<td width="30%" align="right">'.number_format($da,2).'</td>
            			</tr>';
            }
            if($payment[0]->other_allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">Other Allowance</td>
            				<td width="30%" align="right">'.number_format($othera,2).'</td>
            			</tr>';
            }
            if($payment[0]->allowance>0)
            {
                $table_details .= '<tr>
            				<td align="left" width="70%">Extra Allowance</td>
            				<td width="30%" align="right">'.number_format($allo,2).'</td>
            			</tr>';
            }
            $table_details .= '<tr>
            				<td align="right" width="70%"><b>Gross Salary</b></td>
            				<td width="30%" align="right">'.number_format(($payment[0]->basic_salary+$payment[0]->total_allowances),2).'</td>
            			</tr>';
            $table_details .= '<tr>
            				<td align="right" width="70%"><b>Total Net Salary</b></td>
            				<td width="30%" align="right">'.number_format(($payment[0]->basic_salary+$payment[0]->total_allowances-$payment[0]->total_deductions),2).'</td>
            			</tr>';

            if($payment[0]->overtime_amount>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">Overtime Amount</td>
    				<td width="30%" align="right">'.number_format($payment[0]->overtime_amount,2).'</td>
    			</tr>';
            }
            if($payment[0]->ticket_amount>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">Ticket Encashment</td>
    				<td width="30%" align="right">'.number_format($payment[0]->ticket_amount,2).'</td>
    			</tr>';
            }

            if($payment[0]->leave_salary>0)
            {
                $leave_salarydays =$this->Payroll_model->get_leave_salarydays_by_month_user($payment[0]->employee_id,$pay_date);


                $table_details .= '<tr>
    				<td align="left" width="70%">Leave Salary ('.$leave_salarydays.' days) </td>
    				<td width="30%" align="right">'.number_format($payment[0]->leave_salary,2).' </td>
    			</tr>';
            }
            if($payment[0]->employee_expenses>0)
            {
                $employee_expenses =$this->Payroll_model->get_all_expenses_by_month_user($user[0]->user_id,$pay_date);
                if($employee_expenses){
                    foreach($employee_expenses as $expense){
                        $desc = htmlspecialchars_decode(stripslashes($expense->remarks));

// Limit the string to 200 characters
                        if (strlen($desc) > 100) {
                            $desc = substr($desc, 0, 100) . '...';
                        }

                        $table_details .= '<tr>
    				<td align="left" width="70%">'.$expense->type_name.'-'.$desc.' ON '.$expense->date.'</td>
    				<td width="30%" align="right">'.number_format($expense->amount,2).'</td>
    			</tr>';
                    }
                }
                $table_details .= '<tr>
    				<td align="right" width="70%"><b>Total Expenses</b></td>
    				<td width="30%" align="right">'.number_format($payment[0]->employee_expenses,2).'</td>
    			</tr>';
            }

//		</table></td>
//				<td><table cellpadding="5" cellspacing="0" border="1">
//			<tr style="background-color:#ff7575;">
//				<td><strong>Salary Deduction</strong></td>
//				<td align="right"><strong>'.$this->lang->line('xin_amount').'</strong></td>
//			</tr>';
            $total_allowance = floatval($payment[0]->house_rent_allowance) + floatval($payment[0]->medical_allowance) + floatval($payment[0]->travelling_allowance) + floatval($payment[0]->other_allowance) + floatval($payment[0]->telephone_allowance);

            $total_deductions = floatval($payment[0]->loan_emi)+floatval($payment[0]->advance_salary_amount)+floatval($payment[0]->extra_deductions)+floatval($payment[0]->leave_salary_deduct_amount);


            $table_details .= $loan_emi.$ext_did.$ad_sl;
            if($total_deductions>0)
            {
                $table_details .= '<tr>
    				<td align="left" width="70%">Total Dedcutions</td>
    				<td width="30%" align="right">'.number_format($total_deductions,2).'</td>
    			</tr>';
            }
            if($total_allowance>0)
            {
                $table_details .= '<tr>
            				    				<td align="left" width="70%">Total Allowances</td>
<td width="30%" align="right">'.number_format($total_allowance,2).'</td>
            			</tr>';
            }


            $table_details.='<tr style="background-color: lightgrey;"><td align="right" width ="70%"><b>Net Salary</b></td><td align="right" width="30%"><b>'.$this->Xin_model->currency_sign(number_format($payment[0]->payment_amount,2)).'</b></td>
</tr>
		</table>
		';
            $pdf->writeHTML($table_details, true, false, false, false, '');


        }
        $bank_details =$this->Payroll_model->read_bank_account_information_user($user[0]->user_id);
        if($bank_details) {
            $table_bank = '<table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
<tr height="50px;"><td></td></tr>
<tr style="background-color: lightgrey;"><td width="60%" align="left"><b>Payment</b></td><td width="40%" align="right"><b>Payment Method : ' . $p_method . '</b></td></tr>
<tr ><td></td></tr>
<tr><td width="25%"><strong>Bank</strong><br>'.$bank_details[0]->bank_name.'</td><td width="25%"><strong>Account Number</strong><br>'.$bank_details[0]->account_number.'</td><td width="25%"><strong>IBAN Number</strong><br>'.$bank_details[0]->iban.'</td><td align="right" width="25%"><strong>Amount</strong><br>'.$payment[0]->payment_amount.'</td></tr>

</table>';
            $pdf->writeHTML($table_bank, true, false, false, false, '');

        }
        $table_comments = '<table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
<tr style="background-color: lightgrey;"><td width="60%" align="left"><b>Comments:</b></td><td width="40%" align="right"><i>' . $payment[0]->comments . '</i></td></tr>


</table>';
        $pdf->writeHTML($table_comments, true, false, false, false, '');

//        $tbl = '<br><br><br><br>
//		<table cellpadding="5" cellspacing="0" border="0">
//			<tr>
//				<td align="right" colspan="4">'.$this->lang->line('xin_payslip_authorised_signatory').'</td>
//			</tr>
//		</table>
//		';
//
//        $pdf->writeHTML($tbl, true, false, false, false, '');
        $tbl = '
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td align="center"> This is  system generated payroll slip which does not require a signature or company stamp.
</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0">
			<tr>
			<td width="30%" style="border-top: 1px solid black;"align="left">Printed on :'.date('d-m-Y g.i a').'</td>
			<td width="70%" style="border-top: 1px solid black;"></td>
</tr>
<tr><td></td></tr>
		</table>';
        $pdf->xfootertext =$tbl;

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
        //Close and output PDF document
        $pdf->Output();
    }

    // hourly wage templates
    public function hourly_wages()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['breadcrumbs'] = $this->lang->line('left_hourly_wages');
        $data['path_url'] = 'hourly_wages';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('39',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("payroll/hourly_wages", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    // manage employee salary
    public function manage_salary()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = $this->lang->line('left_manage_salary');
        $data['path_url'] = 'manage_salary';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('40',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("payroll/manage_salary", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    // advance salary
    public function advance_salary()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = $this->lang->line('xin_advance_salary');
        $data['path_url'] = 'advance_salary';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('59',$role_resources_ids)) {
            $data['subview'] = $this->load->view("payroll/advance_salary", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('dashboard/');
        }
    }

    // advance salary report
    public function advance_salary_report()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = $this->lang->line('xin_advance_salary_report');
        $data['path_url'] = 'advance_salary_report';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('60',$role_resources_ids)) {
            $data['subview'] = $this->load->view("payroll/advance_salary_report", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('dashboard/');
        }
    }

    // loan report
    public function loan_report()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = 'Loan Report';
        $data['path_url'] = 'loan_report';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('60',$role_resources_ids)) {
            $data['subview'] = $this->load->view("payroll/loan_report", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('dashboard/');
        }
    }

    // advance salary
    public function loan()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = 'Loan';
        $data['path_url'] = 'loan';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('59',$role_resources_ids)) {
            $data['subview'] = $this->load->view("payroll/loan", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('dashboard/');
        }
    }

    public function loan_list()
    {


        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/advance_salary", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $advance_salary = $this->Payroll_model->get_loans();

        $data = array();

        foreach($advance_salary->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }

            $d = explode('-',$r->month_year);
            $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
            $month_year = $get_month.', '.$d[0];
            // get net salary
            $advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);
            // get status
            if($r->status==0): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==1): $status = '<span class="tag tag-success">Accepted</span>'; else: $status = '<span class="tag tag-warning">Rejected</span>'; endif;
            // get monthly installment
            $monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);

            // get onetime deduction value
            if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="Print"><a target="blank" href="'.site_url('payroll/print_loan_agreement/'.$r->loan_id).'" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"><i class="fa fa-print"></i></a></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-loan_id="'. $r->loan_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-loan_id="'. $r->loan_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->loan_id . '"><i class="fa fa-trash-o"></i></button></span>',
                $full_name,
                $advance_amount,
                $r->monthly_installment,
                $month_year,
                number_format($r->total_paid),
                $cdate,
                $status
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $advance_salary->num_rows(),
            "recordsFiltered" => $advance_salary->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // generate payslips
    public function generate_payslip()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = $this->lang->line('left_generate_payslip');
        $data['path_url'] = 'generate_payslip';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('41',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("payroll/generate_payslip", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    // payment history
    public function payslip()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $payment_id = $this->uri->segment(4);

        $result = $this->Payroll_model->read_make_payment_information($payment_id);
        if(is_null($result)){
            redirect('payroll/payment_history');
        }
        $p_method = '';
        if($result[0]->payment_method==1){
            $p_method = 'Online';
        } else if($result[0]->payment_method==2){
            $p_method = 'PayPal';
        } else if($result[0]->payment_method==3) {
            $p_method = 'Payoneer';
        } else if($result[0]->payment_method==4){
            $p_method = 'Bank Transfer';
        } else if($result[0]->payment_method==5) {
            $p_method = 'Cheque';
        } else {
            $p_method = 'Cash';
        }
        // get addd by > template
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);
        // user full name
        if(!is_null($user)){
            $first_name = $user[0]->first_name;
            $last_name = $user[0]->last_name;
        } else {
            $first_name = '--';
            $last_name = '--';
        }
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if(!is_null($designation)){
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }

        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if(!is_null($department)){
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }
        //$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data = array(
            'title' => $this->Xin_model->site_title(),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'employee_id' => $user[0]->employee_id,
            'contact_no' => $user[0]->contact_no,
            'date_of_joining' => $user[0]->date_of_joining,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'monthly_grade_id' => $user[0]->monthly_grade_id,
            'hourly_grade_id' => $user[0]->hourly_grade_id,
            'make_payment_id' => $result[0]->make_payment_id,
            'basic_salary' => $result[0]->basic_salary,
            'payment_date' => $result[0]->payment_date,
            'is_advance_salary_deduct' => $result[0]->is_advance_salary_deduct,
            'advance_salary_amount' => $result[0]->advance_salary_amount,
            'loan_emi' => $result[0]->loan_emi,
            'leave_salary_deduct_amount' => $result[0]->leave_salary_deduct_amount,
            'leave_days' => $result[0]->leave_days,
            'allowance' => $result[0]->allowance,
            'extra_deductions' => $result[0]->extra_deductions,
            'payment_amount' => $result[0]->payment_amount,
            'payment_method' => $p_method,
            'overtime_rate' => $result[0]->overtime_rate,
            'hourly_rate' => $result[0]->hourly_rate,
            'total_hours_work' => $result[0]->total_hours_work,
            'is_payment' => $result[0]->is_payment,
            'house_rent_allowance' => $result[0]->house_rent_allowance,
            'medical_allowance' => $result[0]->medical_allowance,
            'travelling_allowance' => $result[0]->travelling_allowance,
            'dearness_allowance' => $result[0]->dearness_allowance,
            'other_allowance' => $result[0]->dearness_allowance,
            'telephone_allowance' => $result[0]->dearness_allowance,
            'provident_fund' => $result[0]->provident_fund,
            'security_deposit' => $result[0]->security_deposit,
            'tax_deduction' => $result[0]->tax_deduction,
            'gross_salary' => $result[0]->gross_salary,
            'total_allowances' => $result[0]->total_allowances,
            'total_deductions' => $result[0]->total_deductions,
            'net_salary' => $result[0]->net_salary,
            'comments' => $result[0]->comments,
            'overtime_amount' => $result[0]->overtime_amount,
            'overtime_hours' => $result[0]->overtime_hours,
            'employee_expenses' => $result[0]->employee_expenses,
            'ticket_amount' => $result[0]->ticket_amount,
            'leave_salary' => $result[0]->leave_salary,

        );
        $data['breadcrumbs'] = $this->lang->line('xin_payroll_employee_payslip');
        $data['path_url'] = 'payslip';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(!empty($session)){
            $data['subview'] = $this->load->view("payroll/payslip", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }
    }

    // payment history
    public function payment_history()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = $this->lang->line('left_payment_history');
        $data['path_url'] = 'payment_history';
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if(in_array('42',$role_resources_ids)) {
            if(!empty($session)){
                $data['subview'] = $this->load->view("payroll/payment_history", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }

    // payroll template list
    public function template_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/templates", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $template = $this->Payroll_model->get_templates();

        $data = array();

        foreach($template->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->added_by);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }

            // get basic salary
            $sbs = $this->Xin_model->currency_sign($r->basic_salary);
            // get net salary
            $sns = $this->Xin_model->currency_sign($r->net_salary);
            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);
            // total allowance
            if($r->total_allowance == 0 || $r->total_allowance=='') {
                $allowance = '--';
            } else{
                $allowance = $this->Xin_model->currency_sign($r->total_allowance);
            }

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-salary_template_id="'. $r->salary_template_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->salary_template_id . '"><i class="fa fa-trash-o"></i></button></span>',
                $r->salary_grades,
                $sbs,
                $sns,
                $allowance,
                $full_name,
                $cdate
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $template->num_rows(),
            "recordsFiltered" => $template->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // advance salary list
    public function advance_salary_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/advance_salary", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $advance_salary = $this->Payroll_model->get_advance_salaries();

        $data = array();

        foreach($advance_salary->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }

            $d = explode('-',$r->month_year);
            $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
            $month_year = $get_month.', '.$d[0];
            // get net salary
            $advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);
            // get status
            if($r->status==0): $status = '<span class="tag tag-danger">Pending</span>'; elseif($r->status==1): $status = '<span class="tag tag-success">Accepted</span>'; else: $status = '<span class="tag tag-warning">Rejected</span>'; endif;
            // get monthly installment
            $monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);

            // get onetime deduction value
            if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-advance_salary_id="'. $r->advance_salary_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-advance_salary_id="'. $r->advance_salary_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->advance_salary_id . '"><i class="fa fa-trash-o"></i></button></span>',
                $full_name,
                $advance_amount,
                $month_year,
                $cdate,
                $status
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $advance_salary->num_rows(),
            "recordsFiltered" => $advance_salary->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // advance salary report list
    public function advance_salary_report_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/advance_salary", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $advance_salary = $this->Payroll_model->get_advance_salaries_report();

        $data = array();

        foreach($advance_salary->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }

            $d = explode('-',$r->month_year);
            $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
            $month_year = $get_month.', '.$d[0];
            // get net salary
            $advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);
            // get status
            if($r->status==0): $status = $this->lang->line('xin_pending'); elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
            // get monthly installment
            $monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);

            $remainig_amount = $r->advance_amount - $r->total_paid;
            $ramount = $this->Xin_model->currency_sign($remainig_amount);

            // get onetime deduction value
            if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;
            if($r->advance_amount == $r->total_paid){
                $all_paid = '<span class="tag tag-success">All Paid</span>';
            } else {
                $all_paid = '<span class="tag tag-warning">Remaining</span>';
            }
            //total paid
            $total_paid = $this->Xin_model->currency_sign($r->total_paid);

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-employee_id="'. $r->employee_id . '"><i class="fa fa-eye"></i></button></span>',
                $full_name,
                $advance_amount,
                $total_paid,
                $ramount,
                $all_paid,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $advance_salary->num_rows(),
            "recordsFiltered" => $advance_salary->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function loan_report_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/loan", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $advance_salary = $this->Payroll_model->get_loan_report();

        $data = array();

        foreach($advance_salary->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }

            $d = explode('-',$r->month_year);
            $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
            $month_year = $get_month.', '.$d[0];
            // get net salary
            $advance_amount = $this->Xin_model->currency_sign($r->advance_amount);
            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);
            // get status
            if($r->status==0): $status = $this->lang->line('xin_pending'); elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
            // get monthly installment
            $monthly_installment = $this->Xin_model->currency_sign($r->monthly_installment);

            $remainig_amount = $r->advance_amount - $r->total_paid;
            $ramount = $this->Xin_model->currency_sign($remainig_amount);

            // get onetime deduction value
            if($r->one_time_deduct==1): $onetime = $this->lang->line('xin_yes'); else: $onetime = $this->lang->line('xin_no'); endif;
            if($r->advance_amount == $r->total_paid){
                $all_paid = '<span class="tag tag-success">All Paid</span>';
            } else {
                $all_paid = '<span class="tag tag-warning">Remaining</span>';
            }
            //total paid
            $total_paid = $this->Xin_model->currency_sign($r->total_paid);

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-employee_id="'. $r->employee_id . '"><i class="fa fa-eye"></i></button></span>',
                $full_name,
                $advance_amount,
                $total_paid,
                $ramount,
                $all_paid,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $advance_salary->num_rows(),
            "recordsFiltered" => $advance_salary->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // hourly_list > templates
    public function payment_history_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/hourly_wages", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $history = $this->Payroll_model->all_payment_history();

        $data = array();

        foreach($history->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
                $emp_link = '<a target="_blank" href="'.site_url().'employees/detail/'.$r->employee_id.'">'.$user[0]->employee_id.'</a>';

                // view
                $functions = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->employee_id . '" data-pay_id="'. $r->make_payment_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';


                $month_payment = date("F, Y", strtotime($r->payment_date));

                $p_amount = $this->Xin_model->currency_sign($r->payment_amount);

                // get date > created at > and format
                $created_at = $this->Xin_model->set_date_format($r->created_at);
                // get hourly rate
                // payslip
                $payslip = '<a class="text-success" href="'.site_url().'payroll/payslip/id/'.$r->make_payment_id.'">'.$this->lang->line('left_generate_payslip').'</a>';


                if($r->payment_method==1){
                    $p_method = 'Online';
                } else if($r->payment_method==2){
                    $p_method = 'PayPal';
                } else if($r->payment_method==3) {
                    $p_method = 'Payoneer';
                } else if($r->payment_method==4){
                    $p_method = 'Bank Transfer';
                } else if($r->payment_method==5) {
                    $p_method = 'Cheque';
                } else {
                    $p_method = 'Cash';
                }

                $data[] = array(
                    $functions,
                    $emp_link,
                    $full_name,
                    $month_payment,
                    $created_at,
                    $p_amount,
                    $p_method,
                    $payslip
                );
            }
        } // if employee available

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $history->num_rows(),
            "recordsFiltered" => $history->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // hourly_list > templates
    public function hourly_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/hourly_wages", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $hourly_wages = $this->Payroll_model->get_hourly_wages();

        $data = array();

        foreach($hourly_wages->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->added_by);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }

            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);
            // get hourly rate
            $hourly_rate = $this->Xin_model->currency_sign($r->hourly_rate);

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-hourly_rate_id="'. $r->hourly_rate_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->hourly_rate_id . '"><i class="fa fa-trash-o"></i></button></span>',
                $r->hourly_grade,
                $hourly_rate,
                $full_name,
                $cdate
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $hourly_wages->num_rows(),
            "recordsFiltered" => $hourly_wages->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // hourly_list > templates
    public function payslip_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $dept = $this->input->get("dept");

        // date and employee id
        if($this->input->get("employee_id")!=0){
            $employee_id = $this->input->get("employee_id");
            $p_date = $this->input->get("month_year");
            $payslip = $this->Payroll_model->get_employee_template($employee_id,$dept);
        } else {
            $employee_id = $this->input->get("employee_id");
            $p_date = $this->input->get("month_year");
            $payslip = $this->Employees_model->get_employees_d($dept,$p_date);
        }
//        echo $this->db->last_query();
//        die;

        if(empty($p_date)){ $p_date=date('Y-m'); }
        else { $p_date = date("Y-m", strtotime($p_date)); }
        $data = array();

        foreach($payslip->result() as $r) {
            $comments='';
            $extra =0.00;
            $ticket_amount =0.00;
            $overtime =$othours =$leave_salary =0.00;

            // user full name
            $full_name = $r->first_name.' '.$r->last_name;
            $user_info = $this->Xin_model->get_employee_details($r->user_id);

            // get total hours > worked > employee
            $result = $this->Payroll_model->total_hours_worked_payslip($r->user_id,$this->input->get('month_year'));
            /* total work clock-in > clock-out  */
            $hrs_old_int1 = 0;//'';
            $Total = 0;
            $Trest = 0;
            $total_time_rs = 0;
            $hrs_old_int_res1 = 0;
            foreach ($result->result() as $hour_work){
                // total work
                $clock_in =  new DateTime($hour_work->clock_in);
                $clock_out =  new DateTime($hour_work->clock_out);
                $interval_late = $clock_in->diff($clock_out);
                $hours_r  = $interval_late->format('%h');
                $minutes_r = $interval_late->format('%i');
                $total_time = $hours_r .":".$minutes_r.":".'00';

                $str_time = $total_time;

                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                $hrs_old_int1 += $hrs_old_seconds;

                $Total = gmdate("H", $hrs_old_int1);
            }
            $deduct_leave_sal=0;
            $unpaid_off_per_month=0;
            $grade_template = $this->Payroll_model->read_salary_information_by_date($r->user_id,$p_date);

            $total_expenses =$this->Payroll_model->get_expenses_by_month_user($r->user_id,$p_date);
            $ticket_amount =$this->Payroll_model->get_tickets_by_month_user($r->user_id,$p_date);
            $leave_salary =$this->Payroll_model->get_leave_salary_by_month_user($r->user_id,$p_date);

            if(!is_null($grade_template)){
                if($grade_template[0]->basic_salary){
                    $total_allowance = floatval($grade_template[0]->total_allowance);
                    $basic_salary = floatval($grade_template[0]->basic_salary);
                    $net_salary = floatval($grade_template[0]->net_salary);
                    $create_id = $grade_template[0]->salary_id;
                    $gd = 'sl';
                    $p_class = 'emo_monthly_pay';
                    $unpaid_view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".payroll_template_modal" data-date="'. $p_date . '" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
//                    $deduct_salary = 0;
                    $payment_month = strtotime($p_date);
                    $p_month = date('F Y',$payment_month);
                    $all_leave_types = $this->Timesheet_model->all_leave_types();
                    $unpaid_leaves = 0;
                    $deduct_leave_sal = 0;
                    $unpaid_off=0;
                    $leaves_per_yeartype=array();
                    foreach($all_leave_types as $type) {
                        $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$r->user_id,date('Y',$payment_month));
                        if(($count_l>$type->days_per_year)&&($type->type_name!="Unpaid Leave"))
                        {
                            $unpaid_leaves = $unpaid_leaves+($count_l-$type->days_per_year);
                            $count_l-=$type->days_per_year;
                        }else if($type->type_name=="Unpaid Leave"){
                            $unpaid_leaves+=$count_l;
                            $unpaid_off+=$count_l;

                        }else{
                            $unpaid_leaves += 0;
                            $unpaid_off+=$count_l;
                            $count_l=0;

                        }
                        $leaves_per_yeartype[$type->leave_type_id]=$count_l;
                    }

                    if($unpaid_leaves>0)
                    {
                        $unpaid_leaves_count = $this->Timesheet_model->count_total_un_paid_leaves($r->user_id);
                        if($unpaid_leaves_count<$unpaid_leaves)
                        {
                            $unpaid_leaves = $unpaid_leaves_count+$unpaid_off;
                        }
                    }
                    $unpaid_off_per_month =0;
                    foreach($all_leave_types as $type) {
//
                        $count_per_month =$this->Timesheet_model->get_leave_days_month($r->user_id,$p_date,$type->leave_type_id);
                        if($count_per_month) {
                            if ($type->type_name != "Unpaid Leave") {
                                if ($leaves_per_yeartype[$type->leave_type_id] < $count_per_month)
                                    $unpaid_off_per_month += $leaves_per_yeartype[$type->leave_type_id];
                                else
                                    $unpaid_off_per_month += $count_per_month;
                            }
                            else {
                                $unpaid_off_per_month += $count_per_month;
                            }
                        }

                    }
                    $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_employee($r->user_id,$p_date);
                    if($annual_leaves)
                        $unpaid_off_per_month=$unpaid_off_per_month+$annual_leaves;
// get advance salary
                    $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($r->user_id);

                    $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($r->user_id);

                    if(!is_null($advance_salary)){
                        $monthly_installment = $advance_salary[0]->monthly_installment;
                        //check ifpaid
                        $em_advance_amount = floatval($emp_value[0]->advance_amount);
                        $em_total_paid = floatval($emp_value[0]->total_paid);
                        if($em_advance_amount > $em_total_paid){
                            $re_amount = $em_advance_amount - $em_total_paid;

                            if($monthly_installment=='' || $monthly_installment==0) {

                                $ntotal_paid = $emp_value[0]->total_paid;
                                $nadvance = $emp_value[0]->advance_amount;
                                $total_net_salary = $nadvance - $ntotal_paid;
                                $pay_amount = $net_salary - $total_net_salary;
                                $advance_amount = $re_amount;
                            } else {
                                //
                                if($monthly_installment > $re_amount){
                                    $advance_amount = $re_amount;
                                    $total_net_salary = $net_salary - $re_amount;
                                    $pay_amount = $net_salary - $re_amount;
                                } else {
                                    $advance_amount = $monthly_installment;
                                    $total_net_salary = $net_salary - $monthly_installment;
                                    $pay_amount = $net_salary - $monthly_installment;
                                }
                            }

                        } else {

                            $total_net_salary = $net_salary - 0;
                            $pay_amount = $net_salary - 0;
                            $advance_amount = 0;
                        }
                    } else {
                        $pay_amount = $net_salary - 0;
                        $total_net_salary = $net_salary - 0;
                        $advance_amount = 0;
                    }
                    $loan_emi = 0;

                    $loan = $this->Payroll_model->loan_by_employee_id($r->user_id);
                    $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($r->user_id);

                    if(!is_null($loan)){
                        $monthly_installment = $loan[0]->monthly_installment;
                        $loan_emi = $loan[0]->advance_amount;
                        $total_paid = $loan[0]->total_paid;
                        //check ifpaid
                        $em_advance_amount = floatval($loan[0]->advance_amount);
                        $em_total_paid = floatval($loan[0]->total_paid);

                        if($em_advance_amount > $em_total_paid){
                            //
                            $re_amount = $em_advance_amount - $em_total_paid;
                            if($monthly_installment > $re_amount){
                                $loan_emi = $re_amount;
                                $total_net_salary = $pay_amount - $re_amount;
                                $pay_amount = $pay_amount - $re_amount;
                            } else {
                                $loan_emi = $monthly_installment;
                                $total_net_salary = $pay_amount - $monthly_installment;
                                $pay_amount = $pay_amount - $monthly_installment;
                            }

                        } else {
                            $total_net_salary = $pay_amount - 0;
                            $pay_amount = $pay_amount - 0;
                            $loan_emi = 0;
                        }
                    } else {
                        $pay_amount = $pay_amount - 0;
                        $total_net_salary = $pay_amount - 0;
                        $loan_emi = 0;
                    }
                    $no_of_days =$this->Xin_model->get_number_of_days($p_date);


                    $per_day_sal = ($basic_salary+$total_allowance)/$no_of_days;

                    if($unpaid_off_per_month>0)
                    {
                        $unpaid_off_per_month>$no_of_days?$unpaid_off_per_month=$no_of_days:$unpaid_off_per_month=$unpaid_off_per_month;
                        $deduct_leave_sal = round($per_day_sal*$unpaid_off_per_month);

                        $pay_amount = round($pay_amount - $deduct_leave_sal);
                    }
                    $total_deductions = floatval($deduct_leave_sal)+floatval($loan_emi)+floatval($advance_amount);

                    $total_net = floatval($basic_salary)+floatval($total_allowance)-floatval($total_deductions)+floatval($total_expenses)+floatval($ticket_amount)+floatval($leave_salary);
                    $total_allowance = number_format($total_allowance,2);
                    $total_deductions = number_format($total_deductions,2);
                    $basic_salary = number_format($basic_salary,2);
                    $net_salary = number_format($net_salary,2);
                    $total_net=number_format($total_net,2);
                } else {
                    $total_allowance = '--';
                    $total_deductions = '--';
                    $basic_salary = '--';
                    $net_salary = '--';
                    $create_id = '--';
                    $gd = 'sl';
                    $p_class = 'emo_monthly_pay';
                    $unpaid_view = '--';
                    $total_net=0;
                }
            } else {
                $total_allowance = '--';
                $total_deductions = '--';
                $basic_salary = '--';
                $net_salary = '--';
                $create_id = '--';
                $gd = 'sl';
                $p_class = 'emo_monthly_pay';
                $unpaid_view = '--';
                $total_net=0;

            }


            $checkbox='';
            // make payment
            $payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id,$p_date);
            if($payment_check->num_rows() > 0){
                $make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id,$p_date);
                $functions = '<a class="text-success" href="'.site_url().'payroll/payslip/id/'.$make_payment[0]->make_payment_id.'">Generate Payslip</a> 
    <span  data-toggle="tooltip" data-placement="top" title="Delete Payment"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $make_payment[0]->make_payment_id . '"><i class="fa fa-trash-o"></i></button></span>';
                $status = '<span class="tag tag-success">Paid</span>';
                $p_details = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->user_id . '" data-date="'. $p_date . '" data-pay_id="'. $make_payment[0]->make_payment_id . '"><i class="fa fa-arrow-circle-right"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Edit Payment details"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-employee_id="'. $r->user_id . '" data-date="'. $p_date . '" data-pay_id="'. $make_payment[0]->make_payment_id . '"><i class="fa fa-edit"></i></button></span>';
                $total_deductions =$make_payment[0]->total_deductions;
                $total_expenses =$make_payment[0]->employee_expenses??0;
                $ticket_amount =$make_payment[0]->ticket_amount??0;
                $overtime =$make_payment[0]->overtime_amount??0;
                $othours =$make_payment[0]->overtime_hours??0;
                $leave_salary =$make_payment[0]->leave_salary??0;
                $total_net =$make_payment[0]->payment_amount;
                $comments =$make_payment[0]->comments;
                $extra =$make_payment[0]->allowance??0;
            } else {
                if($net_salary > 0) {
                    $functions = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-date="'. $p_date . '" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '"><i class="fa fa-money"></i></button></span>';
                    $checkbox='<input type="checkbox" class="check_payment row-checkbox" data-date="'. $p_date . '" value="'.$r->user_id.'" >';

                } else {
                    $functions = '<span class="text-danger" data-toggle="tooltip" data-placement="left" title="'.$this->lang->line('xin_error_payroll_can_not_make_payment').'">'.$this->lang->line('xin_error_payroll_zero_net_salary').'</span>';
                }

                $status = '<span class="tag tag-danger">UnPaid</span>';
                $p_details = $unpaid_view;
                //$p_details = '-';
            }
               


            $data[] = array(
                $checkbox,
                $r->employee_id,
                $full_name,
                $user_info[0]->department_name,
                $basic_salary,
                $total_allowance,
                number_format($total_expenses,2),
                number_format($extra,2),
                number_format($ticket_amount,2),
                number_format($leave_salary,2),
                number_format($overtime,2),
                $othours,
                $total_deductions,
                $total_net,
                $p_details,
                $status,
                $functions,
                $user_info[0]->user_id,
                $comments
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // salary list
    public function salary_list()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view("payroll/manage_salary", $data);
        } else {
            redirect('');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        if($this->input->get("employee_id")) {
            $salary = $this->Payroll_model->get_employee_template($this->input->get("employee_id"));
        } else {
            $salary = $this->Employees_model->get_employees();
        }

        $data = array();

        foreach($salary->result() as $r) {

            // get designation
            $designation = $this->Designation_model->read_designation_information($r->designation_id);
            if(!is_null($designation)){
                $designation_name = $designation[0]->designation_name;
            } else {
                $designation_name = '--';
            }
            // department
            $department = $this->Department_model->read_department_information($r->department_id);
            if(!is_null($department)){
                $department_name = $department[0]->department_name;
            } else {
                $department_name = '--';
            }
            $department_designation = $designation_name.'('.$department_name.')';

            /* for salary template > hourly*/
            $checked = '';
            /* for salary template > monthly*/
            $m_checked = '';
            /* for salary template > hourly*/
            $disabled = '';
            if($r->hourly_grade_id == 0 || $r->hourly_grade_id == '') {
                $disabled = 'disabled';
            } else {
                $checked = 'checked';
            }
            /* for salary template > monthly*/
            $m_disabled = '';
            if($r->monthly_grade_id == 0 || $r->monthly_grade_id == '') {
                $m_disabled = 'disabled';
            } else {
                $m_checked = 'checked';
            }

            /*  all hourly templates */
            $hourly_rate = '';
            $hr_radio = '
		<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_select_hourly').'"><label class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input hourly_grade hourly_'.$r->user_id.'" id="'.$r->user_id.'" name="grade_status['.$r->user_id.']" value="hourly" '.$checked.'>
			<span class="custom-control-indicator"></span>
			<span class="custom-control-description">&nbsp;</span>
		</label></span>
		<input type="hidden" name="user['.$r->user_id.']" value="'.$r->user_id.'">
		';
            $hourly_rate = $hr_radio . ' <select class="custom-select m-r-1 sm_hourly_'.$r->user_id.'" name="hourly_grade_id['.$r->user_id.']" '.$disabled.'>';
            $hourly_rate .= '<option value="0">--'.$this->lang->line('xin_select').'--</option>';
            $selected = '';
            foreach($this->Payroll_model->all_hourly_templates() as $hourly_template){
                if($r->hourly_grade_id == $hourly_template->hourly_rate_id) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $hourly_rate .= '<option value="'.$hourly_template->hourly_rate_id.'" '.$selected.'>'.$hourly_template->hourly_grade.'</option>';
            }
            $hourly_rate .= '</select>';

            /*  all salary templates */
            $_salary_template = '';
            $salary_radio = '
		<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_select_monthly').'">
		<label class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input monthly_grade monthly_'.$r->user_id.'" id="'.$r->user_id.'" name="grade_status['.$r->user_id.']" value="monthly" '.$m_checked.'>
			<span class="custom-control-indicator"></span>
			<span class="custom-control-description">&nbsp;</span>
		</label></span>
		';
            $_salary_template = $salary_radio . ' <select class="custom-select m-r-1 sm_monthly_'.$r->user_id.'" name="monthly_grade_id['.$r->user_id.']" '.$m_disabled.'>';
            $_salary_template .= '<option value="0">--'.$this->lang->line('xin_select').'--</option>';
            $m_selected = '';
            foreach($this->Payroll_model->all_salary_templates() as $salary_template){

                if($r->monthly_grade_id == $salary_template->salary_template_id) {
                    $m_selected = 'selected';
                } else {
                    $m_selected = '';
                }
                $_salary_template .= '<option value="'.$salary_template->salary_template_id.'" '.$m_selected.'>'.$salary_template->salary_grades.'</option>';
            }
            $_salary_template .= '</select>';

            $_salary_template .= '<script type="text/javascript">
		$(document).ready(function () {
			$(".hourly_grade").click(function(e){
				var th = $(this), id = th.attr("id");
				$(".monthly_"+id).prop("checked", false);
				$(".sm_monthly_"+id).prop("disabled", true);
				$(".sm_monthly_"+id).val("0");
				if (th.is(":checked")) {
					$(".sm_hourly_"+id).prop("disabled", false);
				} else {
					$(".sm_hourly_"+id).val("0");
				}
			});
		});
		</script>';
            $_salary_template .= '<script type="text/javascript">
		$(document).ready(function () {
			$(".monthly_grade").click(function(e){
				var th = $(this), id = th.attr("id");
				$(".hourly_"+id).prop("checked", false);
				$(".sm_hourly_"+id).prop("disabled", true);
				$(".sm_hourly_"+id).val("0");
				if (th.is(":checked")) {
					$(".sm_monthly_"+id).prop("disabled", false);
				} else {
					$(".sm_monthly_"+id).val("0");
				}
			});
		});
		</script>';
            $fname = $r->first_name.' '.$r->last_name;

            if(($r->monthly_grade_id ==0 || $r->hourly_grade_id=='') && ($r->hourly_grade_id ==0 || $r->hourly_grade_id=='')) {
                $functions = '-';
            } else {
                if($r->monthly_grade_id!=0){
                    $functions = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".payroll_template_modal" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
                } else {
                    $functions = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".hourlywages_template_modal" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
                }
            }

            $data[] = array(
                $functions,
                $fname,
                $r->username,
                $department_designation,
                $hourly_rate,
                $_salary_template
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $salary->num_rows(),
            "recordsFiltered" => $salary->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // make payment info by id
    public function make_payment_view()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('pay_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->read_make_payment_information($id);
        // get addd by > template
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if(!is_null($designation)){
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if(!is_null($department)){
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }

        $data = array(
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'monthly_grade_id' => $user[0]->monthly_grade_id,
            'hourly_grade_id' => $user[0]->hourly_grade_id,
            'basic_salary' => $result[0]->basic_salary,
            'payment_date' => $result[0]->payment_date,
            'payment_method' => $result[0]->payment_method,
            'overtime_rate' => $result[0]->overtime_rate,
            'hourly_rate' => $result[0]->hourly_rate,
            'total_hours_work' => $result[0]->total_hours_work,
            'overtime_amount' => $result[0]->overtime_amount,
            'employee_expenses' => $result[0]->employee_expenses,
            'leave_salary' => $result[0]->leave_salary,
            'ticket_amount' => $result[0]->ticket_amount,
            'is_payment' => $result[0]->is_payment,
            'is_advance_salary_deduct' => $result[0]->is_advance_salary_deduct,
            'advance_salary_amount' => $result[0]->advance_salary_amount,
            'loan_emi' => $result[0]->loan_emi,
            'leave_salary_deduct_amount' => $result[0]->leave_salary_deduct_amount,
            'leave_days' => $result[0]->leave_days,
            'allowance' => $result[0]->allowance,
            'extra_deductions' => $result[0]->extra_deductions,
            'house_rent_allowance' => $result[0]->house_rent_allowance,
            'medical_allowance' => $result[0]->medical_allowance,
            'travelling_allowance' => $result[0]->travelling_allowance,
            'dearness_allowance' => $result[0]->dearness_allowance,
            'telephone_allowance' => $result[0]->telephone_allowance,
            'other_allowance' => $result[0]->other_allowance,
            'provident_fund' => $result[0]->provident_fund,
            'security_deposit' => $result[0]->security_deposit,
            'tax_deduction' => $result[0]->tax_deduction,
            'gross_salary' => $result[0]->gross_salary,
            'total_allowances' => $result[0]->total_allowances,
            'total_deductions' => $result[0]->total_deductions,
            'net_salary' => $result[0]->net_salary,
            'payment_amount' => $result[0]->payment_amount,
            'comments' => $result[0]->comments,
        );
        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('payroll/dialog_payslip', $data);
        } else {
            redirect('');
        }
    }
    public function edit_payment_view()
    {

        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('pay_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->read_make_payment_information($id);
        // get addd by > template
        $user = $this->Xin_model->read_user_info($result[0]->employee_id);
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if(!is_null($designation)){
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if(!is_null($department)){
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }

        $data = array(
            'pay_id'=>$id,
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'working_hours' => $user[0]->working_hours,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'monthly_grade_id' => $user[0]->monthly_grade_id,
            'hourly_grade_id' => $user[0]->hourly_grade_id,
            'basic_salary' => $result[0]->basic_salary,
            'payment_date' => $result[0]->payment_date,
            'payment_method' => $result[0]->payment_method,
            'overtime_rate' => $result[0]->overtime_rate,
            'hourly_rate' => $result[0]->hourly_rate,
            'total_hours_work' => $result[0]->total_hours_work,
            'overtime_amount' => $result[0]->overtime_amount,
            'overtime_hours' => $result[0]->overtime_hours,
            'total_expenses' => $result[0]->employee_expenses,
            'leave_salary' => $result[0]->leave_salary,
            'ticket_amount' => $result[0]->ticket_amount,
            'is_payment' => $result[0]->is_payment,
            'comments' => $result[0]->comments,
            'is_advance_salary_deduct' => $result[0]->is_advance_salary_deduct,
            'advance_amount' => $result[0]->advance_salary_amount,
            'loan_emi' => $result[0]->loan_emi,
            'leave_salary_deduct_amount' => $result[0]->leave_salary_deduct_amount,
            'leave_days' => $result[0]->leave_days,
            'allowance' => $result[0]->allowance,
            'extra_deductions' => $result[0]->extra_deductions,
            'house_rent_allowance' => $result[0]->house_rent_allowance,
            'medical_allowance' => $result[0]->medical_allowance,
            'travelling_allowance' => $result[0]->travelling_allowance,
            'dearness_allowance' => $result[0]->dearness_allowance,
            'telephone_allowance' => $result[0]->telephone_allowance,
            'other_allowance' => $result[0]->other_allowance,
            'provident_fund' => $result[0]->provident_fund,
            'security_deposit' => $result[0]->security_deposit,
            'tax_deduction' => $result[0]->tax_deduction,
            'gross_salary' => $result[0]->gross_salary,
            'total_allowances' => $result[0]->total_allowances,
            'extra_allowance' => $result[0]->allowance,
            'extra_deductions' => $result[0]->extra_deductions,
            'total_allowances' => $result[0]->total_allowances,
            'total_deductions' => $result[0]->total_deductions,
            'net_salary' => $result[0]->net_salary,
            'payment_amount' => $result[0]->payment_amount,
            'comments' => $result[0]->comments,
        );

        $session = $this->session->userdata('username');
        if(!empty($session)){
            $this->load->view('payroll/dialog_edit_payment', $data);
        } else {
            redirect('');
        }
    }
    public function generateTextFile()
    {
        // File content
        $pay_date =$this->input->post('pay_date');
        $date = date('Y-m', strtotime($pay_date));

        $employees =$this->input->post('employees');
        $payments =$this->Payroll_model->get_all_bank_transfers($date,$employees);

        $name = strtoupper(date('M')) . date('Y');

        if($payments) {
            $content='';
            foreach ($payments as $p){
                if (stripos($p->bank_name, "EMIRATES NBD") !== false) {

                    $content .= "TR,";
                }elseif (stripos($p->bank_name, "EMIRATES ISLAMIC") !== false) {

                    $content .= "TR,";
                }else{
                    $content .= "OB,";

                }
                $formattedNumber = number_format($p->payment_amount, 2, '.', '');
                $date = $p->payment_date;
                $timestamp = strtotime($date);
                $formattedDate = strtoupper(date('M', $timestamp)) . date('y', $timestamp);
                $name = strtoupper(date('M', $timestamp)) . date('Y', $timestamp);

                $content.=strtoupper($p->first_name."".$p->last_name).",AED,".strtoupper($p->iban).",".$p->bank_code.",".$formattedDate." SALARY,".$formattedNumber.",SAL,/REF/,2";
                $content.="\n";
            }
        }       else{
            $content="No Data Available";
        }

        // Generate a unique filename
        $filename = $name .time() . '.txt';

        // Generate the file on the server
        $filepath = FCPATH . 'uploads/salary/' . $filename;
        file_put_contents($filepath, $content);

        // Prepare the JSON response
        $response = [
            'fileurl' => base_url('uploads/salary/' . $filename),
            'filename' => $filename
        ];

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function send_emails()
    {
        // File content
        $pay_date =$this->input->post('pay_date');
        $date = date('Y-m', strtotime($pay_date));

        $payments =$this->Payroll_model->get_all_salary_transfers($date);

        if($payments) {
            require './mail/gmail.php';

            foreach ($payments as $p) {
                $document = $this->download_payslips($p->make_payment_id);
                $user_info = $this->Xin_model->read_user_info($p->employee_id);
                $cinfo = $this->Xin_model->read_company_setting_info(1);
//                    //get email template
                $template = $this->Xin_model->read_email_template(1);
                if ($p->mail_sent == 0) {
                    $full_name = $user_info[0]->first_name . ' ' . $user_info[0]->last_name;

                    $pdate = date('F,Y', strtotime($p->payment_date));
                    $subject = $template[0]->subject . ' - ' . $cinfo[0]->company_name;

                    $logo = base_url() . 'uploads/logo/emso-logo.png';
//
                    $message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
		<img src="' . $logo . '" title="' . $cinfo[0]->company_name . '" width="170"><br>' . str_replace(array("{var site_name}", "{var site_url}", "{var employee_name}", "{var payslip_date}"), array($cinfo[0]->company_name, site_url(), $full_name, $pdate), html_entity_decode(stripslashes($template[0]->message))) . '</div>';
//
//                    /*
//                    $cid = $this->email->attachment_cid($logo);
//
                    $mail->addAddress($user_info[0]->email, $user_info[0]->first_name);
                    $mail->Subject = $subject;
                    $mail->msgHTML($message);
                    $mail->addAttachment($document);

                    if ($mail->send()) {
                        $result = $this->Payroll_model->update_monthly_payment_payslip(array('mail_sent' => 1), $p->make_payment_id);

                    } else {
                        $Return['error'] = "Mailer Error: " . $mail->ErrorInfo;
                    }
                    $mail->clearAddresses();
                    $mail->clearQueuedAddresses();
                    $mail->clearAttachments();
//                    $mail->clearAttachments();


//
                }

            }
            $Return['result'] = "Succesfully sent Emails To Employees!";
            $Return['error'] = '';
        }else{
            $Return['error']='No payments in the Selected Date';
        }
        $this->output($Return);
        exit;


    }

    public function generate_bulk_payslips()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $Return = array('result'=>'', 'error'=>'');


        $employees =$this->input->post('employees');
        $pay_date =$this->input->post('pay_date');
        $date = $this->input->get('date');
        foreach ($employees as $employee){
            if($employee=="on"){
                continue;
            }
            // get addd by > template
            $user = $this->Xin_model->read_user_info($employee);
            $payee_name = $user[0]->first_name.' '.$user[0]->last_name;

            $result = $this->Payroll_model->read_salary_information_by_date($employee,$date);
            $department = $this->Department_model->read_department_information($user[0]->department_id);
            $location = $this->Location_model->read_location_information($department[0]->location_id);
            if(!$location) {
                $locationid = '';
                $companyid='';
            }
            else {
                $locationid = $location[0]->location_id;
                $companyid=$location[0]->company_id;
            }
            $is_advance_deducted = 0;
            $deduct_salary = 0;
            $payment_month = strtotime($pay_date);
            $p_month = date('F Y',$payment_month);
            $all_leave_types = $this->Timesheet_model->all_leave_types();
            $unpaid_leaves = 0;
            $deduct_leave_sal = 0;
            $unpaid_off=0;
            $leaves_per_yeartype=array();
            if(empty($pay_date)){ $p_date=date('Y-m'); }
            else { $p_date = date("Y-m", strtotime($pay_date)); }

            foreach($all_leave_types as $type) {
                $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$employee,date('Y',$payment_month));
                if(($count_l>$type->days_per_year)&&($type->type_name!="Unpaid Leave"))
                {
                    $unpaid_leaves = $unpaid_leaves+($count_l-$type->days_per_year);
                    $count_l-=$type->days_per_year;
                }else if($type->type_name=="Unpaid Leave"){
                    $unpaid_leaves+=$count_l;
                    $unpaid_off+=$count_l;

                }else{
                    $unpaid_leaves += 0;
                    $unpaid_off+=$count_l;
                    $count_l=0;

                }
                $leaves_per_yeartype[$type->leave_type_id]=$count_l;
            }

            if($unpaid_leaves>0)
            {
                $unpaid_leaves_count = $this->Timesheet_model->count_total_un_paid_leaves($employee);
                if($unpaid_leaves_count<$unpaid_leaves)
                {
                    $unpaid_leaves = $unpaid_leaves_count+$unpaid_off;
                }
            }
            $unpaid_off_per_month =0;
            foreach($all_leave_types as $type) {
//
                $count_per_month =$this->Timesheet_model->get_leave_days_month($employee,$p_date,$type->leave_type_id);
                if($count_per_month) {
                    if ($type->type_name != "Unpaid Leave") {
                        if ($leaves_per_yeartype[$type->leave_type_id] < $count_per_month)
                            $unpaid_off_per_month += $leaves_per_yeartype[$type->leave_type_id];
                        else
                            $unpaid_off_per_month += $count_per_month;
                    }
                    else {
                        $unpaid_off_per_month += $count_per_month;
                    }
                }

            }
            $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_employee($employee,$p_date);
            if($annual_leaves)
                $unpaid_off_per_month=$unpaid_off_per_month+$annual_leaves;
            if($unpaid_leaves>0)
            {
                $unpaid_leaves_count = $this->Timesheet_model->count_total_un_paid_leaves($employee);
                if($unpaid_leaves_count<$unpaid_leaves)
                {
                    $unpaid_leaves = $unpaid_leaves_count;
                }
            }
// get advance salary
            $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($employee);
            $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($employee);
            $net_salary=$result[0]->net_salary;
            $pay_amount2 = $net_salary;
            if(!is_null($advance_salary)){
                $monthly_installment = $advance_salary[0]->monthly_installment;
                $total_paid = $advance_salary[0]->total_paid;
                $advance_amount = $advance_salary[0]->advance_amount;
                //check ifpaid
                $em_advance_amount = floatval($emp_value[0]->advance_amount);
                $em_total_paid = floatval($emp_value[0]->total_paid);
                if($em_advance_amount > $em_total_paid){
                    if($monthly_installment=='' || $monthly_installment==0) {

                        $ntotal_paid = floatval($emp_value[0]->total_paid);
                        $nadvance = floatval($emp_value[0]->advance_amount);
                        $total_net_salary = $nadvance - $ntotal_paid;
                        $pay_amount = $net_salary - $total_net_salary;
                        $advance_amount = $total_net_salary;
                    } else {
                        //
                        $re_amount = $em_advance_amount - $em_total_paid;
                        if($monthly_installment > $re_amount){
                            $advance_amount = $re_amount;
                            $total_net_salary = $net_salary - $re_amount;
                            $pay_amount = $net_salary - $re_amount;
                        } else {
                            $advance_amount = $monthly_installment;
                            $total_net_salary = $net_salary - $monthly_installment;
                            $pay_amount = $net_salary - $monthly_installment;
                        }
                    }

                } else {
                    $total_net_salary = $net_salary - 0;
                    $pay_amount = $net_salary - 0;
                    $advance_amount = 0;
                }
            } else {
                $pay_amount = $net_salary - 0;
                $total_net_salary = $net_salary - 0;
                $advance_amount = 0;
            }

            // get loan
            $loan_emi = 0;

            $loan = $this->Payroll_model->loan_by_employee_id($employee);
            $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($employee);
            $no_of_days =$this->Xin_model->get_number_of_days($p_date);

            $total_allow = floatval($result[0]->house_rent_allowance) + floatval($result[0]->medical_allowance) + floatval($result[0]->travelling_allowance) + floatval($result[0]->other_allowance) + floatval($result[0]->telephone_allowance);
            $per_day_sal = ($result[0]->basic_salary+$total_allow)/$no_of_days;
            if($unpaid_off_per_month>0)
            {
                $unpaid_off_per_month>$no_of_days?$unpaid_off_per_month=$no_of_days:$unpaid_off_per_month=$unpaid_off_per_month;
                $deduct_leave_sal = round($per_day_sal*$unpaid_off_per_month);
                $pay_amount = round($pay_amount - $deduct_leave_sal);
            }

            if(!is_null($loan)){
                $monthly_installment = $loan[0]->monthly_installment;
                $total_paid = $loan[0]->total_paid;
                $loan_emi = $loan[0]->monthly_installment;
                //check ifpaid
                $em_advance_amount = floatval($emp_loan_value[0]->advance_amount);
                $em_total_paid = floatval($emp_loan_value[0]->total_paid);
                if($em_advance_amount > $em_total_paid){
                    $re_amount = $em_advance_amount - $em_total_paid;
                    if($monthly_installment > $re_amount){
                        $loan_emi = $re_amount;
                        $total_net_salary = $pay_amount - $re_amount;
                        $pay_amount = $pay_amount - $re_amount;
                    } else {
                        $loan_emi = $monthly_installment;
                        $total_net_salary = $pay_amount - $monthly_installment;
                        $pay_amount = $pay_amount - $monthly_installment;
                    }
                    $add_amount = $em_total_paid + $loan_emi;
                    //pay_date //emp_id
                    $adv_data = array('total_paid' => $add_amount);
                    //
                    $this->Payroll_model->updated_loan_paid_amount($adv_data,$employee);
                }
                else{
                    $loan_emi=0;
                }
            }

            $total_allow = floatval($result[0]->house_rent_allowance) + floatval($result[0]->medical_allowance) + floatval($result[0]->travelling_allowance) + floatval($result[0]->other_allowance) + floatval($result[0]->telephone_allowance);

            $total_expenses =$this->Payroll_model->get_expenses_by_month_user($employee,$p_date);
            if($total_expenses)
            {
                $pay_amount+=$total_expenses;
            }
            $ticket_amount =$this->Payroll_model->get_tickets_by_month_user($employee,$p_date);
            if($ticket_amount)
                $pay_amount+=$ticket_amount;
            $leave_salary =$this->Payroll_model->get_leave_salary_by_month_user($employee,$p_date);
            if($leave_salary)
                $pay_amount+=$leave_salary;

            $data = array(
                'employee_id' => $employee,
                'designation_id' => $user[0]->designation_id,
                'department_id' => $user[0]->department_id,
                'location_id' => $locationid,
                'company_id' => $companyid,
                'payment_date' => $p_date,
                'basic_salary' => $result[0]->basic_salary,
                'payment_amount' => $pay_amount,
                'gross_salary' => $result[0]->basic_salary,
                'total_allowances' => $total_allow,
                'total_deductions' =>  floatval($deduct_salary)+floatval($deduct_leave_sal)+floatval($loan_emi)+floatval($advance_amount),
                'net_salary' => $net_salary,
                'house_rent_allowance' => $result[0]->house_rent_allowance,
                'medical_allowance' => $result[0]->medical_allowance,
                'travelling_allowance' => $result[0]->travelling_allowance,
                'dearness_allowance' => $result[0]->dearness_allowance,
                'other_allowance' => $result[0]->other_allowance,
                'telephone_allowance' => $result[0]->telephone_allowance,
                'provident_fund' => 0,
                'tax_deduction' => 0,
                'security_deposit' => 0,
                'overtime_rate' => $result[0]->overtime_rate,
                'is_advance_salary_deduct' => $is_advance_deducted,
                'advance_salary_amount' => $deduct_salary,
                'loan_emi' => $loan_emi,
                'leave_salary_deduct_amount' => $deduct_leave_sal,
                'leave_days' => $unpaid_leaves,
                'allowance' => 0,
                'employee_expenses'=>$total_expenses,
                'leave_salary'=>$leave_salary,
                'ticket_amount'=>$ticket_amount,
                'extra_deductions' => 0,
                'is_payment' => '1',
                'payment_method' => 4,
                'comments' =>'',
                'status' => '1',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Payroll_model->add_monthly_payment_payslip($data);

            if ($result == TRUE) {

                $this->Timesheet_model->update_total_un_paid_leaves($employee);


                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
//                if($setting[0]->enable_email_notification == 'yes') {
//
//                    //$this->email->set_mailtype("html");
//                    //get company info
//                    $cinfo = $this->Xin_model->read_company_setting_info(1);
//                    //get email template
//                    $template = $this->Xin_model->read_email_template(1);
//                    //get employee info
//                    $user_info = $this->Xin_model->read_user_info($employee);
//                    $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
//                    // get date
//                    $d = explode('-',$p_date);
//                    $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
//                    $pdate = $get_month.', '.$d[0];
//
//                    $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
//
//                    $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
//                    $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
//                    $subdomain_name; // Print the sub domain
//
//                    $accounts_url = 'https://'.$subdomain_name.'/hrm';
//
//                    $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;
//
//                    $message = '
//			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
//			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';
//                    require './mail/gmail.php';
//                    $mail->addAddress($user_info[0]->email, $user_info[0]->first_name);
//                    $mail->Subject = $subject;
//                    $mail->msgHTML($message);
//
////                    if (!$mail->send()) {
////                        //echo "Mailer Error: " . $mail->ErrorInfo;
////                    } else {
////                        //echo "Message sent!";
////                    }
//
//                }
            } else {
                $Return['error'] = "Error Occured while generating payment for  ".$payee_name;
            }

        }
        $Return['result']="Succesfully Made Payments for Employees!";
        $this->output($Return);
        exit;


    }

    // pay monthly > create payslip
    public function pay_monthly()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('employee_id');
        $date = $this->input->get('date');
        // get addd by > template
        $user = $this->Xin_model->read_user_info($id);
        $result = $this->Payroll_model->read_salary_information_by_date($id,$date);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Location_model->read_location_information($department[0]->location_id);
        if(!$location) {
            $locationid = '';
            $companyid='';
        }
        else {
            $locationid = $location[0]->location_id;
            $companyid=$location[0]->company_id;
        }
        $total_expenses =$this->Payroll_model->get_expenses_by_month_user($user[0]->user_id,$date);
        $ticket_amount =$this->Payroll_model->get_tickets_by_month_user($user[0]->user_id,$date);
        $leave_salary =$this->Payroll_model->get_leave_salary_by_month_user($user[0]->user_id,$date);

        $data = array(
            'department_id' => $user[0]->department_id,
            'designation_id' => $user[0]->designation_id,
            'location_id' => $locationid,
            'company_id' => $companyid,
            'salary_id' => $result[0]->salary_id,
            'user_id' => $user[0]->user_id,
            'working_hours' => $user[0]->working_hours,
            'basic_salary' => $result[0]->basic_salary,
            'overtime_rate' => $result[0]->overtime_rate,
            'house_rent_allowance' => $result[0]->house_rent_allowance,
            'medical_allowance' => $result[0]->medical_allowance,
            'travelling_allowance' => $result[0]->travelling_allowance,
            'other_allowance' => $result[0]->other_allowance,
            'total_expenses'=>$total_expenses,
            'ticket_amount'=>$ticket_amount,
            'leave_salary'=>$leave_salary,
            'telephone_allowance' => $result[0]->telephone_allowance,
            'security_deposit' => $result[0]->security_deposit,
            'provident_fund' => $result[0]->provident_fund,
            'tax_deduction' => $result[0]->tax_deduction,
            'gross_salary' => $result[0]->gross_salary,
            'total_allowance' => $result[0]->total_allowance,
            'total_deduction' => $result[0]->total_deduction,
            'net_salary' => $result[0]->net_salary,
            'added_by' => $result[0]->added_by,
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_make_payment', $data);
        } else {
            redirect('');
        }
    }

    // pay hourly > create payslip
    public function pay_hourly()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Xin_model->read_user_info($id);
        $result = $this->Payroll_model->read_hourly_wage_information($user[0]->hourly_grade_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Location_model->read_location_information($department[0]->location_id);
        $data = array(
            'department_id' => $user[0]->department_id,
            'designation_id' => $user[0]->designation_id,
            'location_id' => $location[0]->location_id,
            'company_id' => $location[0]->company_id,
            'hourly_rate_id' => $result[0]->hourly_rate_id,
            'user_id' => $user[0]->user_id,
            'hourly_rate' => $result[0]->hourly_rate,
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_make_payment', $data);
        } else {
            redirect('');
        }
    }

    // get payroll template info by id
    public function template_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('salary_template_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->read_template_information($id);
        $data = array(
            'salary_template_id' => $result[0]->salary_template_id,
            'salary_grades' => $result[0]->salary_grades,
            'basic_salary' => $result[0]->basic_salary,
            'overtime_rate' => $result[0]->overtime_rate,
            'house_rent_allowance' => $result[0]->house_rent_allowance,
            'medical_allowance' => $result[0]->medical_allowance,
            'travelling_allowance' => $result[0]->travelling_allowance,
            'dearness_allowance' => $result[0]->dearness_allowance,
            'other_allowance' => $result[0]->other_allowance,
            'telephone_allowance' => $result[0]->telephone_allowance,
            'security_deposit' => $result[0]->security_deposit,
            'provident_fund' => $result[0]->provident_fund,
            'tax_deduction' => $result[0]->tax_deduction,
            'gross_salary' => $result[0]->gross_salary,
            'total_allowance' => $result[0]->total_allowance,
            'total_deduction' => $result[0]->total_deduction,
            'net_salary' => $result[0]->net_salary,
            'added_by' => $result[0]->added_by,
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_templates', $data);
        } else {
            redirect('');
        }
    }

    // get payroll template info by id
    public function payroll_template_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Xin_model->read_user_info($id);
        // user full name
        $full_name = $user[0]->first_name.' '.$user[0]->last_name;
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if(!is_null($designation)){
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if(!is_null($department)){
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }
        $data = array(
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'monthly_grade_id' => $user[0]->monthly_grade_id,
            'hourly_grade_id' => $user[0]->hourly_grade_id
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_templates', $data);
        } else {
            redirect('');
        }
    }

    // get hourly wage template info by id
    public function hourlywage_template_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Xin_model->read_user_info($id);
        // user full name
        $full_name = $user[0]->first_name.' '.$user[0]->last_name;
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if(!is_null($designation)){
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if(!is_null($department)){
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }
        $data = array(
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'monthly_grade_id' => $user[0]->monthly_grade_id,
            'hourly_grade_id' => $user[0]->hourly_grade_id
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_templates', $data);
        } else {
            redirect('');
        }
    }

    // get hourly wage info by id
    public function hourly_wage_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('hourly_rate_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->read_hourly_wage_information($id);
        $data = array(
            'hourly_rate_id' => $result[0]->hourly_rate_id,
            'hourly_grade' => $result[0]->hourly_grade,
            'hourly_rate' => $result[0]->hourly_rate,
            'added_by' => $result[0]->added_by
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_hourly_wages', $data);
        } else {
            redirect('');
        }
    }

    // get advance salary info by id
    public function advance_salary_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('advance_salary_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->read_advance_salary_info($id);
        $data = array(
            'advance_salary_id' => $result[0]->advance_salary_id,
            'employee_id' => $result[0]->employee_id,
            'month_year' => $result[0]->month_year,
            'advance_amount' => $result[0]->advance_amount,
            'one_time_deduct' => $result[0]->one_time_deduct,
            'monthly_installment' => $result[0]->monthly_installment,
            'reason' => $result[0]->reason,
            'status' => $result[0]->status,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Xin_model->all_employees_2()
        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_advance_salary', $data);
        } else {
            redirect('');
        }
    }


    // get loan info by id
    public function loan_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('loan_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->read_loan_info($id);
        $data = array(
            'loan_id' => $result[0]->loan_id,
            'employee_id' => $result[0]->employee_id,
            'month_year' => $result[0]->month_year,
            'advance_amount' => $result[0]->advance_amount,
            'total_paid' => $result[0]->total_paid,
            'one_time_deduct' => $result[0]->one_time_deduct,
            'monthly_installment' => $result[0]->monthly_installment,
            'reason' => $result[0]->reason,
            'status' => $result[0]->status,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Xin_model->all_employees_2(),
            'loan_history'=>$this->Xin_model->get_payment_history($result[0]->employee_id,$result[0]->created_at,$result[0]->advance_amount,$result[0]->monthly_installment,$result[0]->one_time_deduct),

        );

        if(!empty($session)){
            $this->load->view('payroll/dialog_loan', $data);
        } else {
            redirect('');
        }
    }

    // get advance salary info by id
    public function advance_salary_report_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();

        $id = $this->input->get('employee_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->advance_salaries_report_view($id);
        $data = array(
            'advance_salary_id' => $result[0]->advance_salary_id,
            'employee_id' => $result[0]->employee_id,
            'month_year' => $result[0]->month_year,
            'advance_amount' => $result[0]->advance_amount,
            'total_paid' => $result[0]->total_paid,
            'one_time_deduct' => $result[0]->one_time_deduct,
            'monthly_installment' => $result[0]->monthly_installment,
            'reason' => $result[0]->reason,
            'status' => $result[0]->status,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Xin_model->all_employees_2(),

        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_advance_salary', $data);
        } else {
            redirect('');
        }
    }


    public function payroll_report_list()
    {
        $session = $this->session->userdata('username');
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $min_date = $this->input->get('min_date')??date('Y-01-01');
        $max_date = $this->input->get('max_date')??date('Y-12-31');

        $dept = $this->input->get("dept");

        // date and employee id
        $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : [];
        $p_date = isset($_GET['month_year']) ? $_GET['month_year'] : [];
        $payslip = $this->Payroll_model->get_payment_report($employee_id,$dept,$p_date,$min_date,$max_date);
        $data = array();

        foreach($payslip->result() as $r) {

            // get addd by > template
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            if(!is_null($user)){
                $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            } else {
                $full_name = '--';
            }
            $user_info = $this->Xin_model->get_employee_details($r->employee_id);

            $d = explode('-',$r->payment_date);
            $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
            $month_year = $get_month.', '.$d[0];
            // get net salary
            $advance_amount = $this->Xin_model->currency_sign($r->payment_amount);
            // get date > created at > and format
            $cdate = $this->Xin_model->set_date_format($r->created_at);

            $data[] = array(
                $r->payment_date,
                $user[0]->employee_id,
                $full_name,
                $user_info[0]->department_name,
                $r->basic_salary,
                $r->total_allowances,
                $r->employee_expenses,
                $r->allowance,
                $r->ticket_amount,
                $r->leave_salary,
                $r->overtime_amount,
                $r->overtime_hours,
                $r->total_deductions,
                $advance_amount,
                $cdate,
                $r->comments

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();

//        echo $this->db->last_query();
//        die;
//        die;




    }
    public function payroll_report()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees_2();
        $data['breadcrumbs'] = "Payroll Report";;
        $data['path_url'] = 'payroll_report';

//        $result = $this->Payroll_model->get_payroll_report();
        if(!empty($session)){
            $data['subview'] = $this->load->view("payroll/payroll_report", $data, TRUE);
            $this->load->view('layout_main', $data); //page load
        } else {
            redirect('');
        }
    }

    // get advance salary info by id
    public function loan_report_read()
    {
        $session = $this->session->userdata('username');
        if(empty($session)){
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('employee_id');
        // $data['all_countries'] = $this->xin_model->get_countries();
        $result = $this->Payroll_model->loan_report_view($id);
        $data = array(
            'loan_id' => $result[0]->loan_id,
            'employee_id' => $result[0]->employee_id,
            'month_year' => $result[0]->month_year,
            'advance_amount' => $result[0]->advance_amount,
            'total_paid' => $result[0]->total_paid,
            'one_time_deduct' => $result[0]->one_time_deduct,
            'monthly_installment' => $result[0]->monthly_installment,
            'reason' => $result[0]->reason,
            'status' => $result[0]->status,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Xin_model->all_employees_2(),
            'loan_history'=>$this->Xin_model->get_payment_history($result[0]->employee_id,$result[0]->created_at,$result[0]->advance_amount,$result[0]->monthly_installment,$result[0]->one_time_deduct),

        );
        if(!empty($session)){
            $this->load->view('payroll/dialog_loan', $data);
        } else {
            redirect('');
        }
    }

    // Validate and add info in database
    public function add_template() {

        if($this->input->post('add_type')=='payroll') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('salary_grades')==='') {
                $Return['error'] = $this->lang->line('xin_error_template_name');
            } else if($this->input->post('basic_salary')==='') {
                $Return['error'] = $this->lang->line('xin_error_basic_salary');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'salary_grades' => $this->input->post('salary_grades'),
                'basic_salary' => $this->input->post('basic_salary'),
                'overtime_rate' => $this->input->post('overtime_rate'),
                'house_rent_allowance' => $this->input->post('house_rent_allowance'),
                'medical_allowance' => $this->input->post('medical_allowance'),
                'travelling_allowance' => $this->input->post('travelling_allowance'),
                'dearness_allowance' => $this->input->post('dearness_allowance'),
                'provident_fund' => $this->input->post('provident_fund'),
                'tax_deduction' => $this->input->post('tax_deduction'),
                'security_deposit' => $this->input->post('security_deposit'),
                'gross_salary' => $this->input->post('gross_salary'),
                'total_allowance' => $this->input->post('total_allowance'),
                'total_deduction' => $this->input->post('total_deduction'),
                'net_salary' => $this->input->post('net_salary'),
                'added_by' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y h:i:s'),

            );
            $result = $this->Payroll_model->add_template($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_payroll_template_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database
    public function add_hourly_rate() {

        if($this->input->post('add_type')=='payroll') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('hourly_grade')==='') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if($this->input->post('hourly_rate')==='') {
                $Return['error'] = $this->lang->line('xin_error_hourly_rate_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'hourly_grade' => $this->input->post('hourly_grade'),
                'hourly_rate' => $this->input->post('hourly_rate'),
                'added_by' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Payroll_model->add_hourly_wages($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_hourly_wage_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');;
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database
    public function update_template() {

        if($this->input->post('edit_type')=='payroll') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('hourly_grade')==='') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if($this->input->post('hourly_rate')==='') {
                $Return['error'] = $this->lang->line('xin_error_hourly_rate_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'salary_grades' => $this->input->post('salary_grades'),
                'basic_salary' => $this->input->post('basic_salary'),
                'overtime_rate' => $this->input->post('overtime_rate'),
                'house_rent_allowance' => $this->input->post('house_rent_allowance'),
                'medical_allowance' => $this->input->post('medical_allowance'),
                'travelling_allowance' => $this->input->post('travelling_allowance'),
                'dearness_allowance' => $this->input->post('dearness_allowance'),
                'provident_fund' => $this->input->post('provident_fund'),
                'tax_deduction' => $this->input->post('tax_deduction'),
                'security_deposit' => $this->input->post('security_deposit'),
                'gross_salary' => $this->input->post('gross_salary'),
                'total_allowance' => $this->input->post('total_allowance'),
                'total_deduction' => $this->input->post('total_deduction'),
                'net_salary' => $this->input->post('net_salary')
            );

            $result = $this->Payroll_model->update_template_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_payroll_template_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database
    public function update_hourly_wages() {

        if($this->input->post('edit_type')=='payroll') {

            $id = $this->uri->segment(3);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('hourly_grade')==='') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if($this->input->post('hourly_rate')==='') {
                $Return['error'] = $this->lang->line('xin_error_hourly_rate_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'hourly_grade' => $this->input->post('hourly_grade'),
                'hourly_rate' => $this->input->post('hourly_rate')
            );

            $result = $this->Payroll_model->update_hourly_wages_record($data,$id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_hourly_wage_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database > update salary template
    public function user_salary_template() {

        if($this->input->post('edit_type')=='payroll') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $count = count($this->input->post('grade_status'));

            /* Set Salary Template for User*/
            if($count > 0) {
                $grade_status = $this->input->post("grade_status");
                foreach($grade_status as $key=>$val) {
                    //update salary template info in DB
                    $data = array(
                        'salary_template' => $val
                    );
                    $this->Payroll_model->update_salary_template($data, $key);
                }
            }  else {
                foreach($this->input->post('user') as $key=>$val) {
                    //update salary template info in DB
                    if(null==$this->input->post('grade_monthly')) {
                        //update salary template info in DB
                        $data = array(
                            'salary_template' => ''
                        );
                        $this->Payroll_model->update_empty_salary_template($data, $key);
                    }
                }
            }

            /* Set Hourly Grade/ for User */
            if(null!=$this->input->post('hourly_grade_id')) {
                foreach($this->input->post('hourly_grade_id') as $key=>$val) {
                    //update Hourly Grade info in DB
                    $data = array(
                        'hourly_grade_id' => $val,
                        'monthly_grade_id' => '0'
                    );
                    $this->Payroll_model->update_hourlygrade_salary_template($data, $key);
                }
            } else {
                foreach($this->input->post('user') as $key=>$val) {
                    //update salary template info in DB
                    if(null==$this->input->post('hourly_grade_id')) {
                        //update Hourly Grade info in DB
                        $data = array(
                            'hourly_grade_id' => '0',
                        );
                        $this->Payroll_model->update_hourlygrade_zero($data, $key);
                    }
                }
            }

            /* Set Monthly Grade/ for User */
            if(null!=$this->input->post('monthly_grade_id')) {
                foreach($this->input->post('monthly_grade_id') as $key=>$val) {
                    //update Hourly Grade info in DB
                    $data = array(
                        'hourly_grade_id' => '0',
                        'monthly_grade_id' => $val
                    );
                    $this->Payroll_model->update_monthlygrade_salary_template($data, $key);

                }
            } else {
                foreach($this->input->post('user') as $key=>$val) {
                    if(null==$this->input->post('monthly_grade_id')) {
                        //update Hourly Grade info in DB
                        $data = array(
                            'monthly_grade_id' => '0'
                        );
                        $this->Payroll_model->update_monthlygrade_zero($data, $key);
                    }
                }
            }

            $Return['result'] = $this->lang->line('xin_success_salary_info_updated');
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database > add monthly payment
    public function add_pay_monthly() {

        if($this->input->post('add_type')=='add_monthly_payment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('payment_method')==='') {
                $Return['error'] = $this->lang->line('xin_error_makepayment_payment_method');
            }
//            else if($this->input->post('comments')==='') {
//                $Return['error'] = $this->lang->line('xin_error_makepayment_comments');
//            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            // get advance salary
            $is_advance_deducted = 0;
            $deduct_salary = 0;

            $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($this->input->post('emp_id'));
            $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($this->input->post('emp_id'));
            if(!is_null($advance_salary)){
                $monthly_installment = $advance_salary[0]->monthly_installment;
                $total_paid = $advance_salary[0]->total_paid;
                $advance_amount = $advance_salary[0]->advance_amount;
                //check ifpaid
                $em_advance_amount = floatval($emp_value[0]->advance_amount);
                $em_total_paid = floatval($emp_value[0]->total_paid);
                if($em_advance_amount > $em_total_paid){
                    if($monthly_installment=='' || $monthly_installment==0) {
                        $add_amount = $em_total_paid + $this->input->post('advance_amount');
                        //pay_date //emp_id
                        $adv_data = array('total_paid' => $add_amount);
                        $payslip_deduct = $this->input->post('advance_amount');
                        //
                        $result = $this->Payroll_model->updated_advance_salary_paid_amount($adv_data,$this->input->post('emp_id'));
                        $deduct_salary = $payslip_deduct;
                        $is_advance_deducted = 1;
                    } else {
                        $add_amount = $em_total_paid + $this->input->post('advance_amount');
                        $payslip_deduct = $this->input->post('advance_amount');
                        //pay_date //emp_id
                        $adv_data = array('total_paid' => $add_amount);
                        //
                        $this->Payroll_model->updated_advance_salary_paid_amount($adv_data,$this->input->post('emp_id'));
                        $deduct_salary = $payslip_deduct;
                        $is_advance_deducted = 1;
                    }

                }
            } else {
                $deduct_salary = 0;
                $is_advance_deducted = 0;
            }

            if(!empty($this->input->post('leave_amount')))
            {
                $deduct_leave_sal = $this->input->post('leave_amount');
            }
            else
            {
                $deduct_leave_sal = 0;
            }

            // get loan
            $loan_emi = 0;

            $loan = $this->Payroll_model->loan_by_employee_id($this->input->post('emp_id'));
            $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($this->input->post('emp_id'));
            if(!is_null($loan)){
                $monthly_installment = $loan[0]->monthly_installment;
                $total_paid = $loan[0]->total_paid;
                $loan_emi = $loan[0]->monthly_installment;
                //check ifpaid
                $em_advance_amount = floatval($emp_loan_value[0]->advance_amount);
                $em_total_paid =floatval($emp_loan_value[0]->total_paid);
                if($em_advance_amount > $em_total_paid){
                    $add_amount = $em_total_paid + $this->input->post('loan_emi');
                    //pay_date //emp_id
                    $adv_data = array('total_paid' => $add_amount);
                    //
                    $this->Payroll_model->updated_loan_paid_amount($adv_data,$this->input->post('emp_id'));
                }
                else{
                    $loan_emi=0;
                }
            }

            if(empty($this->input->post('pay_date'))){ $p_date=date('Y-m'); }
            else { $p_date = date("Y-m", strtotime($this->input->post('pay_date'))); }

            $data = array(
                'employee_id' => $this->input->post('emp_id'),
                'department_id' => $this->input->post('department_id'),
                'company_id' => $this->input->post('company_id'),
                'location_id' => $this->input->post('location_id'),
                'designation_id' => $this->input->post('designation_id'),
                'payment_date' => $p_date,
                'basic_salary' => $this->input->post('basic_salary'),
                'payment_amount' => $this->input->post('payment_amount'),
                'gross_salary' => $this->input->post('gross_salary'),
                'total_allowances' => $this->input->post('total_allowances'),
                'total_deductions' => $this->input->post('total_deductions'),
                'net_salary' => $this->input->post('net_salary'),
                'house_rent_allowance' => $this->input->post('house_rent_allowance'),
                'employee_expenses' => $this->input->post('total_expenses'),
                'ticket_amount' => $this->input->post('ticket_amount'),
                'leave_salary' => $this->input->post('leave_salary'),
                'medical_allowance' => $this->input->post('medical_allowance'),
                'travelling_allowance' => $this->input->post('travelling_allowance'),
                'other_allowance' => $this->input->post('other_allowance'),
                'telephone_allowance' => $this->input->post('telephone_allowance'),
                'provident_fund' => $this->input->post('provident_fund'),
                'tax_deduction' => $this->input->post('tax_deduction'),
                'security_deposit' => $this->input->post('security_deposit'),
                'overtime_rate' => $this->input->post('overtime_rate'),
                'overtime_hours' => $this->input->post('overtime_hours'),
                'overtime_amount' => $this->input->post('overtime_salary'),
                'is_advance_salary_deduct' => $is_advance_deducted,
                'advance_salary_amount' => $this->input->post('advance_amount'),
                'loan_emi' => $this->input->post('loan_emi'),
                'leave_salary_deduct_amount' => $deduct_leave_sal,
                'leave_days' => $this->input->post('leave_days'),
                'allowance' => $this->input->post('allowance'),
                'extra_deductions' => $this->input->post('extra_deductions'),
                'is_payment' => '1',
                'payment_method' => $this->input->post('payment_method'),
                'comments' => $this->input->post('comments'),
                'status' => '1',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Payroll_model->add_monthly_payment_payslip($data);

            if ($result == TRUE) {

                $this->Timesheet_model->update_total_un_paid_leaves($this->input->post('emp_id'),$this->input->post('leave_days'));
                $this->Timesheet_model->update_all_expenses_user($this->input->post('emp_id'),$this->input->post('pay_date'));

                $Return['result'] = $this->lang->line('xin_success_payment_paid');

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
//                if($setting[0]->enable_email_notification == 'yes') {
//
//                    //$this->email->set_mailtype("html");
//                    //get company info
//                    $cinfo = $this->Xin_model->read_company_setting_info(1);
//                    //get email template
//                    $template = $this->Xin_model->read_email_template(1);
//                    //get employee info
//                    $user_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
//                    $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
//                    // get date
//                    $d = explode('-',$p_date);
//                    $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
//                    $pdate = $get_month.', '.$d[0];
//
//                    $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
//
//                    $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
//                    $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
//                    $subdomain_name; // Print the sub domain
//
//                    $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';
//
//                    $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;
//
//                    $message = '
//			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
//			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';
//
//                    /*
//                    $cid = $this->email->attachment_cid($logo);
//                    $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
//                    $this->email->to($user_info[0]->email);
//
//                    $this->email->subject($subject);
//                    $this->email->message($message);
//
//                    $this->email->send();
//                    */
//
//                    require '../mail/gmail.php';
//                    $mail->addAddress($user_info[0]->email, $user_info[0]->first_name);
//                    $mail->Subject = $subject;
//                    $mail->msgHTML($message);
//
//                    if (!$mail->send()) {
//                        //echo "Mailer Error: " . $mail->ErrorInfo;
//                    } else {
//                        //echo "Message sent!";
//                    }
//
//                }
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }
    public function edit_pay_monthly() {

        if($this->input->post('add_type')=='edit_monthly_payment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->input->post('pay_id');

            /* Server side PHP input validation */
            if($this->input->post('payment_method')==='') {
                $Return['error'] = $this->lang->line('xin_error_makepayment_payment_method');
            }
//            else if($this->input->post('comments')==='') {
//                $Return['error'] = $this->lang->line('xin_error_makepayment_comments');
//            }
            $previous_details = $this->Payroll_model->read_make_payment_information($id);

            if($Return['error']!=''){
                $this->output($Return);
            }

            // get advance salary
            $is_advance_deducted = 0;
            $deduct_salary = 0;

            $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($this->input->post('emp_id'));
            $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($this->input->post('emp_id'));
            if(!is_null($advance_salary)){
                if($previous_details[0]->advance_salary_amount)
                    $previous_adv =$previous_details[0]->advance_salary_amount;
                else
                    $previous_adv=0;
                $monthly_installment = $advance_salary[0]->monthly_installment;
                $total_paid = $advance_salary[0]->total_paid;
                $advance_amount = $advance_salary[0]->advance_amount;
                //check ifpaid
                $em_advance_amount = floatval($emp_value[0]->advance_amount);
                $em_total_paid = floatval($emp_value[0]->total_paid);
                if($em_advance_amount > $em_total_paid){

                    if($monthly_installment=='' || $monthly_installment==0) {
                        $add_amount = $em_total_paid + $this->input->post('advance_amount')-$previous_adv;
                        //pay_date //emp_id
                        $adv_data = array('total_paid' => $add_amount);
                        $payslip_deduct = $this->input->post('advance_amount');
                        //
                        $result = $this->Payroll_model->updated_advance_salary_paid_amount($adv_data,$this->input->post('emp_id'));
                        $deduct_salary = $payslip_deduct;
                        $is_advance_deducted = 1;
                    } else {
                        if($this->input->post('advance_amount'))
                            $add_amount = $em_total_paid + $this->input->post('advance_amount')-$previous_adv;
                        else
                            $add_amount = $em_total_paid -$previous_adv;

                        $payslip_deduct = $this->input->post('advance_amount');
                        //pay_date //emp_id
                        $adv_data = array('total_paid' => $add_amount);
                        //
                        $this->Payroll_model->updated_advance_salary_paid_amount($adv_data,$this->input->post('emp_id'));
                        $deduct_salary = $payslip_deduct;
                        $is_advance_deducted = 1;
                    }

                }
            } else {
                $deduct_salary = 0;
                $is_advance_deducted = 0;
            }

            if(!empty($this->input->post('leave_amount')))
            {
                $deduct_leave_sal = $this->input->post('leave_amount');
            }
            else
            {
                $deduct_leave_sal = 0;
            }

            // get loan
            $loan_emi = 0;

            $loan = $this->Payroll_model->loan_by_employee_id($this->input->post('emp_id'));
            $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($this->input->post('emp_id'));
            if(!is_null($loan)){
                if($previous_details[0]->loan_emi)
                    $prev_loan =$previous_details[0]->loan_emi;
                else
                    $prev_loan=0;

                $monthly_installment = $loan[0]->monthly_installment;
                $total_paid = $loan[0]->total_paid;
                $loan_emi = $loan[0]->monthly_installment;
                //check ifpaid
                $em_advance_amount = floatval($emp_loan_value[0]->advance_amount);
                $em_total_paid = floatval($emp_loan_value[0]->total_paid);
                    if($this->input->post('loan_emi'))
                        $add_amount = $em_total_paid + $this->input->post('loan_emi')-$prev_loan;
                    else
                        $add_amount = $em_total_paid -$prev_loan;

                    //pay_date //emp_id
                    $adv_data = array('total_paid' => $add_amount);
                    //
                    $this->Payroll_model->updated_loan_paid_amount($adv_data,$this->input->post('emp_id'));

            }

            if(empty($this->input->post('pay_date'))){ $p_date=date('Y-m'); }
            else { $p_date = date("Y-m", strtotime($this->input->post('pay_date'))); }

            $data = array(
//                'payment_date' => $p_date,
                'payment_amount' => $this->input->post('payment_amount'),
                'total_deductions' => $this->input->post('total_deductions'),
                'net_salary' => $this->input->post('net_salary'),
                'overtime_rate' => $this->input->post('overtime_rate'),
                'overtime_hours' => $this->input->post('overtime_hours'),
                'overtime_amount' => $this->input->post('overtime_salary'),
                'is_advance_salary_deduct' => $is_advance_deducted,
                'advance_salary_amount' => $this->input->post('advance_amount'),
                'loan_emi' => $this->input->post('loan_emi'),
                'leave_salary_deduct_amount' => $deduct_leave_sal,
                'leave_days' => $this->input->post('leave_days'),
                'allowance' => $this->input->post('allowance'),
                'extra_deductions' => $this->input->post('extra_deductions'),
                'is_payment' => '1',
                'payment_method' => $this->input->post('payment_method'),
                'comments' => $this->input->post('comments'),
                'status' => '1',
                'mail_sent'=>0
            );
            $result = $this->Payroll_model->update_monthly_payment_payslip($data,$id);

            if ($result == TRUE) {
                $this->Timesheet_model->update_previous_un_paid_leaves($this->input->post('emp_id'),$previous_details[0]->leave_days);
                $this->Timesheet_model->update_total_un_paid_leaves($this->input->post('emp_id'),$this->input->post('leave_days'));
                $this->Timesheet_model->update_all_expenses_user($this->input->post('emp_id'),$this->input->post('pay_date'));

                $Return['result'] = $this->lang->line('xin_success_payment_paid');

                //get setting info
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }
    public function delete_payslip() {
        if($this->input->post('is_ajax') == 2) {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $payslip = $this->Payroll_model->read_make_payment_information($id);
            $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($payslip[0]->employee_id);
            $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($payslip[0]->employee_id);
            if(!is_null($advance_salary)&&$payslip[0]->advance_salary_amount){
                if($payslip[0]->advance_salary_amount)
                    $previous_adv =$payslip[0]->advance_salary_amount;
                else
                    $previous_adv=0;
                $monthly_installment = $advance_salary[0]->monthly_installment;
                $total_paid = $advance_salary[0]->total_paid;
                $advance_amount = $advance_salary[0]->advance_amount;
                //check ifpaid
                $em_advance_amount = floatval($emp_value[0]->advance_amount);
                $em_total_paid = floatval($emp_value[0]->total_paid);

                $add_amount = $em_total_paid -$previous_adv;
                //pay_date //emp_id
                $adv_data = array('total_paid' => $add_amount);
                //
                $this->Payroll_model->updated_advance_salary_paid_amount($adv_data,$payslip[0]->employee_id);



            } else {
                $deduct_salary = 0;
                $is_advance_deducted = 0;
            }
            $loan = $this->Payroll_model->loan_by_employee_id($payslip[0]->employee_id);
            $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($payslip[0]->employee_id);
            if(!is_null($loan)){

                if($payslip[0]->loan_emi)
                    $prev_loan =$payslip[0]->loan_emi;
                else
                    $prev_loan=0;

                $monthly_installment = $loan[0]->monthly_installment;
                $total_paid = $loan[0]->total_paid;
                $loan_emi = $loan[0]->monthly_installment;
                //check ifpaid
                $em_advance_amount = floatval($emp_loan_value[0]->advance_amount);
                $em_total_paid = floatval($emp_loan_value[0]->total_paid);

                $add_amount = $em_total_paid-$prev_loan;
                //pay_date //emp_id
                $adv_data = array('total_paid' => $add_amount);
                //
                $this->Payroll_model->updated_loan_paid_amount($adv_data,$payslip[0]->employee_id);

            }

            $this->Timesheet_model->update_all_prev_expenses_user($payslip[0]->employee_id,$payslip[0]->payment_date);
            $this->Timesheet_model->update_previous_un_paid_leaves($payslip[0]->employee_id,$payslip[0]->leave_days);
            $result = $this->Payroll_model->delete_payment_record($id);

            if(isset($id)) {
                $Return['result'] = 'Payslip deleted.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
        }
    }

    // Validate and add info in database > add hourly payment
    public function add_pay_hourly() {

        if($this->input->post('add_type')=='pay_hourly') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('payment_method')==='') {
                $Return['error'] = $this->lang->line('xin_error_makepayment_payment_method');
            } else if($this->input->post('comments')==='') {
                $Return['error'] = $this->lang->line('xin_error_makepayment_comments');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'employee_id' => $this->input->post('emp_id'),
                'department_id' => $this->input->post('department_id'),
                'company_id' => $this->input->post('company_id'),
                'location_id' => $this->input->post('location_id'),
                'designation_id' => $this->input->post('designation_id'),
                'payment_date' => $this->input->post('pay_date'),
                'payment_amount' => $this->input->post('payment_amount'),
                'total_hours_work' => $this->input->post('total_hours_work'),
                'hourly_rate' => $this->input->post('hourly_rate'),
                'is_payment' => '1',
                'payment_method' => $this->input->post('payment_method'),
                'comments' => $this->input->post('comments'),
                'status' => '1',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Payroll_model->add_hourly_payment_payslip($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_payment_paid');

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
                if($setting[0]->enable_email_notification == 'yes') {

                    // load email library
                    $this->load->library('email');
                    $this->email->set_mailtype("html");
                    //get company info
                    $cinfo = $this->Xin_model->read_company_setting_info(1);
                    //get email template
                    $template = $this->Xin_model->read_email_template(1);
                    //get employee info
                    $user_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
                    $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
                    // get date
                    $d = explode('-',$this->input->post('pay_date'));
                    $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
                    $pdate = $get_month.', '.$d[0];

                    $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;

                    $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
                    $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
                    $subdomain_name; // Print the sub domain

                    $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';

                    $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;

                    $message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';

                    $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
                    $this->email->to($user_info[0]->email);

                    $this->email->subject($subject);
                    $this->email->message($message);

                    $this->email->send();
                }

            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // add advance salary
    // Validate and add info in database
    public function add_advance_salary() {

        if($this->input->post('add_type')=='advance_salary') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = $this->lang->line('xin_error_employee_id');
            } else if($this->input->post('month_year')==='') {
                $Return['error'] = $this->lang->line('xin_error_advance_salary_month_year');
            } else if($this->input->post('amount')==='') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            // get one time value
            if($this->input->post('one_time_deduct')==1){
                $monthly_installment = 0;
            } else {
                $monthly_installment = $this->input->post('monthly_installment');
            }

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'reason' => $qt_reason,
                'month_year' => $this->input->post('month_year'),
                'advance_amount' => $this->input->post('amount'),
                'monthly_installment' => $monthly_installment,
                'total_paid' => 0,
                'one_time_deduct' => $this->input->post('one_time_deduct'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d h:i:s')
            );

            $result = $this->Payroll_model->add_advance_salary_payroll($data);

            if ($result == TRUE) {
                $Return['result'] = 'Request sent for advance salary.';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // add loan
    // Validate and add info in database
    public function add_loan() {

        if($this->input->post('add_type')=='loan') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);

            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = $this->lang->line('xin_error_employee_id');
            } else if($this->input->post('month_year')==='') {
                $Return['error'] = $this->lang->line('xin_error_advance_salary_month_year');
            } else if($this->input->post('amount')==='') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $monthly_installment = $this->input->post('monthly_installment');

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'reason' => $qt_reason,
                'month_year' => $this->input->post('month_year'),
                'advance_amount' => $this->input->post('amount'),
                'monthly_installment' => $monthly_installment,
                'total_paid' => 0,
                'one_time_deduct' => $this->input->post('one_time_deduct'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d h:i:s')
            );

            $result = $this->Payroll_model->add_loan_payroll($data);

            if ($result == TRUE) {
                $Return['result'] = 'Request sent for loan.';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // updated advance salary
    // Validate and add info in database
    public function update_advance_salary() {

        if($this->input->post('edit_type')=='advance_salary') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
            $id = $this->uri->segment(3);
            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = $this->lang->line('xin_error_employee_id');
            } else if($this->input->post('month_year')==='') {
                $Return['error'] = $this->lang->line('xin_error_advance_salary_month_year');
            } else if($this->input->post('amount')==='') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }
            // get one time value
            if($this->input->post('one_time_deduct')==1){
                $monthly_installment = 0;
            } else {
                $monthly_installment = $this->input->post('monthly_installment');
            }

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'reason' => $qt_reason,
                'month_year' => $this->input->post('month_year'),
                'monthly_installment' => $monthly_installment,
                'one_time_deduct' => $this->input->post('one_time_deduct'),
                'advance_amount' => $this->input->post('amount'),
                'status' => $this->input->post('status')
            );

            $result = $this->Payroll_model->updated_advance_salary_payroll($data,$id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_advance_salary_updated');

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
                if($setting[0]->enable_email_notification == 'yes')
                {

                    if($this->input->post('status') == 1){
                        $this->load->library('email');
                        $this->email->set_mailtype("html");

                        //get company info
                        $cinfo = $this->Xin_model->read_company_setting_info(1);
                        //get email template
                        $template = $this->Xin_model->read_email_template(18);
                        //get employee info
                        $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

                        $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;

                        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
                        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
                        $subdomain_name; // Print the sub domain

                        $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';

                        $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;

                        $message = '
    			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
    			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}"),array($cinfo[0]->company_name,site_url()),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


                        require './mail/gmail.php';
                        $mail->addAddress($user_info[0]->email, $full_name);
                        $mail->Subject = $subject;
                        $mail->msgHTML($message);

                        if (!$mail->send()) {
                            //echo "Mailer Error: " . $mail->ErrorInfo;
                        } else {
                            //echo "Message sent!";
                        }

                    } else if($this->input->post('status') == 2){ // rejected

                        $this->load->library('email');
                        $this->email->set_mailtype("html");

                        //get company info
                        $cinfo = $this->Xin_model->read_company_setting_info(1);
                        //get email template
                        $template = $this->Xin_model->read_email_template(19);
                        //get employee info
                        $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

                        $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;

                        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
                        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
                        $subdomain_name; // Print the sub domain

                        $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';

                        $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;

                        $message = '
    			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
    			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}"),array($cinfo[0]->company_name,site_url()),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


                        require './mail/gmail.php';
                        $mail->addAddress($user_info[0]->email, $full_name);
                        $mail->Subject = $subject;
                        $mail->msgHTML($message);

                        if (!$mail->send()) {
                            //echo "Mailer Error: " . $mail->ErrorInfo;
                        } else {
                            //echo "Message sent!";
                        }

                    }

                }

            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }


    // updated advance salary
    // Validate and add info in database
    public function update_loan() {

        if($this->input->post('edit_type')=='loan') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'');
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
            $id = $this->uri->segment(3);
            /* Server side PHP input validation */
            if($this->input->post('employee_id')==='') {
                $Return['error'] = $this->lang->line('xin_error_employee_id');
            } else if($this->input->post('month_year')==='') {
                $Return['error'] = $this->lang->line('xin_error_advance_salary_month_year');
            } else if($this->input->post('amount')==='') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $monthly_installment = $this->input->post('monthly_installment');

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'reason' => $qt_reason,
                'month_year' => $this->input->post('month_year'),
                'monthly_installment' => $monthly_installment,
                'one_time_deduct' => $this->input->post('one_time_deduct'),
                'advance_amount' => $this->input->post('amount'),
                'total_paid' => $this->input->post('total_paid'),
                'status' => $this->input->post('status')
            );

            $result = $this->Payroll_model->updated_loan_payroll($data,$id);

            if ($result == TRUE) {
                $Return['result'] = 'Loan Updated';

                //get setting info
                $setting = $this->Xin_model->read_setting_info(1);
                if($setting[0]->enable_email_notification == 'yes')
                {

                    if($this->input->post('status') == 1){
                        $this->load->library('email');
                        $this->email->set_mailtype("html");

                        //get company info
                        $cinfo = $this->Xin_model->read_company_setting_info(1);
                        //get email template
                        $template = $this->Xin_model->read_email_template(20);
                        //get employee info
                        $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

                        $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;

                        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
                        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
                        $subdomain_name; // Print the sub domain

                        $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';

                        $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;

                        $message = '
    			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
    			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}"),array($cinfo[0]->company_name,site_url()),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


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

                    } else if($this->input->post('status') == 2){ // rejected

                        $this->load->library('email');
                        $this->email->set_mailtype("html");

                        //get company info
                        $cinfo = $this->Xin_model->read_company_setting_info(1);
                        //get email template
                        $template = $this->Xin_model->read_email_template(21);
                        //get employee info
                        $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

                        $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;

                        $subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
                        $subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
                        $subdomain_name; // Print the sub domain

                        $accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';

                        $logo = $accounts_url.'uploads/logo/'.$cinfo[0]->logo;

                        $message = '
    			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
    			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'" width="170"><br>'.str_replace(array("{var site_name}","{var site_url}"),array($cinfo[0]->company_name,site_url()),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


                        require './mail/gmail.php';
                        $mail->addAddress($user_info[0]->email, $full_name);
                        $mail->Subject = $subject;
                        $mail->msgHTML($message);

                        if (!$mail->send()) {
                            //echo "Mailer Error: " . $mail->ErrorInfo;
                        } else {
                            //echo "Message sent!";
                        }

                    }

                }

            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }


    public function delete_advance_salary() {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result'=>'', 'error'=>'');
        $id = $this->uri->segment(3);
        $result = $this->Payroll_model->delete_advance_salary_record($id);
        if(isset($id)) {
            $Return['result'] = $this->lang->line('xin_success_advance_salary_deleted');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');;
        }
        $this->output($Return);
    }

    public function delete_loan() {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result'=>'', 'error'=>'');
        $id = $this->uri->segment(3);
        $result = $this->Payroll_model->delete_loan_record($id);
        if(isset($id)) {
            $Return['result'] = 'Loan Deleted Successfully';
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');;
        }
        $this->output($Return);
    }

    public function delete_template() {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result'=>'', 'error'=>'');
        $id = $this->uri->segment(3);
        $result = $this->Payroll_model->delete_template_record($id);
        if(isset($id)) {
            $Return['result'] = $this->lang->line('xin_success_payroll_template_deleted');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');;
        }
        $this->output($Return);
    }

    public function delete_hourly_wage() {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result'=>'', 'error'=>'');
        $id = $this->uri->segment(3);
        $result = $this->Payroll_model->delete_hourly_wage_record($id);
        if(isset($id)) {
            $Return['result'] = $this->lang->line('xin_success_hourly_wage_deleted');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');;
        }
        $this->output($Return);
    }




    public function print_loan_agreement()
    {


        $re_paid_amount = 0;
        //$this->load->library('Pdf');
        $system = $this->Xin_model->read_setting_info(1);


        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $id = $this->uri->segment(3);
        $payment = $this->Payroll_model->read_loan_info($id);
        $user = $this->Xin_model->read_user_info($payment[0]->employee_id);

        $fname = $user[0]->first_name.' '.$user[0]->last_name;


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Xin_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Xin_model->read_company_setting_info($location[0]->company_id);


        //$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $company_name = $company[0]->company_name;
        // set default header data
        $c_info_email = $company[0]->email;
        $c_info_phone = $company[0]->phone;
        $country = $this->Xin_model->read_country_info($company[0]->country);
        $c_info_address = $company[0]->address_1.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
        $c_info_address = trim($company[0]->address_1).' '.$company[0]->address_2.', '.$company[0]->city.', '.$country[0]->country_name;
        $email_phone_address = "".$this->lang->line('dashboard_email')." : $c_info_email | ".$this->lang->line('xin_phone')." : $c_info_phone \n".$this->lang->line('xin_address').": $c_info_address";
        $header_string = $email_phone_address;


        // set document information
        $pdf->SetCreator('Corbuz');
        $pdf->SetAuthor('Corbuz');
        //$pdf->SetTitle('Workable-Zone - Payslip');
        //$pdf->SetSubject('TCPDF Tutorial');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $header_string = preg_replace('/[ \t]+/', ' ', $header_string);

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $company_name, $header_string);

        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array('helvetica', '', 11.5));
        $pdf->setFooterFont(Array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);
        $pdf->SetAuthor($company_name);
        $pdf->SetTitle($company_name.' - '.$this->lang->line('xin_print_payslip'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));
        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        // -----------------------------------------------------------------------------

        $tbl = '<br><br>
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="center"><h1>EMPLOYEE LOAN AGREEMENT</h1></td>
			</tr>
			<tr>
				<td align="center"><strong>'.$this->lang->line('xin_e_details_date').':</strong> '.date("d F, Y").'</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        //-----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td>This Employee Loan Agreement and Promissory Note (the “Agreement”) is made and effective this '.date("d F, Y").',</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        //-----------------------------------------------------------------------------
        $employee_id = '';
        if(!empty($user[0]->employee_id))
        {
            $employee_id = '#'.$user[0]->employee_id;
        }

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td width="20%"><b>BETWEEN:</b></td> <td width="80%">'.$company_name.' (the Company), '.$company[0]->address_1.', '.$company[0]->city.', '.$country[0]->country_name.'<br></td>
			</tr>
			<tr>
				<td><b>AND:</b></td> <td>'.strtoupper($fname).' (the Employee '.$employee_id.'), an individual with his/her main address at: '.$user[0]->address.'</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td>WHEREAS, Employee has requested a loan from Company for personal reasons;</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        //-----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td>NOW, THEREFORE, in consideration of the mutual promises and covenants contained herein, The company and Employee agree as follows:</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        //-----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td><b><u>LOAN & PAYMENT</u></b></td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        //-----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td>On the date first written above, Company shall loan to Employee the sum of <b>'.$this->Xin_model->currency_sign($payment[0]->advance_amount).'</b> at an <b>EMI of '.$this->Xin_model->currency_sign($payment[0]->monthly_installment).'</b>, It will deduct from employee monthly salary.</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        //-----------------------------------------------------------------------------

        $tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td>WITNESS WHEROF, The company and Employee have executed this Agreement as of the date '.date("d F, Y").'.</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');


        // -----------------------------------------------------------------------------

        $tbl = '<br><br><br><br>
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td align="left">COMPANY</td>
				<td align="right">EMPLOYEE</td>
			</tr>
		</table>
		';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        // ---------------------------------------------------------

        $tbl = '<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="left">'.$this->lang->line('xin_payslip_authorised_signatory').'</td>
				<td align="right">'.strtoupper($fname).'</td>
			</tr>
		</table>
		';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->created_at)));
        //Close and output PDF document
        $pdf->Output();

    }
    public function download_payslips($id) {

        //$this->load->library('Pdf');
        $system = $this->Xin_model->read_setting_info(1);
        $re_paid_amount = 0;

        // create new PDF document
        $pdf = new Mypdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $payment = $this->Payroll_model->read_make_payment_information($id);
        $user = $this->Xin_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        if($system[0]->is_payslip_password_generate==1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if($system[0]->payslip_password_format=='dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if($system[0]->payslip_password_format=='contact_no') {
                $password_val = $user[0]->contact_no;
            } else if($system[0]->payslip_password_format=='full_name') {
                $password_val = $user[0]->first_name.$user[0]->last_name;
            } else if($system[0]->payslip_password_format=='email') {
                $password_val = $user[0]->email;
            } else if($system[0]->payslip_password_format=='password') {
                $password_val = $user[0]->password;
            } else if($system[0]->payslip_password_format=='user_password') {
                $password_val = $user[0]->username.$user[0]->password;
            } else if($system[0]->payslip_password_format=='employee_id') {
                $password_val = $user[0]->employee_id;
            } else if($system[0]->payslip_password_format=='employee_id_password') {
                $password_val = $user[0]->employee_id.$user[0]->password;
            } else if($system[0]->payslip_password_format=='dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob.$fname[0].$lname[0];
            }
            $pdf->SetProtection(array('print', 'copy','modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        $location = $this->Xin_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Xin_model->read_company_setting_info($location[0]->company_id);


        $p_method = '';
        if($payment[0]->payment_method==1){
            $p_method = 'Online';
        } else if($payment[0]->payment_method==2){
            $p_method = 'PayPal';
        } else if($payment[0]->payment_method==3) {
            $p_method = 'Payoneer';
        } else if($payment[0]->payment_method==4){
            $p_method = 'Bank Transfer';
        } else if($payment[0]->payment_method==5) {
            $p_method = 'Cheque';
        } else {
            $p_method = 'Cash';
        }

        //$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $company_name = $company[0]->company_name;
        // set default header data
        $c_info_email = $company[0]->email;
        $c_info_phone = $company[0]->phone;
        $country = $this->Xin_model->read_country_info($company[0]->country);
        $c_info_address = $company[0]->address_1??''.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
        $c_info_address = trim($company[0]->address_1).' '.$company[0]->address_2.', '.$company[0]->city.', '.$country[0]->country_name;
        $email_phone_address = "".$this->lang->line('dashboard_email')." : $c_info_email | ".$this->lang->line('xin_phone')." : $c_info_phone \n".$this->lang->line('xin_address').": $c_info_address";
        $header_string = $email_phone_address;

//        $header_string="Payslip #".$payment[0]->make_payment_id."-".date('F Y', strtotime($payment[0]->payment_date));



        // set document information
        $pdf->SetCreator('Workable-Zone');
        $pdf->SetAuthor('Workable-Zone');
        //$pdf->SetTitle('Workable-Zone - Payslip');
        //$pdf->SetSubject('TCPDF Tutorial');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $header_string = preg_replace('/[ \t]+/', ' ', $header_string);

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $company_name, $header_string);

        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array('helvetica', '', 11.5));
        $pdf->setFooterFont(Array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);
        $pdf->SetAuthor($company_name);
        $pdf->SetTitle($company_name.' - '.$this->lang->line('xin_print_payslip'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));
        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pay_date = strtolower(date("Y-m", strtotime($payment[0]->payment_date)));

        // -----------------------------------------------------------------------------

        $tbl = '
		<table style="font-family: Verdana;font-size: 10px"; cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="right"><h2>'.$this->lang->line('xin_payslip').' </h2></td>
			</tr>
			<tr>
				<td align="right"><strong>'.$this->lang->line('xin_e_details_date').':</strong> '.date("d F, Y").'</td>
			</tr>
		</table>
		';
        $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        $fname = $user[0]->first_name.' '.$user[0]->last_name;
        $tbl = '
<table border="1">
<tr><td><table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
			<tr>
				<td><b>'.$this->lang->line('xin_name').'</b></td>
				<td align="left" colspan="2">'.$fname.'</td>
				<td><b>'.$this->lang->line('dashboard_employee_id').'</b></td>
				<td align="left" colspan="2">'.$user[0]->employee_id.'</td>
			</tr>
			<tr>
				<td><b>'.$this->lang->line('left_department').'</b></td>
				<td align="left" colspan="2">'.$department[0]->department_name.'</td>
				<td><b>'.$this->lang->line('left_designation').'</b></td>
				<td align="left" colspan="2">'.$_des_name[0]->designation_name.'</td>
			</tr>
			<tr>
				<td><b>Pay Period</b></td>
				<td align="left" colspan="2">'.date("F Y", strtotime($payment[0]->payment_date)).'</td>
				<td><b>Emirates ID :</b></td>
				<td align="left" colspan="2">'.$user[0]->emirates_id.'</td>
			</tr>
			<tr>
			<td><b>Date of Joining</b></td>
			<td align="left" colspan="2">'.date('jS M Y', strtotime($user[0]->date_of_joining)).'</td>
</tr>
		
		</table></td></tr>
</table>';

        $pdf->writeHTML($tbl, true, false, true, false, '');

        $company_details = $this->Xin_model->get_employee_company($user[0]->user_id);
        $company_country = $company_details[0]->country;

        // -----------------------------------------------------------------------------

        // Allowances
        if($payment[0]->house_rent_allowance!='' || $payment[0]->house_rent_allowance!=0){
            $hra = $payment[0]->house_rent_allowance;
        } else { $hra = '0';}
        if($payment[0]->medical_allowance!='' || $payment[0]->medical_allowance!=0){
            $ma = $payment[0]->medical_allowance;
        } else { $ma = '0';}
        if($payment[0]->travelling_allowance!='' || $payment[0]->travelling_allowance!=0){
            $ta = $payment[0]->travelling_allowance;
        } else { $ta = '0';}
        if($payment[0]->telephone_allowance!='' || $payment[0]->telephone_allowance!=0){
            $da = $payment[0]->telephone_allowance;
        } else { $da = '0';}
        if($payment[0]->other_allowance!='' || $payment[0]->other_allowance!=0){
            $othera = $payment[0]->other_allowance;
        } else { $othera = '0';}
        if($payment[0]->allowance!='' || $payment[0]->allowance!=0){
            $allo = $payment[0]->allowance;
        } else { $allo = '0';}

        // Deductions
        // get advance salary
        if($payment[0]->is_advance_salary_deduct==1){
            $re_paid_amount = $payment[0]->net_salary - $payment[0]->advance_salary_amount;
            $ad_sl = '<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_advance_deducted_salary').'</td>
				<td  width="30%" align="right">'.number_format($payment[0]->advance_salary_amount,2).'</td>
			</tr>
			<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_paid_amount').'</td>
				<td width="30%"  align="right">'.number_format($payment[0]->payment_amount,2).'</td>
			</tr>
			';
        }
        else {
            $ad_sl = '<tr>
				<td align="left" width="70%">'.$this->lang->line('xin_paid_amount').'</td>
				<td width="30%" align="right">'.number_format($payment[0]->payment_amount,2).'</td>
			</tr>';
        }

//        if($payment[0]->leave_salary_deduct_amount>0){
//            $re_paid_amount = $re_paid_amount - $payment[0]->leave_salary_deduct_amount;
//            $ad_lv_sl = '<tr>
//				<td align="left" width="70%">Leave Deducted Salary ('.$payment[0]->leave_days.' Days)</td>
//				<td width="30%" align="right">'.number_format($payment[0]->leave_salary_deduct_amount,2).'</td>
//			</tr>';
//        }
//        else
//        {
        $ad_lv_sl ='';
//        }

        if($payment[0]->loan_emi>0){
            $re_paid_amount = $re_paid_amount - $payment[0]->loan_emi;
            $loan_emi = '<tr>
				<td align="left" width="70%">Loan EMI</td>
				<td width="30%" align="right">'.number_format($payment[0]->loan_emi,2).'</td>
			</tr>';
        }
        else
        {
            $loan_emi ='';
        }

        if($payment[0]->allowance>0){
            $re_paid_amount = $re_paid_amount + $payment[0]->allowance;
            $ext_alv = '<tr>
				<td align="left" width="70%">Extra Allowance</td>
				<td width="30%" align="right">'.number_format($payment[0]->allowance,2).'</td>
			</tr>';
        }
        else
        {
            $ext_alv ='';
        }

        if($payment[0]->extra_deductions>0){
            $re_paid_amount = $re_paid_amount - $payment[0]->extra_deductions;
            $ext_did = '<tr>
				<td align="left" width="70%">Extra Deductions</td>
				<td width="30%" align="right">'.number_format($payment[0]->extra_deductions,2).'</td>
			</tr>';
        }
        else
        {
            $ext_did ='';
        }
        $table_details='<table border="1" cellpadding="12" cellspacing="0" style="font-size: 10px;font-family: Verdana"><tr>
<td align="left" width="70%">Currency</td><td align="right" width="30%" >AED</td></tr>
<tr><td align="left" width="70%">Basic Salary</td><td align="right" width="30%">'.number_format($payment[0]->basic_salary,2).'</td></tr>';
        if($payment[0]->house_rent_allowance>0)
        {
            $table_details .= '<tr>
    				<td align="left" width="70%">'.$this->lang->line('xin_Payroll_house_rent_allowance').'</td>
    				<td width="30%" align="right">'.number_format($hra,2).'</td>
    			</tr>';
        }
        if($payment[0]->medical_allowance>0)
        {
            $table_details .= '<tr>
            				<td align="left" width="70%">'.$this->lang->line('xin_payroll_medical_allowance').'</td>
            				<td width="30%" align="right">'.number_format($ma,2).'</td>
            			</tr>';
        }
        if($payment[0]->travelling_allowance>0)
        {
            $table_details .= '<tr>
            				<td align="left" width="70%">'.$this->lang->line('xin_payroll_travel_allowance').'</td>
            				<td width="30%" align="right">'.number_format($ta,2).'</td>
            			</tr>';
        }
        if($payment[0]->telephone_allowance>0)
        {
            $table_details .= '<tr>
            				<td align="left" width="70%">Telephone Allowance</td>
            				<td width="30%" align="right">'.number_format($da,2).'</td>
            			</tr>';
        }
        if($payment[0]->other_allowance>0)
        {
            $table_details .= '<tr>
            				<td align="left" width="70%">Other Allowance</td>
            				<td width="30%" align="right">'.number_format($othera,2).'</td>
            			</tr>';
        }
        if($payment[0]->allowance>0)
        {
            $table_details .= '<tr>
            				<td align="left" width="70%">Extra Allowance</td>
            				<td width="30%" align="right">'.number_format($allo,2).'</td>
            			</tr>';
        }
        $table_details .= '<tr>
            				<td align="right" width="70%"><b>Gross Salary</b></td>
            				<td width="30%" align="right">'.number_format(($payment[0]->basic_salary+$payment[0]->total_allowances),2).'</td>
            			</tr>';
        $table_details .= '<tr>
            				<td align="right" width="70%"><b>Total Net Salary</b></td>
            				<td width="30%" align="right">'.number_format(($payment[0]->basic_salary+$payment[0]->total_allowances-$payment[0]->total_deductions),2).'</td>
            			</tr>';

        if($payment[0]->overtime_amount>0)
        {
            $table_details .= '<tr>
    				<td align="left" width="70%">Overtime Amount</td>
    				<td width="30%" align="right">'.number_format($payment[0]->overtime_amount,2).'</td>
    			</tr>';
        }
        if($payment[0]->employee_expenses>0)
        {
            $employee_expenses =$this->Payroll_model->get_all_expenses_by_month_user($user[0]->user_id,$pay_date);
            if($employee_expenses){
                foreach($employee_expenses as $expense){
                    $desc = htmlspecialchars_decode(stripslashes($expense->remarks));

// Limit the string to 200 characters
                    if (strlen($desc) > 100) {
                        $desc = substr($desc, 0, 100) . '...';
                    }

                    $table_details .= '<tr>
    				<td align="left" width="70%">'.$expense->type_name.'-'.$desc.' ON '.$expense->date.'</td>
    				<td width="30%" align="right">'.number_format($expense->amount,2).'</td>
    			</tr>';
                }
            }
            $table_details .= '<tr>
    				<td align="right" width="70%"><b>Total Expenses</b></td>
    				<td width="30%" align="right">'.number_format($payment[0]->employee_expenses,2).'</td>
    			</tr>';
        }

//		</table></td>
//				<td><table cellpadding="5" cellspacing="0" border="1">
//			<tr style="background-color:#ff7575;">
//				<td><strong>Salary Deduction</strong></td>
//				<td align="right"><strong>'.$this->lang->line('xin_amount').'</strong></td>
//			</tr>';
        $total_allowance = floatval($payment[0]->house_rent_allowance) + floatval($payment[0]->medical_allowance) + floatval($payment[0]->travelling_allowance) + floatval($payment[0]->other_allowance) + floatval($payment[0]->telephone_allowance);

        $total_deductions = floatval($payment[0]->loan_emi)+floatval($payment[0]->advance_salary_amount)+floatval($payment[0]->extra_deductions)+floatval($payment[0]->leave_salary_deduct_amount);


        $table_details .= $loan_emi.$ext_did.$ad_lv_sl.$ad_sl;
        if($total_deductions>0)
        {
            $table_details .= '<tr>
    				<td align="left" width="70%">Total Dedcutions</td>
    				<td width="30%" align="right">'.number_format($total_deductions,2).'</td>
    			</tr>';
        }
        if($total_allowance>0)
        {
            $table_details .= '<tr>
            				    				<td align="left" width="70%">Total Allowances</td>
<td width="30%" align="right">'.number_format($total_allowance,2).'</td>
            			</tr>';
        }


        $table_details.='<tr style="background-color: lightgrey;"><td align="right" width ="70%"><b>Net Salary</b></td><td align="right" width="30%"><b>'.$this->Xin_model->currency_sign(number_format($payment[0]->payment_amount,2)).'</b></td>
</tr>
		</table>
		';
        $pdf->writeHTML($table_details, true, false, false, false, '');



        $bank_details =$this->Payroll_model->read_bank_account_information_user($user[0]->user_id);
        if($bank_details) {
            $table_bank = '<table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
<tr height="50px;"><td></td></tr>
<tr style="background-color: lightgrey;"><td width="60%" align="left"><b>Payment</b></td><td width="40%" align="right"><b>Payment Method : ' . $p_method . '</b></td></tr>
<tr ><td></td></tr>
<tr><td width="25%"><strong>Bank</strong><br>'.$bank_details[0]->bank_name.'</td><td width="25%"><strong>Account Number</strong><br>'.$bank_details[0]->account_number.'</td><td width="25%"><strong>IBAN Number</strong><br>'.$bank_details[0]->iban.'</td><td align="right" width="25%"><strong>Amount</strong><br>'.$payment[0]->payment_amount.'</td></tr>

</table>';
            $pdf->writeHTML($table_bank, true, false, false, false, '');

        }
        $table_comments = '<table style="font-family: Verdana;font-size: 10px"; cellpadding="5" cellspacing="0" border="0" rules="none" frame="box">
<tr style="background-color: lightgrey;"><td width="60%" align="left"><b>Comments:</b></td><td width="40%" align="right"><i>' . $payment[0]->comments . '</i></td></tr>


</table>';
        $pdf->writeHTML($table_comments, true, false, false, false, '');



        // -----------------------------------------------------------------------------

        $tbl = '
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td align="center"> This is  system generated payroll slip which does not require a signature or company stamp.
</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0">
			<tr>
			<td width="30%" style="border-top: 1px solid black;"align="left">Printed on :'.date('d-m-Y g.i a').'</td>
			<td width="70%" style="border-top: 1px solid black;"></td>
</tr>
<tr><td></td></tr>
		</table>';
        $pdf->xfootertext =$tbl;

//        $pdf->SetY(-20);
////        $this->SetY(-10);
//        $this->SetX(10);
//        // Set font
//        $this->SetFont('helvetica', 'I', 8);
//
//        $pdf->writeHTML($tbl, true, false, false, false, '');

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
        //Close and output PDF document
        $pdfContent = $pdf->Output('payslip'.time() . '_'.$id.'.pdf', 'S');

// Save the PDF content to a file
        $file = 'uploads/payslips/payslip'.time() . '_'.$id.'.pdf'; // Replace with the desired file path
        file_put_contents($file, $pdfContent);
        return $file;

    }


}
