<div class="row">

    <div class="col-xl-12 order-xl-1">
         <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-large text-muted mb-4">List Of Retailers</h4>
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
              <th scope="col">MEMBER ID</th>
               <th scope="col">REFERENCE iD</th>
              <th scope="col">ACCEPTED DATE</th>
              <th scope="col">Type</th>
              <th scope="col">STATUS</th>
              <th scope="col">Remark</th>
              <!-- <th scope="col">DETAILS</th> -->
           
            </tr>
          </thead>
        </table>
      </div>
            </div>
        </div>
          </div>
          </div>

          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Remark</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <span id="success1"></span>
     <span id="error1"></span>
        <form id="save_contact" method="post" action="<?php echo base_url('Gstfiling/gstfiling/submitRemark'); ?>">
        <div class="modal-body">
        <div class="form-group">
       <input type="hidden" id="sl_id" name="sl_id">

        <input type="text" class="form-control" name="remark" id="remark">
        
        </div>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"
                                autocomplete="off">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary rounded-0">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
   <script type="text/javascript">
  $(document).ready(function() {       
          
           var Api = '<?php echo base_url('Gstfiling/gstfiling/'); ?>';
            
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
                    url: Api + "get_admin_list?id=" + id,
                    type: "GET",
                  },
            
                  "pageLength": 10
                });
  });
  </script>
   