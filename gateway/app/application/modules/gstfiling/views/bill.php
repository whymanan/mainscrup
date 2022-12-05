

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
if($fetch_user_details_byid->num_rows() > 0)
  { 
  $i= 1;
 foreach($fetch_user_details_byid->result() as $row)
 {
$organisation = $row->organisation;
$aadhar=$row->aadhar;
$address= $row->address;

}
}

if($fetch_aeps_bill_byid->num_rows() > 0)
  { 
  $i= 1;
 foreach($fetch_aeps_bill_byid->result() as $row)
 {
$transection_id = $row->transection_id;
$transection_type=$row->transection_type;
$transection_amount = $row->transection_amount;
$reference_number= $row->reference_number;
$transection_status= $row->transection_status;
$created = $row->created;

}
}
?>
<div class="container mt-5">
    <div class="d-flex justify-content-center row">
        <div class="col-md-12">
              <div class="row">
                        <div class="col-md-12 text-center">


                                             
                    </div>
                    
                </div>
            <div class="p-3 bg-white rounded ">
                


                  <div class="row">
                       <div class="col-md-4 justify-content-center">
                           </div>
                        <div class="col-md-4 justify-content-center">

 <img src="<?php echo base_url('optimum/logoside.png'); ?>" alt="Thank You"  style="width:200px;margin-left:150px;">

                                               <h3 class="text-danger text-center">Transaction Receipt</h3>
                    </div>
                    
                </div>
                
                
                  <div class="row">
                       <div class="col-md-4 justify-content-center">
                           </div>
                        <div class="col-md-4" style="margin-left:100px;">
                        <small class="text-dark ">Txn Type : <?php if($transection_type =='BE'){
                            echo 'Balance Enquiry';
                        }elseif($transection_type =='CW'){
                              echo 'Cash Withdrawal';
                        }else{
                          echo 'Cash Deposit';  
                        }
                        
                        ?></small>  <br>
                        <small class="text-dark">Date :<?php echo date('Y-m-d',strtotime($created)); ?> </small><br>  
                        <small class="text-dark">Time : <?php echo date('H:i:s',strtotime($created)); ?></small><br>  
                        <small class="text-dark">Amount:<?php echo $transection_amount; ?></small><br>  
                         <small class="text-dark">Adhar No. : <?php $a = $aadhar;
echo '********'; echo substr($a, 0, 3).substr($a,-1);?></small> <br>
                             <small class="text-dark">Txn Id : <?php  echo $transection_id ?></small> <br>
                             <small class="text-dark">RRN No : <?php echo $reference_number ?></small><br>
                              <small class="text-dark">status : <?php if($transection_status == '1'){echo 'Success';}else{echo 'Pending';} ?></small><br>
                             <small class="text-dark">Retail Name:<?php echo $organisation; ?></small><br>
                             <small class="text-dark">Retailer address:<?php echo $address; ?></small><br>

                    </div>
                    
                </div>


                <div class="row">
                     <div class="col-md-4 text-center">
                         </div>
                    <div class="col-md-4 text-center">
                        <hr>
                                              <a href="">
                                                  
                                                 <h6 class="text-primary">SkyPay Private Limited</h6></a>
                                                 <small>State: 09-Uttar Pradesh</small><br>
                                                 <small>Phone no.: 9792355052 , Email: info@skypay.com</small><br>

                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <hr>
                                              <a href="https://www.vitefintech.in/">
                                                 <h6 class="text-primary">Powered By: Vite fintech Private Limited<br>www.vitefintech.in</h6><br><h6></h6></a>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>