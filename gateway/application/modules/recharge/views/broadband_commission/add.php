  <div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/broadband_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                                    <label class="form-control-label">Service Provider</label>
                                         <select name="operator" id="operator" class="form-control select2">
                                            <option value="">Select Provider</option>
                                            <option value="ACT BroadBand">ACT BroadBand</option>
                                            <option value="AirJaldi - Rural Broadband">AirJaldi - Rural Broadband</option>
                                            <option value="Airtel Broadband">Airtel Broadband</option>
                                            <option value="Alliance Broadband Services Pvt. Ltd">Alliance Broadband Services Pvt. Ltd	</option>
                                            <option value="ASIANET Broadband (ASIANET)">ASIANET Broadband (ASIANET)</option>
                                            
                                            <option value="Comway Broadband">Comway Broadband		</option>
                                            <option value="Connect BroadBand">Connect BroadBand</option>
                                            <option value="Den Broadband">Den Broadband</option>
                                            <option value="DWAN Supports Private Ltd">DWAN Supports Private Ltd		</option>
                                            <option value="Excell Broadband">Excell Broadband</option>
                                            <option value="Ficus Telecom Pvt Ltd">Ficus Telecom Pvt Ltd		</option>
                                            
                                            <option value="Flash Fibernet">Flash Fibernet</option>
                                            <option value="Fusionnet Web Services Private Limited">Fusionnet Web Services Private Limited</option>
                                            <option value="GTPL KCBPL Broadband Pvt Ltd">GTPL KCBPL Broadband Pvt Ltd</option>
                                            <option value="Hathway">Hathway</option>
                                            <option value="Instalinks">Instalinks</option>
                                            <option value="Instanet Broadband">Instanet Broadband	</option>
                                            
                                            <option value="ION">ION</option>
                                            <option value="Kerala Vision Broadband Pvt Ltd">Kerala Vision Broadband Pvt Ltd</option>
                                            <option value="Limras Eronet">Limras Eronet</option>
                                            <option value="Linkio Fibernet">Linkio Fibernet</option>
                                            <option value="Mnet Broadband">Mnet Broadband</option>
                                            <option value="MTNL Delhi Broadband">MTNL Delhi Broadband</option>
                                            
                                            <option value="Netplus Broadband">Netplus Broadband</option>
                                            <option value="Nextra Broadband">Nextra Broadband</option>
                                            <option value="Skylink Fibernet Private Limited">Skylink Fibernet Private Limited</option>
                                            <option value="SpectraNet Broadband">SpectraNet Broadband</option>
                                            <option value="Swifttele Enterprises Private Limited">Swifttele Enterprises Private Limited</option>
                                            <option value="Timbl Broadband">Timbl Broadband</option>
                                            
                                            <option value="TTN BroadBand">TTN BroadBand</option>
                                            <option value="Vfibernet Broadband">Vfibernet Broadband</option>
                                            <option value="Wish Net Pvt Ltd">Wish Net Pvt Ltd</option>
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="42"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
