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

class Liability extends MY_Controller
{

    public function __construct()
    {
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
    public function output($Return = array())
    {
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

        $data['title'] = $this->Xin_model->site_title();
        $data['all_employees'] = $this->Xin_model->all_employees();
        $data['all_exit_types'] = $this->Employee_exit_model->all_exit_types();
        $data['breadcrumbs'] = 'Liability Report';
        $data['path_url'] = 'liability';
        $session = $this->session->userdata('username');
        $role_resources_ids = $this->Xin_model->user_role_resource();
        if (in_array('110', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("exit/liability_report", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
            } else {
                redirect('');
            }
        } else {
            redirect('dashboard/');
        }
    }
    public function get_liability_report()


    {

        $min_date = $this->input->get('min_date')??date('Y-01-01');
        $max_date = $this->input->get('max_date')??date('Y-12-31');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $employee = $this->Employees_model->get_employees();

        $data = array();
        $p_date=date('Y-m');
        $total_sal = 0;
        $total_loan =0;
        $grand_total=0;
        $total_advance=0;
        $total_exp=0;
        $total_grat=0;

        foreach($employee->result() as $r) {

            $minDate = date_create_from_format('Y-m-d', $min_date);
            $maxDate = date_create_from_format('Y-m-d', $max_date);

            // Generate a list of months between those dates
            $months = array();
            $currentDate = clone $minDate;
            while ($currentDate <= $maxDate) {
                $months[] = $currentDate->format('Y-m');
                $currentDate->modify('+1 month');
            }
            $salary = $this->Payroll_model->read_salary_information_by_date($r->user_id, $p_date);

            $loan = $this->Payroll_model->loan_by_employee_id($r->user_id);
            if ($loan) {
                $em_advance_amount = floatval($loan[0]->advance_amount);
                $em_total_paid = floatval($loan[0]->total_paid);

                if ($em_advance_amount > $em_total_paid) {
                    //
                    $re_amount = $em_advance_amount - $em_total_paid;
                } else {
                    $re_amount = 0;
                }
            }
            else{
                $re_amount=0;
            }
            $total_loan+=$re_amount;
            $grand_total-=$re_amount;
            $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($r->user_id);
            $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($r->user_id);

            if(!is_null($advance_salary)){
                $monthly_installment = $advance_salary[0]->monthly_installment;
                //check ifpaid
                $em_advance_amount = floatval($emp_value[0]->advance_amount);
                $em_total_paid = floatval($emp_value[0]->total_paid);
                if($em_advance_amount > $em_total_paid) {
                    $re_advance_amount = $em_advance_amount - $em_total_paid;
                }else{
                    $re_advance_amount =0;
                }
            }
            else {
                $re_advance_amount =0;
            }
            $total_advance+=$re_advance_amount;
            $grand_total-=$re_advance_amount;
            $unpaid_off_per_month =0;
            $deduct_leave_sal = 0;
            $expenses=0;
            foreach ($months as $p_date) {
                $no_of_days =$this->Xin_model->get_number_of_days($p_date);

                $expenses +=$this->Payroll_model->get_expenses_by_month_user($r->user_id,$p_date);

                $all_leave_types = $this->Timesheet_model->all_leave_types();
                $unpaid_leaves = 0;
                $unpaid_off=0;
                $leaves_per_yeartype=array();
                foreach($all_leave_types as $type) {
                    $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$r->user_id,date('Y',strtotime($p_date)));
//                        echo $this->db->last_query();
//                        die;
                    if(($count_l>$type->days_per_year)&&($type->type_name!="Unpaid Leave"))
                    {
                        $unpaid_leaves = $unpaid_leaves+($count_l-$type->days_per_year);
                    }else if($type->type_name=="Unpaid Leave"){
                        $unpaid_leaves+=$count_l;
                        $unpaid_off+=$count_l;

                    }
                   else{
                        $unpaid_leaves += 0;
                        $unpaid_off+=$count_l;
                        $count_l=0;

                    }
                    $leaves_per_yeartype[$type->leave_type_id]=$count_l;
                }
                $total_exp+=$expenses;
                $grand_total+=$expenses;

                foreach ($all_leave_types as $type) {
//
                    $count_per_month = $this->Timesheet_model->get_leave_days_month($r->user_id, $p_date, $type->leave_type_id);
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
                if($salary)
                    $per_day_sal = ($salary[0]->basic_salary + $salary[0]->total_allowance) / $no_of_days;
                else
                    $per_day_sal=0;

                if ($unpaid_off_per_month > 0) {
                    $unpaid_off_per_month > $no_of_days ? $unpaid_off_per_month = $no_of_days : $unpaid_off_per_month = $unpaid_off_per_month;
                    $deduct_leave_sal += round($per_day_sal * $unpaid_off_per_month);

                }

            }
            $payments = $this->Xin_model->getPaymentsByMonths($months,$r->user_id);
            $pending_sal_months=count($months)-$payments;
            if($salary) {
                $net_sal = $pending_sal_months * (floatval($salary[0]->basic_salary) + floatval($salary[0]->total_allowance));
            }
            else{
                $net_sal=0;
            }
            $net_sal=$net_sal-$deduct_leave_sal;
            $total_sal+=$net_sal;
            $grand_total+=$net_sal;

            $gratuity=$this->check_gratuity_balance($r->user_id,$max_date);
            $total_grat+=$gratuity;
            $grand_total+=$gratuity;
            $data[] = array(
                $r->employee_id,
                $r->first_name." ".$r->last_name,
                number_format($net_sal,2),
                number_format($re_amount,2),
                number_format($re_advance_amount,2),
                number_format($expenses,2),
                number_format($gratuity,2),
                number_format($gratuity+$net_sal+$expenses-$re_advance_amount-$re_amount),
            );
        }
        $total_data[]=array("",
            "Total Liabilities and Assets",
            number_format($total_sal),
            number_format($total_loan),
            number_format($total_advance),
            number_format($total_exp),
            number_format($total_grat),
            number_format($grand_total),
        );
        $mergeddata = array_merge($total_data, $data);
//        var_dump($mergeddata);
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows()+1,
            "recordsFiltered" => $employee->num_rows()+1,
            "data" => $mergeddata
        );
        echo json_encode($output);
        exit();

    }
    public function check_gratuity_balance($employee_id,$date){

        $user_info = $this->Xin_model->read_user_info($employee_id);
        $salary_data = $this->Payroll_model->read_salary_information($employee_id);
        $previous_encashments =$this->Payroll_model->get_previous_encashments($employee_id);
        if($salary_data&&$user_info[0]->gratuity_eligibilty) {
            $salary = $salary_data[0]->basic_salary;

            $doj = $user_info[0]->date_of_joining;
            $current_date = new DateTime($date);
            $date_of_joining = new DateTime($doj);

            $diff = $date_of_joining->diff($current_date);

            $yearsOfService = $diff->y + ($diff->m / 12) + ($diff->d / 365);
            $unpaid_leaves =$this->Timesheet_model->count_all_unpaid_leaves($employee_id);

            if($unpaid_leaves)
                $yearsOfService = $yearsOfService - ($unpaid_leaves / 365); // add unpaid leaves to years of service

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
            return round(floatval($final_gratuity),2);

            // Return gratuity amount
        }else{
            return 0.00;
        }


    }


}