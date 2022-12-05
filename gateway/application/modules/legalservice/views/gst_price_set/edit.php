
<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('legalservice/LegalController/gst_priceupdate'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-6">
                      <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Services</label>
                        <select class="form-control" name="service">
                             <option value="53">GST Registration</option>
                            <option value="54">GST Return</option>
                            <option value="57">ITR Bussioness</option>
                            <option value="58">ITR Salary</option>
                            <option value="59">MSME Registation</option>
                        </select>
                        
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Set Amount</label>
                        <input type="number" class="form-control" value="<?php echo $gst[0]['price'];?>" name="amount" placeholder="">
                    </div>
                </div>
            <div class="col-2">
                  <input type="hidden"   name="role_id" class='btn btn-primary' value="<?php echo $gst[0]['role']?>"  required>
                  <input type="hidden"   name="id" class='btn btn-primary' value="<?php echo $gst[0]['id']?>"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
                   <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
</div>