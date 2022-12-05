<div class="row">



    <div class="col-xl-12 order-xl-1">

        <div class="card">

            <div class="card-header">

                <div class="row align-items-center">

                    <div class="col-8">

                        <h3 class="mb-0">Add KYC</h3>

                    </div>

                    <div class="col-4 text-right">



                    </div>

                </div>

            </div>

            <div class="card-body">

                <form id="User">
                    <!-- 
                    <h4 class="heading-small text-muted mb-4">User information</h4> -->

                    <div class="pl-lg-4">



                        <div class="row">

                            <div class="col-lg-6">

                                <div class="form-group">

                                    <label class="form-control-label">Select Member Id

                                        <button type="button" class="btn btn-sm btn-link" data-toggle="tooltip"
                                            data-placement="top" title="Select the parent of this user">

                                            <i class="ni ni-bulb-61"></i>

                                        </button>

                                    </label>

                                    <select name="vendor" id="vendor" class="form-control" required>

                                        <option value="<?php echo $this->session->userdata('user_id')?>">
                                            <?php echo $this->session->userdata('user_name')?></option>

                                    </select>

                                </div>

                            </div>

                            <div class="col-lg-6 d-flex justify-content-center">

                                <div class="form-group">

                                    <button type="submit" class="btn btn-success" id="addkyc" >Add
                                        Kyc</button>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<section id="AddNewKyc">
    
</section>

 <script type="text/javascript">

    function readURL(input) {
        console.log(input);
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                img = $(input).attr('data-id');

                $(img).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {

        $('.upload').on('submit', function(e) {
            e.preventDefault();
            if ($(this).find('.finput').val() == '') {
                alert("Please Select the File");
            } else {
                $.ajax({
                    url: "<?php  echo base_url('users/file_upload'); ?>",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(res) {
                        console.log(res.success);
                        if (res.success == true) {
                            $('#blah').attr('src',
                                '//www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/'
                            );
                            $(this).find('input[type="submit"]').attr("disable.true");
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: res.msg,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (res.success == false) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: res.msg,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }

                    }
                });
            }
        });
        $('#bank').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php  echo  base_url('users/add_bank'); ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(res) {
                    console.log(res);
                    if (res == 1) {
                        $('#card_bank').remove();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Bank Added Successfully !!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Something went wrong !!",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }

                }
            });

        });
    });
    </script>
<!-- chandan verma // edit -->
<script>
   
$(document).ready(function(){
    
$("#addkyc").on('click', function(){
  var id = $("#vendor").val();
    console.log(id);
   
   
      $.ajax({
                url: "<?php  echo  base_url('kyc/kyc/get_user_detail'); ?>",
                method: "POST",
               data: {

            'id': id,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
          },
                
                success: function(res) {
                    if(res!=1){
                         $("#AddNewKyc").html(res) 
                        $(".user_id").val(id);
                    }
                 
                        else{
                            
                            $("#AddNewKyc").html("<blockquote><h2>Kyc Already Uploaded... Verify Kyc</h2></blockquote>")  
                        }
                }
            });

        });
       

});

</script>