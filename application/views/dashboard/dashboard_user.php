<?php
$session = $this->session->userdata('username');
$system = $this->Xin_model->read_setting_info(1);
$user_info = $this->Xin_model->read_user_info($session['user_id']);
$role = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
// get designation
$designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);

$total_employees = $this->Employees_model->get_total_employees();
$root_account    = $this->Xin_model->get_root_account();
$package_info    = $this->Package_model->read_package_information($root_account[0]->package_id);

$root_id = $this->Xin_model->get_root_id(1);

$subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
$subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
$subdomain_name; // Print the sub domain

$account_url = base_url();
?>
<div class="main-body" xmlns="http://www.w3.org/1999/html">
    <div class="page-wrapper">
        <div class="page-body">

            <div class="row mb-3">
                <div class="top-card-buttons">
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card core-card primary g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </div>
                                <div class="t-content">
                                    <h5 class="m-t-10"><span></span><?php echo date('F');?> <?php echo $this->lang->line('dashboard_attendance');?></h5>
                                    <?php
                                    $m =  date('m');
                                    $y =  date('Y');
                                    $numDays = cal_days_in_month (CAL_GREGORIAN, $m,$y);
                                    ?>
                                    <span id="invoice" class="count"><?php echo $this->Xin_model->current_month_attendance();?>/<?php echo $numDays;?></span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card  core-card secondary g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                </div>
                                <a href="<?php echo site_url('employee/leave') ?>"><h5 class="m-t-10"><span></span>Leaves</h5></a>
                                 <span id="invoice" class="count"><?php echo $unpaid_leaves_count;?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card  core-card info g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                </div>
                                <a href="<?php echo site_url('requests/my_requests') ?>">   <h5 class="m-t-10"><span></span>My Requests</h5></a>
                               <span id="customer2" class="count"><?php echo $my_request_count;?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card  core-card danger g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="26" height="26" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
</svg>

                                </div>
                                    <h5 class="m-t-10"><span></span>Leave Balance</h5>
                                    <span id="invoice" class="expense_count count"><?php echo $leave_balance."/30";?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card  core-card danger g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="26" height="26" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
</svg>


                                </div>
                                
                                <div class="t-content">
                                    <h5 class="m-t-10"><span></span>Ticket Balance</h5>
                                    <span id="invoice" class="expense_count count"><?php echo $ticket_balance."/1";?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card  core-card warning g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V9l-7-7z"/><path d="M13 3v6h6"/></svg>


                                </div>
                                <a href="<?php echo site_url('profile#document');?>"> <h5 class="m-t-10"><span></span>Expired Documents</h5></a>
                                <span id="delivery2" class="count"><?php echo  $my_document_count;?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                        <div class="card  core-card success g-user-card">
                            <div class="card-block card-1">
                            <div class="g-card-main-ico ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="26" height="26" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
</svg>



                                </div>
                                <h5 class="m-t-10"><span></span>My Expenses</h5>
                                <span id="proforma" class="expense_count count" ><?php echo $this->Xin_model->currency_sign(number_format($my_expense_count,2));?></span>
                            </div>
                        </div>
                    </div>
                    

                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="row g-user-row">
                    <div class=" col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <div class="card g-wid-card">
                <div class="card-header">
                  <h5>My Profile</h5>
                  <div class="card-header-right"></div>
                </div>
                <div class="card-block">
                  <div class="box bg-white user-1">
                    <div class="u-img img-cover" style="background-image: url(https://emsohrm.corbuz.com/uploads/profile/background/);"></div>
                    <div class="u-content">
                      <div class="avatar box-64"> <?php
                          if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {
                              $lde_file = $account_url.'uploads/profile/'.$user_info[0]->profile_picture;
                          } else {
                              if($user_info[0]->gender=='Male') {
                                  $lde_file = $account_url.'uploads/profile/default_male.jpg';
                              } else {
                                  $lde_file = $account_url.'uploads/profile/default_female.jpg';
                              }
                          }
                          $last_login =  new DateTime($user_info[0]->last_login_date);
                          ?> <img class="b-a-radius-circle shadow-white" src="
													<?php echo $lde_file;?>" alt="">
                        <i class="status bg-success bottom right"></i>
                      </div>
                      <h5>
                        <a class="text-black" href="
														<?php echo base_url().'profile';?>"> <?php echo $user_info[0]->first_name. ' ' .$user_info[0]->last_name;?> </a>
                      </h5>
                      <p class="text-muted pb-0-5"> <?php echo $role[0]->role_name;?> </p>
                      <p class="text-muted pb-0-5">Last Login: <?php echo $this->Xin_model->set_date_format($user_info[0]->last_login_date).' '.$last_login->format('h:i a');?> </p>
                      <!--                        --> <?php //if($system[0]->enable_attendance == 'yes'){?>
                      <!--                            <div class="text-xs-center pb-0-5">-->
                      <!--                                <form name="set_clocking" id="set_clocking" method="post">-->
                      <!--                                    <input type="hidden" name="timeshseet" value="--> <?php //echo $user_info[0]->user_id;?>
                      <!--">-->
                      <!--                                    --> <?php //$attendances = $this->Timesheet_model->attendance_time_checks($user_info[0]->user_id); $dat = $attendances->result();?>
                      <!--                                    --> <?php //if($attendances->num_rows() < 1) {?>
                      <!--                                        <input type="hidden" value="clock_in" name="clock_state" id="clock_state">-->
                      <!--                                        <input type="hidden" value="" name="time_id" id="time_id">-->
                      <!--                                        <button class="form-control b-a btn btn-success text-uppercase" type="submit" id="clock_btn"><i class="fa fa-arrow-circle-right"></i> --> <?php //echo $this->lang->line('dashboard_clock_in');?>
                      <!--</button>-->
                      <!--                                    --> <?php //} else {?>
                      <!--                                        <input type="hidden" value="clock_out" name="clock_state" id="clock_state">-->
                      <!--                                        <input type="hidden" value="--> <?php //echo $dat[0]->time_attendance_id;?>
                      <!--" name="time_id" id="time_id">-->
                      <!--                                        <button class="form-control b-a btn btn-warning text-uppercase theme-btn" type="submit" id="clock_btn"><i class="fa fa-arrow-circle-left"></i> --> <?php //echo $this->lang->line('dashboard_clock_out');?>
                      <!--</button>-->
                      <!--                                    --> <?php //} ?>
                      <!--                                </form>-->
                      <!--                            </div>-->
                      <!--                        --> <?php //} ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php /*  <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <div class="card g-wid-card">
                <div class="card-header">
                  <h5>Upcoming Birthdays</h5>
                  <div class="card-header-right"></div>
                </div>
                <div class="card-block">
                  <div class="x_content" style="display: block;">
                    <div class="dashboard-widget-content">
                      <ul class="list-unstyled timeline widget"> <?php if($birthdays) {
                              foreach ($birthdays as $birthday){
                                  if($birthday->profile_picture!='' && $birthday->profile_picture!='no file') {
                                      $pic = $account_url.'uploads/profile/'.$user_info[0]->profile_picture;
                                  } else {
                                      if($user_info[0]->gender=='Male') {
                                          $pic = $account_url.'uploads/profile/default_male.jpg';
                                      } else {
                                          $pic = $account_url.'uploads/profile/default_female.jpg';
                                      }
                                  }

                                  $dateOfBirth = new DateTime($birthday->date_of_birth);
                                  $formattedDateOfBirth = $dateOfBirth->format('d M');

                                  echo '                        
													<li>
														<div class="block g-card-block">
                            <div class="g-avatar-box">
															<div class="avatar box-64">
																<img class="b-a-radius-circle shadow-white" src="'.$pic.'" alt="">
																	<i class="status bg-success bottom right"></i>
																</div>
																<div class="block_content">
																	<h2 class="title">
																		<a>'.$birthday->first_name.' '.$birthday->last_name.'</a>
																	</h2>
																	<div class="byline">
																		<span>'.$formattedDateOfBirth.'</span>
																	</div></div>
																</div>
															</div>
														</li>
';
                              }
                          }?>
                        <!--                        <li>-->
                        <!--                          <div class="block">-->
                        <!--                            <div class="avatar box-64">-->
                        <!--                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""><i class="status bg-success bottom right"></i>-->
                        <!--                            </div>-->
                        <!--                            <div class="block_content">-->
                        <!--                              <h2 class="title">-->
                        <!--                                <a>John Honai</a>-->
                        <!--                              </h2>-->
                        <!--                              <div class="byline">-->
                        <!--                                  <span>13 Dec</span>-->
                        <!--                              </div>-->
                        <!---->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                          <div class="block">-->
                        <!--                            <div class="avatar box-64">-->
                        <!--                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""><i class="status bg-success bottom right"></i>-->
                        <!--                            </div>-->
                        <!--                            <div class="block_content">-->
                        <!--                              <h2 class="title">-->
                        <!--                                <a>Bilal John Kurishingal</a>-->
                        <!--                              </h2>-->
                        <!--                              <div class="byline">-->
                        <!--                                <span>28 Feb</a>-->
                        <!--                              </div>-->
                        <!---->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </li>-->
                        <!---->
                        <!--                        <li>-->
                        <!--                          <div class="block">-->
                        <!--                            <div class="avatar box-64">-->
                        <!--                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""><i class="status bg-success bottom right"></i>-->
                        <!--                            </div>-->
                        <!--                            <div class="block_content">-->
                        <!--                              <h2 class="title">-->
                        <!--                                <a>Stephen Nedumpally </a>-->
                        <!--                              </h2>-->
                        <!--                              <div class="byline">-->
                        <!--                                <span>17 Sep</a>-->
                        <!--                              </div>-->
                        <!---->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </li>-->
                        <!---->
                        <!--                        <li>-->
                        <!--                          <div class="block">-->
                        <!--                            <div class="avatar box-64">-->
                        <!--                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""><i class="status bg-success bottom right"></i>-->
                        <!--                            </div>-->
                        <!--                            <div class="block_content">-->
                        <!--                              <h2 class="title">-->
                        <!--                                <a>Vadakkan Veettil Kochukunju</a>-->
                        <!--                              </h2>-->
                        <!--                              <div class="byline">-->
                        <!--                                <span>14 Jan</a>-->
                        <!--                              </div>-->
                        <!---->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </li>-->
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div> */?>

                        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="card box box-block bg-white g-wid-card-2" style="overflow-y: overlay;">
                                <div class="card-header"><h2><strong><?php echo $this->lang->line('dashboard_announcements');?></strong></h2></div>
                                <div class="">
                                    <div>
                                        <?php
                                        $announcement = $this->Announcement_model->get_announcements();
                                        $dId = array(); $i=1; foreach($announcement->result() as $an):
                                            if($an->department_id == $department_id) {
                                                $andate = $this->Xin_model->set_date_format($an->created_at);
                                                ?>
                                                <div class="n-item" style="margin-bottom: 0;">
                                                    <div class="media">
                                                        <div class="media-body">
                                                            <div class="n-text"><strong><?php echo $an->title;?></strong> <span class="text-muted" style="float:right;"><?php echo $andate;?> </span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            <?php 	 }
                                            // } ?>
                                            <?php $i++; endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <div class="card box box-block bg-white g-wid-card-2" style="overflow-y: overlay;">
                            <div class="card-header">
                  <h5>My Requests</h5>
                  <div class="card-header-right"></div>
                </div>
                                <div class="card-block g-wid-card-block">
                                    <div>
                                        <ul>
                                        <?php
                                          if($requests) {
    foreach ($my_requests as $request) {
        $desc = htmlspecialchars_decode(stripslashes($request->description));

// Limit the string to 200 characters
        if (strlen($desc) > 200) {
            $desc = substr($desc, 0, 50) . '...';
        }
        if($request->status==1): $status = '<span class="tag tag-warning">Pending</span>'; elseif($request->status==2): $status = '<span class="tag tag-success">Approved</span>';elseif($request->status==3): $status = '<span class="tag tag-danger">Rejected</span>';elseif($request->status==4): $status = '<span class="tag tag-primary">Issued</span>'; else:$status="--";endif;


        echo '<li class="list-group-item g-list-group"><a href="'.base_url()."requests".'" ' . $request->first_name . ' ' . $request->last_name . '<span>' . $desc . '</span></a>'.$status.'<div class="download-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#403f3f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg></div></li>';
    }
}?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>