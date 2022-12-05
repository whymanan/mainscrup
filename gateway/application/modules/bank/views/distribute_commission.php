<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-small text-muted mb-4">Commission Distribute</h4>
                    </div>
                    <div class="col-4 text-right">
                      <h3 class="mb-0">Balance :- Rs : <?php echo $bal?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('bank/distribute_commission'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="pl-lg-4">

                        <div class="row">
                            <div class="col-sm-2" >

                                <label class="form-control-label">Retailer ID
                                        <button type="button" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="Select the parent of this user">
                                            <i class="ni ni-bulb-61"></i>
                                        </button>
                                    </label>
                            </div>
    
                            <div class="col-sm-4" >
                                <input name="vendor" id="vendor" class="form-control" type="text"required>
                                       
                            </div>
                            
                               
                        <div class="col-sm-2" >
                            <p > Quantity of Account <span style="color:red;"> * </span> </p>
                        </div>
                        <div class="col-sm-4" >
                            <p >
                                <input name="quantity" id="amount" type="text" class="form-control" value="0" >

                            </p>
                        </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p > Created <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-10">
                               
                                     <input name="created" type="date" id="" class="form-control">
                                
                            </div>
                                 
                        </div>
</br>
</br>
                            <div class="text-center container">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" >
                                <input type="hidden" name="balance" value="<?php echo $bal?>" >
                                <input type="submit" name="" value="Submit" class="btn btn-primary">
                                <input type="submit" name="" value="Reset" class="btn btn-danger">
                            </div>



              
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

