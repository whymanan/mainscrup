  <div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/municipality_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                                    <label class="form-control-label">Service Provider</label>
                                         <select name="operator" id="operator" class="form-control select2">
                                        <option value="">Select Provider</option>
                                        <option value="Greater Chennai Corporation">Greater Chennai Corporation</option>
                                        <option value="Gulbarga City Corporation">Gulbarga City Corporation</option>
                                        <option value="Hubli-Dharwad Municipal Corporation">Hubli-Dharwad Municipal Corporation</option>
                                        <option value="Kalyan Dombivali Municipal Corporation">Kalyan Dombivali Municipal Corporation</option>
                                        <option value="Kolhapur Municipal Corporation - Water Tax<">Kolhapur Municipal Corporation - Water Tax</option>
                                        <option value="Madhya Pradesh Urban (e-Nagarpalika) - Property">Madhya Pradesh Urban (e-Nagarpalika) - Property</option>
                                        <option value="Minicipal Corporation - Meerut">Minicipal Corporation - Meerut</option>
                                        <option value="Municipal Corporation Rohtak">Municipal Corporation Rohtak</option>
                                        <option value="Nagar Nigam Agra">Nagar Nigam Agra</option>
                                        <option value="Nagar Palika Parishad Lalitpur">Nagar Palika Parishad Lalitpur</option>
                                        <option value="Orange Retail Finance India Pvt Ltd">Orange Retail Finance India Pvt Ltd</option>
                                        <option value="Port Blair Municipal Council">Port Blair Municipal Council</option>
                                        <option value="Prayagraj Nagar Nigam - Property">Prayagraj Nagar Nigam - Property</option>
                                        <option value="Puducherry Urban Development Agency(LAD)-Property Tax">Puducherry Urban Development Agency(LAD)-Property Tax</option>
                                        <option value="Rajkot Municipal Corporation">Rajkot Municipal Corporation</option>
                                        <option value="Shivamogga City Corporation">Shivamogga City Corporation</option>
                                        <option value="UDD Uttarakhand">UDD Uttarakhand</option>
                                        <option value="Vasai Virar Municipal Corporation - Property">Vasai Virar Municipal Corporation - Property</option>
                                        </select>
                                </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Start Range</label>
                        <input type="number" class="form-control" name="start" placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">End Range</label>
                            <input type="number" class="form-control" name="end"
                                placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols3Input">Commission</label>
                        <input type="text" class="form-control" name="commision"  placeholder="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">Max Commission</label>
                            <input type="text" class="form-control"  name="max"
                                placeholder="">
                    </div>
                </div>
                <div class="col-1">
                    <div class="form-group">
                        <label class="form-control-label " for="example3cols3Input">flat</label>
                        <input type="checkbox" class="form-control" name="flat"> 
                    </div>
                </div>
            
            <div class="col-2">
              <input type="hidden" id="role_id"  name="role_id" class='btn btn-primary' value="<?php echo $role_id ?>"  required>
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="46"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
