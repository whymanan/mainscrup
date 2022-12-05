<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/insurance_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                 <div class="col-3">
                    <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                           <select name="operator" id="operator" class="form-control select2 valid" aria-invalid="false">
                                            <option value="">Select Provider</option>
                                            <option value="Aviva Life Insurance">Aviva Life Insurance</option>
                                            <option value="Bajaj Allianz General Insurance">Bajaj Allianz General Insurance</option>
                                            <option value="Bajaj Allianz Life Insurance">Bajaj Allianz Life Insurance</option>
                                            <option value="Bharti Axa Life Insurance">Bharti Axa Life Insurance</option>
                                            <option value="Canara HSBC Oriental Bank of Commerce Life Insurance">Canara HSBC Oriental Bank of Commerce Life Insurance</option>
                                            
                                            <option value="DHFL Pramerica Life Insurance Co. Ltd">DHFL Pramerica Life Insurance Co. Ltd		</option>
                                            <option value="Edelweiss Tokio Life Insurance">Edelweiss Tokio Life Insurance</option>
                                            <option value="Exide Life Insurance">Exide Life Insurance</option>
                                            <option value="Future Generali India Life Insurance Company Limited">Future Generali India Life Insurance Company Limited		</option>
                                            <option value="HDFC Life Insurance Co. Ltd">HDFC Life Insurance Co. Ltd</option>
                                            <option value="ICICI Prudential Life Insurance">ICICI Prudential Life Insurance</option>
                                            
                                            <option value="IDBI federal Life Insurance">IDBI federal Life Insurance</option>
                                            <option value="INDIA FIRST Life Insurance">INDIA FIRST Life Insurance</option>
                                            <option value="Life Insurance Corporation">Life Insurance Corporation</option>
                                            <option value="Max Bupa Health Insurance<">Max Bupa Health Insurance</option>
                                            <option value="Max Life Insurance">Max Life Insurance</option>
                                            <option value="PNB Metlife">PNB Metlife	</option>
                                            
                                            <option value="Pramerica Life Insurance Limited">Pramerica Life Insurance Limited</option>
                                            <option value="Reliance General Insurance Company Limited">Reliance General Insurance Company Limited</option>
                                            <option value="Reliance Nippon Life Insurance">Reliance Nippon Life Insurance</option>
                                            <option value="Religare Health Insurance Co Ltd">Religare Health Insurance Co Ltd</option>
                                            <option value="SBI Life Insurance">SBI Life Insurance</option>
                                            <option value="SBIG">SBIG</option>
                                            
                                            <option value="Shriram General Insurance">Shriram General Insurance</option>
                                            <option value="Shriram Life Insurance Co Ltd">Shriram Life Insurance Co Ltd</option>
                                            <option value="Star Union Dai Ichi Life Insurance">Star Union Dai Ichi Life Insurance</option>
                                            <option value="Tata AIA Life Insurance">Tata AIA Life Insurance</option>
                                            <option value="Vastu Housing Finance Corporation Limited">Vastu Housing Finance Corporation Limited</option>
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="20"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
