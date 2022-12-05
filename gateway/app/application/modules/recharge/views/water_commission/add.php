<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/water_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                            <select name="operator" class="form-control valid" aria-invalid="false">
                                <option value="BWSS">Bangalore Water Supply and Sewerage Board (BWSSB)	</option>
                                <option value="BMCW">Bhopal Municipal Corporation	</option>
                                <option value="DDAW">Delhi Development Authority (DDA)	</option>
                                <option value="DLBW">Delhi Jal Board	</option>
                                <option value="DPHE">Department of Public Health Engineering-Water, Mizoram	</option>
                                <option value="GWMC">Greater Warangal Municipal Corporation	</option>
                                <option value="GMCW">Gwalior Municipal Corporation	</option>
                                <option value="HUDA">Haryana Urban Development Authority	</option>
                                <option value="HMWS">Hyderabad Metropolitan Water Supply and Sewerage Board (HMWSSB)	</option>
                                <option value="IMCW">Indore Municipal Corporation	</option>

                                <option value="JMCW">Jabalpur Municipal Corporation		</option>
                                <option value="JVNN">Jalkal Vibhag Nagar Nigam Prayagraj		</option>
                                <option value="KWAW">Kerala Water Authority (KWA)		</option>
                                <option value="LWBW">Ludhiana Water Board	</option>
                                <option value="MPUN">Madhya Pradesh Urban (e-Nagarpalika)	</option>
                                <option value="MUCC">Municipal Corporation Chandigarh		</option>
                                <option value="MCJW">Municipal Corporation Jalandhar		</option>
                                <option value="MCAW">Municipal Corporation of Amritsar	</option>
                                <option value="MCGW">Municipal Corporation of Gurugram (MCG)		</option>
                                <option value="MCCW">Mysuru City Corporation	</option>
                                
                                <option value="NDMW">New Delhi Municipal Council (NDMC)		</option>
                                <option value="PBMC">Port Blair Municipal Council		</option>
                                <option value="PNMW">Pune Municipal Corporation	</option>
                                <option value="PMCW">Punjab Municipal Corporation/Council	</option>
                                <option value="RMCW">Ranchi Municipal Corporation		</option>
                                <option value="SMCW">Silvassa Municipal Council		</option>
                                <option value="SUMC">Surat Municipal Corporation		</option>
                                <option value="UITW">Urban Improvement Trust (UIT) - Bhiwadi		</option>
                               
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
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="24"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
