<form name="validate" role="form" id="AddCustomer">
  <h4 class="heading-small text-red mb-4">Customer is not registered. Please do the registration process.</h4>
  <div class="pl-lg-4">
    <div class="row">
      <div class="col-lg-2">
        <div class="form-group">
          <label class="form-control-label">Title</label>
          <select name="title" class="form-control" required>
            <option value="Mr">Mr.</option>
            <option value="Ms">Ms.</option>
            <option value="Miss">Miss</option>
            <option value="Mis">Mis.</option>
          </select>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="form-group">
          <label class="form-control-label"> First Name</label>
          <input type="text" name="first_name" class="form-control" required placeholder="first name" value="">
        </div>
      </div>
      <div class="col-lg-5">
        <div class="form-group">
          <label class="form-control-label"> Last Name</label>
          <input type="text" name="last_name" class="form-control" required placeholder="last name" value="">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-5">
        <div class="form-group">
          <label class="form-control-label"> Phone Number</label>
          <input type="text" name="phone_no" class="form-control" required placeholder="Phone Number" value="<?php if(isset($mobile)){ echo $mobile; }?>">
        </div>
      </div>
      <div class="col-lg-5">
        <div class="form-group">
          <label class="form-control-label"> Customer Full Address</label>
          <input type="text" name="customer_address" class="form-control" placeholder="Enter Full Address" value="">
        </div>
      </div>
    </div>
  </div>
  </div>
  <hr class="my-4" />
  <div class="text-center">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-primary my-4">Save</button>
  </div>
</form>
