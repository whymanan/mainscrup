<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Add New User</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('users/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">User information</h4>
                    <div class="pl-lg-4">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">First name</label>
                                    <input type="text" name="firstname" class="form-control" required placeholder="First name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Last name</label>
                                    <input type="text" name="lastname" class="form-control" required placeholder="Last name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Mobile</label>
                                    <span class="mobile_error"></span>
                                    <input type="text" name="phone_no1" class="form-control" required placeholder="Mobie Number" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="jesse@example.com">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Category</label>
                                    <select name="user_role" id="role" class="form-control" required>
                                        <option value="">Select Category</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Parent Name
                                        <button type="button" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="Select the parent of this user">
                                            <i class="ni ni-bulb-61"></i>
                                        </button>
                                    </label>
                                    <select name="vendor" id="vendor2" class="form-control" required>
                                        <option value="<?php echo $this->session->userdata('user_id')?>"><?php echo $this->session->userdata('user_name')?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Deal Amount</label>
                                    <span class="mobile_error"></span>
                                    <input type="Number" name="deal_amount" class="form-control" required placeholder="Deal Amount" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Receive Amount</label>
                                    <input type="Number" name="receive_amount" class="form-control" placeholder="Receive Amount">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!--Second Address -->
                    <hr class="my-4" />
                    <div class="row">
                      <div class="col-md-10">
                          <h6 class="heading-small text-muted mb-4">Home Address</h6>
                      </div>
                    </div>
                    <div class="pl-lg-4">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label class="form-control-label">Full Home Address</label>
                                  <input name="home_address" class="form-control" required placeholder="Home Address" id="HAddress" value="" type="text">
                              </div>
                          </div>
                      </div>
                     <div class="row">
                          <div class="col-lg-3">
                              <div class="form-group">
                                  <label class="form-control-label">Postal code</label>
                                  <input type="number" name="home_pincode" class="form-control pincode" placeholder="Postal code"
                                      required>
                              </div>
                          </div>
                          <div class="col-lg-3">
                              <div class="form-group">
                                  <label class="form-control-label">Your Area</label>
                                  <select name="home_area" class="form-control area"  placeholder="Your Area">
                                      <option value="">Select Your Area</option>
                                  </select>
                              </div>
                          </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">State</label>
                                    <select name="home_states" class="form-control states" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">City</label>
                                    <select name="home_city" class="form-control cities" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr class="my-4" />
                    <div class="text-center">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" id="type" value="add">
                        <button type="submit" class="btn btn-primary my-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#role").change(function()
        {
             var role_id=$(this).val();
             $('#vendor2').val('<?php echo $this->session->userdata('user_id')?>');
             $('#vendor2').select2({
      ajax: {
        url: '<?php echo base_url('autovendor'); ?>',
        type: "GET",
        dataType: 'json',
        data: function(params) {
          var query = {
            search: params.term,
            role: duid,
            roleid:role_id,
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
        })
      
   
    });
</script>