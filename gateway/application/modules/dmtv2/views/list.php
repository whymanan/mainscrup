// filter
<div class="collapse" id="collapseExample">
  <div class="card card-body">
    <form method="post" id="filter">
      <div class="form-row">
        <div class="col-2">
          <input type="date"  id="date_from" name="date_from" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
        </div>
        <div class="col-2">
          <input type="date" id="date_to" name="date_to" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
        </div>
       <?php if (isAdmin($this->session->userdata('user_roles'))){?>
        <div class="col-2">
          <select id="searchBymember" name="searchBymeber" class="form-control form-control-sm" >
            <option value="">-- Select Member Id --</option>
            <?php foreach($member_list as $value){?>
            <option value="<?php echo $value['member_id'];?>"><?php  echo $value['member_id'];?></option>
            }
            <?php }?>
          </select>
        </div>
        <?php }?>
        <div class="col-2">
          <select id="searchByCat" name="searchByCat" class="form-control form-control-sm" >
            <option value="">-- Select Category --</option>
            <option value="reference_number">Reference Id</option>
            <option value="transection_id">TRANSECTION ID</option>
            <option value="transaction_bank_account_no">ACCOUNT NUMBER</option>
          </select>
        </div>
      
        <div class="col-2">
          <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search" >
        </div>
         <div class="col-2">
          <select id="searchBystatus" name="searchBystatus" class="form-control form-control-sm" >
            <option value="">-- Select Status --</option>
            <option value="processing">Processing</option>
            <option value="processed">Processed</option>
            <option value="other">Other</option>
          </select>
        </div>
        </br>
        </br>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <div style="float:right">
        <button id='simplefilter' class="btn btn-primary btn-xs"> <i class="fas fa-search"></i> Search</button>
        <button  id='export' class="btn btn-primary btn-xs"> <i class="fas fa-eraser"></i> Export</button>
        </div>
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
              <th scope="col">transection_bank_Acoount_Number</th>
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

<div class="modal fade" id="modal-add-beneficiary" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-" id="print" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
        <div class="modal-body" >
          <div class="container" id='print' >
              
              
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="beneficiary_submit" class="btn btn-success">Submit Beneficiary</button>
          <button onclick="window.print()">Print this page</button>

          <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function()
        {     var duid = '<?php echo $this->session->userdata("member_id") ?>';
              var Api = '<?php echo base_url('dmtv2/DmtvtwoController/'); ?>';
            $("#simplefilter").click(function()
            {
                var member=$("#searchBymember").val();
                var from=$("#date_from").val();
                var to=$("#date_to").val();
                var default_a=$("#searchByCat").val();
                var default_v=$("#searchValue").val();
                var status=$("#searchBystatus").val();
                if(member==null)
                {
                    member=0;
                }
                if(from==null)
                {
                    from=0;
                }
                if(to==null)
                {
                    to=0;
                }if(default_a==null)
                {
                    default_a=0;
                }
                if(default_v==null)
                {
                    default_v=0;
                }
                if(status==null)
                {
                    status=0;
                }
                
                var $transectionlist = $('#transectionlist');
               
                $transectionlist.dataTable().fnDestroy()
                var $table = $transectionlist.DataTable({
                        "searching": false,
                        "processing": true,
                        "serverSide": true,
                        "deferRender": true,
                        "language": {
                          "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                          "emptyTable": "No distributors data available ...",
                         },
                      "order": [],
                   "ajax": {
                      url: Api + "get_history?key=" + duid +"&member="+member+"&from="+from+"&to="+to+"&default_a="+default_a+"&default_v="+default_v+"&status="+status+"&list=all",
                     type: "GET",
                     },

                    "pageLength": 10
                 })
                
            });
            $('#export').click(function()
            {
             var member=$("#searchBymember").val();
                var from=$("#date_from").val();
                var to=$("#date_to").val();
                var default_a=$("#searchByCat").val();
                var default_v=$("#searchValue").val();
                var status=$("#searchBystatus").val();
                if(member==null)
                {
                    member=0;
                }
                if(from==null)
                {
                    from=0;
                }
                if(to==null)
                {
                    to=0;
                }if(default_a==null)
                {
                    default_a=0;
                }
                if(default_v==null)
                {
                    default_v=0;
                }
                if(status==null)
                {
                    status=0;
                }
             $.ajax({
                url: Api +"export?key=" + duid+"&member="+member+"&from="+from+"&to="+to+"&default_a="+default_a+"&default_v="+default_v+"&status="+status+"&list=all",
                type: "GET", //send it through get method
               success: function(response) {
               //Do Something
              window.location=response;
                //  console.log(response);
                  },
              error: function(xhr) {
              //Do Something to handle error
            }
              });
         })
            
            
        })
  function status(id)
  {
               $.ajax({
                        type: "GET",
                        url: "<?php echo base_url('/dmtv2/DmtvtwoController/status/')?>" + id,
                        // dataType: "json",
                        success: function(response) {
                               var $transectionlist = $('#transectionlist');
                               var duid = '<?php echo $this->session->userdata("member_id") ?>';
                               var Api = '<?php echo base_url('dmtv2/DmtvtwoController/'); ?>';
                              var table = $transectionlist.DataTable();
                                 table.destroy();
                               var $table = $transectionlist.DataTable({
                                  "searching": false,
                                  "processing": true,
                                  "serverSide": true,
                                  "deferRender": true,
                                  "language": {
                                        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                                       "emptyTable": "No distributors data available ...",
                                    },
                                  "order": [],
                                  "ajax": {
                                      url: Api + "get_history?key=" + duid + "&list=all",
                                      type: "GET",
                                    },
                                 "pageLength": 10
                               });
                        }
                    })    
  }
  function Print(id) {

    var sureDel = confirm("Are you sure want to Print Reciept");
    var $dmtTransactionPanel = $('#print');
        if (sureDel == true) {
            
                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url('/dmtv2/DmtvtwoController/print/')?>" + id,
                        // dataType: "json",
                        success: function(response) {
                            console.log(response)
                            $('#modal-add-beneficiary').modal('show'); 
                            $dmtTransactionPanel.html(response);
                            
                        }
                    })    
            
        }
          

  }     
  
</script>
    
    
