<div class="row">
    <div class="col-xl-4 order-xl-1">
        <div class="card py-5">
            <div class="d-flex">
                <!--<a href="" data-toggle="modal" data-target="#exampleModal"><img src="<?= base_url('assets/img/axis_bank_logo.png'); ?>" height="50px"></a>-->

                <!--<a href="" data-toggle="modal" data-target="#exampleModal">-->
                <!--    <h1>Saving Account</h1>-->
                <!--</a>-->
                 <a href="https://www.axisbank.com/retail/accounts/savings-account/digital-savings-account/offers"><img src="<?= base_url('assets/img/axis_bank_logo.png'); ?>" height="50px"></a>

                <!--<a href="" data-toggle="modal" data-target="#exampleModal">-->
                <!--    <h1>Saving Account</h1>-->
                <!--</a>-->
                 <a href="https://www.axisbank.com/retail/accounts/savings-account/digital-savings-account/offers"> 
                    <h1>Saving Account</h1>
                </a>


            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Generate a Link</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <center>
                                <a href="<?= base_url('bank/generated-axis-account'); ?>" class="btn btn-primary">Generate a link</a>
                            </center>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 order-xl-8">
        <div class="card">
            <div class="card-header">History</div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush" id="bank_account_history">
                        <thead class="thead-light">
                            <tr>
                                <!-- <th scope="col">Print</th>
                                <th scope="col">Refresh</th> -->
                                <th scope="col">Member id</th>
                                <th scope="col">User Name</th>
                                <th scope="col">User Type</th>
                                <th scope="col">Bank URL</th>
                                <th scope="col">A/C Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>