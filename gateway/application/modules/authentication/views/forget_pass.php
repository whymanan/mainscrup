<div class="row justify-content-center" id="forget">
    <div class="col-lg-10 col-md-7">
        <div class="card card-profile bg-secondary mt-5">

            <div class="card-body pt-7 px-5">
                <div class="text-center mb-4">
                    <h3>Enter Your USER ID</h3>
                </div>
                <form>
                    <div class="form-group">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input class="form-control" placeholder="User Id" id="userid" type="text" value="">
                        </div>

                    </div>

                    <button type="button" id="get_user" class="btn btn-primary mt-2">Search</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<template id="verify">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-7">
            <div class="card card-profile bg-secondary mt-5">

                <div class="card-body pt-7 px-5">
                    <div class="text-center mb-4">
                        <h3>Enter OTP sent to your Registered Mobile Number or Email Id</h3>
                    </div>
                    <form>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="Enter otp" id="otp" type="number">
                                <input type="hidden" name="mobile" id="mobile">
                                <input type="hidden" name="user_id" id="user_id">
                            </div>

                        </div>
                        <p id="timer"></p>
                        <div class="text-center"> <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                            <button type="button" id="verify_otp" class="btn btn-primary mt-2">verify</button>
                            <button type="button" id="resend" class="btn btn-primary mt-2" style="display:none;">Resend</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <template id="change_pass">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-7">
                <div class="card card-profile bg-secondary mt-5">

                    <div class="card-body pt-7 px-5">
                        <div class="text-center mb-4">
                            <h3>Enter Your New Password</h3>
                        </div>
                        <form>
                            <div class="form-group mb-3">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="NEW Password" id="password" type="text" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" type="password" id="confirm" placeholder="Confirm Password" value="">
                                </div>
                            </div>
                            <div class="text-center"> <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input type="hidden" name="user" id="user">
                                <button type="button" id="reset_password" class="btn btn-primary mt-2">Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>

<script>
    
    $(document).on("click", "#reset_password", function() {
      var password = $('#password').val();
      var user_id = $('#user').val();
      console.log(user_id)

      if (password != "") {
        $.ajax({
          url: '<?php echo base_url('reset_pass'); ?>',
          type: 'POST',
          dataType: 'json',
          data: {
            password: password,
            user_id:user_id,
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

          },
          success: function(data) {
            if (data.status == true) {
              
              Swal.fire({
                position: 'top-end',
                icon: 'Success',
                title: data.msg,
                showConfirmButton: false,
                timer: 3000
              });
              
              location.reload();
              
            } else {
              Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: "OTP does not match",
                showConfirmButton: false,
                timer: 3000
              });
            }

          },

        })
        
      }else{
          
          console.log('password')
          
      }

    });
    
    
    
</script>