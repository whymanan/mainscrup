 <div class="row justify-content-center">
     <div class="col-lg-10 col-md-7">
         <div class="card card-profile bg-secondary mt-5">

             <div class="card-body pt-7 px-5">
                 <div class="text-center mb-4">
                     <h3>Enter OTP sent to your Registered Mobile Number</h3>
                 </div>
                 <form>
                     <div class="form-group">
                         <div class="input-group input-group-merge input-group-alternative">
                             <div class="input-group-prepend">
                                 <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                             </div>
                             <input class="form-control" placeholder="Enter password" id="password" type="password" value="">
                             <input class="form-control" placeholder="Enter password" id="confirm" type="text" value="">

                         </div>

                     </div>
                     <div class="text-center"> <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                         <button type="button" id="get_user" class="btn btn-primary mt-2">Search</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>