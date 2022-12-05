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
            <option value="mode">MODE</option>
            <option value="refrence">REFRENCE ID</option>
            <option value="stock_type">STOCK TYPE</option>
          </select>
        </div>
      
        <div class="col-2">
          <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search" >
        </div>
         <div class="col-2">
          <select id="searchBystatus" name="searchBystatus" class="form-control form-control-sm" >
            <option value="">-- Select Status --</option>
            <option value="debit">Debit</option>
            <option value="credit">Credit</option>
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
<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Wallet Requests</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                    <h4 class="heading-small text-muted mb-4">Wallet Information</h4>
                    <div class="pl-lg-4">
                        
                        
                    <div class="table-responsive">  

                      <table class="table table-striped" id="request">
                        <!--<table class=" align-items-center  table-responsive" id="request">-->
                              <thead class="thead-light">
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">MEMBER ID</th>
                                  <th scope="col">Amount</th>
                                  <th scope="col">Status</th>
                                  <th scope="col">Mode</th>
                                  <th scope="col">Refrence</th>
                                  <th scope="col">Stock Type</th>
                                  <th scope="col">Open balance</th>
                                  <th scope="col">Close balance</th>
                                  <th scope="col">Commission</th>
                                  <th scope="col">Sub Charge</th>
                                  <th scope="col">Bank</th>
                                  <th scope="col">Narration</th>
                                 <th scope="col">Type</th>
                                  <th scope="col">Date</th>
                                </tr>
                              </thead>
                        </table>


                        </div>
              
                    </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script type="text/javascript">
 $(document).ready(function() {
 var $request = $('#request');
 var duid = '<?php echo $this->session->userdata("user_id") ?>';
 var Api = '<?php echo base_url('wallet/wallet/'); ?>';
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
                
                
               
                $request.dataTable().fnDestroy()
                var $table = $request.DataTable({
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
                      url: Api + "get_requests?key=" + duid +"&member="+member+"&from="+from+"&to="+to+"&default_a="+default_a+"&default_v="+default_v+"&status="+status+"&list=all",
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
   var Api = '<?php echo base_url('wallet/wallet/'); ?>';
            
              
    var $table = $request.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No distributors data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_requests",
        type: "GET",
      },

      "pageLength": 10
    });
 });
    </script>