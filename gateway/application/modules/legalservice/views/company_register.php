    <div class="container">

        <form method="post" id="filter" action="<?php echo base_url('legalservice/LegalController/cmp_reg'); ?>" enctype="multipart/form-data"> 
            <div class="row">
                <div class="col-xl-12 order-xl-1 card card-body">
                    <div class="card">

                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Company Registation</h3>
                                </div>
                            </div>
                        </div>

                            <div class="card-body">
                                 <div class="row">
                                       <div class="col-4">
                                     <div class="">
                                        <label class="form-control-label" for="example3cols1Input">State
                                        </label>
                                      <select name="state" id="state" class="form-control" required>
                                          <option value="">Select State</option>
                                          <?php foreach($state as $value){?>
                                          <option value="<?php echo $value['state']; ?>"><?php echo $value['state']; ?></option>
                                          <?php }?>
                                      </select>
                                      <input class="form-control" id="amount" name="amount" value='' readonly/>
                                     </div>
                                    </div>
                                    <div class="col-4">
                                     <div class="">
                                        <label class="form-control-label" for="example3cols1Input">Address's Company
                                        </label>
                                       <input type="text" class="form-control" name="Address_company" placeholder="" required>
                                     </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="">
                                          <label class="form-control-label" for="example3cols1Input">Electrict Bill Of company
                                          </label>
                                         <input type="file" class="form-control" name="electrict_bill" placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-lg-4">
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row"> 
                <div class="col-lg-12 card card-body"> 
                    <span>Director 1</span>

                        <div class="row">

                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <input type="hidden" name="service_type" value="cmp_registation">
                            <input type="hidden" name="service_id" value="55">
                            <div class="col-sm-4">
                                
                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Name of Person
                                    </label>
                                    <input type="Name" class="form-control" name="name[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Email</label>
                                    <input type="text" class="form-control" name="email[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Date Of Birth
                                    </label>
                                    <input type="date" class="form-control" name="dob[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">PAN NO.
                                    </label>
                                    <input type="text" class="form-control" name="pan_no[]" placeholder="" required>
                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Mobile No.</label>
                                    <input type="text" class="form-control" name="mobile[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Father/Husband Name</label>
                                    <input type="text" class="form-control" name="father_name[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Adhar No.</label>
                                    <input type="text" class="form-control" name="adhar_no[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Adress
                                    </label>
                                    <input type="text" class="form-control" name="address[]" placeholder="" required>
                                </div>


                            </div>


                            <!-- <div class="col-sm-3">
                            
                                <div class="">
                                    <img src="<?php //echo base_url('assets'). '/img/theme/avtar.png'; ?>" class="img-responsive  w-50">
                                </div>

                                <div class="">
                                    <img src="<?php //echo base_url('assets'). '/img/theme/adhar_front.jpg'; ?>" class="img-responsive w-75">
                                </div>

                                <div class="">
                                    <img src="<?php //echo base_url('assets'). '/img/theme/adhar_back.jpg'; ?>" class="img-responsive  w-75">
                                </div>

                                <div class="">
                                    <img src="<?php //echo base_url('assets'). '/img/theme/pan.png'; ?>" class="img-responsive  w-75">
                                </div>

                                <div class="">
                                    <img src="<?php //echo base_url('assets'). '/img/theme/pan.png'; ?>" class="img-responsive  w-75">
                                </div> 

                            </div>  -->

                            <div class="col-sm-4">
                        
                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input"> Photo</label>
                                    <input type="file" class="form-control" accept="image/*" name="director_photo[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Adhar Front</label>
                                    <input type="file" class="form-control" accept="image/*" name="adhar_front[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Adhar Back</label>
                                    <input type="file" class="form-control" accept="image/*" name="adhar_back[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">PAN</label>
                                    <input type="file" class="form-control" accept="image/*" name="pan_file[]" placeholder="" required>
                                </div>

                                <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Bank Statement</label>
                                    <input type="file" class="form-control" accept="image/*" name="bank_statement[]" placeholder="" required>
                                </div>
                                  <div class="">
                                    <label class="form-control-label" for="example3cols1Input">Voter Id/Driving License</label>
                                    <input type="file" class="form-control" accept="image/*" name="Voter_License[]" placeholder="" required>
                                </div>
                            
                            </div>
                    
                        </div>

                        <hr>
                        
                        <div class="container" >
                        
                            <div class='element' id='div_1'>
                            </div>

                        </div>
                        
                        <div class="row justify-content-center">   
                            
                            <span class='btn btn-primary add'>Add New director</span>
                        
                        </div>
                        
                        <div class="row">
                            <span style="display:none;" id="spanmessage"></span>
                        </div>
                        <hr>
                           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                           <input type="hidden" name="MAX_FILE_SIZE" value="500" />
                           <div class="row justify-content-center">   
                             <button type="submit" class="btn btn-success pull-right" >Submit</button>
                           </div>
                </div>
            </div>
        </form>    
        
    </div>
    <script>
    $(document).ready(function(){


        var $gstFormSubmit = $('#gstFormSubmit');

        $(".add").click(function(){

            var total_element = $(".element").length;
            
            var lastid = $(".element:last").attr("id");
            var split_id = lastid.split("_");
            var nextindex = Number(split_id[1]) + 1;

            var max = 5;
            
            if(total_element < max ){
                
                $(".element:last").after("<div class='element' id='div_"+ nextindex +"'></div>");
                            
                $("#div_" + nextindex).append("<br> <div class='row justify-content-center'> <div class='col-sm-4'> <div> <label class=form-control-label' for='example3cols1Input'>Email</label> <input type='text' class='form-control' name='email[]' placeholder=''> </div><div> <label class='form-control-label' for='example3cols1Input'>Name of Person </label> <input type='Name' class='form-control' name='name[]' placeholder=''> </div><div> <label class='form-control-label' for='example3cols1Input'>Date Of Birth </label> <input type='date' class='form-control' name='dob[]' placeholder=''> </div><div> <label class='form-control-label' for='example3cols1Input'>PAN NO. </label> <input type='text' class='form-control' name='pan_no[]' placeholder=''> </div></div><div class='col-sm-4'> <div> <label class='form-control-label'>Mobile No. </label> <input type='text' class='form-control' name='mobile[]' placeholder=''> </div><div> <label class='form-control-label' >Father/Husband Name</label> <input type='text' class='form-control' name='father_name[]' placeholder=''> </div><div> <label class='form-control-label'>Adhar No.</label> <input type='text' class='form-control' name='adhar_no[]' placeholder=''> </div><div> <label class='form-control-label'>Adress </label> <input type='text' class='form-control' name='address[]' placeholder='Address'> </div></div><div class='col-sm-4'> <div> <label class='form-control-label' for='example3cols1Input'> Photo</label> <input type='file' class='form-control' accept='image/*' name='director_photo[]' placeholder=''> </div><div> <label class='form-control-label' >Adhar Front</label> <input type='file' class='form-control' name='adhar_front[]' accept='image/*' placeholder=''> </div><div > <label class='form-control-label' for='example3cols1Input'>Adhar Back</label> <input type='file' class='form-control' name='adhar_back[]' accept='image/*' placeholder=''> </div><div> <label class='form-control-label' >PAN</label> <input type='file' class='form-control' name='pan_file[]' accept='image/*' placeholder=''> </div> <div> <label class='form-control-label' >Bank Statement </label> <input type='file' class='form-control' name='bank_statement[]' accept='image/*' placeholder=''> </div><div> <label class='form-control-label' >Voter Id/Driving License</label> <input type='file' class='form-control' name='Voter_License[]' accept='image/*' placeholder=''> </div></div></div><br>");
                
                $("#div_" + nextindex).append("<button id='remove_" + nextindex + "' class='remove btn btn-danger pull-right' >X</button>");
            }
                        
        });


        $('.container').on('click','.remove',function(){
                    
            var id = this.id;
            var split_id = id.split("_");
            var deleteindex = split_id[1];

            // Remove <div> with id
            $("#div_" + deleteindex).remove();
        });  
      $('#state').change(function()
      {
        var data=$(this).val();
        $.ajax({
                type: 'GET',
                url: '<?php echo base_url('legalservice/LegalController/company_registration_price'); ?>',
                data: {'state':data},
                dataType: 'json',
                success: function(data) {
                  $('#amount').val('₹ '+data)
                },
            
            });
      })
        // $("#filter").on('submit', function(e){
        //     e.preventDefault();

        //     $.ajax({
        //         type: 'POST',
        //         url: '<?php echo base_url('legalservice/Legalcontroller/submit_gst'); ?>',
        //         data: new FormData(this),
        //         dataType: 'json',
        //         contentType: false,
        //         cache: false,
        //         processData:false,
        //         beforeSend: function(){
        //           $gstFormSubmit.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
        //         },
        //         success: function(data) {
        //           console.log("hdhfihd")
        //             $gstFormSubmit.html(data);
        //         },
            
        //     });

        // });

    });

</script>