<div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('dmtv2/DmtvtwoController/update_surcharge'); ?>">
        
        <div class="form-row">
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Start Range</label>
                        <input type="number" class="form-control" id="start" name="start" placeholder="" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">End Range</label>
                            <input type="number" class="form-control" id="end" name="end"
                                placeholder="" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols3Input">SurCharge</label>
                        <input type="text" class="form-control" id="charge" name="charge"  placeholder="" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-control-label " for="example3cols3Input">flat</label>
                        <input type="checkbox" class="form-control" name="flat"> 
                    </div>
                </div>
            
            <div class="col-2">
              <input type="hidden" name="service_charge_id" id="service_charge_id" class='btn btn-primary' value=""  required>
              <input type="hidden" name="service_id" id="service_id" class='btn btn-primary' value="16"  required>
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Update</button>
                   </div>
                  
           
            </div>
        </div>
        
    </form>
</div>