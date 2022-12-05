  <form name="validate" role="form" id="submitBeneficiaryForm">
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
      <h4 class="heading-small text-black mb-4">Beneficiary Details:</h4>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label class="form-control-label">Beneficiary Name</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input class="form-control" name="beneficiary_name" placeholder="User Name" type="text">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label class="form-control-label">Beneficiary Mobile</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-phone"></i></span>
              </div>
              <input class="form-control" name="beneficiary_mobile" placeholder="Mobile Number" type="text">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label class="form-control-label">Beneficiary Account Number</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
              </div>
              <input class="form-control" name="beneficiary_account_number" placeholder="Account Number" type="text">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label class="form-control-label">Beneficiary IFSC</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-university"></i></span>
              </div>
              <input class="form-control" name="beneficiary_ifsc" placeholder="Enter Ifsc Code" type="text">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label class="form-control-label">Bank Name</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
              </div>
              <input class="form-control" name="beneficiary_bank_name" placeholder="Enter Bank Name" type="text">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label class="form-control-label">Mobile Number</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text">+91</span>
              </div>
              <input readonly class="form-control" name="beneficiary_ifsc" placeholder="Enter Ifsc Code" type="text" value="<?php echo $phone_no; ?>">
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <hr class="my-4" />
    <div class="text-center">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" class="btn btn-primary my-4">Submit</button>
    </div>
  </form>
