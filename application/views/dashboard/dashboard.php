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
?> <div class="main-body">
  <div class="page-wrapper">
    <div class="page-body">
      <div class="row mb-3">
        <div class="top-card-buttons">
          <!-- First Widgets -->
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary g-sm-card">
              <div class="card-block card-1 g-card-2">
                <div class="row"></div>
                <div class="g-card-main-ico ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                </div>
                <h5 class="m-t-10">
                  <span class="text-c-blue"></span>Employees
                </h5>
                <span id="quote" class="count"> <?php echo $employee_count;?> </span>
                <div class="g-sec-icons">
                  <a class="btn btn-inverse-dark" href="
										<?php echo site_url('employees') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </a>
                  <a id="qoutation_btn" class="btn btn-inverse-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="8" y1="6" x2="21" y2="6"></line>
                      <line x1="8" y1="12" x2="21" y2="12"></line>
                      <line x1="8" y1="18" x2="21" y2="18"></line>
                      <line x1="3" y1="6" x2="3.01" y2="6"></line>
                      <line x1="3" y1="12" x2="3.01" y2="12"></line>
                      <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <!-- 2nd Widgets -->
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary g-sm-card">
              <div class="card-block card-1 g-card-2">
                <div class="row"></div>
                <div class="g-card-main-ico ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h18v18H3zM21 9H3M21 15H3M12 3v18" />
                  </svg>
                </div>
                <h5 class="m-t-10">
                  <span></span>Departments
                </h5>
                <span id="invoice" class="count"> <?php echo $department_count;?> </span>
                <div class="g-sec-icons">
                  <a class="btn btn-inverse-dark" href="
										<?php echo site_url('departments') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </a>
                  <a id="qoutation_btn" class="btn btn-inverse-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="8" y1="6" x2="21" y2="6"></line>
                      <line x1="8" y1="12" x2="21" y2="12"></line>
                      <line x1="8" y1="18" x2="21" y2="18"></line>
                      <line x1="3" y1="6" x2="3.01" y2="6"></line>
                      <line x1="3" y1="12" x2="3.01" y2="12"></line>
                      <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <!-- 3rd Widgets -->
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary g-sm-card">
              <div class="card-block card-1 g-card-2">
                <div class="row"></div>
                <div class="g-card-main-ico ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                  </svg>
                </div>
                <h5 class="m-t-10">
                  <span></span>Employee Requests
                </h5>
                <span id="customer2" class="count"><?php echo $request_count;?></span>
                <div class="g-sec-icons">
                  <a class="btn btn-inverse-dark" href="
										<?php echo site_url('departments') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </a>
                  <a id="qoutation_btn" class="btn btn-inverse-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="8" y1="6" x2="21" y2="6"></line>
                      <line x1="8" y1="12" x2="21" y2="12"></line>
                      <line x1="8" y1="18" x2="21" y2="18"></line>
                      <line x1="3" y1="6" x2="3.01" y2="6"></line>
                      <line x1="3" y1="12" x2="3.01" y2="12"></line>
                      <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <!-- 4th Widgets -->
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary g-sm-card">
              <div class="card-block card-1 g-card-2">
                <div class="row"></div>
                <div class="g-card-main-ico ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                  </svg>
                </div>
                <h5 class="m-t-10">
                  <span></span>Total Expenses
                </h5>
                <span id="proforma" class="count expense_count"><?php echo $this->Xin_model->currency_sign(number_format($expense_count,2));?></span>
                <div class="g-sec-icons">
                  <a class="btn btn-inverse-dark" href="
										<?php echo site_url('expense') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </a>
                  <a id="qoutation_btn" class="btn btn-inverse-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="8" y1="6" x2="21" y2="6"></line>
                      <line x1="8" y1="12" x2="21" y2="12"></line>
                      <line x1="8" y1="18" x2="21" y2="18"></line>
                      <line x1="3" y1="6" x2="3.01" y2="6"></line>
                      <line x1="3" y1="12" x2="3.01" y2="12"></line>
                      <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <!-- 5th Widgets -->
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary g-sm-card">
              <div class="card-block card-1 g-card-2">
                <div class="row"></div>
                <div class="g-card-main-ico ">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 9v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9" />
                    <path d="M9 22V12h6v10M2 10.6L12 2l10 8.6" />
                  </svg>
                </div>
                <h5 class="m-t-10">
                  <span></span>Total Assets
                </h5>
                <span id="purchase" class="count"><?php echo $asset_count;?></span>
                <div class="g-sec-icons">
                  <a class="btn btn-inverse-dark" href="
										<?php echo site_url('assets') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </a>
                  <a id="qoutation_btn" class="btn btn-inverse-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="8" y1="6" x2="21" y2="6"></line>
                      <line x1="8" y1="12" x2="21" y2="12"></line>
                      <line x1="8" y1="18" x2="21" y2="18"></line>
                      <line x1="3" y1="6" x2="3.01" y2="6"></line>
                      <line x1="3" y1="12" x2="3.01" y2="12"></line>
                      <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <!-- 6th Widgets -->
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary g-sm-card">
              <div class="card-block card-1 g-card-2">
                <div class="row"></div>
                <div class="g-card-main-ico ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V9l-7-7z"/><path d="M13 3v6h6"/></svg>
                </div>
                <h5 class="m-t-10">
                  <span></span>Expired Documents
                </h5>
                <span id="delivery2" class="count"><?php echo  $employee_count;?></span>
                <div class="g-sec-icons">
                  <a class="btn btn-inverse-dark" href="
										<?php echo site_url('employees') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </a>
                  <a id="qoutation_btn" class="btn btn-inverse-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CA7FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="8" y1="6" x2="21" y2="6"></line>
                      <line x1="8" y1="12" x2="21" y2="12"></line>
                      <line x1="8" y1="18" x2="21" y2="18"></line>
                      <line x1="3" y1="6" x2="3.01" y2="6"></line>
                      <line x1="3" y1="12" x2="3.01" y2="12"></line>
                      <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!--  cols-->
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-4">
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
            </div>
            <div class="col-lg-4">
              <div class="card g-wid-card">
                <div class="card-header">
                  <h5>All Requests</h5>
                  <div class="card-header-right"></div>
                </div>
                <div class="card-block">
                  <div id="m-product" style="min-width: 310px;  margin: 0 auto">
                    <ul class="list-group"> <?php if($requests) {
    foreach ($requests as $request) {
        $desc = htmlspecialchars_decode(stripslashes($request->description));

// Limit the string to 200 characters
        if (strlen($desc) > 200) {
            $desc = substr($desc, 0, 50) . '...';
        }
        if($request->status==1): $status = '
													<span class="tag tag-warning">Pending</span>'; elseif($request->status==2): $status = '
													<span class="tag tag-success">Approved</span>';elseif($request->status==3): $status = '
													<span class="tag tag-danger">Rejected</span>';elseif($request->status==4): $status = '
													<span class="tag tag-primary">Issued</span>'; else:$status="--";endif;

        echo '
													<li class="list-group-item g-list-group">
														<a href="'.base_url()."requests".'" ' . $request->first_name . ' ' . $request->last_name . '
															<span>' . $desc . '</span>
														</a>'.$status.'
                            <div class="download-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#403f3f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg></div>
													</li>';
    }
}?> </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>