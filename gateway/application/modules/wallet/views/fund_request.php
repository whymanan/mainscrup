<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Fund request</h3>
                    </div>
                    <div class="col-4 text-right">
                        <h3 class="mb-0">Balance :- Rs : <?php echo $bal?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('wallet/Wallet/fund_request/'); ?>"
                    method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">User information</h4>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-sm-2" ">
                            <p style=" padding:15px;"> Amount <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-3" ">
                            <p style=" padding:10px;">
                                <input name="amount" type="number" class="form-control" required>

                                </p>
                            </div>

                            <div class="col-sm-2">
                                <p style="padding:15px;"> Remarks <span style="color:red;"> </span> </p>
                            </div>
                            <div class="col-sm-3">
                                <p>
                                </p>
                                <div class="checkbox">
                                    <input name="narration" type="text" id="" class="form-control">
                                </div>
                                <p></p>
                            </div>
                        </div>
                        <div class="row" id="append">
                            <div class="col-sm-2" "><p style=" padding:15px;">UTR NO.<span style="color:red;">*
                                </span> </p>
                            </div>
                            <div class="col-sm-3" ">
                              <p style=" padding:10px;">
                                <input type="text" name="utr" placeholder="Enter your UTR number" class="form-control" required>
                              </p>
                            </div>
                            <div class="col-sm-2" "><p style=" padding:15px;"> Receipt <span style="color:red;">
                                </span> </p>
                            </div>
                            <div class="col-sm-3" ">
                            <p style=" padding:10px;">
                                <input type="file" name="screenshot" class="form-control"
                                    accept="image/png,image/jpeg,image/jpg" title="only accept png,jpg and jpeg" />

                                </p>
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>"
                                value="<?php echo $this->security->get_csrf_hash();?>">
                            <!-- <input type="hidden" id="type" value="add" autocomplete="off"> -->
                            <input type="submit" name="" value="Submit" class="btn btn-primary">
                            <input type="submit" name="" value="Reset" class="btn btn-danger">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12 order-xl-1">
<div class="card">
<div class="row align-items-center">
    <div class="card-body">
        <div class="table-responsive">
            <!-- Projects table -->
            <table class="table align-items-center table-flush" id="fund_request">
                <thead class="thead-light">
                    <tr>

                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">MEMBER ID</th>
                        <th scope="col">Amount</th>
                        <th scope="col">UTR NO</th>
                        <th scope="col">REFRENCE_ID</th>
                        <th scope="col">REMARKS</th>
                        <th scope="col"> Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#Rtype").change(function() {
        if ($(this).val() == 'offline') {
            $('#append').html(
                '<div class="col-sm-2" "><p style="padding:15px;"> UTR NUMBER  <span style="color:red;"> * </span> </p></div>' +
                '<div class="col-sm-3" ">' +
                '<p style="padding:10px;">' +
                ' <input type="text" name="utr" placeholder="Enter your UTR number" class="form-control">' +

                '</p>' +
                '</div>' +
                '<div class="col-sm-2" "><p style="padding:15px;"> Receipt  <span style="color:red;"> * </span> </p></div>' +
                '<div class="col-sm-3" ">' +
                '<p style="padding:10px;">' +
                ' <input type="file" name="screenshot" class="form-control"  accept="image/png,image/jpeg,image/jpg" title="only accept png,jpg and jpeg" />' +

                '</p>' +
                '</div>'
            )
        } else {
            $('#append').html('');
        }
    })

    var Api = "<?php echo base_url('wallet/Wallet/');?>";
    var $table = $('#fund_request').DataTable({
        "searching": false,
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
            "emptyTable": "No distributors data available ...",
        },
        "order": [],
        "ajax": {
            url: Api + "get_fund",
            type: "GET",
        },
        "pageLength": 10
    })
})

function modal(data) {
    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        keyboard: false
    })
    if (data !=null && data.length!=0) {
        $('.modal-body').html('<img style="width:100%" src="<?php echo base_url('uploads/fund_request/');?>' + data +
            '">');
    } else {
        $('.modal-body').html('<p>Image not persent</p>');
    }
    myModal.toggle();
}

function hide() {
    $('#exampleModal').modal('hide');
}
function approve(id)
{
    $.ajax(
        {
        'url':'<?php echo base_url('wallet/Wallet/approve_fund');?>'+"?id="+id,
        'type':'GET',
        success:function(data)
        {
            var Api = "<?php echo base_url('wallet/Wallet/');?>";
            $('#fund_request').dataTable().fnDestroy();
            var $table = $('#fund_request').DataTable({
                         "searching": false,
                         "processing": true,
                         "serverSide": true,
                         "deferRender": true,
                         "language": {
                           "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                           "emptyTable": "No distributors data available ...",
                        },
                        "order": [],
                        "ajax": {
                          url: Api + "get_fund",
                          type: "GET",
                        },
                  "pageLength": 10
             })
        }

        }
    )
}
function deny(id)
{
    $.ajax(
        {
        'url':'<?php echo base_url('wallet/Wallet/deny_fund');?>'+"?id="+id,
        'type':'GET',
        success:function(data)
        {
            var Api = "<?php echo base_url('wallet/Wallet/');?>";
            $('#fund_request').dataTable().fnDestroy();
            var $table = $('#fund_request').DataTable({
                         "searching": false,
                         "processing": true,
                         "serverSide": true,
                         "deferRender": true,
                         "language": {
                           "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                           "emptyTable": "No distributors data available ...",
                        },
                        "order": [],
                        "ajax": {
                          url: Api + "get_fund",
                          type: "GET",
                        },
                  "pageLength": 10
             })
        }

        }
    )
}
</script>

<!-- Modal -->