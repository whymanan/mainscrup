// filter
<div class="collapse" id="collapseExample">
  <div class="card card-body">
    <form method="post" id="filter">
      <div class="form-row">
        <div class="col-2">
          <input type="date"  id="date_from" name="date_from" class="form-control form-control-sm" value="<?php echo date('Y-04-01') ?>">
        </div>
        <div class="col-2">
          <input type="date" id="date_to" name="date_to" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
        </div>
        <div class="col-2">
          <select id="searchByCat" name="searchByCat" class="form-control form-control-sm" required>
            <option value="">-- Select Category --</option>
            <option value="member_id">MEMBER ID</option>
            <option value="transection_id">TRANSECTION ID</option>
            <option value="transection_mobile">PHONE</option>
          </select>
        </div>

        <div class="col-2">
          <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search" required>
        </div>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <button id='simplefilter' class="btn btn-primary btn-xs"> <i class="fas fa-search"></i> Search</button>
        <button id='clear' class="btn btn-primary btn-xs"> <i class="fas fa-eraser"></i> Clear</button>
      </div>
    </form>
  </div>
</div>


<div class="row" ng-controller="squad">
  <div class="col-xl-4 col-md-6">
    <div class="card card-stats border-0 node" data-info="all">
      <!-- Card body -->
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase mb-0">Total Transection</h5>
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
  <div class="col-xl-4 col-md-6">
    <div class="card card-stats border-0 node" data-info="active">
      <!-- Card body -->
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Pending Transection</h5>
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
  <div class="col-xl-4 col-md-6">
    <div class="card card-stats border-0 node" data-info="pending">
      <!-- Card body -->
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Failed Transection</h5>
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
            <h3 class="mb-0" id="tabs"> Users</h3>
          </div>
          <div class="col text-right">
            <a href="#!" id="notify" class="btn btn-sm btn-primary">See all</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="transectionlist">
          <thead class="thead-light">
            <tr>
              
                  <th scope="col">#</th>
              <th scope="col">MEMBER ID</th>
              <th scope="col">transection_id</th>
              <th scope="col">transection_msg</th>
              <th scope="col">transection_mobile</th>
              <th scope="col">transection_amount</th>
              <th scope="col">transection_bank_code</th>
              <th scope="col">transection_bank_ifsc</th>
              <th scope="col">reference_number</th>
              <th scope="col">created</th>
              
              
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
