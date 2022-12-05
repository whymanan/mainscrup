<div class="row"  ng-controller="AepsBanks">
     <div class="col-xl-12 order-xl-1">
         <div class="card">
             <div class="card-header">
                 <div class="row align-items-center">
                     <div class="col-8">
                         <h3 class="mb-0">Add New Adhar Payments</h3>
                     </div>
                     <div class="col-4 text-right">

                     </div>
                 </div>
             </div>
             <div class="card-body" id="aepsTransactionPanel">
               <form role="form" id="bankList">
                   <div class="pl-lg-4">
                     <div class="row">
                         <div class="col-lg-6">
                             <div class="form-group">
                                 <label class="form-control-label">Select Your Bank</label>
                                 <select name="bank-select" class="form-control" required >
                                   <option value="">Select Your Transactions Bank</option>
                                   <option ng-repeat="banklist in bankList" value="{{banklist.code}}">{{banklist.value}}</option>
                                 </select>
                             </div>
                             <div class="form-group">
                               <button type="submit" class="btn btn-success" name="bank-select-confirm" disabled>Confirm</button>
                               <button type="reset" class="btn btn-primary" name="bank-select-confirm">Resat</button>
                             </div>
                         </div>
                         <!-- <div class="col-lg-6">
                           <div class="card bg-gradient-default">
                             <div class="card-body">
                               <h3 class="card-title text-white">Services Objectives by AePS</h3>
                               <blockquote class="blockquote text-white mb-0">
                                 <p>To empower a bank customer to use Aadhaar as his/her identity to access his / her respective Aadhaar enabled bank account and perform basic banking transactions like cash deposit, cash withdrawal, Intrabank or interbank fund transfer, balance enquiry and obtain a mini statement through a Business Correspondent</p>
                                 <footer class="blockquote-footer text-danger">National Payments Corporation of India  <cite title="Source Title">(NPCI)</cite></footer>
                               </blockquote>
                             </div>
                           </div>
                         </div> -->
                         <div class="col-lg-6">
                           <div class="card bg-gradient-danger">
                             <div class="card-body">
                               <h3 class="card-title text-white"> AePS Services is temporary unavailable</h3>
                               <blockquote class="blockquote text-white mb-0">
                                 <p>AePS services are currently unavailable. we will get back to next Tuesday.</p>
                                 <footer class="blockquote-footer text-white">EmoPay Team  <cite title="Source Title">(ViteFintech)</cite></footer>
                               </blockquote>
                             </div>
                           </div>
                         </div>
                     </div>
                   </div>
               </form>

             </div>
         </div>
     </div>
 </div>
