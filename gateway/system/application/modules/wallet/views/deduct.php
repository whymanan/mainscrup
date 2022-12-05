<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-small text-muted mb-4">Redeem Money</h4>
                    </div>
                    <div class="col-4 text-right">
                     <h3 class="mb-0"></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('wallet/deduct_bal'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="pl-lg-4">

                        <div class="row">
                            <div class="col-sm-2" >

                                <label class="form-control-label">Parent Name
                                        <button type="button" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="Select the parent of this user">
                                            <i class="ni ni-bulb-61"></i>
                                        </button>
                                    </label>
                            </div>
    
                            <div class="col-sm-4" >
                                <select name="vendor" id="vendor" class="form-control" required>
                                        <option value="<?php echo $this->session->userdata('user_id')?>"><?php echo $this->session->userdata('user_name')?></option>
                                    </select>
                            </div>
                            
                               
                        <div class="col-sm-2" >
                            <p > Amount <span style="color:red;"> * </span> </p>
                        </div>
                        <div class="col-sm-4" >
                            <p >
                                <input name="amount" id="amount" type="text" class="form-control" value="0" >

                            </p>
                        </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p > Remark <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-10">
                               
                                     <input name="narration" type="text" id="" class="form-control">
                                
                            </div>
                                 
                        </div>

                            <div class="text-center container">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >
                                <input type="hidden" name="balance" value="<?php echo $bal?>" >
                                <input type="submit" name="" value="Submit" class="btn btn-primary">
                                <input type="submit" name="" value="Reset" class="btn btn-danger">
                            </div>



              
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
        <table class="table align-items-center table-flush" id="deduction_list">
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
 $("#vendor").on('change', function() {

      var search = $(this).val();

      $.ajax({
        url: '<?php echo base_url('wallet/get_balance'); ?>',
        type: 'GET',
        data: {
          'search': search,
        },
        beforeSend: function() {
          $('input[name="vendor"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
        console.log(data);
        $('input[name="amount"]').val(data)
        },
        complete: function() {
          $('input[name="vendor"]').parent().find('img').remove();
        },
      })

    });  
     
      var Api = '<?php echo base_url('wallet/wallet/'); ?>';
            
               var $deduction_list = $('#deduction_list');
               
                var $table = $deduction_list.DataTable({
                  "processing": true,
                  "serverSide": true,
                  "deferRender": true,
                  "language": {
                    "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                    "emptyTable": "No data available ...",
                  },
                  "order": [],
                  "ajax": {
                    url: Api + "get_deduct_list",
                    type: "GET",
                  },
            
                  "pageLength": 10
                });


 });
</script>