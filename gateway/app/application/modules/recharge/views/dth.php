<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">DTH Recharge</h3>
                        </div>
                        <div class="col-4 text-right">
    
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('recharge/mobile_submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                   
                            <div class="row">
                              
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Service Provider</label>
                                        <select name="operator" class="form-control">
                                             <option value="">Select Service Provider</option>
                                            <option value="AD">Airtel Digital TV</option>
                                            <option value="DT">Dish TV</option>
                                            <option value="SD"> Sun Direct</option>
                                            <option value="TS">Tata Sky</option>
                                            <option value="VT">Videocon d2h</option>
            
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                  <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Registered Mobile No. or Subscriber ID</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control clear" placeholder="Registered Mobile No. or Subscriber ID" required>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Circle</label>
                            <select name="circle" class="form-control" id="circle">
                                <option value="">Select Circle</option>
                                <option value="1">Andhra Pradesh</option>
                                <option value="2">Assam</option>
                                <option value="3">Bihar Jharkhand</option>
                                <option value="4">Chennai</option>
                              <option value="5">Delhi</option>
                                 <option value="26">Goa</option>
                                <option value="6">Gujarat</option>
                                <option value="7">Haryana</option>
                                <option value="8">Himachal Pradesh</option>
                              <option value="9">Jammu & Kashmir</option> 
                              <option value="10">Karnataka</option>
                                <option value="11">Kerala</option>
                                <option value="12">Kolkata</option>
                                <option value="14">Madhya Pradesh & Chhattisgarh	</option>
                              <option value="13">Maharashtra</option> 
                              <option value="27">Manipur</option>
                                <option value="15">Mumbai</option>
                                <option value="16">North East	</option>
                                <option value="17">Orissa</option>
                              <option value="18">Punjab</option> 
                              <option value="19">Rajasthan</option>
                                <option value="20">Tamil Nadu	</option>
                                <option value="21">Uttar Pradesh (E)	</option>
                                <option value="22">Uttar Pradesh (W)	</option>
                              <option value="23">West Bengal	</option> 
                              
                            </select>
                            </div>
                        </div>
                    </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control clear" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" id="submit">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <button type="submit" class="btn btn-primary my-4" id="submit_btn">Save</button>
                                <input type="hidden"  name="type" value="2"> 
                                 <input type="hidden"  name="service" value="7"> 
                                <button type="button" class="btn btn-primary my-4"  data-toggle="modal" data-target="#exampleModal" id="view_plan">view R offer</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-8 order-xl-2">
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
                            <th scope="col">Trn id</th>
                            <th scope="col">Details</th>
                             <th scope="col">Mobile</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                             <th scope="col">Created At</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Plans</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="body">
        
        </div>
      </div>
     
    </div>
  </div>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>

 <script type="text/javascript">

  var $transectionlist = $('#transectionlist');
           var duid = '<?php echo $this->session->userdata("member_id") ?>';
         var type =2; 
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
        url: Api + "get_history?key=" + duid + "&list=all&type="+ type ,
        type: "GET",
      },

      "pageLength": 10
    });

      $(document).on('change','select[name="operator"]', function() {

      var operator = $(this).val();
      
       //console.log(operator);
     $.ajax({
        url: '<?php echo base_url('recharge/fetch_dth_plan'); ?>',
        type: 'POST',
        data: {
          'operator': operator,
          
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

        },
        beforeSend: function() {
          $('#body').html('<center><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span></center>');
        },
        success: function(data) {
          $('#body').html(data)

        },

      });
      });
         </script>
