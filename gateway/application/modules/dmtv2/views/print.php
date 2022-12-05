<html>
    <head> 
    </head>
    <body onload="window.print()">
        <div style="position:absolute;left:50%;margin-left:-306px;top:0px;width:612px;height:792px;border-style:outset;overflow:hidden">
        <div style="position:absolute;left:0px;top:0px">
        <img src="<?php echo base_url() ?>optimum/background.png" width=612 height=792></div>
        <div style="position:absolute;left:64.50px;top:126.79px" class="cls_003"><span class="cls_003">Transaction Details</span></div>
        <div style="position:absolute;left:76.50px;top:160.24px" class="cls_002"><span class="cls_002">Transaction Status</span></div>
        <div style="position:absolute;left:231.04px;top:160.24px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:160.39px" class="cls_004"><span class="cls_004"><?php if($result->transection_msg == "PENDING") echo "SUCCESS"; else echo $result->transection_msg ?></span></div>
        <div style="position:absolute;left:76.50px;top:186.71px" class="cls_002"><span class="cls_002">Amount</span></div>
        <div style="position:absolute;left:231.04px;top:186.71px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:186.86px" class="cls_004"><span class="cls_004">Rs <?php echo $result->transection_amount ?></span></div>
        <div style="position:absolute;left:76.50px;top:213.04px" class="cls_002"><span class="cls_002">Transaction ID</span></div>
        <div style="position:absolute;left:231.04px;top:213.04px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:213.04px" class="cls_002"><span class="cls_002"><?php echo $result->transection_id ?></span></div>
        <div style="position:absolute;left:76.50px;top:239.21px" class="cls_002"><span class="cls_002">Account Number</span></div>
        <div style="position:absolute;left:231.04px;top:239.21px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:239.21px" class="cls_002"><span class="cls_002"><?php echo $result->transaction_bank_account_no ?></span></div>
        <div style="position:absolute;left:76.50px;top:265.39px" class="cls_002"><span class="cls_002">Date & Time</span></div>
        <div style="position:absolute;left:231.04px;top:265.39px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:265.39px" class="cls_002"><span class="cls_002"><?php echo $result->created ?></span></div>
        <div style="position:absolute;left:76.50px;top:291.56px" class="cls_002"><span class="cls_002">Service Name</span></div>
        <div style="position:absolute;left:231.04px;top:291.56px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:291.56px" class="cls_002"><span class="cls_002">DMT</span></div>
        <div style="position:absolute;left:76.50px;top:317.74px" class="cls_002"><span class="cls_002">Bank IFSC Code</span></div>
        <div style="position:absolute;left:231.04px;top:317.74px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:317.74px" class="cls_002"><span class="cls_002"><?php echo $result->transection_bank_ifsc ?></span></div>
        <div style="position:absolute;left:76.50px;top:343.91px" class="cls_002"><span class="cls_002">Transaction Mobile</span></div>
        <div style="position:absolute;left:231.04px;top:343.91px" class="cls_002"><span class="cls_002">:</span></div>
        <div style="position:absolute;left:243.30px;top:343.91px" class="cls_002"><span class="cls_002"><?php echo $result->transection_mobile ?></span></div>
        <?php if(isset(json_decode($result->transection_response)->data->transfer->utr) && !empty(json_decode($result->transection_response)->data->transfer->utr)){?>
         <div style="position:absolute;left:76.50px;top:365.91px" class="cls_002"><span class="cls_002">Utr No.</span></div>
         <div style="position:absolute;left:231.04px;top:365.91px" class="cls_002"><span class="cls_002">:</span></div>
         <div style="position:absolute;left:243.30px;top:365.91px" class="cls_002"><span class="cls_002"><?php echo json_decode($result->transection_response)->data->transfer->utr; ?></span></div>
        <?php }?>
        </div>
        
        
    </body>
</html>
