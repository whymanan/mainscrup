 
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Transaction List</h3>
                    </div>

                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush" id="transectionlist">
                    <thead class="thead-light">
                        <tr>
                             <th scope="col">Member id</th>
                            <th scope="col">Trn id</th>
                            <th scope="col">Details</th>
                             <th scope="col">Mobile/Account No</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                             <th scope="col">Created At</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
   
   
    <script type="text/javascript">
         $(document).ready(function() {
         
            
            var $transectionlist = $('#transectionlist');
                   var duid = '<?php echo $this->session->userdata("member_id") ?>';
                    var type =1; 
              var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
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
                url: Api + "get_history_all?key=" + duid + "&list=all&type="+type,
                type: "GET",
              },
        
              "pageLength": 10
            });
         });
 
    </script>