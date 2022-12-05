// filter 
<div class="collapse" id="collapseExample">
  <div class="card card-body">
  <form  method="post" id="filter">
                                        <div class="form-row">
                                            <div class="col-2">
                                                <input type="date" name="date_from" class="form-control form-control-sm" value="<?php echo date('Y-04-01') ?>">
                                            </div>
                                            <div class="col-2">
                                                <input type="date" name="date_to" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
                                            </div>
                                            <div class="col-2">
                                                <select id="searchByCat" name="searchByCat" class="form-control form-control-sm" required>
                                                    <option value="">-- Select Category --</option>
                                                    <option value="member_id">MEMBER ID</option>
                                                    <option value="parent">PARENT</option>
                                                    <option value="email">EMAIL</option>
                                                    <option value="phone">PHONE</option>
                                                    <option value="roles_id">User Type</option>
                                                </select>
                                            </div>
              
                                            <div class="col-2">
                                                <input type="text" name="searchValue" class="form-control form-control-sm" value="" placeholder="Search" required>
                                            </div>
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                            <button id='simplefilter'    class="btn btn-primary btn-xs"> <i class="fas fa-search"></i> Search</button>
                                            <button id='clear'    class="btn btn-primary btn-xs"> <i class="fas fa-eraser"></i> Clear</button>
                                        </div>
                                    </form>
  </div>
</div>


<div class="row"  ng-controller="squad">
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats border-0 node" data-info="all">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase mb-0">Total Users</h5>
              <span class="h2 font-weight-bold mb-0">{{all}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                <i class="ni ni-single-02"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
            <span class="text-nowrap">Since last month</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats node border-0" data-info="new">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">New users</h5>
              <span class="h2 font-weight-bold mb-0">{{new}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                <i class="ni ni-circle-08"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
            <span class="text-nowrap">Since last month</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats border-0 node" data-info="active">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Active Users</h5>
              <span class="h2 font-weight-bold mb-0">{{active}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                <i class="ni ni-user-run"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
            <span class="text-nowrap">Since last month</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats border-0 node" data-info="pending">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Pending</h5>
              <span class="h2 font-weight-bold mb-0">{{pending}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                <i class="ni ni-chart-bar-32"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
            <span class="text-nowrap">Since last month</span>
          </p>
        </div>
      </div>
    </div>
  </div>
<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-header border-0">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="mb-0">New Join</h3>
          </div>
          <div class="col text-right">
            <a href="#!" id="notify" class="btn btn-sm btn-primary">See all</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="squadlist">
          <thead class="thead-light">
            <tr>
              <th scope="col">#</th>
              <th scope="col">MEMBER ID</th>
              <th scope="col">PARENT</th>
              <th scope="col">EMAIL</th>
              <th scope="col">PHONE</th>
              <th scope="col">User Type</th>
              <th scope="col">STATUS</th>
              <th scope="col">KYC</th>
              <th scope="col">Join Date</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
