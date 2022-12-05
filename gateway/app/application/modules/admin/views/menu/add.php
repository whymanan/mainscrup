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