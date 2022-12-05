<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Bank Kyc</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <div class="row">

                        <div class="col-lg-6">

                            <div class="form-group">

                                <label class="form-control-label">Select Member Id

                                    <button type="button" class="btn btn-sm btn-link" data-toggle="tooltip"
                                        data-placement="top" title="Select the parent of this user">

                                        <i class="ni ni-bulb-61"></i>

                                    </button>

                                </label>

                                <select name="vendor" id="vendor" class="form-control" required>

                                    <option value="<?php echo $this->session->userdata('user_id')?>">
                                        <?php echo $this->session->userdata('user_name')?></option>

                                </select>

                            </div>

                        </div>

                        <div class="col-lg-6 d-flex justify-content-center">

                            <div class="form-group">

                                <button type="button" class="btn btn-success" id="addkyc" style="margin-top: 38px;">Add
                                    Kyc</button>

                            </div>

                        </div>

                    </div>



                </div>
            </div>

        </div>

    </div>
</div>
<section>
    <div class="container">
        <div id="AddNewKyc"></div>
    </div>

</section>
</div>
   
   <script>
   
      $("#addkyc").click(function(){
  var vendor = $("#vendor").val();
 
      $.ajax({
          type: "POST",
          url: "<?php echo base_url('kyc/get_info') ?>",
          data: {

            'vendor': vendor,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
          },
              success: function(res) {
                  $("#AddNewKyc").html(res);

              }
            });
 
});</script>
