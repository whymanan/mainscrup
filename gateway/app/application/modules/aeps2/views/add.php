<form name="validate" role="form" id="AddTransaction">
    <h4 class="heading-small text-muted mb-4">User information</h4>
    <div class="pl-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-control-label">Transactions Types</label>
                    <select name="selectTransactionsTypes" class="form-control" required>
                      <option value="">Select Transactions Types</option>
                      <option value="CW">Cash Withdrawal</option>
                      <option value="BE">Balance Enquiry</option>
                      <option value="MS">Mini Statement</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Adhar Number</label>
                  <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                      </div>
                      <input class="form-control" name="adharCardNumber" id="myvalidation"  placeholder="xxxx-xxxx-xxxx-xxxx" type="number">
                  </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6" >
                <div class="form-group">
                    <label class="form-control-label">Mobile Number</label>
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input class="form-control" name="tmobilenumber" placeholder="Enter Mobile Number" type="number">
                    </div>
                </div>
            </div>
            <div class="col-lg-6" >
                <div class="form-group" id="tAmountBox">
                    <label class="form-control-label">Amount</label>
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                        </div>
                        <input class="form-control" name="transectionAmount" placeholder="0.00" type="number" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-4" />
    <div class="text-center">
        <button type="submit" class="btn btn-primary my-4">Submit</button>
    </div>
</form>

<script> 
    $("#myvalidation").change(function()
       {   
            
          adhar = document.getElementById("myvalidation").value;
            var adharcard = /^\d{12}$/;
            var adharsixteendigit = /^\d{16}$/;
            if (adhar != '') {
                 if (!adhar.match(adharcard))
                 {
                      $('#myvalidation').css('border', 'solid 1px red');
                      $('button[type="submit"]').attr('disabled', 'true');
                        alert("Invalid Aadhar Number");
                    //   demo.showNotification('top', 'right','Invalid Aadhar Number', 'danger');
                 } else {
                     $('button[type="submit"]').removeAttr('disabled');
                      $('#myvalidation').css('border', 'solid 1px #d2d6de');
                 }
             }
       });
   </script>
