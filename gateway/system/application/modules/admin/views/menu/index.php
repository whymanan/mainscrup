<div class="row">

<div class="col-xl-4 order-xl-1" id="addCommissionForm">
     
     
      <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-8">
            <h3 class="mb-0">Add New Menu</h3>
          </div>
          <div class="col-4 text-right">

          </div>
        </div>
      </div>
      <div class="card-body">
        <form name="validate" role="form" action="<?php echo base_url('menu/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
          <h4 class="heading-small text-muted mb-4">Menu</h4>



          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Menu Name</label>
                <input type="text" name="name" id="name" class="form-control clear" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Parent</label>
                <select name="parent" id="parent" class="form-control" required>
                  <option value="0">None</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Menu Path</label>
                <input type="text" name="path" id="path" class="form-control clear" required>
              </div>
            </div>
          </div>
          
            <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label">Menu Is Service</label>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="custom-toggle">
                    <input type="checkbox" name="is_service_menu" id="is_service_menu">
                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                </label>
              </div>
            </div>
          </div>
          <div class="row" id="service_box" style="display: none;">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Select Service</label>
                <select name="select_service" id="select_service" class="form-control" required>
                  <option value="">None</option>
                </select>
              </div>
            </div>
          </div>

          <div class="text-center" id="submit">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" class="btn btn-primary my-4" id="submit_btn">Save</button>
          </div>

          
        </form>
      </div>
    </div>
     
     
 </div>
 
  <div class="col-xl-8 order-xl-2">
    <div class="card">
      <div class="card-header border-0">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="mb-0">Pending List</h3>
          </div>

        </div>
      </div>
      <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="squadlist">
          <thead class="thead-light">
            <tr>
              <th scope="col">#</th>
              <th scope="col">NAME</th>
              <th scope="col">Path</th>
              <th scope="col">PARENT</th>
              <th scope="col">SERVICE</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  function Delete(id) {

    var sureDel = confirm("Are you sure want to delete");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "<?php echo base_url() ?>admin/menu/delete/" + id,

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

        }
      });

    }

  }



  function Edit(id) {
var $addCommissionForm = $('#addCommissionForm');
    var sureDel = confirm("Are you sure want to edit");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "<?php echo base_url() ?>admin/menu/edit_menu/" + id,

        success: function(response) {
          response = JSON.parse(response);
          console.log(response);
          if (response != "") {
            $('#submit').hide();
            $('#update').show();
            
            $.ajax({
              url: '<?php echo base_url('admin/menu/addupdate'); ?>',
              type: 'GET',
              beforeSend: function(){
                $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                  
                   $addCommissionForm.html(data);
                  
           if (response.parent_id == null) {
              console.log("true")
              $('#name').val(response.data_menu);
            } else {
              console.log("false")
              $('#name').val(response.data_sub_menu);
            }

            $('#path').val(response.path);
          
            $('#menu_id').val(id);
            
            
              $('#parent').select2({
      ajax: {
        url: '<?php echo base_url('external/get_menu'); ?>',
        type: "GET",
        dataType: 'json',

        data: function(params) {

          var queryParameters = {
            term: params.term,
            type: 'public',
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
          }
          return queryParameters;
        },
        processResults: function(data) {
          // console.log(data);
          return {
            results: $.map(data, function(item) {
              return {
                text: item.text,
                id: item.id
              }
            })
          };
        },
      }
    });
            
            
            
            
            
            
              },
              })
         
         
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

    }
    
    
    


  }


</script>