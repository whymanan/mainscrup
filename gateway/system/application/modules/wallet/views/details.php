<div class="row">

    <div class="col-xl-12 order-xl-1">
         <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-large text-muted mb-4">List</h4>
                    </div>
                    <div class="col-4 text-right">
                    
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="details_list">
          <thead class="thead-light">
            <tr>
              <th scope="col">SR No.</th>    
              <th scope="col">Member ID</th>
              <th scope="col">Member Name</th>
              <th scope="col">Mobile</th>
              <th scope="col">Package Name</th>
              <th scope="col">Debit </th>
              <th scope="col">credit</th>
              <th scope="col"> Balance </th>
              <th scope="col"> Detais</th>
        
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
          
           var Api = '<?php echo base_url('wallet/Wallet/'); ?>';
            
               var $details_list = $('#details_list');
               var id= <?php  echo $member_id['q'] ?>;
                var $table = $details_list.DataTable({
                  "processing": true,
                  "serverSide": true,
                  "deferRender": true,
                  "language": {
                    "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                    "emptyTable": "No distributors data available ...",
                  },
                  "order": [],
                  "ajax": {
                    url: Api + "get_details_list?id=" + id,
                    type: "GET",
                  },
            
                  "pageLength": 10
                });
  });
  </script>
   