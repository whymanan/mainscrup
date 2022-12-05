<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('bank/Axis_bank/b_commissionupdate'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                   <div class="form-group">
                                <label class="form-control-label">Operator</label>
                              <select name="operator" class="form-control" id="operator">
                                 <option value="OPENING_ACCOUNT" >Select Operator</option>
                               <option value="OPENING_ACCOUNT">OPENING_ACCOUNT</option>
                               
                            </select>
                            </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Start Range</label>
                        <input type="number" class="form-control start" id="start" name="start" value="" placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">End Range</label>
                            <input type="number" class="form-control" id="end" value="" name="end"
                                placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols3Input">Commission</label>
                        <input type="text" class="form-control" id="commision" value="" name="commision"  placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">Max Commission</label>
                            <input type="text" class="form-control" id="max" value="" name="max"
                                placeholder="">
                    </div>
                </div>
                <div class="col-1">
                    <div class="form-group">
                        <label class="form-control-label " for="example3cols3Input">flat</label>
                        <input type="checkbox" class="form-control" id="flat" value="" name="flat"> 
                    </div>
                </div>
            
            <div class="col-2">
              <input type="hidden" id="role_id"  name="role_id" class='btn btn-primary' value=""  required>
              <input type="hidden" id="service_commission_id"  name="service_commission_id" class='btn btn-primary' value=""  required>
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="50"  required>
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                 
                    <div class="text-center" id="update">
                    <button type="Submit" class="btn btn-primary my-4" id="update_btn">Update</button>
                   </div>
           
            </div>
        </div>
    </form>
</div>