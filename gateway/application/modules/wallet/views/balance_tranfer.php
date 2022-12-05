<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Balance Transfer</h3>
                    </div>
                    <div class="col-4 text-right">
 <h3 class="mb-0">Balance :- Rs : <?php echo $bal?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('balance/transfer'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">User information</h4>
                    <div class="pl-lg-6">

                        <div class="row">
                            <div class="col-sm-2">

                                <p style="padding:15px;"> Member ID <span style="color:red;"> * </span> </p>
                            </div>
                                <div class="col-sm-3">
                                   <p style="padding:10px;">
                                <select name="vendor" id="vendor" class="form-control" required>
                                    </select>

                                      </p>
                              </div>
                            
                            
                               
                        <div class="col-sm-2" ">
                            <p style="padding:15px;"> Amount <span style="color:red;"> * </span> </p>
                        </div>
                        <div class="col-sm-3" ">
                            <p style="padding:10px;">
                                <input name="amount" type="number" class="form-control" required>

                            </p>
                        </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Current EWallet Balance <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-3">
                                <p ">
                                </p>
                                <div class="checkbox">
                                    <input name="" type="text" readonly="readonly" id="" class="form-control">
                                </div>
                                <p></p>
                            </div>
                            
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Narration <span style="color:red;">  </span> </p>
                            </div>
                            <div class="col-sm-3">
                                <p ">
                                </p>
                                <div class="checkbox">
                                    <input name="narration" type="text" id="" class="form-control">
                                </div>
                                <p></p>
                            </div>
                        </div>

                            <div class="text-center">
                               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                <input type="hidden" id="type" value="add" autocomplete="off">
                                <input type="submit" name="" value="Submit" class="btn btn-primary">
                                <input type="submit" name="" value="Reset" class="btn btn-danger">
                            </div>



              
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
             var role="<?php echo $this->session->userdata('user_roles'); ?>"
             var member_id="<?php echo $this->session->userdata('user_id')?>";
             $('#vendor').select2({
      ajax: {
        url: '<?php echo base_url('autovendor_balance'); ?>',
        type: "GET",
        dataType: 'json',
        data: function(params) {
          var query = {
            search: params.term,
            role:role,
            vendor:member_id,
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
