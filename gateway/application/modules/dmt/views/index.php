<div class="row" >

    <div class="col-xl-12 order-xl-1" id="dmtTransactionPanel">
         <div class="card">
             <div class="card-header">
                 <div class="row align-items-center">
                     <div class="col-9">
                         <h3 class="mb-0">Add New DMT</h3>
                     </div>
                     <div class="col-3">
                         <h3 class="mb-0">Balance :- Rs : <?php echo $bal?></h3>
                     </div>
                 </div>
             </div>
             <div class="card-body" >
               <form role="form" name="dmtTransactionForm" id="dmtTransactionForm">
                  <div class="pl-lg-4">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label">Mobile Number</label>
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text">+91</span>
                            </div>
                            <input class="form-control" id="mobileSearch" name="dmt_mobile" placeholder="Enter Mobile" type="text" >
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                    <div class="card bg-gradient-default">
           
          </div>
          </div>
                    </div>
                     <div class="row">
                       <div class="col-lg-2">
                         <div class="form-group">
                           <label class="form-control-label">Title</label>
                           <select name="title" class="form-control" required>
                             <option value="Mr">Mr.</option>
                             <option value="Ms">Ms.</option>
                             <option value="Miss">Miss</option>
                             <option value="Mis">Mis.</option>
                           </select>
                         </div>
                       </div>
                       <div class="col-lg-5">
                         <div class="form-group">
                           <label class="form-control-label"> First Name</label>
                           <input type="text" name="first_name" class="form-control" required placeholder="first name" >
                         </div>
                       </div>
                       <div class="col-lg-5">
                         <div class="form-group">
                           <label class="form-control-label"> Last Name</label>
                           <input type="text" name="last_name" class="form-control" required placeholder="last name" >
                         </div>
                       </div>

                     </div>
                     <h4 class="heading-small text-black mb-4">Beneficiary Details:</h4>
                     <div class="row">
                       <div class="col-lg-6">
                         <div class="form-group">
                           <label class="form-control-label">Beneficiary Name</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fas fa-user"></i></span>
                             </div>
                             <input class="form-control" name="beneficiary_name" placeholder="User Name" type="text" >
                           </div>
                         </div>
                       </div>
                       <div class="col-lg-6">
                         <div class="form-group">
                           <label class="form-control-label">Beneficiary Mobile</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fa fa-phone"></i></span>
                             </div>
                             <input class="form-control" name="beneficiary_mobile" id="mobile" placeholder="Mobile Number" type="text" >
                           </div>
                         </div>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-lg-6">
                         <div class="form-group">
                           <label class="form-control-label"> Beneficiary Account Number</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                             </div>
                             <input class="form-control" name="beneficiary_account_number" id="account" placeholder="Account Number" type="text" required>
                           </div>
                         </div>
                       </div>
                       <div class="col-lg-4">
                         <div class="form-group">
                           <label class="form-control-label">Beneficiary IFSC</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fa fa-university"></i></span>
                             </div>
                             <input class="form-control" name="beneficiary_ifsc" id="ifsc" placeholder="Enter Ifsc Code" type="text" required>
                           </div>
                         </div>
                       </div>
                       <div class="col-lg-2">
                         <div class="form-group">
                               <label class="form-control-label">Verify </label>
                              <div class="input-group input-group-merge">
                             <button type="button" class="btn btn-success" name="button" id="verify"> Click </button>
                           </div>
                         </div>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-lg-6">
                         <div class="form-group">
                           <label class="form-control-label">Bank Name</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                             </div>
                             <input class="form-control" name="beneficiary_bank_name" placeholder="Enter Bank Name" type="text" required>
                           </div>
                         </div>
                       </div>
                       <div class="col-lg-3">
                         <div class="form-group">
                           <label class="form-control-label">Enter Amount</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fa">&#xf156;</i></span>
                             </div>
                             <input class="form-control" name="transaction_amount" id="amount"  placeholder="Enter Amount" type="text" required>
                             <input   id="total"  type="hidden" value="<?php echo $bal?>" >
                           </div>
                         </div>
                       </div>
                        <div class="col-lg-3">
                         <div class="form-group">
                           <label class="form-control-label">SURCHARGE</label>
                           <div class="input-group input-group-merge">
                             <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fa ">&#xf156;</i></span>
                             </div>
                             <input class="form-control" name="surcharge" id="surcharge"  type="text" readonly>
                           </div>
                         </div>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-lg-6">
                         <div class="form-group">
                           <label class="form-control-label">Remark</label>
                           <textarea class="form-control" name="dmt_remark" placeholder="Enter Transaction Note" type="text"> </textarea>
                         </div>
                       </div>

                     </div>
                     <div class="row">
                       <div class="col-lg-6">
                         <div class="form-group">
                           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                           <button type="submit" class="btn btn-success" name="button"> Submit </button>
                           <button type="reset" class="btn btn-primary" name="button"> Reset </button>
                         </div>
                       </div>
                     </div>
                   </div>
               </form>
             </div>
         </div>
     </div>
 </div>
