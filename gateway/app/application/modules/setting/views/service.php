<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Add New Service</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('service/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">Services</h4>



                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control clear" required>
                            </div>
                        </div>
                    </div>



                    <div class="text-center" id="submit">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="btn btn-primary my-4" id="submit_btn">Save</button>
                    </div>

                    <div class="text-center" id="update">
                        <input type="hidden" id="id" name="id">
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
                        <h3 class="mb-0">Pending List</h3>
                    </div>

                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush" id="slablist">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Id</th>
                            <th scope="col">Created</th>

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
                url: "<?php echo base_url() ?>setting/services/delete/" + id,

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
    $(document).ready(function() {
        $("#update_btn").on('click', function() {
            var form = $("form").serializeArray();
            console.log(form);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('setting/services/update') ?>",
                data: {
                    'form': form,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                datatype: 'json',
                success: function(data) {
                    if (data = 1) {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: 'Update Successfully',
                            showConfirmButton: false,
                            timer: 3500
                        });
                        $('#slablist').DataTable().ajax.reload();
                        $('#update').hide();
                        $('#submit').show();
                        $('.clear').val("");
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
        });
        var Api = '<?php echo base_url('setting/services/'); ?>';



        var $squadlist = $('#slablist');


        var $table = $squadlist.DataTable({
            "processing": true,
            "serverSide": true,
            "deferRender": true,
            "language": {
                "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                "emptyTable": "No distributors data available ...",
            },
            "order": [],
            "ajax": {
                url: Api + "get_servicelist?list=all",
                type: "GET",
            },

            "pageLength": 10
        });
    });

    function Edit(id) {

        $('#menu_id').val(id);
        var sureDel = confirm("Are you sure want to edit");
        if (sureDel == true) {
            $.ajax({
                type: "GET",
                url: "<?php echo base_url() ?>setting/services/edit/" + id,

                success: function(response) {
                    response = JSON.parse(response);
                    console.log(response.data_menu);
                    if (response != "") {
                        $('#submit').hide();
                        $('#update').show();


                        $('#name').val(response.name);
                        $('#id').val(response.id);

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