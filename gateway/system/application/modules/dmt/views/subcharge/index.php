<div class="row">

    <div class="col-xl-12 ">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">DMT Services Commision</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                 <form method="post" id="filter" action="<?php echo base_url('dmt/add_surcharge'); ?>">
             <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Start Range</label>
                        <input type="number" class="form-control" name="start" placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">End Range</label>
                            <input type="number" class="form-control" name="end"
                                placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols3Input">SurCharge</label>
                        <input type="text" class="form-control" name="charge"  placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label " for="example3cols3Input">flat</label>
                        <input type="checkbox" class="form-control" name="flat"> 
                    </div>
                </div>
            
            <div class="col-2">
              
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="2"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
               </form>
            </div>
        </div>
        
           
    </div>
     
</div>

<div class="card">
    <div class="card-body">
     <div class="table-responsive">
                <!-- Projects table -->
               <table class="table align-items-center table-flush" id="surcharge_list">
    <thead class="thead-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Start</th>
            <th scope="col">End</th>
            <th scope="col">Charge </th>
            <th scope="col">Flat</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        foreach($charge as $row){?>
        <tr>
        <td><button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit('<?php echo $row['service_charge_id'] ?> ')" title="Edit Commission Information"><i class="fa fa-pencil-alt"></i></button>
           <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' <?php echo $row['service_charge_id'] ?> ')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button></td>
            <td><?php echo $row['start_range']  ?></td>
            <td><?php echo $row['end_range']  ?></td>
            <td><?php echo $row['charge']  ?></td>
            <td><?php echo $row['c_flate']  ?></td>
        </tr>
        <?php $i++;} ?>


    </tbody>
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
        url: "https://perkpe.in/dmt/dmtController/delete/" + id,

        success: function(response) {
          if (response == 1) {
            $('#squadlist').DataTable().ajax.reload();

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


 function Edit(id) {


var $addCommissionForm = $('#addCommissionForm');
    $('#menu_id').val(id);
    var sureDel = confirm("Are you sure want to edit");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "https://perkpe.in/dmt/dmtController/edit/" + id,

        success: function(response) {
          response = JSON.parse(response);
          if (response != "") {
            
                 
        var role_id = $('#role').val();
            $.ajax({
              url: '<?php echo base_url('dmt/dmtController/addupdate'); ?>',
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

