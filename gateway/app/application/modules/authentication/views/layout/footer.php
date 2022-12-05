<!-- Footer -->
<footer class="py-5" id="footer-main">
  <div class="container">
    <div class="row align-items-center justify-content-xl-between">
      <div class="col-xl-6">
        <div class="copyright text-center text-xl-left text-muted">
          &copy; 2020 <a href="#" class="font-weight-bold ml-1" target="_blank">RB WISH</a>
        </div>
      </div>
      <div class="col-xl-6">
        <ul class="nav nav-footer justify-content-center justify-content-xl-end">
          <li class="nav-item">
            <a href="#" class="nav-link" target="_blank">Support Tim</a>
          </li>
          <li class="nav-item">
            <a href="#/presentation" class="nav-link" target="_blank">About Us</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" target="_blank">Blog</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" target="_blank">Contact Us</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- Argon Scripts -->
<!-- Core -->
<script src="<?php echo base_url(ASSETS); ?>/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/js-cookie/js.cookie.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<!-- Argon JS -->
<script src="<?php echo base_url(ASSETS); ?>/js/argon.js?v=1.2.0"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

</body>
<script type="text/javascript">

$(document).ready(function() {



    var location = {};
    var geoOptions = {
      enableHighAccuracy: true
    }

    var geoSuccess = function(position) {
        location = {
         'latitude': position.coords.latitude,
         'longitude': position.coords.longitude
       };
    };

    var geoError = function(error) {
      var msg = '';
      if (error.code == 1) {
        msg = "Please enabled your loaction for this feature. <br> <b>Error code</b> - " + error.code;
      }else if(error.code == 2){
        msg = "Location is unavailable. <br> <b>Error code</b> - " + error.code;
      }else{
        msg = "enabled to access your loaction.  <br> <b>Error code</b> - " + error.code;
      }
      Swal.fire({
        type: 'error',
        position: 'center',
        html: msg + "<br> <a class='btn btn-link' href=''>how to enabled loaction</a>",
        showConfirmButton: false,
        timer: 6000
      });
    };

    if (navigator.geolocation) {

      navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);

    } else {
      console.log("Geolocation is not supported by this browser.");
    }


    $('form[name="logform"]').on('submit', function(event) {
        event.preventDefault();
        var form = $(this).serializeArray();
        var formdata = new FormData();
        $.each(form, function(index, value){
          formdata.append(value.name, value.value);
        });
        if (typeof location.latitude !== 'undefined' && typeof location.latitude !== 'undefined') {
          formdata.append('latitude', location.latitude);
          formdata.append('longitude', location.longitude);
        }else{
          var error = {
            'code': 0
          };
          geoError(error);
        }
        var member_id = $('input[name="member_id"]');
        var password = $('input[name="password"]');
        if(!member_id.val()){
          member_id.parent().parent().prepend('<p class="text-danger">Mamber Id must be entered</p>');
        }else if(!password.val()){
          password.parent().parent().prepend('<p class="text-danger">Password must be entered</p>');
        } else {
          $('p.text-danger').remove();
          $.ajax({
            url: '<?php echo base_url("login"); ?>',
            processData: false,
            contentType: false,
            type: 'POST',
            data: formdata,
            dataType: 'json',
            beforeSend: function(){
              $('button[type="submit"]').attr('disabled', true).html('Login...');
            },
            success: function(data) {
                if (data.loginStatus) {
                $('button[type="submit"]').parent().prepend('<p class="text-success">Welcome! Back '+data.uname+'.</p>');
                $('form[name="logform"]')[0].submit();
              }else {
                $('button[type="submit"]').attr('disabled', false).html('Sign In');
                $('button[type="submit"]').parent().prepend('<p class="text-danger">Invalide Member-Id or Password</p>');
              }
            }
          });
        }

    });

    $('#forget').click(function() {

      $.ajax({
        url: '<?php echo base_url('forget'); ?>',
        type: 'Get',

        success: function(data) {
          $('#varify-port').html(data)

        },

      })
    });
    $(document).on("click", "#get_user", function() {
      var id = $('#userid').val();
      console.log(id);
      if (id != "") {
        $.ajax({
          url: '<?php echo base_url('auth/get_data'); ?>',
          type: 'POST',
          data: {
            id: id,
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

          },
          success: function(data) {
            if (data == 0) {
              Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: "Customer Id/User Id not found",
                showConfirmButton: false,
                timer: 3000
              });


            } else {
              var html = $('#verify').html();
              $('#varify-port').html(html);
              $('#mobile').val(data)

            }

          },

        })
      } else {
        Swal.fire({
          position: 'top-end',
          icon: 'info',
          title: "Please enter your Customer Id/User Id",
          showConfirmButton: false,
          timer: 3000
        });
      }

    });
    $(document).on("click", "#verify_otp", function() {
      var code = $('#otp').val();
      var mobile = $('#mobile').val();

      if (code != "") {
        $.ajax({
          url: '<?php echo base_url('verify_otp'); ?>',
          type: 'POST',
          data: {
            code: code,
            mobile: mobile,
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

          },
          success: function(data) {
            if (data == 1) {
              var html = $('#change_pass').html();
              console.log(html);
              $('#varify-port').html(html);
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
      } else {
        Swal.fire({
          position: 'top-end',
          icon: 'info',
          title: "Please enter your OTP",
          showConfirmButton: false,
          timer: 3000
        });
      }

    });
  });


  $(document).ready(function(){
    // ON form is submit event
    $('#logon').on('submit', function(){
      event.preventDefault();
      var email = $('input[name="mamber_id"]');
      var password = $('input[name="password"]');
      if(!email.val()){
        email.css({'border': '1px solid #d44d4d'}).parent().append('<p class="text-danger">Mamber Id must be entered</p>');
      }else if(!password.val()){
        password.css({'border': '1px solid #d44d4d'}).parent().append('<p class="text-danger">Password must be entered</p>');
      }else{
        email.css({'border': '1px solid #e9ecef'});
        password.css({'border': '1px solid #e9ecef'});
        $('p.text-danger').remove();
        var form = $(this).serialize();
        $.ajax({
          url: 'http://vitetransact.in/api/login',
          type: 'POST',
          data: form,
          beforeSend: function(){
            $('button[type="submit"]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
          },
          success: function(data) {
            if (data.is_login) {
              $('button[type="submit"]').parent().prepend('<p class="text-success">Welcome! Back '+data.uname+'.</p>');
              $('#logon')[0].submit();
            }else {
              $('button[type="submit"]').attr('disabled', false).html('Sign In');
              $('button[type="submit"]').parent().prepend('<p class="text-danger">Invalide Username or Password</p>');
            }
          }
        });
      }
    });

  });
</script>

</html>
