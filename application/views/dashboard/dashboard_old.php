<div class="main-body">
  <div class="page-wrapper">
    <div class="page-body">

      <div class="row mb-3">
        <div class="top-card-buttons">
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card core-card primary">
              <div class="card-block card-1">
                <i class="fa fa-users card-large-icon"></i>
                <h5 class="m-t-10"><span class="text-c-blue"></span>Employees</h5>
                <span id="quote" class="count">23 </span>
                <a class="btn btn-inverse-dark" href="<?php echo site_url('employees') ?>"><i class="fa fa-plus icon"></i></a>
                <a id="qoutation_btn" class="btn btn-inverse-dark"><i class="fa fa-list icon"></i></a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card  core-card secondary">
              <div class="card-block card-1">
                <i class="fa fa-building card-large-icon"></i>
                <h5 class="m-t-10"><span></span>Departments</h5>
                <span id="invoice" class="count">32</span>
                <a class="btn btn-inverse-dark" href="<?php echo site_url('') ?>"><i class="fa fa-plus icon"></i></a>
                <a class="btn btn-inverse-dark" id="invoice_btn" href="#"><i class="fa fa-list icon"></i> </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card  core-card info">
              <div class="card-block card-1">
                <i class="fa fa-paper-plane card-large-icon"></i>
                <h5 class="m-t-10"><span></span>Employee Requests</h5>
                <span id="customer2" class="count">10</span>
                <a class="btn btn-inverse-dark" href="<?php echo site_url('') ?>"><i class="fa fa-plus icon"></i></a>
                <a class="btn btn-inverse-dark" id="customer_btn"><i class="fa fa-list icon"></i></a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card  core-card danger">
              <div class="card-block card-1">
                <i class="fa fa-money card-large-icon"></i>
                <h5 class="m-t-10"><span></span>Total Expenses</h5>
                <span id="proforma" class="count">14</span>
                <a class="btn btn-inverse-dark" href="<?php echo site_url('') ?>"><i class="fa fa-plus icon"></i></a>
                <a class="btn btn-inverse-dark" id="proformainvoice_btn"><i class="fa fa-list icon"></i> </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card  core-card success">
              <div class="card-block card-1">
                <i class="fa fa-laptop card-large-icon"></i>
                <h5 class="m-t-10"><span></span>Total Assets</h5>
                <span id="purchase" class="count">56</span>
                <a class="btn btn-inverse-dark" href="<?php echo site_url('purchase_order/create_purchase_order') ?>"><i class="fa fa-plus icon"></i></a>
                <a class="btn btn-inverse-dark" id="purchase_btn"><i class="fa fa-list icon"></i></a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
            <div class="card  core-card warning">
              <div class="card-block card-1">
                <i class="fa fa-file-o card-large-icon"></i>
                <h5 class="m-t-10"><span></span>Expired Documents</h5>
                <span id="delivery2" class="count">86</span>
                <a class="btn btn-inverse-dark" href="<?php echo site_url('delivery_note/create_delivery_note') ?>"><i class="fa fa-plus icon"></i></a>
                <a class="btn btn-inverse-dark" id="delivery_btn"><i class="fa fa-list icon"></i></a>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5>My Profile</h5>
                  <div class="card-header-right"></div>
                </div>
                <div class="card-block">
                  <div class="box bg-white user-1">
                    <div class="u-img img-cover" style="background-image: url(https://emsohrm.corbuz.com/uploads/profile/background/);"></div>
                    <div class="u-content">
                      <div class="avatar box-64">
                        <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""> <i class="status bg-success bottom right"></i>
                      </div>
                      <h5><a class="text-black" href="https://emsohrm.corbuz.com/profile"> Admin User</a></h5>
                      <p class="text-muted pb-0-5">Administrator</p>
                      <p class="text-muted pb-0-5">Last Login: 13-May-2023 10:18 am</p>
                      <div class="text-xs-center pb-0-5">
                        <form name="set_clocking" id="set_clocking" method="post">
                          <input type="hidden" name="timeshseet" value="1017">
                          <input type="hidden" value="clock_in" name="clock_state" id="clock_state">
                          <input type="hidden" value="" name="time_id" id="time_id">
                          <button class="form-control b-a btn btn-success text-uppercase theme-btn" type="submit" id="clock_btn"><i class="fa fa-arrow-circle-right"></i> Clock IN</button>
                        </form>
                      </div>
                    </div>
                    <div class="u-counters">
                      <div class="row no-gutter">
                        <div class="col-xs-12 uc-item"> <a class="text-black" href="javascript:void(0);"> My Office Shift: 10:00 am to 03:00 pm</a> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5>Upcoming Birthdays</h5>
                  <div class="card-header-right"></div>
                </div>
                <div class="card-block">
                  <div class="x_content" style="display: block;">
                    <div class="dashboard-widget-content">
                      <ul class="list-unstyled timeline widget">
                        <li>
                          <div class="block">
                            <div class="avatar box-64">
                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""> <i class="status bg-success bottom right"></i>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a>John Honai</a>
                              </h2>
                              <div class="byline">
                                <span>13 Dec</a>
                              </div>

                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="avatar box-64">
                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""> <i class="status bg-success bottom right"></i>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a>Bilal John Kurishingal</a>
                              </h2>
                              <div class="byline">
                                <span>28 Feb</a>
                              </div>

                            </div>
                          </div>
                        </li>

                        <li>
                          <div class="block">
                            <div class="avatar box-64">
                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""> <i class="status bg-success bottom right"></i>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a>Stephen Nedumpally </a>
                              </h2>
                              <div class="byline">
                                <span>17 Sep</a>
                              </div>

                            </div>
                          </div>
                        </li>

                        <li>
                          <div class="block">
                            <div class="avatar box-64">
                              <img class="b-a-radius-circle shadow-white" src="https://emsohrm.corbuz.com/uploads/profile/default_male.jpg" alt=""> <i class="status bg-success bottom right"></i>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a>Vadakkan Veettil Kochukunju</a>
                              </h2>
                              <div class="byline">
                                <span>14 Jan</a>
                              </div>

                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card">
                <div class="card-header">
                  <h5>My Requests</h5>
                  <div class="card-header-right"></div>
                </div>
                <div class="card-block">
                  <div id="m-product" style="min-width: 310px; height: 426px; margin: 0 auto">
                    <ul class="list-group">
                      <li class="list-group-item">Product-1 <span>AED1400</span></li>
                      <li class="list-group-item">Product-2 <span>AED800</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>