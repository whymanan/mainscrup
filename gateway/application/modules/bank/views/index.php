
   
 <div class="col-xl-12 order-xl-1">
            <div class="card " id="card_bank">
                <div class="card-header">
                    <div class="row justify-content-between align-items-center">
                        <div class="col">
                            <h6 class="heading-small text-muted mb-4">Bank Account Details</h6>
                        </div>
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body">
                        <form method="post" id="sent_form">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Account Holder Name</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                            </div>
                                            <input class="form-control" name="name" placeholder="Account Holder Name"
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Account Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-credit-card"></i></span>
                                            </div>
                                            <input class="form-control" name="account_no" placeholder="Account Number"
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Bank Name</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-university"></i></span>
                                            </div>
                                            <input class="form-control" name="bank_name" placeholder="Bank Name"
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Bank IFSC Code</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                            </div>
                                            <input class="form-control" name="ifsc" placeholder="IFSC Code" type="text"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Phone Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="ni ni-mobile-button"></i></span>
                                            </div>
                                            <input class="form-control" name="phone" placeholder="Phone Number"
                                                type="tel" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="user_id" value="<?php if (isset($user_id)) {
                                                echo $user_id;
                                                                                                 }?>">
                            <!--<button id="sent_otp" class="btn btn-success">Submit</button>-->
                             <button type="button" id="Verify" class="btn btn-primary">Submit</button>
                             </div>
                             </form>
                    </div>
                </div>
                
                
<div class="container">
        <div class="row">
              <div class="col-lg">
                    <div class="card ">
                    <div class="card-body" >
                     <table id="banklist" class="align-items-center table-flush table">
    <thead>
        <tr>
            <th>#
            </th>
            <th>Varification
            </th>
            <th>NAme
            </th>
            <th>ACC Number
            </th>
            <th>Bank
            </th>
            <th>IFSC
            </th>
            <th>Mobile
            </th>
        </tr>
    </thead>
    <tbody>
      <?php $i=1;
      if(isset($bank)){foreach($bank as $row){ ?>
     <td><?php echo $i?></td> 
     <td><?php if($row['varification']==1){ echo "<span class='badge badge-success'>Varified</span>";}else{?><button class='btn btn-sm btn-success' id='1varify' value='<?php echo $row['fk_user_id']?>'>Varify</button><?php }?></td> 
     <td><?php echo $row['account_holder_name']?></td> 
     <td><?php echo $row['account_no']?></td>
     <td><?php echo $row['bank_name']?></td> 
     <td><?php echo $row['ifsc_code']?></td>
     <td><?php echo $row['phone_no']?></td>
      <?php $i++;}} ?>
    </tbody>
</table>
                    </div>
                </div>
          </div>
     </div>
</div> 
<!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">-->
<!--  <div class="modal-dialog modal-dialog-centered" role="document">-->
<!--    <div class="modal-content">-->
<!--      <div class="modal-header">-->
<!--        <h5 class="modal-title" id="exampleModalLongTitle">OTP Has Been Sent On Your Register Mobile <?php echo substr($this->session->userdata('phone'),0,2)."XXXXXX".substr($this->session->userdata('phone'),-2)?></h5>-->
<!--        </button>-->
<!--      </div>-->
<!--      <div class="modal-body">-->
<!--         <div class="col-12">-->
<!--       <p id="error"></p>-->
<!--        <input type="number" class="form-control" name="otp" value="" placeholder="Enter the OTP">-->
<!--        <p id="timer"></p>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="modal-footer">-->
<!--        <button type="button" id="Resend" class="btn btn-secondary">Resend</button>-->
<!--        <button type="button" id="Verify" class="btn btn-primary">Verify</button>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
        <script>
                  
            $('#1varify').click(function()
            {
                var sureDel = confirm("Varification Charge four rupees");
               if (sureDel == true) {
                   var userid=$(this).val();
                $.ajax({
                  type: "GET",
                  url: "<?php echo base_url('bank/bank/varify/')?>" + userid,
                  success: function(response) {
                      console.log(response);
                     Swal.fire({
                              position: 'top-end',
                              icon: 'success',
                              title: response,
                              showConfirmButton: false,
                              timer: 1500
                            });
                      location.reload()
                    // consol.log(response);
                  }
              });
        }
            }); 
            $('#sent_otp').click(function()
            {
              if($('input[name=name]').val()!='' && $('input[name=account_no]').val()!='' && $('input[name=bank_name]').val()!='' && $('input[name=ifsc]').val()!='' && $('input[name=phone]').val()!='')
              {
                  $.ajax(
                  {
                      url:"<?php echo base_url('bank/send_otp');?>",
                      type: "GET",
                      success:function(result)
                      {
                          if(result!="false")
                          {
                            //  $('#myModal').modal('show');
                             $('#myModal').modal(
                                 {
    	                        	keyboard: false,
    	                        	show:true,
    	                        	backdrop:false
                                 });
                             $('#Resend').hide();
                             var timeLeft = 30;
                             var timerId = setInterval(countdown, 1000);
                             function countdown() {
                                 if (timeLeft == -1) {
                                     clearTimeout(timerId);
                                  } else {
                                    $('#timer').text(timeLeft+" Seconds");
                                if(timeLeft==0)
                                {
                                 $('#timer').text('');
                                 $('#Resend').show();
                               }
                           timeLeft--;
                            }
                           }
                          }
                      }
                  });
              }
            });
            $('#Verify').click(function()
            {
                var name=$('input[name=name]').val();
                var account_no=$('input[name=account_no]').val();
                var ifsc=$('input[name=ifsc]').val();
                var phone=$('input[name=phone]').val();
                // var otp=$('input[name=otp]').val();
                var bank_name=$('input[name=bank_name]').val()
                
              if($('input[name=name]').val()!='' && $('input[name=account_no]').val()!='' && $('input[name=bank_name]').val()!='' && $('input[name=ifsc]').val()!='' && $('input[name=phone]').val()!='')
              {
                  $.ajax(
                  {
                      url:"<?php echo base_url('bank/otp_verify');?>",
                      type: "POST",
                      data:{'name':name,'bank_name':bank_name,'account_no':account_no,'ifsc':ifsc,'phone':phone,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash();?>'},
                      success:function(result)
                      {
                          if(result=="true")
                          {
                               Swal.fire({
                                position: 'top-end',
                               icon: 'success',
                               title: 'Bank Details Added',
                               showConfirmButton: false,
                               timer: 1500
                               })
                              window.location.reload();
                             
                          }
                          else
                          {  
                               $('#error').html("<span style='color:red;font-size:16px'>**Invalid OTP</span>");
                          }
                      }
                  });
              }
            });
            $('#Resend').click(function()
            {
              if($('input[name=name]').val()!='' && $('input[name=account_no]').val()!='' && $('input[name=bank_name]').val()!='' && $('input[name=ifsc]').val()!='' && $('input[name=phone]').val()!='')
              {
                  $.ajax(
                  {
                      url:"<?php echo base_url('bank/send_otp');?>",
                      type: "GET",
                      success:function(result)
                      {
                          if(result!="false")
                          {
                             $('#Resend').hide();
                             var timeLeft = 30;
                             var timerId = setInterval(countdown, 1000);
                             function countdown() {
                                 if (timeLeft == -1) {
                                     clearTimeout(timerId);
                                  } else {
                                    $('#timer').text(timeLeft+" Seconds");
                                if(timeLeft==0)
                                {
                                 $('#timer').text('');
                                 $('#Resend').show();
                               }
                           timeLeft--;
                            }
                           }
                          }
                      }
                  });
              }
            });
            
        </script>