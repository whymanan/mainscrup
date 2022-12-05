<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Upload Documents</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">

                    <div class="row">
                        <div class="col-lg-4 bg-info">
                            <form method="POST" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label class="form-control-label">Profile</label>
                                    <input type="file" class="form-control finput" data-id="#blah1" name="image_file" multiple="true" accept="image/*" onchange="readURL(this);">
                                    <input type="hidden" name="type" value="photo"><br>
                                    <div class="container text-center">
                                        <img id="blah1" src="//www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/" alt="your image" width="150px" />
                                    </div>

                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                    <button type="submit" class="btn btn-primary my-4">Upload</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 bg-secondary">
                            <form method="POST" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label class="form-control-label">Adhar</label>
                                    <input type="file" class="form-control finput" data-id="#blah2" name="image_file" multiple="true" accept="image/*" onchange="readURL(this);">
                                    <input type="hidden" name="type" value="adhar"><br>
                                    <div class="container text-center">
                                        <img id="blah2" src="//www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/" alt="your image" width="150px" />
                                    </div>

                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                    <button type="submit" class="btn btn-primary my-4">Upload</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 bg-info">
                            <form action="" method="POST" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label class="form-control-label">Pan</label>
                                    <input type="file" class="form-control finput" data-id="#blah3" name="image_file" multiple="true" accept="image/*" onchange="readURL(this);">
                                    <input type="hidden" name="type" value="pan"><br>
                                    <div class="container text-center">
                                        <img id="blah3" src="//www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/" alt="your image" width="150px" />
                                    </div>

                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                    <button type="submit" class="btn btn-primary my-4">Upload</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
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
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    if ($('.finput').val() == '') {
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
                                    $('#blah').attr('src', '//www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/');
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
            });
        </script>