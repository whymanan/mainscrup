<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/gas_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                           <select name="operator" id="operator" class="form-control valid" aria-invalid="false">
                                <option value="Vadodara Gas Limited">Vadodara Gas Limited	</option>
                                <option value="Unique Central Piped Gases Pvt Ltd (UCPGPL)">Unique Central Piped Gases Pvt Ltd (UCPGPL)</option>
                                <option value="Tripura Natural Gas">Tripura Natural Gas</option>
                                <option value="Torrent Gas Moradabad Limited Formerly Siti Energy Limited">Torrent Gas Moradabad Limited Formerly Siti Energy Limited	</option>
                                <option value="Siti Energy">Siti Energy</option>
                                <option value="Sanwariya Gas Limited">Sanwariya Gas Limited	</option>
                                <option value="Sabarmati Gas Limited (SGL)">Sabarmati Gas Limited (SGL)		</option>
                                <option value="Megha Gas">Megha Gas</option>
                                <option value="Maharashtra Natural Gas Limited">Maharashtra Natural Gas Limited</option>
                                <option value="Mahanagar Gas Limited">Mahanagar Gas Limited	</option>
                                 <option value="IRM Energy Private Limited">IRM Energy Private Limited	</option>
                                <option value="Indraprastha Gas">Indraprastha Gas</option>
                                <option value="Indian Oil-Adani Gas Private Limited">Indian Oil-Adani Gas Private Limited</option>
                                <option value="Indane Gas">Indane Gas</option>
                                <option value="Haryana City Gas">Haryana City Gas</option>
                                 <option value="Gujarat Gas Company Limited">Gujarat Gas Company Limited</option>
                                <option value="Green Gas Limited(GGL)">Green Gas Limited(GGL)</option>
                                <option value="Gail Gas Limited">Gail Gas Limited		</option>
                                <option value="Charotar Gas Sahakari Mandali Ltd">Charotar Gas Sahakari Mandali Ltd</option>
                                <option value="Central U.P. Gas Limited">Central U.P. Gas Limited		</option>
                                <option value="Bhagyanagar Gas Limited">Bhagyanagar Gas Limited		</option>
                                <option value="Assam Gas Company Limited">Assam Gas Company Limited		</option>
                                <option value="Adani Gas">Adani Gas</option>
                                <option value="Aavantika Gas Ltd">Aavantika Gas Ltd</option>
                                <option value="Indian Oil Corporation Limited">Indian Oil Corporation Limited</option>
                                <option value="Indane Gas">Indane Gas</option>
                                <option value="Hindustan Petroleum Corporation Ltd (HPCL)">Hindustan Petroleum Corporation Ltd (HPCL)</option>
                                <option value="Bharat Petroleum Corporation Limited (BPCL)">Bharat Petroleum Corporation Limited (BPCL)</option>
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="22"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
