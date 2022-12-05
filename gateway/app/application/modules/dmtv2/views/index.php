<div class="row">
    <div class="col-xl-12 order-xl-1">
         <div class="card">
             <div class="card-header">
                 <div class="row align-items-center">
                     <div class="col-8">
                         <h3 class="mb-0">Add New DMT</h3>
                     </div>
                 </div>
             </div>
             <div class="card-body">
               <form role="form" id="mobileSearch">
                   <div class="pl-lg-4">
                     <div class="row">
                         <div class="col-lg-6">
                             <div class="form-group">
                                 <label class="form-control-label">Enter Customer Mobile Number</label>
                                 <input type="text" name="phone_no" class="form-control" required placeholder="Mobile Number" value="">
                             </div>
                             <div class="form-group">
                               <button type="submit" class="btn btn-success" name="mobile-confirm">Search</button>
                               <button type="reset" class="btn btn-primary" name="reset">Reset</button>
                             </div>
                         </div>
                         <div class="col-lg-6">
                           <div class="card bg-gradient-default">
                             <div class="card-body">
                               <h3 class="card-title text-white">Wallet Balance</h3>
                               <blockquote class="blockquote text-white mb-0">
                                 
                                 <footer class="blockquote-footer text-white">Rs <?php echo $bal ?> </footer>
                               </blockquote>
                             </div>
                           </div>
                         </div>
                     </div>
                   </div>
               </form>
               <span id="dmtTransactionPanel"></span>
             </div>
         </div>
     </div>
 </div>
