<div class="row">
    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Add Balance</h3>
                    </div>
                    <div class="col-4 text-right">
 <h3 class="mb-0">Balance :- Rs : <?php echo $bal?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('Superadmin/superadmin/submitWallet'); ?>" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    <div class="pl-lg-4">

                        <div class="row">
                           
                            <div class="col-sm-2 ">
                            <p style=" padding:15px;"> Amount <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4" ">
                            <p style=" padding:10px;">
                                <input name="amount" type="text" class="form-control">
                            </div>
                             <div class="col-sm-2">
                                <p style="padding:15px;"> Our Bank <span style="color:red;"> * </span> </p>
                            </div>
                           <div class="col-sm-4">
                               
                                    <select name="bank"  class="form-control">
                                        <?php foreach($bank as $row){ ?>
                                        <option><?php echo $row['bank_name']." ".$row['account_no']?></option>
                                        <?php } ?>
                                        </select>                             
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p style="padding:15px;"> UTR/Refrence No <span style="color:red;"> * </span>
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <div class="checkbox">
                                    <input name="reference" type="text"  class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Stock type <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4">
                               
                                    <select name="stock_type"  class="form-control">
                                        <option>Main Bal</option></select>                             
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-sm-2">
                                <p style="padding:15px;"> Remark <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4">
                             <input name="remark" type="text"   class="form-control">

                            </div>
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Payment Mode <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4">
                               <select name="mode"  class="form-control">
                                        <option>SAME BANK FUND TRANSFER</option>
                                        <option>OTHER BANK FUND TRANSFER</option>
                                        <option>ATM FUND TRANSFER</option>
                                        <option>IMPS/UPI</option>
                                        <option>OTHER</option>
                                        </select>    
                            </div>
                           
                        </div>

                        <div class="row">
                             
                            <div class="col-sm-2">
                                <p style="padding:15px;"> Balance Type<span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4">
                               <select name="balance_type"  class="form-control">
                                        <option value="Legal Balance">Legal Balance</option>
                                        <option value="DMT Balance">DMT Balnce</option>
                                        <option value="Recharge Balance">Recharge Balance</option>
                                       
                                        </select>    
                            </div>
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-4">
                            <div class="text-center">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"
                                autocomplete="off">
                            <input type="hidden" id="type" value="add" autocomplete="off">
                            <input type="submit" name="" value="Submit" class="btn btn-primary">
                            <input type="submit" name="" value="Reset" class="btn btn-danger">
                        </div>
                            </div>
                           
                        </div>
                        

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>