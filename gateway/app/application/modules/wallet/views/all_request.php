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

                         <table class=" align-items-center  table-responsive" id="widthdrawal_list">
                              <thead class="thead-light">
                                <tr>
                                 
                                  <th scope="col">MEMBER ID</th>
                                  
                                  <th scope="col">Amount</th>
                                  <th scope="col">Status</th>
                                  <th scope="col">Mode</th>
                                  <th scope="col">Refrence</th>
                                 <th scope="col"> Stock Type</th>
                                  <th scope="col"> Bank</th>
                                  <th scope="col">Narration</th>
                                 <th scope="col"> Type</th>
                                  <th scope="col"> Date</th>
                                </tr>
                              </thead>
                        </table>



              
                    </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
         $(document).ready(function() {
         var $request = $('#widthdrawal_list');
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
                url: Api + "get_all_history",
                type: "GET",
              },
        
              "pageLength": 10
            });
         });
    </script>

