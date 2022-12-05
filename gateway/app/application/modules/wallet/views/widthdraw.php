<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-small text-muted mb-4">Redeem Money</h4>
                    </div>
                    <div class="col-4 text-right">
                      <h3 class="mb-0">Balance :- Rs : <?php echo $bal?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('wallet/widthdraw_bal'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="pl-lg-4">

                        <div class="row">
                            <input type="hidden" name="vendor" value="<?php echo $this->session->userdata('user_id')?>" >
       
                        <div class="col-sm-2" >
                            <p > Amount <span style="color:red;"> * </span> </p>
                        </div>
                        <div class="col-sm-2" >
                            <p >
                                <input name="amount" id="amount" type="text" required class="form-control" value="" >
                                <input type="hidden" name="balance" id="balance" value="<?php echo $bal?>" >
                            </p>
                        </div>

                        
                            <div class="col-sm-2">
                                <p > Remark <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-6">
                               
                                     <input name="narration" type="text" required class="form-control">
                                
                            </div>
                                 
                        </div>

                            <div class="row text-center container" id="submit">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >
                               
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
        <table class="table align-items-center table-flush" id="widthdrawal_list">
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
     
      $("#submit").hide();
 $("#amount").on('change', function() {
    var amount = $('#amount').val();
    var balance = $('#balance').val();
    var net =balance-amount;
    console.log(net);
  if( net>500){
       $("#submit").show();
  }
    else{
              $("#submit").hide();

    }
    });  
     
      var Api = '<?php echo base_url('wallet/wallet/'); ?>';

   var $widthdrawal_list = $('#widthdrawal_list');
   
    var $table = $widthdrawal_list.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_widthdrawal_list",
        type: "GET",
      },

      "pageLength": 10
    });



 });
</script>