    <div class="header pb-6 d-flex align-items-center"
        style="min-height: 500px; background-image: url(../../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
        <!-- Mask -->

        <span class="mask bg-gradient-default opacity-8"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-7 col-md-10">
                    <h1 class="display-2 text-white">Hello</h1>
                    <p class="text-white mt-0 mb-5">This is your GST Return page. You can see the progress you've
                        made with your work and manage your task</p>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalaccept">
                        Accept
                    </button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalMessage">
                        Reject
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- Page content -->

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Gst </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Gst Return</h6>

                        <div class="pl-lg-4">

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">GST NO</label>
                                        <input type="text" class="form-control"
                                            value="<?php  echo $gst_details->gst_no ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">User name</label>
                                        <input type="text" class="form-control"
                                            value="<?php if(isset($gst_details->name)){echo $gst_details->name;} ?>" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Password</label>
                                        <input type="text" class="form-control"
                                            value="<?php if(isset($gst_details->password)){echo $gst_details->password; } ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Mobile</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $gst_details->mobile ?>" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Refrence ID</label>
                                        <input type="text" class="form-control" required
                                            value="<?php echo $gst_details->referance_number ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Notation</label>
                                        <input type="textarea" class="form-control" required
                                            value="<?php echo $gst_details->notation ?>" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                       
                        <?php $i=1; foreach($image as $value){ ?>
                           
                         <hr class="my-4" />
                         <!-- Address -->
                         <h6 class="heading-small text-muted mb-4">Document Details</h6>

                         <div class="pl-lg-4">
                             <div class="row">

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Purchase</label>
                                        <img height="200px" src="<?php  echo base_url('/uploads/gst/gst_return_doc/').$value['Purchase'];?> ">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Sale</label>
                                       <img height="200px" src="<?php  echo base_url('/uploads/gst/gst_return_doc/').$value['Sale'];?> ">
                                    </div>
                                </div>
                            </div>
                         </div>
                         <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <modal for reject> -->
    <div class="col-md-4">
        <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reject GST</h5>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="<?php echo base_url('legalservice/LegalController/gst_return_reject')?>">
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Notation(why reject):</label>
                                <input type="text" name="gst_massage" value="" placeholder="why reject"
                                    class="form-control" required>
                                <input type="hidden" name="gst_id" value="<?php echo $gst_details->id;?> "
                                    class="form-control">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>"
                                    value="<?php echo $this->security->get_csrf_hash();?>">
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-danger" value="Reject">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <!--For Production-->
<script id="context" type="text/javascript"
src="https://payments.open.money/layer"></script> 

<script>
    function Approve() {
        var total_amount=$('#total_amount').text();
        var email=$('#email').val();
        var sureDel = confirm("do you ready for pay for GST Registation");
        if (sureDel == true) {
            var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
            var amount = total_amount;
              

            if(numberRegex.test(amount) && email ) {
                
                var csrfName = '<?php echo $this->security->get_csrf_token_name();?>',
                csrfHash = '<?php echo $this->security->get_csrf_hash();?>';
                var data = { [csrfName]: csrfHash , amount: amount,email:email};
            
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('legal/gst/pay')?>",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                    if (response) {
                       console.log(response)
                       triggerLayer(response.id,'b6e70c40-72d9-11ec-b941-e9d4b8eaecc5',response);
                        
                    } else {
                        Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Something went wrong',
                        showConfirmButton: false,
                        timer: 3500
                        });
                    }
                    //  location.reload()
                    }
                });
            }else{
                        Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Please Enter Valid Details',
                        showConfirmButton: false,
                        timer: 3500
                        });
                }
    
        }
    
    }


    //You can bind the Layer.checkout initialization script to a button click event.
    //Binding inside a click event open Layer payment page on click of a button
        // var result = {token: '<?php //echo $result->id ?>' , access: '<?php //echo $accessKey  ?>' };
    function triggerLayer($payment_token,$accesskey,$data) {
        console.log($payment_token)
        console.log($accesskey)
        var gst_refrence_id=$('#gst_refrence_id').text();
        var gst_id=$('#gst_id').val();
        Layer.checkout({
            token: $payment_token,
            accesskey: $accesskey,
            theme: {
                logo : "https://open-logo.png",
                color: "#3d9080",
                error_color : "#ff2b2b"
              }
        },
        function(response) {
            console.log(response);
            if (response.status == "captured") {
            
                            var csrfName = '<?php echo $this->security->get_csrf_token_name();?>',
                            csrfHash = '<?php echo $this->security->get_csrf_hash();?>';
                            var data = { [csrfName]: csrfHash , amount: $data.amount, refrence: $data.mtx,status:response.status,response:response,gst_refrence_id:gst_refrence_id,gst_id:gst_id};
                        
                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('legal/gst/pay/save')?>",
                                data: data,
                                dataType: "json",
                                success: function(response) {
                                if (response == 0) {
                                   console.log(response);
                                   Swal.fire({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Request Successfully Sent',
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
                                //   location.reload()
                                }
                            });
                            
                        
            } else if (response.status == "created") {


            } else if (response.status == "pending") {


            } else if (response.status == "failed") {


            } else if (response.status == "cancelled") {
            
                     var csrfName = '<?php echo $this->security->get_csrf_token_name();?>',
                            csrfHash = '<?php echo $this->security->get_csrf_hash();?>';
                            var data = { [csrfName]: csrfHash , amount: $data.amount, refrence: $data.mtx,status:response.status};
                        
                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('legal/gst/pay/save')?>",
                                data: data,
                                dataType: "json",
                                success: function(response) {
                                    console.log(response);
                                    \                            if (response == 1) {
                                   Swal.fire({
                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Something went wrong',
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
                                //  location.reload()
                                }
                            });
                

            }
        },
        function(err) {
        //integration errors
            console.log(err)
        }
        );
    }
</script>