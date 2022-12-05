         <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Edit Menu</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('menu/update'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
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
          

                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                       <div class="text-center" id="update">
                        <input type="hidden" id="menu_id" name="menu_id" value="">
                        <button type="submit" class="btn btn-primary my-4" id="update_btn">Update</button>
                      </div
                    </div>
                </form>
            </div>
        </div>