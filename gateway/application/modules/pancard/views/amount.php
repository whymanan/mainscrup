
        <div class="row">
            
            <div class="col-xl-12 order-xl-12">
                
                <div class="card">
                    
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"> Pancard Coupon Amount Set </h3>
                            </div>
                            <div class="col-4 text-right">
    
                            </div>
                        </div>
                    </div>
    
                    <div class="card-body">
                        
                        <form method="post" action="" class="ng-pristine ng-valid">
                            
    						<div class="row">
    						    
    							<div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Coupon Type *</label>
                                        <select type="text" id="type" class="form-control" name="type" required="">
                                                <option value="">Select Coupon Type</option>
                                                <option value="pcoupon">Physical Coupon</option>
                                                <option value="ecoupon">Electronic Coupon</option>
                                            </select>
                                    </div>
                                </div>
    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Coupon Amount *</label>
                                        <input type="number" name="coupon_amount" class="form-control" placeholder="Enter Coupon Amount" required="">
                                    </div>
                                </div>
    
    						</div>  
    
    							<div class="form-group d-flex justify-content-center">
    								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
    								<button type="submit" id="submit" class="btn btn-success" >Submit</button>
    							</div>
    						
    						</form>
    					
                    </div>
    
    
                </div>
                
            </div>
            
        </div>    
        
        
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="list">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Coupon type</th>
                                <th scope="col">Coupon Price</th>
                                <th scope="col">Date</th>
                            </tr>
                            <tr>
                                <?php if(isset($data)){ foreach($data as $value){ ?>
                                    <th scope="col"> <?php echo '<button type="button" class="btn btn-sm btn-primary" data-placement="bottom" onclick="Delete('.$value['id'].')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button>'  ?> </th>
                                    <th scope="col"> <?php if($value['pan_coupon_type'] == 2)echo "Electronic Coupon"; else echo "Physical Coupon"  ?>  </th>
                                    <th scope="col"> <?php echo $value['pan_coupon_amount'] ?> </th>
                                    <th scope="col"><?php echo $value['created'] ?></th>
                                </tr>
                                <?php } } ?>
                                
                        </thead>
                    </table>
            </div>
        </div>
    </div>
    
    
    <script>

      function Delete(id) {
    
        var sureDel = confirm("Are you sure want to delete");
        if (sureDel == true) {
          $.ajax({
            type: "GET",
            url: "<?php echo base_url('pancard/pancard/deleteAmount/')?>" + id,
    
            success: function(response) {
              if (response == 1) {
    
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Deleted Successfully',
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
                location.reload()
            }
          });
    
        }
    
      }
      
    </script>  

