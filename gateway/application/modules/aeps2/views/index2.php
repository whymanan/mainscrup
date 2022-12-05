
<div class="row"  ng-controller="AepsBanks">

<div class="col-xl-12 order-xl-1">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                 <div class="col-8">
                     <h3 class="mb-0">Add New Adhar Payments</h3>
                 </div>
                 <div class="col-4 text-right">
                <a href="<?php echo  base_url('aeps2/download') ?>"><h3 class="mb-0">Download Drive</h3></a>
                 </div>
            </div>
        </div>
        <div class="card-body" id="aepsTransactionPanel">
           <form role="form" id="bankList">
               <!-- select Bank -->
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Select Your Bank</label>
                                    <select name="bankselect" id="bankselect" class="form-control" required >
                                   <option value="">Select Your Transactions Bank</option>
                                   <option ng-repeat="banklist in bankList" value="{{banklist.code}}">{{banklist.value}}</option>
                                 </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bank END -->

                    <!-- Transaction Start -->

                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Transactions Types</label>
                                    <select name="selectTransactionsTypes" id="selectTransactionsTypes" class="form-control" required>
                                    <option value="">Select Transactions Types</option>
                                    <option value="CW">Cash Withdrawal</option>
                                    <option value="BE">Balance Enquiry</option>
                                    <option value="MS">Mini Statement</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                <label class="form-control-label">Adhar Number</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                    <input class="form-control" name="adharCardNumber" placeholder="xxxx-xxxx-xxxx-xxxx" type="number">
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label class="form-control-label">Mobile Number</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input class="form-control" name="tmobilenumber" placeholder="Enter Mobile Number" type="number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group" id="tAmountBox">
                                    <label class="form-control-label">Amount</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                        </div>
                                        <input class="form-control" name="transectionAmount" placeholder="0.00" type="number" value="">
                                        <input class="form-control" name="referenceno" value="<?php echo $rrn ?>" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- transaction End -->

                    <!-- BioMetric Data Start -->
                    <h4 class="heading-small text-muted mb-4">Bio Metric information</h4>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Select Biometric Device</label>
                                    <select name="device-select" id="device-select" class="form-control" required>
                                        <option>Select Device</option>
                                        <option value="mantra-mfs-100">Mantra MFS100</option>
                                        <option value="mph-se002a">Morpho</option>
                                    </select>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4" />
                    <div class="text-center">
                        <button type="button" name="bioMetricCapture" id="bioMetricCapture" class="btn btn-primary my-4" disabled>Capture</button>
                        <!-- <button type="submit" name="bioMetricSubmit" id="bioMetricSubmit" class="btn btn-primary my-4" disabled>Submit</button> -->
                    </div>
                        <!-- BioMetric Data END -->
            </form>

        </div>
    </div>
</div>

</div>