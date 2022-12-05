<html>
    
    <head>
        
        <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
            <style type="text/css">
            
            span.cls_002{font-family:Arial,serif;font-size:10.6px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
            div.cls_002{font-family:Arial,serif;font-size:10.6px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
            span.cls_003{font-family:Arial,serif;font-size:9.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
            div.cls_003{font-family:Arial,serif;font-size:9.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
            span.cls_004{font-family:Arial,serif;font-size:9.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
            div.cls_004{font-family:Arial,serif;font-size:9.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
            
            </style>
    </head>
    
    <body onload="window.print()">
        <?php $response = json_decode($result->transection_response)?>
        <div style="position:absolute;left:50%;margin-left:-306px;top:0px;width:612px;height:792px;border-style:outset;overflow:hidden">
            
            <div style="position:absolute;left:0px;top:0px">
                
                <img src="<?php echo base_url() ?>/optimum/aepsprint.png" width=612 height=792>
            </div>
            
            <div style="position:absolute;left:209.66px;top:126.97px" class="cls_002">
                <span class="cls_002">Customer Copy - <?php if($result->transection_type == "MS") echo "AEPS Mini Statememt";
                if($result->transection_type == "CW") echo "AEPS Cash Withdrawal";
                if($result->transection_type == "BE") echo "AEPS Balance Enquiry"; ?> </span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:167.25px" class="cls_002">
                <span class="cls_002">Date and Time:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:167.25px" class="cls_002">
                <span class="cls_002"><?php echo $result->created ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:188.40px" class="cls_002">
                <span class="cls_002">Reference ID:</span>
                </div>
                
            <div style="position:absolute;left:264.00px;top:188.40px" class="cls_002">
                <span class="cls_002"><?php echo $result->reference_number ?></span>
            </div>
                
            <div style="position:absolute;left:150.00px;top:209.55px" class="cls_002">
                <span class="cls_002">Agent ID:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:209.55px" class="cls_002">
                <span class="cls_002"><?php echo $result->member_id ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:230.70px" class="cls_002">
                <span class="cls_002">BC Name:</span>
            </div>
            
            
            <div style="position:absolute;left:264.00px;top:230.70px" class="cls_002">
                <span class="cls_002"><?php if(isset($response->name)) echo ($response->name) ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:251.85px" class="cls_002">
                <span class="cls_002">BC Location:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:251.85px" class="cls_002">
                <span class="cls_002"><?php echo $result->location ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:273.00px" class="cls_002">
                <span class="cls_002">Aadhaar No./VID:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:273.00px" class="cls_002">
                <span class="cls_002">XXXXXXXX<?php if(isset($response->last_aadhar)) echo ($response->last_aadhar) ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:294.15px" class="cls_002">
                <span class="cls_002">RRN:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:294.15px" class="cls_002">
                <span class="cls_002"><?php if(isset($response->bankrrn)) echo ($response->bankrrn) ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:315.30px" class="cls_002">
                <span class="cls_002">Response Message:</span>
                
            </div>
            
            <div style="position:absolute;left:264.00px;top:315.30px" class="cls_002">
                <span class="cls_002"><?php if(isset($response->message)) echo $response->message ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:336.45px" class="cls_002">
                <span class="cls_002">Txn Status:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:336.45px" class="cls_002">
                <span class="cls_002"><?php if(isset($result->transection_msg) == "Request Completed") echo "Success" ?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:357.60px" class="cls_002">
                <span class="cls_002">Amount:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:357.60px" class="cls_002">
                <span class="cls_002"><?php if(isset($response->amount)){ echo ($response->amount."₹");}else{echo 0 ; }?></span>
            </div>
            
            <div style="position:absolute;left:150.00px;top:378.75px" class="cls_002">
                <span class="cls_002">A/c bal:</span>
            </div>
            
            <div style="position:absolute;left:264.00px;top:378.75px" class="cls_002">
                <span class="cls_002"> <?php if(isset($response->balanceamount)) echo ($response->balanceamount."₹") ?></span>
            </div>
            
            <div style="position:absolute;left:110.18px;top:672.79px" class="cls_003">
                <span class="cls_003">Ab Whatsapp number </span>
                <span class="cls_004">+91 7905779865</span>
                <span class="cls_003"> dwara prapt karein apne bills, transaction status and</span>
            </div>
            
            <div style="position:absolute;left:110.18px;top:683.21px" class="cls_003">
                <span class="cls_003">transaction receipt ki details.</span>
            </div>
            
            <div style="position:absolute;left:190.95px;top:720.94px" class="cls_003">
                <span class="cls_003">NOTE: No charges are required to pay for this transaction</span>
            </div>
            
            <?php if($result->transection_type == "MS"): ?>
            
                <!-- Projects table -->
                <table style="position:absolute;left: 220.95px;top: 434.94px;" >
                    <thead>
                        <tr>
                                <th style="position:relative;left: -87.05px;top: -3.06px;" > <span class="cls_004">Date</span> </th>
                                <th style="position:absolute;left: -17.05px;top: -3.06px" > <span class="cls_004">TxnType</span> </th>
                                <th style="position:absolute;left: 77.95px;top: -3.06px" > <span class="cls_004">Amount</span> </th>
                                <th style="position:absolute;left: 167.95px;top: -3.06px;" > <span class="cls_004">Narration</span>  </th>
                        </tr>
                    </thead>
                    
                    <?php foreach($response->ministatement as $value){ ?>
                        <tbody>
                          <tr>
                            <td style="position:relative;left: -87.05px;" > <span class="cls_004"> <?php echo $value->date ?> </span> </td>
                            <td style="position:relative;left: -55.05px"> <span class="cls_004"> <?php echo $value->txnType ?> </span> </td>
                            <td style="position:relative;left: 12.95px"> <span class="cls_004"> <?php echo $value->amount ?> </span> </td>
                            <td style="position:relative;left: 66.95px"> <span class="cls_004"> <?php echo $value->narration ?> </span> </td>
                          </tr>
                        </tbody>
                    <?php } ?>
                    
                </table>
            <?php endif ?> 
            
        </div>
    
    </body>
    
</html>
