<div class="row">

  <div class="col-xl-4 order-xl-1">
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
        <form name="validate" role="form" action="<?php echo base_url('role/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
          <h4 class="heading-small text-muted mb-4">Menu</h4>



          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Role Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Role Name" value="" id="name">
              </div>
            </div>

          </div>


          <div class="text-center" id="submit">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" class="btn btn-primary my-4" id="submit_btn">Save</button>
          </div>

          <div class="text-center" id="update">
            <input type="hidden" id="roleid" name="roleid">
            <button type="button" class="btn btn-primary my-4" id="update_btn">Update</button>
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
            <h3 class="mb-0">New Join</h3>
          </div>
          <div class="col text-right">
            <a href="#!" class="btn btn-sm btn-primary">See all</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="squadlist">
          <thead class="thead-light">
            <tr>
              <th scope="col">SNO</th>
              <th scope="col">#</th>
              <th scope="col">NAME</th>
              <th scope="col">STATUS</th>

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
        url: "<?php echo base_url() ?>admin/role/delete/" + id,

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

    var sureDel = confirm("Are you sure want to edit");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "<?php echo base_url() ?>admin/role/edit/" + id,

        success: function(response) {
          response = JSON.parse(response);
          if (response.role != "") {
            $('#submit').hide();
            $('#update').show();
            $('#name').val(response.role);
            $('#roleid').val(id);
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
