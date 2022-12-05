<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Edit User</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('users/update'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">User information</h4>
                    <div class="pl-lg-4">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">First name</label>
                                    <input type="text" name="firstname" class="form-control" required placeholder="First name" value="<?php echo $details->first_name ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Last name</label>
                                    <input type="text" name="lastname" class="form-control" required placeholder="Last name" value="<?php echo $details->last_name ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Mobile</label>
                                    <input type="text" name="phone_no1" class="form-control" required placeholder="Mobie Number" value="<?php echo $user->phone ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email address</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo $user->email ?>">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Category</label>
                                    <select name="user_role" class="form-control" required>
                                        
                                        <option value="<?php echo $user->role_id ?>" seleted><?php echo $user->role ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Vendor
                                        <button type="button" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="Select the parent of this user" value="<?php echo $user->role_id ?>">
                                            <i class="ni ni-bulb-61"></i>
                                        </button>
                                    </label>
                                    <select name="vendor" id="vendor2" class="form-control" required >
                                        <option selected value="<?php echo $user->parent ?>"><?php echo $user->parent1 ?></option>
                                        <option value="">Select Vendor</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Deal Amount</label>
                                    <span class="mobile_error"></span>
                                    <input type="Number" name="deal_amount" class="form-control" required readonly placeholder="Deal Amount" value="<?php echo $details->deal_amount ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Receive Amount</label>
                                    <input type="Number" name="receive_amount" class="form-control" placeholder="Receive Amount" value="<?php echo $details->receive_amount ?>">
                                    <span style="font-size:16px;color:red;">**Be Alert This Field Over Write Privious Amount</span>
                                </div>
                            </div>

                        </div>
                    </div>
                 
                   
                    <!--Second Address -->
                    <hr class="my-4" />
                    <h6 class="heading-small text-muted mb-4">Home Address</h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Full Home Address</label>
                                    <input name="home_address" class="form-control" required value="<?php echo $details->home_address ?>" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Postal code</label>
                                    <input type="number" name="home_pincode" class="form-control pincode" value="<?php echo $details->home_pincode ?>" required>
                                </div>
                            </div>
                             <div class="col-lg-3">
                              <div class="form-group">
                                  <label class="form-control-label">Your Area</label>
                                  <select name="home_area" class="form-control "  placeholder="Your Area">
                                       <option selected value="<?php echo $details->home_area ?>"><?php echo $details->home_area ?></option>
                                      <option value="">Select Your Area</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">City</label>
                                    <select name="home_city" class="form-control cities" required>
                                         <option selected value="<?php echo $details->home_state ?>"><?php echo $details->home_city ?></option>
                                        <option value="">Select Home City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">State</label>
                                    <select name="home_states" class="form-control states"  required>
                                        <option selected value="<?php echo $details->home_state ?>"><?php echo $details->home_state ?></option>
                                        <option value="">Select Home State</option>
                                    </select>

                                </div>
                            </div>
                            
                           
                            
                        </div>
                    </div>
                    <hr class="my-4" />



                    <div class="text-center">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user->user_id ?>">
                        <input type="hidden" name="user_detail_id" value="<?php echo $details->user_detail_id ?>">
                         <input type="hidden" id="type" value="edit">
                        <button type="submit" class="btn btn-primary my-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="vendor"] option[value="<?php echo $user->parent ?>"]').attr("selected", "selected");
   
        $('#vendor2').select2({
      ajax: {
        url: '<?php echo base_url('autovendor'); ?>',
        type: "GET",
        dataType: 'json',
        data: function(params) {
          var query = {
            search: params.term,
            role: duid,
            roleid:"<?php echo $user->role_id?>",
            type: $('#type').val(),
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
          }
          return query;
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
      }
    });
    });
</script>