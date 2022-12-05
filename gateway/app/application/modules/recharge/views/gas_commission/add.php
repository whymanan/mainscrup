<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/gas_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                            <select name="operator" id="operator" class="form-control valid" aria-invalid="false">
                                <option value="AVTG">Aavantika Gas Ltd	</option>
                                <option value="ASSG">Assam Gas Company Limited	</option>
                                <option value="BHAG">Bhagyanagar Gas Limited	</option>
                                <option value="CUGL">Central U.P. Gas Limited	</option>
                                <option value="CGSM">Charotar Gas Sahakari Mandali Ltd	</option>
                                <option value="GAIG">GAIL Gas Limited	</option>
                                <option value="GREG">Green Gas Limited(GGL)		</option>
                                <option value="GUJG">Gujarat Gas Limited		</option>
                                <option value="HARG">Haryana City Gas	</option>
                                <option value="INAG">Indian Oil-Adani Gas Private Limited		</option>
                                 <option value="INPG">Indraprastha Gas Limited	</option>
                                <option value="IRMG">IRM Energy Private Limited		</option>
                                <option value="MEGG">Megha Gas		</option>
                                <option value="NAVG">Naveriya Gas Pvt Ltd		</option>
                                <option value="SABG">Sabarmati Gas Limited (SGL)	</option>
                                 <option value="TGML">Torrent Gas Moradabad Limited	</option>
                                <option value="TRIG">Tripura Natural Gas	</option>
                                <option value="UCPG">Unique Central Piped Gases Pvt Ltd (UCPGPL)		</option>
                               
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="23"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
