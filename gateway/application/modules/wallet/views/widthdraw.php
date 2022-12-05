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
        </div>
    </div>
</div>

<div class="row">

        <div class="col-xl-6 order-xl-1">
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
                             
                             <th scope="col">#</th>
                             <th scope="col">MEMBER ID</th>
                             <th scope="col">Amount</th>
                             <th scope="col">Charge</th>
                             
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
         
        
            <div class="col-xl-6 order-xl-1">
            
                <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                        <h4 class="heading-large text-muted mb-4">Bank List</h4>
                        </div>
                        <div class="col-4 text-right">
                        
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush" id="bank_list">
                          <thead class="thead-light">
                            <tr>
                             
                            <th scope="col">#</th>
                            <th scope="col">NAME</th>
                            <th scope="col">ACCOUNT NUMBER</th>
                            
                            <th scope="col">BANK</th>
                            <th scope="col">IFSC</th>
                            <th scope="col">MOBILE</th>
                             
                            
                            </tr>
                          </thead>
                        </table>
                    </div>
                </div>
            </div>
                
                <?php if( !isAdmin($this->session->userdata('user_roles')) ){ ?>  
                
                    <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                            <h4 class="heading-large text-muted mb-4"> Move to Distributor </h4>
                            </div>
                            <div class="col-4 text-right">
                            
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush" id="parent_list">
                              <thead class="thead-light">
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">NAME</th>
                                
                                <th scope="col">Member Id</th>
                                <th scope="col">MOBILE</th>
                                 
                                
                                </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <?php } ?>
        
        </div>
</div>


<div class="modal fade" id="modal-add-beneficiary" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title" id="modal-title-default">Move To Bank  </h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
           <!--<div id="otptomove">-->
           <!--   <form method="post" enctype="multipart/form-data" autocomplete="off">-->
           <!--     <div class="modal-body">-->
           <!--      <div class="row">-->
           <!--     <div class="col-md-12">-->
           <!--         <p>OTP Has Been Sent On Your Register Mobile <?php echo substr($this->session->userdata('phone'),0,2)."XXXXXX".substr($this->session->userdata('phone'),-2)?></p>-->
           <!--     </div>-->
           <!--     <div class="col-md-12">-->
           <!--       <div class="form-group">-->
           <!--         <div class="input-group input-group-merge">-->
           <!--           <div class="input-group-prepend">-->
           <!--             <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>-->
           <!--           </div>-->
           <!--           <input class="form-control" placeholder="Enter Four digit OTP" name="otp" type="number" required>-->
           <!--          <span style="color:red;font-size:13px;" id="otp_error"></span>-->
           <!--             <input type="hidden" name="vendor" value="<?php echo $this->session->userdata('user_id')?>">-->
           <!--             <input type="hidden" name="phone" value="<?php echo $this->session->userdata('phone')?>" >-->
                          
           <!--         </div>-->
           <!--       </div>-->
           <!--       <div class="text-muted" id="timer" style="font-size:14px;"></div>-->
           <!--     </div>-->
           <!--   </div>-->
           <!-- </div>-->
           <!--     <div class="modal-footer">-->
           <!--      <button id="otp" class="btn btn-success">Submit</button>-->
           <!--      <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>-->
           <!--     </div>-->
           <!--    </form>-->
           <!-- </div>-->
          <form name="validate" id="widthdraw_bal"  role="form" action="<?php echo base_url('wallet/widthdraw_bal'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                      </div>
                      <input class="form-control" placeholder="Enter Amount" id="move_id" name="amount" type="number" required>
                        <input type="hidden" name="vendor" value="<?php echo $this->session->userdata('user_id')?>" >
                        <input type="hidden" name="balance" id="balance" value="<?php echo $bal?>" >
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                      </div>
                      <input class="form-control" id="charge" name="charge" type="text" readonly required>
                       <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
             <button type="submit" name="move_submit" id="move_submit" class="btn btn-success">Submit</button>
              <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
    </div>
</div>


<div class="modal fade" id="distributor-model" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
    
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
    
        <div class="modal-content">
            
          <div class="modal-header">
            <h6 class="modal-title" id="modal-title-default"> Move To Distributor  </h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
            
            <form id="distributor_bal_move" action="<?php echo base_url('wallet/wallet/distributor_movve'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            
                <div class="modal-body">
                  <div class="row">
                    
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                          </div>
                            <input class="form-control" placeholder="Enter Amount" id="distributor_move_id" name="amount" type="number" required>
                            <input type="hidden" name="vendor" value="<?php echo $this->session->userdata('user_id')?>" >
                            <input type="hidden" name="balance" id="balance" value="<?php echo $bal?>" >
                            <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $parent_id?>" >
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >
                        
                        </div>
                      </div>
                    </div>
                    
                    
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                          </div>
                          <input class="form-control" name="distributor_charge" value="0" type="text" readonly required></div>   
                          <!--id="distributor_charge"-->
                      </div>
                    </div>
                    
                  </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" name="move_distributor" id="move_distributor" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
                </div>
          
            </form>
            
        </div>
    </div>
</div>



<script type="text/javascript">
$(document).ready(function() {
    
     $('#otp').click(function()
    {
        var otp=$("input[name='otp']").val();
        var temp='<form name="validate" role="form" action="<?php echo base_url('wallet/widthdraw_bal'); ?>" method="post" id="widthdraw_bal" enctype="multipart/form-data" autocomplete="off">'+
            '<div class="modal-body">'+
              '<div class="row">'+
                '<div class="col-md-12">'+
                  '<div class="form-group">'+
                    '<div class="input-group input-group-merge">'+
                      '<div class="input-group-prepend">'+
                        '<span class="input-group-text"><i class="fa fa-credit-card"></i></span>'+
                      '</div>'+
                      '<input class="form-control" placeholder="Enter Amount" id="move_id" name="amount" type="number" required>'+
                        '<input type="hidden" name="vendor" value="<?php echo $this->session->userdata('user_id')?>" >'+
                        '<input type="hidden" name="balance" id="balance" value="<?php echo $bal?>" >'+
                    '</div>'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-12">'+
                  '<div class="form-group">'+
                    '<div class="input-group input-group-merge">'+
                      '<div class="input-group-prepend">'+
                        '<span class="input-group-text"><i class="fa fa-credit-card" aria-hidden="true"></i></span>'+
                      '</div>'+
                      '<input class="form-control" id="charge" name="charge" type="text" readonly required>'+
                       '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >'+
                    '</div>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="modal-footer">'+
             '<button type="submit" name="move_submit" class="btn btn-success">Submit</button>'+
              '<button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>'
            '</div>'
          '</form>';
        $.ajax(
            {
                url:'<?php echo base_url("wallet/otp_verify")?>',
                type:"POST",
                data:{'otp':otp,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                success:function(data)
                {
                    if(data=='true')
                    {
                        $('#otptomove').html(temp); 
                    }
                    else
                    {
                      $('#otp_error').text('**Invaild OTP'); 
                    }
                        
                }
            });
    });
    // bank wid
    
        $('#widthdraw_bal').submit(function(){
            $(this).find('#move_submit').prop('disabled', true);
        });
    
    
        $('#move_submit').attr('disabled', 'true');
    
          $('#move_id').on('change', function() {
            var amount = $(this).val();  
            var userId = <?php echo $this->session->userdata('user_id') ?>;
            var data = {
              "search": userId,
              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
              type: "GET",
              url: "<?php echo base_url(); ?>wallet/wallet/get_balance",
              cache: false,
              dataType: "json",
              data: data,
              success: function(response) {
                  
                  var data = {
                              "search": amount,
                              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            };
                    $.ajax({
                      type: "GET",
                      url: "<?php echo base_url(); ?>wallet/wallet/get_move_charge",
                      cache: false,
                      dataType: "json",
                      data: data,
                      success: function(data) {
                          
                          $('#charge').val(data)
                        var total = parseInt(amount) + parseInt(data);
                        
                        if (total > response ) {
                            
                          $('#move_id').css('border', 'solid 1px red');
                          $('#move_submit').attr('disabled', 'true');
                        } else {
                            
                          $('#move_submit').removeAttr('disabled');
                          $('#move_id').css('border', 'solid 1px #d2d6de');
                        }
                          
                      }
                      
                    });
              }
            });
          });
        //   Bnak wad end
        
        
        $('#distributor_bal_move').submit(function(){
            $(this).find('#move_distributor').prop('disabled', true);
        });
    
    
        $('#move_distributor').attr('disabled', 'true');
    
        $('#distributor_move_id').on('change', function() {
            var amount = $(this).val();  
            var userId = <?php echo $this->session->userdata('user_id') ?>;
            var data = {
              "search": userId,
              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
              type: "GET",
              url: "<?php echo base_url(); ?>wallet/wallet/get_balance",
              cache: false,
              dataType: "json",
              data: data,
              success: function(response) {
                  
                  var data = {
                              "search": amount,
                              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            };
                    $.ajax({
                      type: "GET",
                      url: "<?php echo base_url(); ?>wallet/wallet/get_move_charge",
                      cache: false,
                      dataType: "json",
                      data: data,
                      success: function(data) {
                          
                          $('#distributor_charge').val(data)
                        var total = parseInt(amount) + parseInt(data);
                        
                        if (total > response ) {
                            
                          $('#distributor_charge').css('border', 'solid 1px red');
                          $('#move_distributor').attr('disabled', 'true');
                        } else {
                            
                          $('#move_distributor').removeAttr('disabled');
                          $('#distributor_charge').css('border', 'solid 1px #d2d6de');
                        }
                          
                      }
                      
                    });
              }
            });
        });
        
        
});
</script>



<script type="text/javascript">

    function Deny(id) {
        var sureDel = confirm("Are you sure want to reject");
        if (sureDel == true) {
          $.ajax({
            type: "GET",
            url: "<?php echo base_url('wallet/wallet/deny/')?>" + id,
    
            success: function(response) {
              if (response == 1) {
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Rejected',
                  showConfirmButton: false,
                  timer: 3500
                  
                });
                
              } else {
                Swal.fire({
                  position: 'top-end',
                  type: 'error',
                  title: 'Something went wrong',
                  showConfirmButton: false,
                  timer: 3500
                });
              }
             location.reload()
            }
          });
    
        }
    
    }
    
    function Approve(id) {
        
        var sureDel = confirm("Are you sure want to Approve");
        if (sureDel == true) {
          $.ajax({
            type: "GET",
            url: "<?php echo base_url('wallet/payout?id=')?>" + id,
    
            success: function(response) {
              if (response == 1) {
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Success',
                  showConfirmButton: false,
                  timer: 3500
                  
                });
                
              } else {
                Swal.fire({
                  position: 'top-end',
                  type: 'error',
                  title: 'Something went wrong',
                  showConfirmButton: false,
                  timer: 3500
                });
              }
             location.reload()
            }
          });
    
        }
    
    }

 $(document).ready(function() {
     
      $("#submit").hide();
     $("#amount").on('change', function() {
        var amount = $('#amount').val();
        var balance = $('#balance').val();
        var net =balance-amount;
      if(net>100){
           $("#submit").show();
      }
        else{
                  $("#submit").hide();

    }
    });  
    
    // GET MOVE TO BANK TRANSACTION LIST
     
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
        
    // GET BANK DETAILS    
            
    var Api = '<?php echo base_url('wallet/wallet/'); ?>';

    var $bank_list = $('#bank_list');
   
    var $table = $bank_list.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_bank_list",
        type: "GET",
      },

      "pageLength": 10
    });
        
    // Get parent    
    
    var Api = '<?php echo base_url('wallet/wallet/'); ?>';

    var $parent_list = $('#parent_list');
   
    var $table = $parent_list.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_parent_list",
        type: "GET",
      },

      "pageLength": 10
    });
    
   



 });
  function sent_otp()
    {
        var user_id=<?php echo $this->session->userdata('user_id')?>;
        var phone=<?php echo $this->session->userdata('phone')?>;
         $.ajax(
            {
                url:'<?php echo base_url("wallet/otp_send")?>',
                type:"POST",
                data:{'user_id':user_id,'phone':phone,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                success:function(data)
                {
                   var timeLeft = 30;
                   var timerId = setInterval(countdown, 1000);
    
                  function countdown() {
      if (timeLeft == -1) {
        clearTimeout(timerId);
      } else {
         $('#timer').text(timeLeft+" Seconds");
        if(timeLeft==0)
        {
        //   var sms="<?php echo base_url('/sms')?>";
           $('#timer').html("<a href='javascript:void(0)' onclick='sms()' id='resend1'>Resend</a>") 
        }
        timeLeft--;
      }
    }
                }
            });
    }
    function sms()
    {
        var user_id=<?php echo $this->session->userdata('user_id')?>;
         var phone=<?php echo $this->session->userdata('phone')?>;
        $.ajax(
            {
                url:'<?php echo base_url("wallet/otp_send")?>',
                type:"POST",
                 data:{'user_id':user_id,'phone':phone,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                success:function(data)
                {
                    if(data=='true')
                    {
                        var timeLeft = 30; 
                        var timerId = setInterval(countdown, 1000);
                        function countdown() {
                          if (timeLeft == -1) {
                             clearTimeout(timerId);
                          } else {
                             $('#timer').text(timeLeft+" Seconds");
                          if(timeLeft==0)
                            {
        //   var sms="<?php echo base_url('/sms')?>";
           $('#timer').html("<a href='javascript:void(0)' onclick='sms()' id='resend1'>Resend</a>") 
        }
                            timeLeft--;
                         }
                        } 
                    }
                }
            });
      
    }
</script>