  <div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('recharge/RechargeController/electricity_commissioninsert'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                                    <label class="form-control-label">Service Provider</label>
                                          <select name="operator" class="form-control valid" aria-invalid="false">
                                    <option value="">Provider</option>
                                    <option value="Adani Electricity Mumbai Limited">Adani Electricity Mumbai Limited</option>
                                    <option value="Ajmer Vidyut Vitran Nigam Ltd">Ajmer Vidyut Vitran Nigam Ltd</option>
                                    <option value="APEPDCL - Eastern Power Distribution CO AP Ltd">APEPDCL - Eastern Power Distribution CO AP Ltd.</option>
                                    <option value="APSPDCL - Southern Power Distribution CO AP Ltd">APSPDCL - Southern Power Distribution CO AP Ltd. </option>
                                    <option value="Assam Power Distribution Company Ltd">Assam Power Distribution Company Ltd</option>

                                    <option value="Assam Power Distribution Company Ltd (NON-RAPDR)">Assam Power Distribution Company Ltd (NON-RAPDR) </option>
                                    <option value="Bangalore Electricity Supply">Bangalore Electricity Supply</option>
                                    <option value="BEST">BEST</option>
                                    <option value="Bhagalpur Electricity Distribution Company (P) Ltd ">Bhagalpur Electricity Distribution Company (P) Ltd </option>
                                    <option value="Bharatpur Electricity Services Ltd. (BESL)">Bharatpur Electricity Services Ltd. (BESL)</option>
                                    <option value="Bikaner Electricity Supply Limited (BkESL)">Bikaner Electricity Supply Limited (BkESL) </option>

                                    <option value="Brihan Mumbai Electric Supply And Transport Undertaking">Brihan Mumbai Electric Supply And Transport Undertaking</option>
                                    <option value="249">Brihanmumbai Electric Supply And Transport </option>
                                    <option value="Brihanmumbai Electric Supply And Transport">BSES Rajdhani Power Limited</option>
                                    <option value="BSES Yamuna Power Limited">BSES Yamuna Power Limited </option>
                                    <option value="Calcutta Electricity Supply Co. Ltd.">Calcutta Electricity Supply Co. Ltd. </option>
                                    <option value="CESU, Odisha">CESU, Odisha </option>

                                    <option value="Chamundeshwari Electricity Supply Corp Ltd (CESCOM)">Chamundeshwari Electricity Supply Corp Ltd (CESCOM)</option>
                                    <option value="Chandigarh Electricity Department">Chandigarh Electricity Department </option>
                                    <option value="Chhattisgarh Electricity Board">Chhattisgarh Electricity Board </option>
                                    <option value="Dakshin Gujarat Vij Company Ltd">Dakshin Gujarat Vij Company Ltd </option>
                                    <option value="Dakshin Haryana Bijli Vitran Nigam (DHBVN)">Dakshin Haryana Bijli Vitran Nigam (DHBVN)</option>
                                    <option value="Daman and Diu Electricity Department">Daman and Diu Electricity Department </option>

                                    <option value="Department of Power, Nagaland">Department of Power, Nagaland </option>
                                    <option value="Dnh Power Distribution Company Limited">Dnh Power Distribution Company Limited </option>
                                    <option value="Goa Electricity Department">Goa Electricity Department</option>
                                    <option value="Government of Puducherry Electricity Department">Government of Puducherry Electricity Department </option>
                                    <option value="Gulbarga Electricity Supply Company Limited">Gulbarga Electricity Supply Company Limited</option>
                                    <option value="Himachal Pradesh Electricity">Himachal Pradesh Electricity </option>

                                    <option value="Hubli Electricity Supply Company Ltd (HESCOM)">Hubli Electricity Supply Company Ltd (HESCOM)</option>
                                    <option value="India Power Corporation - West Bengal">India Power Corporation - West Bengal </option>
                                    <option value="India Power Corporation Limited">India Power Corporation Limited</option>
                                    <option value="Jaipur Vidyut Vitran Nigam Ltd">Jaipur Vidyut Vitran Nigam Ltd</option>
                                    <option value="Jammu and Kashmir Power Development Department">Jammu and Kashmir Power Development Department </option>
                                    <option value="Jamshedpur Utilities and Services Company">Jamshedpur Utilities and Services Company</option>

                                    <option value="Jharkhand Bijli Vitran Nigam Limited (JBVNL)">Jharkhand Bijli Vitran Nigam Limited (JBVNL) </option>
                                    <option value="Jodhpur Vidyut Vitran Nigam Ltd">Jodhpur Vidyut Vitran Nigam Ltd </option>
                                    <option value="Kanpur Electricity Supply Company Ltd">Kanpur Electricity Supply Company Ltd </option>
                                    <option value="Kerala State Electricity Board Ltd. (KSEBL)">Kerala State Electricity Board Ltd. (KSEBL) </option>
                                    <option value="Kota Electricity Distribution Limited (KEDL)">Kota Electricity Distribution Limited (KEDL) </option>
                                    <option value="M.p. Madhya Kshetra Vidyut Vitaran - Agriculture">M.p. Madhya Kshetra Vidyut Vitaran - Agriculture </option>

                                    <option value="M.P. Madhya Kshetra Vidyut Vitaran - RURAL">M.P. Madhya Kshetra Vidyut Vitaran - RURAL </option>
                                    <option value="M.P. Madhya Kshetra Vidyut Vitaran - URBAN">M.P. Madhya Kshetra Vidyut Vitaran - URBAN </option>
                                    <option value="M.P. Paschim Kshetra Vidyut Vitaran">M.P. Paschim Kshetra Vidyut Vitaran </option>
                                    <option value="M.P. Poorv Kshetra Vidyut Vitaran - URBAN<">M.P. Poorv Kshetra Vidyut Vitaran - URBAN</option>
                                    <option value="M.P. Poorv Kshetra Vidyut Vitaran – RURAL">M.P. Poorv Kshetra Vidyut Vitaran – RURAL </option>
                                    <option value="Madhya Gujarat Vij Company Ltd Limited">Madhya Gujarat Vij Company Ltd Limited </option>

                                    <option value="Mahavitaran-Maharashtra State Electricity Distribution Company Ltd. (MSEDCL)">Mahavitaran-Maharashtra State Electricity Distribution Company Ltd. (MSEDCL) </option>
                                    <option value="Mangalore Electricity Supply Co. Ltd (MESCOM)">Mangalore Electricity Supply Co. Ltd (MESCOM) </option>
                                    <option value="Meghalaya Power Distribution Cor. Ltd">Meghalaya Power Distribution Cor. Ltd</option>
                                    <option value="MP-Poorv Kshetra Vidyut Vitaran Co. Ltd.(Jabalpur)">MP-Poorv Kshetra Vidyut Vitaran Co. Ltd.(Jabalpur)</option>
                                    <option value="Muzaffarpur Vidyut Vitran Limited">Muzaffarpur Vidyut Vitran Limited </option>
                                    <option value="NESCO, Odisha">NESCO, Odisha </option>

                                    <option value="New Delhi Municipal Council (NDMC) - Electricity">New Delhi Municipal Council (NDMC) - Electricity </option>
                                    <option value="Noida Power Company Limited">Noida Power Company Limited </option>
                                    <option value="North Bihar Power Distribution Company Ltd">North Bihar Power Distribution Company Ltd </option>
                                    <option value="Northern Power Distribution Of Telanagana Ltd">Northern Power Distribution Of Telanagana Ltd </option>
                                    <option value="Odisha Discoms B2B">Odisha Discoms B2B</option>
                                    <option value="Odisha Discoms B2C">Odisha Discoms B2C </option>

                                    <option value="Paschim Gujarat Vij Company Ltd">Paschim Gujarat Vij Company Ltd </option>
                                    <option value="Power and Electricity Department Government Of Mizoram">Power &amp; Electricity Department Government Of Mizoram</option>
                                    <option value="Punjab State Power Corporation Ltd (PSPCL)">Punjab State Power Corporation Ltd (PSPCL)</option>
                                    <option value="Rajasthan Vidyut Vitran Nigam Limited">Rajasthan Vidyut Vitran Nigam Limited </option>
                                    <option value="Sikkim Power - URBAN">Sikkim Power - URBAN </option>
                                    <option value="Sikkim Power – RURAL">Sikkim Power – RURAL </option>

                                    <option value="South Bihar Power Distribution Company Ltd">South Bihar Power Distribution Company Ltd </option>
                                    <option value="SOUTHCO, Odisha">SOUTHCO, Odisha</option>
                                    <option value="Tamil Nadu Electricity Board (TNEB)">Tamil Nadu Electricity Board (TNEB)</option>
                                    <option value="Tata Power Delhi Distribution Ltd">Tata Power Delhi Distribution Ltd</option>
                                    <option value="Tata Power Mumbai">Tata Power Mumbai</option>
                                    <option value="Torrent power - Agra">Torrent power - Agra</option>
                                    <option value="Torrent power - Ahmedabad">Torrent power - Ahmedabad</option>
                                    <option value="Torrent power - Bhiwandi">Torrent power - Bhiwandi</option>
                                    <option value="Torrent power - Surat">Torrent power - Surat</option>
                                    <option value="TP Ajmer Distribution Ltd (TPADL)">TP Ajmer Distribution Ltd (TPADL)</option>
                                    <option value="Tp Center Odisha Distribution Limited">Tp Center Odisha Distribution Limited</option>
                                    <option value="Tripura State Electricity Board<">Tripura State Electricity Board</option>
                                    <option value="Uttar Gujarat Vij Company Ltd">Uttar Gujarat Vij Company Ltd</option>
                                    <option value="Uttar Haryana Bijli Vitran Nigam (UHBVN)">Uttar Haryana Bijli Vitran Nigam (UHBVN)</option>
                                    <option value="Uttar Pradesh Power Corp Ltd (UPPCL) - RURAL">Uttar Pradesh Power Corp Ltd (UPPCL) - RURAL</option>
                                    <option value="Uttar Pradesh Power Corp. Ltd. (UPPCL) - URBAN">Uttar Pradesh Power Corp. Ltd. (UPPCL) - URBAN</option>
                                    <option value="Uttarakhand Power Corporation Limited">Uttarakhand Power Corporation Limited</option>
                                    <option value="WESCO Utility">WESCO Utility</option>
                                    <option value="West Bengal State Electricity Distribution Co. Ltd">West Bengal State Electricity Distribution Co. Ltd</option>
                                </select>
                                </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label " for="example3cols3Input">Mode</label>
                        <select  class="form-control" name="mode"> 
                           <option value="online">Online</option>
                           <option value="offline">Offline</option>
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
               
                  <div class="col-1">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">Max</label>
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
                
                </div>
            
            <div class="col-2">
              <input type="hidden" id="role_id"  name="role_id" class='btn btn-primary' value="<?php echo $role_id ?>"  required>
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="18"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>
