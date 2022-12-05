<div class="row">

    <div class="col-xl-12 order-xl-1">
         <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-large text-muted mb-4">List Of Admin</h4>
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
            <!-- <th scope="col">#</th> -->
              <th scope="col">firm_name</th>
           
              <th scope="col">State </th>
              <th scope="col">District</th>
              <th scope="col">Com Type</th>
              
              <th scope="col">Details</th>
              <th scope="col">action</th>
              <th scope="col">action</th>
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
          
           var Api = '<?php echo base_url('gstfiling/'); ?>';
            
               var $details_list = $('#details_list');
               var id= 'EMOA001';
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
                    url: Api + "get_gstreg_list?id=" + id,
                    type: "GET",
                  },
            
                  "pageLength": 10
                });
  });
  </script>
   