<div class="row">

     <div class="col-xl-12 order-xl-1">
         <div class="card">
             <div class="card-header">
                 <div class="row align-items-center">
                     <div class="col-8">
                         <h3 class="mb-0">Add New Distributor</h3>
                     </div>
                     <div class="col-4 text-right">

                     </div>
                 </div>
             </div>
             <div class="card-body">
                 <form name="validate" role="form" action="<?php echo base_url('distributor/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                     <h4 class="heading-small text-muted mb-4">Distributor information</h4>
                     <div class="pl-lg-4">

                         <div class="row">
                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">First name</label>
                                     <input type="text" name="firstname" class="form-control" required placeholder="First name">
                                 </div>
                             </div>
                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">Last name</label>
                                     <input type="text" name="lastname" class="form-control" required placeholder="Last name">
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">Mobile</label>
                                     <input type="text" name="phone_no" class="form-control" required placeholder="Mobie Number" value="">
                                 </div>
                             </div>
                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">Vendor</label>
                                     <select name="vendor" id="vendor" class="form-control" required>
                                         <option value="">Select Vendor</option>
                                     </select>
                                 </div>
                             </div>
                         </div>
                         <div class="row">

                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">Email address</label>
                                     <input type="email" name="email" class="form-control" placeholder="jesse@example.com">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <hr class="my-4" />
                     <!-- Address -->
                     <h6 class="heading-small text-muted mb-4">Other information</h6>
                     <div class="pl-lg-4">
                         <div class="row">
                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">Aadhar Number</label>
                                     <input type="text" name="adharcard" class="form-control" required placeholder="xxxx-xxxx-xxxx-xxxx" value="">
                                 </div>
                             </div>

                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">PAN Number</label>
                                     <input type="text" name="pancard" class="form-control uppercase"  placeholder="xxxxx-xxxx-x" required value="">
                                 </div>
                             </div>

                         </div>
                         <div class="row">
                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">Organization Name</label>
                                     <input type="text" name="organization_name" class="form-control" placeholder="XYZ pvt. ltd." value="">
                                 </div>
                             </div>

                             <div class="col-lg-6">
                                 <div class="form-group">
                                     <label class="form-control-label">GST Number</label>
                                     <input type="text" name="gst_no" class="form-control uppercase"  placeholder="xxxxx-xxxx-x" value="">
                                 </div>
                             </div>

                         </div>
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <label class="form-control-label">Full Address</label>
                                     <input name="address" class="form-control" required placeholder="Home Address" value="" type="text">
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label class="form-control-label">State</label>
                                     <select name="states" id="states" class="form-control" required>
                                         <option value="">Select State</option>
                                     </select>

                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label class="form-control-label">City</label>
                                     <select name="city" id="cities" class="form-control" required>
                                         <option value="">Select City</option>

                                     </select>
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="form-group">
                                     <label class="form-control-label">Postal code</label>
                                     <input type="number" name="pincode" class="form-control" placeholder="Postal code" required>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <hr class="my-4" />
                     <div class="text-center">
                         <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                         <button type="submit" class="btn btn-primary my-4">Submit</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
