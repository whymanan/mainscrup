<div class="row">
  <?php if (!isset($bank[0])) { ?>
    <div class="col-xl-12 order-xl-1">
        <div class="card " id="card_bank">
            <div class="card-header" >
                <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <h3>Enter Details</h3>
                    </div>
                </div>
            </div>
            <!-- Card body -->
            <div class="card-body">

                <div class="mt-4">
                    <form name="kyc_validate" class="form-primary" id="bank">
                      <h4 class="heading-small text-muted mb-4">Business information</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Aadhar Number/ आधार नंबर</label>
                                    <input type="text" name="adharcard" class="form-control" required
                                        placeholder="xxxx-xxxx-xxxx-xxxx" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">PAN Number/ पैन नंबर</label>
                                    <input type="text" name="pancard" class="form-control uppercase"
                                        placeholder="xxxxx-xxxx-x" required value="">
                                    <span id="pan_error" style="font-size: 10px;color: red;"></span>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Shop Name/ दुकान का नाम</label>
                                    <input type="text" name="organization_name" class="form-control"
                                        placeholder="XYZ pvt. ltd." value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">GST Number</label>
                                    <input type="text" name="gst_no" class="form-control uppercase"
                                        placeholder="xxxxx-xxxx-x" value="">
                                </div>
                            </div>

                        </div>
                         <h6 class="heading-small text-muted mb-4">Shop Address</h6>
                    <div class="pl-lg-4">


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Full Shop Address</label>
                                    <input name="address" class="form-control" required placeholder="Shop Address"
                                        id="s_address" value="" type="text">
                                </div>
                            </div>
                        </div>
                         <div class="row">
                          <div class="col-lg-3">
                              <div class="form-group">
                                  <label class="form-control-label">Postal code</label>
                                  <input type="number" name="pincode" class="form-control pincode" placeholder="Postal code"
                                      required>
                              </div>
                          </div>
                          <div class="col-lg-3">
                              <div class="form-group">
                                  <label class="form-control-label">Your Area</label>
                                  <select name="area" class="form-control area"  placeholder="Your Area">
                                      <option value="">Select Your Area</option>
                                  </select>
                              </div>
                          </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">State</label>
                                    <select name="states" class="form-control states" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">City</label>
                                    <select name="city" class="form-control cities" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                        <hr class="my-4">
                        <h6 class="heading-small text-muted mb-4">Bank Account Details</h6>
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
                                            type="number" required>
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
                                        <input class="form-control" name="bank_name" placeholder="Bank Name" type="text"
                                            required>
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
                                        <input class="form-control" name="ifsc" placeholder="IFSC Code" type="text" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                  <label class="form-control-label">Phone Number</label>
                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        </div>
                                        <input class="form-control" name="phone" placeholder="Phone Number" type="tel"  required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                            value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden"  name="user_id" class="user_id" value="<?php if(isset($user_id)) echo $user_id;?>">
                        <button type="submit" id="mainsubmit" class="btn btn-success">Save Information</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
</div>
<div class="row">
    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Upload Documents</h3>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">

                    <div class="row">
                        <div class="col-lg-4 ">
                            <form method="POST" enctype="multipart/form-data" class="upload">
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Profile</label>
                                            <input type="file" class="form-control finput" data-id="#blah1"
                                                name="image_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                            <input type="hidden" name="type" value="photo"><br>


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah1"
                                                src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"
                                                alt="your image" width="150px" />
                                        </div>


                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <input type="hidden"
                                                name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                                value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden"  name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="user_id">
                                            <button type="submit"  name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="btn btn-primary my-4 ">Upload</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 ">
                            <form method="POST" enctype="multipart/form-data" class="upload">
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Aadhar Front</label>
                                            <input type="file" class="form-control finput" data-id="#blah2"
                                                name="image_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                            <input type="hidden" name="type" value="adhar_front"><br>


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah2"
                                                src="<?php if(isset($doc[1]['type']) && $doc[1]['type'] == 'adhar_front'){echo base_url('uploads/'). $doc[1]['type'].'/'. $doc[1]['name'] ;}else{ echo base_url('assets'). '/img/theme/adhar_front.jpg'; }?>"
                                                alt="your image" width="150px" />
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <input type="hidden"
                                                name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                                value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden"  name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>"class="user_id">
                                            <button type="submit" name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="btn btn-primary my-4 ">Upload</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 ">
                            <form action="" method="POST" enctype="multipart/form-data" class="upload">
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Aadhar Back</label>
                                            <input type="file" class="form-control finput" data-id="#blah3"
                                                name="image_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                            <input type="hidden" name="type" value="adhar_back"><br>


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah3"
                                                src="<?php  if(isset($doc[2]['type']) && $doc[2]['type'] == 'adhar_back'){echo base_url('uploads/'). $doc[2]['type'].'/'. $doc[2]['name'] ;}else{ echo base_url('assets'). '/img/theme/adhar_back.jpg';} ?>"
                                                alt="your image" width="150px" />
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <input type="hidden"
                                                name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                                value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden"  name="user_id" value="<?php if(isset($user_id)) echo $user_id;?> " class="user_id">
                                            <button type="submit" name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="btn btn-primary my-4 ">Upload</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 order-xl-1">
                            <form action="" method="POST" enctype="multipart/form-data" class="upload">

                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Pan</label>
                                            <input type="file" class="form-control finput" data-id="#blah4"
                                                name="image_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                            <input type="hidden" name="type" value="pan"><br>

                                        </div>
                                    </div>

                                    <div class="card-body">


                                        <div class="container text-center">
                                            <img id="blah4"
                                                src="<?php if (isset($doc[3]['type']) && $doc[3]['type'] == 'pan') {
                                                                                    echo base_url('uploads/') . $doc[3]['type'] . '/' . $doc[3]['name'];
                                                                                } else {
                                                                                    echo base_url('assets').'/img/theme/pan.png';} ?>"
                                                alt="your image" width="150px" />
                                        </div>

                                        <div class="card-footer">
                                            <div class="text-center">
                                                <input type="hidden"
                                                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                                               <input type="hidden"  name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="user_id">
                                                <button type="submit" name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="btn btn-primary my-4">Upload</button>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 order-xl-2">
                            <form action="" method="POST" enctype="multipart/form-data" class="upload">
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Passbook Photo</label>
                                            <input type="file" class="form-control finput" data-id="#blah5"
                                                name="image_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                            <input type="hidden" name="type" value="passbook">


                                        </div>
                                    </div>

                                    <div class="card-body">

                                        <div class="container text-center">
                                            <img id="blah5"
                                                src="<?php if (isset($doc[4]['type']) && $doc[4]['type'] == 'passbook') {
                                                                                        echo base_url('uploads/') . $doc[4]['type'] . '/' . $doc[4]['name'];
                                                                                    } else {
                                                                                        echo base_url('assets').'/img/theme/passbook.jpg'; }?>"
                                                alt="your image" width="150px" />
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <input type="hidden"
                                                name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <input type="hidden"  name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="user_id">
                                            <button type="submit" name="user_id" value="<?php if(isset($user_id)) echo $user_id;?>" class="btn btn-primary my-4">Upload</button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script type="text/javascript">
$('#mainsubmit').hide();
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
                url: "<?php echo base_url('users/file_upload'); ?>",
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
    $("input[name='pancard']").change(function()
    {
        var pan=$(this).val();
        var user_id=$(".user_id").val();
        var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        $.ajax({
            url: "<?php echo base_url('users/pan_verify'); ?>",
            method: "POST",
            data: {'pan':pan,'user_id':'user_id','<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash();?>'},
            success: function(res) {
                if(res=="false")
                {
                 $("#pan_error").html("**Invailid Pancard Number");
                   $('#mainsubmit').hide();
                }
                else
                {
                    $("#pan_error").html("**"+res);
                    $('#mainsubmit').show();
                }
            }
            });
    })
    $('#bank').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url('users/add_bank'); ?>",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function(res) {
        console.log(res);
                if (res==1) {
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
