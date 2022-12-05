<div class="row">

    <?php if($coupon_amount){ ?>

        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"> Pancard Coupon Buy </h3>
                        </div>
                        <div class="col-4 text-right">

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="post" action="<?php echo base_url() ?>pancard/pancard/coupon_buy">
						
                        <h3 style="color: red;"> * Electronic Coupon / Physical Coupon  Price is  <?php foreach($coupon_amount as $value) { echo $value['pan_coupon_amount']."/"; } ?> </h3>
						<div class="row">
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Coupon Type *</label>
                                    <select type="text" id="type" class="form-control" name="type" required>
                                            <option value="">Select Coupon Type</option>
                                            <option value="pcoupon">Physical Coupon</option>
                                            <option value="ecoupon">Electronic Coupon</option>
                                        </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Coupon Quantity *</label>
                                    <input type="number" name="coupon_qty" class="form-control" placeholder="Coupon Qty." id="coupon_qty" required>
                                </div>
                            </div>

							</div>
                            
                            <div class="form-group d-flex justify-content-center">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" >Amount</label>
                                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Total Amount"  readonly>
                                    </div>
                                </div>
                            </div>    

							<div class="form-group d-flex justify-content-center">


								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
								<button type="submit" id="submit" class="btn btn-success">Buy</button>
							</div>
						
						</div>	
					</form>
                </div>


            </div>
        </div>
     
    <?php }else{ ?>   

        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Pancard Coupon</h3>
                        </div>
                        <div class="col-4 text-right">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h1 style="text-align: center;"> Please Connect Admin </h1>
                </div>
            </div>
        </div>

    <?php } ?>    
        
</div>

<script type="text/javascript">
        
        $('button[type="submit"]').attr('disabled', 'true');
            
            $('#widthdraw_bal').submit(function(){
                $(this).find('button[type="submit"]').prop('disabled', true);
            });
        
        $("#coupon_qty,#type").on('change', function() {
                
                var qty = $('#coupon_qty').val(); 
                var data = {
                              "type": $('#type').val(),
                              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            };
                            
                var userId = <?php echo $this->session->userdata('user_id') ?>;
                var balance = {
                  "search": userId,
                  '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                };
                $.ajax({
                  type: "GET",
                  url: "<?php echo base_url(); ?>wallet/wallet/get_balance",
                  cache: false,
                  dataType: "json",
                  data: balance,
                  success: function(response) {            
                            
                        $.ajax({
                          type: "GET",
                          url: "<?php echo base_url(); ?>pancard/pancard/get_amount",
                          cache: false,
                          dataType: "json",
                          data: data,
                          success: function(data) {
                              
                            var total = parseFloat(qty) * parseFloat(data);
                            
                            $('#amount').val(total)
                            
                            if (total > response ) {
                                
                              $('#move_id').css('border', 'solid 1px red');
                              $('button[type="submit"]').attr('disabled', 'true');
                            } else {
                                
                              $('button[type="submit"]').removeAttr('disabled');
                              $('#move_id').css('border', 'solid 1px #d2d6de');
                            }
                              
                          }
                          
                        });
                        
                  }
                });
        });

    
</script>