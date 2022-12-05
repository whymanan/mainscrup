<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/water_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                           <select name="operator" class="form-control valid" aria-invalid="false">
                                <option value="Ahmedabad Municipal Corporation">Ahmedabad Municipal Corporation</option>
                                <option value="Bhopal Municipal Corporation">Bhopal Municipal Corporation</option>
                                <option value="Bangalore Water Supply and Sewerage Board">Bangalore Water Supply and Sewerage Board</option>
                                <option value="City Municipal Council –Ilkal">City Municipal Council –Ilkal</option>
                                <option value="Department of Public Health Engineering-Water, Mizoram">Department of Public Health Engineering-Water, Mizoram</option>
                                <option value="Delhi Jal Board (BBPS)">Delhi Jal Board (BBPS)</option>
                                <option value="Delhi Development Authority (DDA) - Water">Delhi Development Authority (DDA) - Water</option>
                                <option value="Gwalior Municipal Corporation<">Gwalior Municipal Corporation</option>
                                <option value="Greater Warangal Municipal Corporation – Water">Greater Warangal Municipal Corporation – Water</option>
                                <option value="Hyderabad Metropolitan Water Supply and Sewerage Board">Hyderabad Metropolitan Water Supply and Sewerage Board</option>
                                <option value="Haryana Urban Development Authority">Haryana Urban Development Authority</option>
                                <option value="Indore Municipal Corporation">Indore Municipal Corporation</option>
                                <option value="Jammu Kashmir Water Billing-JKPHE Kashmir">Jammu Kashmir Water Billing-JKPHE Kashmir		</option>
                                <option value="Jammu Kashmir Water Billing-JKPHE Jammu">Jammu Kashmir Water Billing-JKPHE Jammu</option>
                                <option value="Jalkal Vibhag Nagar Nigam Prayagraj">Jalkal Vibhag Nagar Nigam Prayagraj</option>
                                <option value="Jabalpur Municipal Corporation">Jabalpur Municipal Corporation</option>
                                <option value="Kerala Water Authority (KWA)">Kerala Water Authority (KWA)</option>
                                <option value="Kalyan Dombivali Municipal Corporation - Water">Kalyan Dombivali Municipal Corporation - Water</option>
                                <option value="Mysuru Citi Corporation">Mysuru Citi Corporation</option>
                                <option value="Municipal Corporation of Gurugram">Municipal Corporation of Gurugram</option>
                                <option value="Municipal corporation of Amritsar">Municipal corporation of Amritsar</option>
                                <option value="Municipal Corporation Ludhiana – Water">Municipal Corporation Ludhiana – Water</option>
                                
                                <option value="Municipal Corporation Jalandhar">Municipal Corporation Jalandhar</option>
                                <option value="Municipal Corporation Chandigarh">Municipal Corporation Chandigarh</option>
                                <option value="MCGM Water Department">MCGM Water Department	</option>
                                <option value="Madhya Pradesh Urban Administration and Development - Water">Madhya Pradesh Urban Administration and Development - Water	</option>
                                <option value="New Delhi Municipal Council (NDMC) - Water">New Delhi Municipal Council (NDMC) - Water</option>
                                <option value="Nagar Nigam Aligarh- water">Nagar Nigam Aligarh- water</option>
                                <option value="Punjab Municipal Corporations/Councils">Punjab Municipal Corporations/Councils</option>
                                <option value="Pune Municipal Corporation Water">Pune Municipal Corporation Water</option>
                                <option value="Public Health Engineering Department, Haryana">Public Health Engineering Department, Haryana</option>
                                <option value="Port Blair Municipal Council - Water">Port Blair Municipal Council - Water	</option>
                                <option value="Ranchi Municipal Corporation">Ranchi Municipal Corporation</option>
                                <option value="Surat Municipal Corporation">Surat Municipal Corporation</option>
                                <option value="Silvassa Municipal Council">Silvassa Municipal Council</option>
                                <option value="Shivamogga City Corporation - Water Tax">Shivamogga City Corporation - Water Tax	</option>
                                <option value="Uttarakhand Jal Sansthan">Uttarakhand Jal Sansthan</option>
                                <option value="Urban Improvement Trust (UIT) - Bhiwadi">Urban Improvement Trust (UIT) - Bhiwadi</option>
                                <option value="Ujjain Nagar Nigam – PHED">Ujjain Nagar Nigam – PHED</option>
                                <option value="Vatva Industrial Estate Infrastructure Development Ltd">Vatva Industrial Estate Infrastructure Development Ltd</option>
                                <option value="Vasai Virar Municipal Corporation - Water">Vasai Virar Municipal Corporation - Water	</option>
                                <option value="Vasai Virar Municipal Corporation">Vasai Virar Municipal Corporation</option>
                               
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="19"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
