<div class="row">

    <div class="col-xl-12 ">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0"> Pan Card Services Commision</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="pl-lg-6">
                  <div class="row">
                      <div class="col-lg-4">
                          <div class="form-group">
                              <label class="form-control-label">Select Package</label>
                              <select name="user_role" id="role" class="form-control" required>
                                  <option value="">Select Role</option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div id="addCommissionForm"></div>
                    </div>
                  </div>
              </div>
              
            </div>
        </div>
        
           
    </div>
     
</div>

<div class="card">
    <div class="card-body">
     <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush" id="list">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Start</th>
                            <th scope="col">End</th>
                            <th scope="col">Commision </th>
                            <th scope="col">Flat</th>
                        </tr>
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
        url: "<?php echo base_url('pancard/pancard/delete/')?>" + id,

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
        }
      });
        $('#list').DataTable().ajax.reload();

    }

  }


 function Edit(id) {

    var $addCommissionForm = $('#addCommissionForm');
    $('#menu_id').val(id);
    var sureDel = confirm("Are you sure want to edit");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "<?php echo base_url('pancard/pancard/edit/') ?>" + id,

        success: function(response) {
          response = JSON.parse(response);
          if (response != "") {
            
                 
        var role_id = $('#role').val();
            $.ajax({
              url: '<?php echo base_url('pancard/pancard/addupdate'); ?>',
              type: 'POST',
              data: {"addupdate": role_id, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
             beforeSend: function(){
                $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                $addCommissionForm.html(data);
                $('#role_id').val(role_id);
                
                $('#start').val(response.start_range);

               $('#end').val(response.end_range);
              
               $('#commision').val(response.g_commission);
           
               $('#max').val(response.max_commission);
              
               $('#flat').val(response.c_flate);
               
               $('#service_commission_id').val(response.service_commission_id);
               
              
              },
            })
               
          }


        }
      });

    }
    
    

  }
        
  
</script>

