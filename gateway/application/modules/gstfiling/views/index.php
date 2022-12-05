


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
  
<?php if($_SESSION['user_roles'] == 94 ){?>
  
  <div class="col-xl-4 col-md-6">
    <div class="card card-stats border-0 node" data-info="pending">
      <!-- Card body -->
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Admin Balance</h5>
            <span class="h2 font-weight-bold mb-0"><?php echo $bal ?></span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
              <i class="ni ni-money-coins"></i>
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
            <h5 class="card-title text-uppercase text-muted mb-0">Total Balance </h5>
            <span class="h2 font-weight-bold mb-0"><?php echo $total[0]['bal'] ?></span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
              <i class="ni ni-money-coins"></i>
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
            <h5 class="card-title text-uppercase text-muted mb-0">Requested Balance </h5>
            <span class="h2 font-weight-bold mb-0"><?php echo $remain->amount ?></span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
              <i class="ni ni-money-coins"></i>
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
  
</div><?php } ?>
<div class="row">
  <div class="col-xl-12">
    <!--<div class="card">-->
    <!--  <div class="card-header border-0">-->
    <!--    <div class="row align-items-center">-->
    <!--      <div class="col">-->
    <!--        <h3 class="mb-0" id="tabs"> Users</h3>-->
    <!--      </div>-->
    <!--      <div class="col text-right">-->
    <!--        <a href="#!" id="notify" class="btn btn-sm btn-primary">See all</a>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>  -->
      
        <!-- Projects table -->
                    <div class="row">
        <div class="col-xl-6">
          <!--* Card header *-->
          <!--* Card body *-->
          <!--* Card init *-->
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Surtitle -->
              <h6 class="surtitle">Partners</h6>
              <!-- Title -->
              <h5 class="h3 mb-0">Affiliate traffic</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
              <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <!-- Chart wrapper -->
                <canvas id="chart-pie" class="chart-canvas chartjs-render-monitor" width="457" height="350" style="display: block; width: 457px; height: 350px;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6">
          <!--* Card header *-->
          <!--* Card body *-->
          <!--* Card init *-->
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Surtitle -->
              <h6 class="surtitle">Overview</h6>
              <!-- Title -->
              <h5 class="h3 mb-0">Product comparison</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
              <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <!-- Chart wrapper -->
                <canvas id="chart-bar-stacked" class="chart-canvas chartjs-render-monitor" style="display: block; width: 457px; height: 350px;" width="457" height="350"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    
     
    </div>
  </div>
</div>
