
   
 <div class="col-xl-12 order-xl-1">
            <div class="card " id="card_bank">
                <div class="card-header">
                    <div class="row justify-content-between align-items-center">
                        <div class="col">
                            <h6 class="heading-small text-muted mb-4">Bank Account Details</h6>
                        </div>
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body">
                        <form action="<?php echo base_url('bank/bank/add') ?>" method="post">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Account Holder Name</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                            </div>
                                            <input class="form-control" name="name" placeholder="Account Holder Name"
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Account Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-credit-card"></i></span>
                                            </div>
                                            <input class="form-control" name="account_no" placeholder="Account Number"
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Bank Name</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-university"></i></span>
                                            </div>
                                            <input class="form-control" name="bank_name" placeholder="Bank Name"
                                                type="text" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Bank IFSC Code</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                            </div>
                                            <input class="form-control" name="ifsc" placeholder="IFSC Code" type="text"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Phone Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="ni ni-mobile-button"></i></span>
                                            </div>
                                            <input class="form-control" name="phone" placeholder="Phone Number"
                                                type="tel" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="user_id" value="<?php if (isset($user_id)) {
                                                echo $user_id;
                                                                                                 }?>">
                            <input type="submit" class="btn btn-success">
                             </div>
                             </form>
                    </div>
                </div>
                
                
<div class="container">
        <div class="row">
              <div class="col-lg">
                    <div class="card ">
                    <div class="card-body" >
                     <table id="banklist" class="align-items-center table-flush table">
    <thead>
        <tr>
            <th>#
            </th>
            <th>NAme
            </th>
            <th>ACC Number
            </th>
            <th>Bank
            </th>
            <th>IFSC
            </th>
            <th>Mobile
            </th>
        </tr>
    </thead>
    <tbody>
      <?php $i=1;
      foreach($bank as $row){ ?>
     <td><?php echo $i?></td> 
     <td><?php echo $row['account_holder_name']?></td> 
     <td><?php echo $row['account_no']?></td>
     <td><?php echo $row['bank_name']?></td> 
     <td><?php echo $row['ifsc_code']?></td>
     <td><?php echo $row['phone_no']?></td>
      <?php $i++;} ?>
    </tbody>
</table>
                    </div>
                </div>
          </div>
     </div>
</div> 
                