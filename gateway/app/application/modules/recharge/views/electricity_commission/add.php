<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/electricity_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <!--<div class="col-3">-->
                <!--    <div class="form-group">-->
                <!--                    <label class="form-control-label">Service Provider</label>-->
                <!--                        <select name="operator" id="operator" class="form-control select2">-->
                <!--                            <option value="">Select Provider</option>-->
                <!--                            <option value="ADEM">Adani Electricity	</option>-->
                <!--                            <option value="AVVR">Ajmer Vidyut Vitran Nigam Limited</option>-->
                <!--                            <option value="APDC">APDCL (Non-RAPDR) - ASSAM	)</option>-->
                <!--                            <option value="APDR">APDCL (RAPDR) - ASSAM	</option>-->
                <!--                            <option value="APEP">APEPDCL - Andhra Pradesh	</option>-->
                <!--                            <option value="APSP">APSPDCL - Andhra Pradesh	</option>-->
                                            
                <!--                            <option value="BESC">Bangalore Electricity supply company Ltd		</option>-->
                <!--                            <option value="BEUM">BEST Undertaking	</option>-->
                <!--                            <option value="BKEB">Bikaner Electricity Supply Limited	</option>-->
                <!--                            <option value="BSRD">BSES Rajdhani Power Limited		</option>-->
                <!--                            <option value="BSYD">BSES Yamuna Power Limited		</option>-->
                <!--                            <option value="CESC">Calcutta Electric Supply Corporation Limited		</option>-->
                                            
                <!--                            <option value="CESK">CESCOM - KARNATAKA		</option>-->
                <!--                            <option value="CSPD">Chhattisgarh State Power Distribution Company Ltd	</option>-->
                <!--                            <option value="DGVC">Dakshin Gujarat Vij Company Limited	</option>-->
                <!--                            <option value="DHBH">Dakshin Haryana Bijli Vitran Nigam	</option>-->
                <!--                            <option value="DADE">Daman And Diu Electricity		</option>-->
                <!--                            <option value="DNHP">DNH Power Distribution Company Limited	</option>-->
                                            
                <!--                            <option value="GOED">Goa Electricity Department		</option>-->
                <!--                            <option value="GESK">Gulbarga Electricity Supply Company Limited	</option>-->
                <!--                            <option value="HPSE">Himachal Pradesh State Electricity Board Ltd	</option>-->
                <!--                            <option value="HESK">Hubli Electricity Supply Company Ltd	</option>-->
                <!--                            <option value="JVVR">Jaipur Vidyut Vitran Nigam		</option>-->
                <!--                            <option value="JKPD">Jammu and Kashmir Power Development Department		</option>-->
                                            
                <!--                            <option value="JUSC">Jamshedpur Utilities &amp; Services (JUSCO)			</option>-->
                <!--                            <option value="JBVN">Jharkhand Bijli Vitran Nigam Limited	</option>-->
                <!--                            <option value="JDVR">Jodhpur Vidyut Vitran Nigam Limited		</option>-->
                <!--                            <option value="KESC">Kanpur Electricity Supply Company			</option>-->
                <!--                            <option value="KSEB">Kerala State Electricity Board Ltd			</option>-->
                <!--                            <option value="KEDR">Kota Electricity Distribution Limited			</option>-->
                                            
                <!--                            <option value="MGVG">Madhya Gujarat Vij Company Limited	</option>-->
                <!--                            <option value="MKMP">Madhya Kshetra Vitaran (Rural) - Madhya Pradesh		</option>-->
                <!--                            <option value="MKVU">Madhya Kshetra Vitaran (Urban) - Madhya Pradesh		</option>-->
                <!--                            <option value="MPDC">Meghalaya Power Dist Corp Ltd		</option>-->
                <!--                            <option value="MSEM">MSEDCL		</option>-->
                <!--                            <option value="MZVV">Muzaffarpur Vidyut Vitran		</option>-->
                                            
                <!--                            <option value="NESO">NESCO Utility			</option>-->
                <!--                            <option value="NDMC">New Delhi Municipal Council (NDMC)		</option>-->
                <!--                            <option value="NOPN">Noida Power Copmpany Limited		</option>-->
                <!--                            <option value="NBBR">North Bihar Power Distribution Co. Ltd		</option>-->
                <!--                            <option value="PGVG">Paschim Gujarat Vij Company Limited			</option>-->
                <!--                            <option value="PVMP">Paschim Kshetra Vidyut Vitaran - Madhya Pradesh			</option>-->
                                            
                <!--                             <option value="PKVU">Poorv Kshetra Vitaran (NBG-Urban) - MADHYA PRADESH		</option>-->
                <!--                            <option value="PKVR">Poorv Kshetra Vitaran (Rural) - MADHYA PRADESH		</option>-->
                <!--                            <option value="PSPC">Punjab State Power Corporation Limited		</option>-->
                <!--                            <option value="SNPN">SNDL Power - NAGPUR		</option>-->
                <!--                            <option value="SBBR">South Bihar Power Distribution Co. Ltd			</option>-->
                <!--                            <option value="SOTO">Southern Electricity Supply Company Of Odisha Limited	</option>-->
                                            
                <!--                            <option value="TNEB">Tamil Nadu Electricity Board			</option>-->
                <!--                            <option value="TAPM">Tata Power - MUMBAI		</option>-->
                <!--                            <option value="TAPM">Tata Power -Mumbai		</option>-->
                <!--                            <option value="TPAR">Tata Power AJMER - RAJASTHAN		</option>-->
                <!--                            <option value="TAPD">Tata Power Delhi Distribution Limited		</option>-->
                <!--                            <option value="TESS">Telangana Co-Operative Electric Supply Society Ltd		</option>-->
                                            
                <!--                            <option value="TSSP">Telangana State Southern Power Distribution Compan				</option>-->
                <!--                            <option value="TPAG">Torrent Power Limited - Agra		</option>-->
                <!--                            <option value="TPAH">Torrent Power Limited - Ahmedabad			</option>-->
                <!--                            <option value="TPBW">Torrent Power Limited - Bhiwandi			</option>-->
                <!--                            <option value="TPSR">Torrent Power Limited - Surat		</option>-->
                <!--                            <option value="TPCO">TP Central Odisha Distribution Limited (TPCODL)			</option>-->
                                            
                <!--                            <option value="TSTP">Tripura Electricity Corp Ltd		</option>-->
                <!--                            <option value="UGVG">Uttar Gujarat Vij Company Limited		</option>-->
                <!--                            <option value="UHBV">Uttar Haryana Bijli Vitran Nigam		</option>-->
                <!--                            <option value="URUP">Uttar Pradesh Power Corporation Ltd (RURAL)			</option>-->
                <!--                            <option value="UUUP">Uttar Pradesh Power Corporation Ltd (Urban - Smart Meter)			</option>-->
                <!--                            <option value="UPUK">Uttarakhand Power Corporation Ltd - UPCL		</option>-->
                                            
                <!--                            <option value="WESO">WESCO Utility				</option>-->
                <!--                            <option value="WBSE">West Bengal State Electricity Distribution Company Limited		</option>-->
                                           
                <!--                        </select>-->
                <!--                </div>-->
                <!--</div>-->
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
