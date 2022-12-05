<div class="row">
    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Send File</h3>
                    </div>
                    <div class="col-4 text-right">
 
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('gstfiling/submit'); ?>" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    <div class="pl-lg-4">

                        <div class="row">
                           
                            <div class="col-sm-2 ">
                            <p style=" padding:15px;"> Member Id <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4" >
                            <p style=" padding:10px;">
                                <input name="member_id" type="text" value="<?php echo $_SESSION['member_id']; ?>" class="form-control" readonly>
                            </div>
                             <div class="col-sm-2">
                                <p style="padding:15px;"> Accepted Date <span style="color:red;"> * </span> </p>
                            </div>
                           <div class="col-sm-4">
                               
                                   <input type="date" name="accepted_date" class="form-control">                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p style="padding:15px;">Refrence No <span style="color:red;"> * </span>
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <div class="checkbox">
                                    <input name="reference_id" type="text" value="<?php echo random_string('alnum',10); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <p style="padding:15px;"> File Type <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4">
                               
                                    <select name="type"  class="form-control">
                                        <option value="itr">itr</option></select>                             
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-sm-2">
                                <p style="padding:15px;"> Choose a file <span style="color:red;"> * </span> </p>
                            </div>
                            <div class="col-sm-4">
                             <input name="image_file" type="file"   class="form-control">

                            </div>
                            <div class="col-sm-2">
                                <p style="padding:15px;"> <span style="color:red;">  </span> </p>
                            </div>
                            <div class="col-sm-4">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"
                                autocomplete="off">
                            <input type="hidden" id="type" value="add" autocomplete="off">
                            <input type="submit" name="" value="Submit" class="btn btn-primary">
                            <input type="submit" name="" value="Reset" class="btn btn-danger">
                            </div>
                           
                        </div>

                       
                        

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>