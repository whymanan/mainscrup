 <div class="row"  ng-controller="squad">
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats border-0 node" data-info="new">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase mb-0">New Join User</h5>
              <span class="h2 font-weight-bold mb-0">{{new}}</span>
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
      <div class="card card-stats node border-0" data-info="reject">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Rejected KYC</h5>
              <span class="h2 font-weight-bold mb-0">{{reject}}</span>
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
      <div class="card card-stats border-0 node" data-info="verify">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Verify KYC</h5>
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
             <h3 class="mb-0" id="tabs"> All New Users</h3>
           </div>
         <div class="col text-right">
            <a class="pull-right btn btn-primary btn-xs" href="<?php echo base_url('kyc/createxls')?>"><i class="fa fa-file-excel-o"></i> Export Data</a>
             <a class="pull-right btn btn-primary btn-xs" href="<?php echo base_url('kyc/createzip')?>"><i class="fa fa-file-excel-o"></i> Export Images</a>
           </div>
         </div>
       </div>
       <div class="table-responsive">
         <!-- Projects table -->
         <table class="table align-items-center table-flush" id="squadlist">
           <thead class="thead-light">
             <tr>
               <th scope="col">#</th>
               <th scope="col">CUSTOMER ID</th>
               <th scope="col">PARENT</th>
               <th scope="col">NAME</th>
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
