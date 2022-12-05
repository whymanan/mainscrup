<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Add Services Commision</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('commision/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="heading-small text-muted mb-4">User information</h4>
                    <div class="pl-lg-4">


                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label">Start Range</label>
                                    <input type="text" name="start" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label">End Range</label>
                                    <input type="text" name="end" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label">Commission</label>
                                    <input type="text" name="rate" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label">Max Commission</label>
                                    <input type="text" name="max" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label">Flat</label>
                                     <input type="checkbox" name="flat" class="form-control" required>
                                    
                                </div>
                            </div>
                             <div class="text-center">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                        <button type="submit" class="btn btn-primary my-4">Submit</button>
                    </div>
                        </div>
                    </div>
                    <div id="slab">

                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>