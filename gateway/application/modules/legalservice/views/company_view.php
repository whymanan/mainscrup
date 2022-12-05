<div class="header pb-6 d-flex align-items-center"
        style="min-height: 500px; background-image: url(../../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
        <!-- Mask -->

        <span class="mask bg-gradient-default opacity-8"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-7 col-md-10">
                    <h1 class="display-2 text-white">Hello</h1>
                    <p class="text-white mt-0 mb-5">This is your Company Registration page. You can see the progress you've
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
                                <h3 class="mb-0">Legal </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Company information</h6>

                        <div class="pl-lg-4">

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">State</label>
                                        <input type="text" class="form-control"
                                            value="<?php  echo $gst_details->state ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nature of Property</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $gst_details->nature_of_properties ?>" readonly>
                                    </div>
                                </div>

                            </div>


                            <div class="row">

                               
                            <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Business Address</label>
                                        <input type="text" class="form-control" required
                                            value="<?php echo $gst_details->business_address ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nature Of Business(5 Points)</label>
                                        <input type="textarea" class="form-control" required
                                            value="<?php echo $gst_details->nature_of_business ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="container">
                                        <h5 class="h3 mb-0">Electricity Bill</h5>
                                        <hr>
                                        <div class="container">
                                            <?php if(isset($gst_details->electricity_bill)) { ?>
                                            <img id="blah3"
                                                src="<?php echo base_url('/uploads/gst/documents/'). $gst_details->electricity_bill;?>"
                                                alt="your image" width="150px" />
                                            <?php } else { echo  '<p class=" mb-0">Electricity Bill Not Uploaded</p>'; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php $i=1; foreach($director as $value){ ?>
                        <hr class="my-4" />
                        <!-- Address -->
                        <h6 class="heading-small text-muted mb-4">Director<?php echo $i;?> Details</h6>

                        <div class="pl-lg-4">
                            <div class="row">

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Name of Person</label>
                                        <input type="text" name="adharcard" class="form-control" required
                                            value="<?php echo $value['name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Mobile Number</label>
                                        <input type="text" name="pancard" class="form-control uppercase"
                                            value="<?php echo $value['mobile_number']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input type="text" name="organization_name" class="form-control"
                                            value="<?php echo $value['email']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Date of Birth</label>
                                        <input type="text" name="gst_no" class="form-control uppercase"
                                            value="<?php  echo $value['dob']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Father Name</label>
                                        <input type="text" name="gst_no" class="form-control uppercase"
                                            value="<?php  echo $value['father_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Adhar Number</label>
                                        <input type="text" name="gst_no" class="form-control uppercase"
                                            value="<?php  echo $value['adhar_number']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Pan Number</label>
                                        <input type="text" name="gst_no" class="form-control uppercase"
                                            value="<?php  echo $value['pan_number']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Address</label>
                                        <input type="text" name="gst_no" class="form-control uppercase"
                                            value="<?php  echo $value['address']; ?>">
                                    </div>
                                </div>
                            </div>
                            <h6 class="heading-small text-muted mb-4">Document</h6>
                            <hr />
                            <div class="row">
                                <?php if(isset($director_doc[$i-1]))
                                 { foreach($director_doc[$i-1] as $value1){?>
                                <div class="col-lg-4">
                                    <div class="container">
                                        <h5 class="h3 mb-0"><?php echo $value1['type'];?></h5>
                                        <hr>
                                        <div class="container">
                                            <?php if(isset($value1['image_name'])){ 
                                if($value1['type']=='photo')
                                {
                                    $url=base_url('/uploads/gst/').$value1['type']."/".$value1['image_name'];
                                }
                                else
                                {
                                    $url=base_url('/uploads/gst/').$value1['type']."/".$value1['image_name'];
                                }
                                ?>

                                            <img src="<?php echo $url;?>" alt="your image" width="150px" />
                                            <?php } else { echo  '<p class=" mb-0">Not Uploaded</p>'; } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } }?>
                            </div>
                            <?php $i++; }?>
                        </div>
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
                        <form method="post" action="<?php echo base_url('legalservice/LegalController/gst_reject')?>">
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Notation(why reject):</label>
                                <input type="text" name="gst_massage" value="" placeholder="why reject"
                                    class="form-control" required>
                                <input type="hidden" name="gst_id" value="<?php echo $gst_details->gst_id;?> "
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

    <!-- <modal for accept> -->
    <div class="col-md-4">
        <div class="modal fade" id="exampleModalaccept" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do PAYMENT TO Vitefintch</h5>
                    </div>
                    <div class="modal-body">

                        <div class="card">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">GST Refernce no</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0" id="gst_refrence_id"><?php echo $gst_details->referance_number;?></p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">Firm Name</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $gst_details->firm_name?></p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">Company Type</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $gst_details->company_type?></p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">GST Registration Amount</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">₹250</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">GST 18% Charge</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo 250*(18/100);?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">Total</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0" id="total_amount"><?php echo 250*(18/100)+250;?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">Email</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                               <input type="email" class="form-control" id="email" placeholder="enter your email ID">
                                               <input type="hidden" class="form-control" id="gst_id" value="<?php echo $gst_details->gst_id?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="text-align:center">
                         <button class="btn btn-primary" onclick="Approve()">PAY</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    //payment gateway
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