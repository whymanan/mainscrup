<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/municipaltax_commissionupdate'); ?>">
        <div class="form-row">
            <div class="row">
                 <div class="col-3">
                    <div class="form-group">
                                    <label class="form-control-label">Service Provider</label>
                                       <select name="operator" id="operator" class="form-control select2">
                                        <option value="">Select Provider</option>
                                        <option value="Bicholim Municipal council">Bicholim Municipal council</option>
                                        <option value="Canacona Municipal council">Canacona Municipal council</option>
                                        <option value="Corporation of City Panaji">Corporation of City Panaji</option>
                                        <option value="Cuncolim Municipal counci">Cuncolim Municipal counci</option>
                                        <option value="Curchorem Cacora Municipal council">Curchorem Cacora Municipal council</option>
                                        <option value="Margao Municipal Council">Margao Municipal Council</option>
                                        <option value="Mhapsa Municipal Council">Mhapsa Municipal Council</option>
                                        <option value="Mormugao Municipal Council">Mormugao Municipal Council</option>
                                        <option value="Pernem Municipal council">Pernem Municipal council</option>
                                        <option value="Ponda Municipal Council">Ponda Municipal Council</option>
                                        <option value="Quepem Municipal council">Quepem Municipal council</option>
                                        <option value="Sanguem Municipal council">Sanguem Municipal council</option>
                                        <option value="Sankhali Municipal council">Sankhali Municipal council</option>
                                        <option value="Valpoi Municipal council">Valpoi Municipal council</option>
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="45"  required>
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                 
                    <div class="text-center" id="update">
                    <button type="Submit" class="btn btn-primary my-4" id="update_btn">Update</button>
                   </div>
           
            </div>
        </div>
    </form>
</div>