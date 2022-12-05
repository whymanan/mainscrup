<div class="row">

    <div class="col-xl-12 ">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">DMTv2 Services Commision</h3>
                    </div>
                    <div class="col-4 text-right">

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
        <td><button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit('<?php echo $row['service_charge_id'] ?>')" title="Edit Commission Information"><i class="fa fa-pencil-alt"></i></button>
           <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete('<?php echo $row['service_charge_id'] ?>')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button></td>
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
    console.log(id)
    var sureDel = confirm("Are you sure want to delete");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "<?php echo base_url('dmtv2/DmtvtwoController/deletesub/') ?>" + id,

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
        url: "<?php echo base_url('dmtv2/DmtvtwoController/editsub/') ?>" + id,

        success: function(response) {
          response = JSON.parse(response);
          if (response != "") {
            
                 
        var role_id = $('#role').val();
            $.ajax({
              url: '<?php echo base_url('dmtv2/DmtvtwoController/addupdatesub'); ?>',
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
              
               $('#charge').val(response.charge);
              
               $('#flat').val(response.c_flate);
               
               $('#service_charge_id').val(response.service_charge_id);
               
              
              },
            })
               
          }


        }
      });

    }
    
    

  }
        
  
</script>

