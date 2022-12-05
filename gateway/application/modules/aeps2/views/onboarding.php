
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-small text-muted mb-4">Onboarding</h4>
                    </div>
                </div>
            </div>
           
           <div class="card-body">
                <form  method="post" autocomplete="off">
                    
                    <div class="pl-lg-4">

                        <div class="row">
                            <div class="col-sm-2" "="">

                                <p style="padding:15px;"> Merchant ID <span style="color:red;"> * </span> </p>
                            </div>
    
                            <div class="col-sm-3" "="">
                                <p style="padding:10px;">
                                    <input name="merchant" type="text" class="form-control valid" value="<?php echo $this->session->userdata('member_id') ?>" aria-invalid="false" readonly>
                                </p>
                            </div>
                            
                               
                        <div class="col-sm-2" "="">
                            <p style="padding:15px;"> Name <span style="color:red;"> * </span> </p>
                        </div>
                        <div class="col-sm-3" "="">
                            <p style="padding:10px;">
                                <input name="name" type="text" class="form-control">

                            </p>
                        </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Email <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-3">
                                <p "="">
                                </p>
                                <div class="checkbox">
                                    <input name="email" type="email"  id="" class="form-control">
                                </div>
                                <p></p>
                            </div>
                            
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Mobile Number <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-3">
                                <p "="">
                                </p>
                                <div class="checkbox">
                                    <input name="number" type="number" id="" class="form-control">
                                </div>
                                <p></p>
                            </div>
                        </div>

                            <div class="text-center">
                               
                                <input type="submit" name="" id="submit" value="Submit" class="btn btn-primary">
                                <input type="reset" name="" value="Reset" class="btn btn-danger">
                                
                            </div>

                    </div>
                </form>
            </div>
           
        </div>
        
        <!--<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-bulma@4/dark.css" rel="stylesheet">-->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
        
        <script>
            
                var csrfName = '<?php echo $this->security->get_csrf_token_name();?>',
                csrfHash = '<?php echo $this->security->get_csrf_hash();?>';
            $(document).on('click', '#submit', function(event){
                      var merchant = $('input[name="merchant"]');
                      var email = $('input[name="email"]');
                      var number = $('input[name="number"]');
                      var name = $('input[name="name"]');
            
                     var dataJson = { [csrfName]: csrfHash, merchant: merchant.val(), email: email.val() , number: number.val() , name: name.val() };
            
                    $.ajax({
                        url : "<?php echo base_url('aeps/submitonboarding'); ?>",
                        type: 'post',
                        data: dataJson,  
                        dataType: "json",
                        beforeSend: function() {
                         '<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>'
                        },
                        success : function(data)
                        {   
                            console.log(data.response_code)
                            if(data.status == false){
                                
                                  Swal.fire({
                                      type: 'error',
                                      text: data.message
                                     }); 
                                
                            }else{
                                
                                 Swal.fire({
                                  type: 'success',
                                  text: "Done click on Ok!"
                                });
                                
                                $('.swal2-confirm').click(function(){
                                        window.location.href = data.redirecturl;
                                  });
                                
                            }
                        }  
                    });
            });
            
            
        </script>