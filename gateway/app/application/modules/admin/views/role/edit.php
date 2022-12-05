<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card" style="border: 2px solid #ced4da;">
            <div class="card-header" style="background: #525f7f;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0 text-white">Add New Menu</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('role/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">Role Permission</h4>



                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Role Name</label>
                                <input type="text" name="name" class="form-control" readonly placeholder="Role Name" value="<?php echo $role->role ?>">
                                <input type="hidden" id="role_id" value="<?php echo $role->roles_id ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Role Status</label>
                                <input type="text" name="name" class="form-control" readonly placeholder="Role Name" value="<?php echo $role->role_status ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card" style="background: #ced4da;">
                                <div class="card-header border-0 " style="background: #525f7f;">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0 text-white">Role Permission</h3>
                                        </div>

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <!-- Projects table -->
                                    <div class="nav-wrapper">

                                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                            <?php $i = 0;
                                            foreach ($roles as $row) { ?>
                                                <li class="nav-item ">
                                                    <a class="nav-link mb-sm-3 mb-md-0 <?php if ($i == 0) echo "active"; ?> tab" id="<?php echo $row['menu_permission_id'] ?>" data-toggle="tab" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i> <?php echo $row['data_menu'] ?></a>
                                                </li>
                                            <?php $i++;
                                            }
                                            ?>
                                        </ul>

                                    </div>
                                    <hr style="height:5px;border-width:2;background-color:white">
                                    <div class="card shadow">
                                        <div class="card-body" style="background: #ced4da;">
                                            <div class="tab-content" id="myTabContent">



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                </form>
            </div>
        </div>
    </div>
</div>
