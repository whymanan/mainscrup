
<style>
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.span1,.span2,.span3,.span4,.span5{
    color:red;
    font-size:12px;
}
</style>
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
                                <div class="form-group vali">
                                    <label class="form-control-label">Select Your Bank</label>
                                    <select name="bankselect" id="bankselect" class="form-control validation" required >
                                   <option value="">Select Your Transactions Bank</option>
                                   <option ng-repeat="banklist in bankList" value="{{banklist.code}}">{{banklist.value}}</option>
                                 </select>
                                 <span class="span1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bank END -->

                    <!-- Transaction Start -->

                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group vali">
                                    <label class="form-control-label">Transactions Types</label>
                                    <select name="selectTransactionsTypes" id="selectTransactionsTypes" class="form-control validation" required>
                                    <option value="">Select Transactions Types</option>
                                    <option value="CW">Cash Withdrawal</option>
                                    <option value="BE">Balance Enquiry</option>
                                    <option value="MS">Mini Statement</option>
                                    </select>
                                    <span class="span2"></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group vali">
                                <label class="form-control-label">Aadhar Number</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                    <input class="form-control validation" name="adharCardNumber" placeholder="xxxx-xxxx-xxxx-xxxx" type="text">
                                </div>
                                <span class="span3"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6" >
                                <div class="form-group vali">
                                    <label class="form-control-label">Mobile Number</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input class="form-control validation" name="tmobilenumber" placeholder="Enter Mobile Number" type="text">
                                    </div>
                                    <span class="span4"></span>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group vali" id="tAmountBox">
                                    <label class="form-control-label">Amount</label>
                                    
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                        </div>
                                        
                                        <!--<select name="transectionAmount" id="transectionAmount" class="form-control validation">-->
                                        <!--    <option value="">Select Amount</option>-->
                                        <!--    <option value="100">100</option>-->
                                        <!--    <option value="300">300</option>-->
                                        <!--    <option value="500">500</option>-->
                                        <!--    <option value="700">700</option>-->
                                        <!--    <option value="1000">1000</option>-->
                                        <!--    <option value="1200">1200</option>-->
                                        <!--    <option value="1500">1500</option>-->
                                        <!--    <option value="1800">1800</option>-->
                                        <!--    <option value="2000">2000</option>-->
                                        <!--    <option value="2500">2500</option>-->
                                        <!--    <option value="3000">3000</option>-->
                                        <!--    <option value="3500">3500</option>-->
                                        <!--    <option value="4000">4000</option>-->
                                        <!--    <option value="4500">4500</option>-->
                                        <!--    <option value="5000">5000</option>-->
                                        <!--    <option value="5500">5500</option>-->
                                        <!--    <option value="6000">6000</option>-->
                                        <!--    <option value="6500">6500</option>-->
                                        <!--    <option value="7000">7000</option>-->
                                        <!--    <option value="7500">7500</option>-->
                                        <!--    <option value="8000">8000</option>-->
                                        <!--    <option value="8500">8500</option>-->
                                        <!--    <option value="9000">9000</option>-->
                                        <!--    <option value="9500">9500</option>-->
                                        <!--    <option value="10000">10000</option>-->
                                        <!--</select>-->
                                        <input class="form-control validation" id="transectionAmount" name="transectionAmount" placeholder="0.00" type="text" value="">
                                        
                                        <input class="form-control" name="referenceno" value="<?php echo $rrn ?>" hidden>
                                    </div>
                                    <span class="span5"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- transaction End -->

                    <!-- BioMetric Data Start -->
                    <h4 class="heading-small text-muted mb-4 biom">Bio Metric information</h4>
                    <div class="pl-lg-4 biom">
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
<script>
    $('#bioMetricCapture').hide();
        $('.biom').hide();
    $(document).ready(function()
    {
     $('#bioMetricCapture').hide();
     $('.biom').hide();
     $('.validation').change(function()
     {
         var data=$('.validation').map((_,el) => el.value).get();
         if(data[0]=='')
         {
          $('.span1').text('**Please Select Bank');
         }
         else
         {
            $('.span1').empty();
         }
         if(data[1]=='')
         {
            $('.span2').text('**Please Select Type');
         }
         else{
            $('.span2').empty('**Please Select Type');  
         }
         if(data[2]!='')
         {
            if(!isNaN(data[2]))
            {
             if(data[2].length==12)
             {
                $('.span3').empty();
             }
             else
             {
                $('.span3').text('**Adhar contains 12 digit number');
             }
            }
            else
            {
                $('.span3').text('**only number is allow');
            }
         }
         else
         {
            $('.span3').text('**Please enter Adhar number');
         }
         if(data[3]!='')
         {
            if(!isNaN(data[3]))
            {
             if(data[3].length==10)
             {
                $('.span4').empty();
             }
             else
             {
                $('.span4').text('**Mobile contains 10 digit number');
             }
            }
            else
            {
                $('.span4').text('**only number is allow');
            }
         }
         else{
            $('.span4').text('**Please enter Mobile number');
         }
         if(data[1]=='CW')
         {
            if(data[4]!='')
           {
            if(!isNaN(data[4]))
            {
             if(data[4]<100 ||data[4]>10000)
             {
                $('.span5').text('**Amount will be greater than or equal to 100 and less than or equal to 10000  ');
             }
             else
             {
                $('.span5').empty();
             }
            }
            else
            {
                $('.span5').text('**only number is allow');
            }
           }
           else{
            $('.span5').text('**Please enter Amount');
           }
         }
         if(data[1]=='CW')
         {
             var data=$('.validation').map((_,el) => el.value).get();
           if(data[0]!='' && data[1]!='' && data[2]!='' && data[2].length==12 && data[3]!='' && data[3].length==10 && data[4]!='' && data[4]>=100 && data[4]<=10000)
            {
         $('#bioMetricCapture').show();
         $('.biom').show();
     }
           else
           {
         $('#bioMetricCapture').hide();
         $('.biom').hide();
     }
         }
         else
         {
           if(data[0]!='' && data[1]!='' && data[2]!='' && data[2].length==12 && data[3]!='' && data[3].length==10)
            {
         $('#bioMetricCapture').show();
         $('.biom').show();
     }
           else
           {
         $('#bioMetricCapture').hide();
         $('.biom').hide();
     }  
         }
     })
     $("input[name='transectionAmount']").keyup(function()
     {
         if($(this).val()!='' && $(this).val()>=100 && $(this).val()<=10000)
         {
            if(data[0]!='' && data[1]!='' && data[2]!='' && data[2].length==12 && data[3]!='' && data[3].length==10 && data[4]!='' && data[4]>=100 && data[4]<=10000)
     {
         $('#bioMetricCapture').show();
         $('.biom').show();
     }
     else
     {
         $('#bioMetricCapture').hide();
         $('.biom').hide();
     }
         }
     })
    })
</script>