<style>
#resend:hover
{
    cursor:pointer;
}
body
{
        overflow: hidden !important;
}
</style>
<div class="main-content">
  <!-- Header -->
  <div class="header bg-gradient-primary py-5" style="padding-top:70px !important;">
    <div class="separator separator-bottom separator-skew zindex-100">
      <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
      </svg>
    </div>
  </div>
  <!-- Page content -->
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="container mt--7 pb-5" id="varify-port">
        <div class="row justify-content-center">
          <div class="col-lg-10 col-md-7">
            <div class="card bg-secondary border-0 mb-0">
              <div class="card-body px-lg-5 py-lg-5">
                <div class="text-center text-muted mb-4">
                  <a class="navbar-brand" href="#">
                    <img style="height: 65px;" src="<?php echo base_url('/optimum/logoside.png'); ?>">
                  </a></br>
                  <small>OTP Has Been Sent On Your Register Mobile Number <?php echo $moble?></small>
                </div>
                <form role="form" name="logform" action="" method="post" autocomplete="off">
                 
                  <div class="form-group">
                    <span style="color:red;font-size:13px;" id="otp_error"></span>
                    <div class="input-group input-group-merge input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                      </div>
                      <input class="form-control" type="number" name="otp" placeholder="Enter The OTP" value="" autocomplete="off" size="4">
                    </div>
                  </div>
                  <div class="custom-control custom-control-alternative">
                    <!--<input class="custom-control-input" id="customCheckLogin" type="checkbox">-->
                      <div class="text-muted" id="timer" style="font-size:14px;"></div>
                  </div>
                  <div class="text-center">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button id="submit" class="btn btn-primary my-4">Sign in</button>
                  </div>
                </form>
              </div>
            </div>
          
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function()
{
    $('#submit').click(function()
    {
        var otp=$("input[name='otp']").val();
        $.ajax(
            {
                url:'<?php echo base_url("otp_verify")?>',
                type:"POST",
                data:{'otp':otp,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                success:function(data)
                {
                    if(data=='true')
                    {
                        window.location.href = "<?php echo base_url('dashboard')?>";
                    }
                    else
                    {
                       $('#otp_error').text('**Invaild OTP'); 
                    }
                        
                }
            });
    });
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
});
 function sms()
    {
        $.ajax(
            {
                url:'<?php echo base_url("sms")?>',
                type:"GET",
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