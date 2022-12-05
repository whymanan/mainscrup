<div class="row">
 <div class="col-xl-4 ">
      </div>
    <div class="col-xl-4 ">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h3 class="mb-0">Verify</h3>
                    </div>
                    
                </div>
            </div>
            <div class="card-body">
                        <form>
                                    <div class="form-group"><label class="form-control-label">Enter OTP send toi your Mobile</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni fa-university"></i></span>
                                            </div>
                                            <input class="form-control" name="otp" type="text" required>
                                           
                                        </div>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >

                                         <input type="submit" name="submit" class="form-control btn btn-primary">
                                    </div>
                        </form>                       
                                      
            </div>
        </div>
         </div>
        </div>
        <script type="text/javascript">
           

            $(document).ready(function() {
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    if ($(this).find('.finput').val() == '') {
                        alert("Please Select the File");
                    } else {
                        $.ajax({
                            url: "<?php echo base_url('wallet/start_wallet'); ?>",
                            method: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function(res) {
                                console.log(res.success);
                                if (res.success == true) {
                                    
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: res.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    window.location.href = "<?php echo base_url('wallet') ?> ";
                                } else if (res.success == false) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: res.msg,
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    window.location.href = "<?php echo base_url('wallet') ?> ";
                                }

                            }
                        });
                    }
                });
            });
        </script>