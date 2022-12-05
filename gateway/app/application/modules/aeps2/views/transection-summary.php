

<?php //echo"<pre>";print_r($transaction);exit;
if ($transaction->status):
  $icon = '<i class="fas fa-check text-success"></i>';

  ?>
  <div class="card card-pricing border-0 text-center mb-4">

<?php else:
  $icon = '<i class="fas fa-exclamation-triangle text-danger"></i>';
?>
  <div class="card card-pricing border-0 text-center mb-4">
<?php endif; ?>
  <div class="card-header bg-transparent">
    <h4 class="text-uppercase ls-1  py-3 mb-0"><?php echo $transaction->message; ?> - Code: <?php echo $transaction->response_code; ?></h4>
  </div>
  
    <?php if($transaction->response_code == 1){ ?>
        <div class="card-body">
    <div class="display-2 "><?php echo $icon; ?></div>
    <div class="display-2 ">Rs. <?php if($transaction->balanceamount){ echo ($transaction->balanceamount) ;}?></div>
    <span class="">Transaction ID: <span class="text-dark"><?php echo $transaction->transection_id ?></span> </span>
    <span class="">Reference Number: <span class="text-dark"><?php  if($transaction->bankrrn)echo $transaction->bankrrn ?></span></span>
    <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="table-responsive">
            <table class="table mt-5 text-left">
              <tbody>
                <tr>
                  <td class="px-0 ">Adhar Card Number</td>
                  <td class="px-0 ">
                    <?php echo $transaction->aadharno;  ?>
                  </td>
                  <td class="px-0 ">Bank Account</td>
                  <td class="px-0 ">
                    <?php //echo  $transaction->aepsTransactionSbm->invoiceNo;  ?>
                  </td>

                </tr>
                <tr>
                  <td class="px-0 ">Invoice Number</td>
                  <td class="px-0 ">
                    <?php echo $transaction->bankiin;  ?>
                  </td>
                </tr>
                <tr>
                  <td class="px-0 ">Mobile Number</td>
                  <td class="px-0 ">
                    <?php echo $transaction->mobile; ?>
                  </td>
                  <td class="px-0 ">Transection Type</td>
                  <td class="px-0 "><?php if($transaction->transactionType == "BE"){echo "Balance Enquery";} elseif($transaction->transactionType == "CW"){ echo "Cash Withdrawal";} else{ echo "Mini Statement"; } ?>
                  </td>

                </tr>

                <tr>
                  <td class="px-0 ">Bank Reference</td>
                  <td class="px-0 "><?php if (isset($transaction->bankrrn)){echo $transaction->bankrrn;}?>   
                    
                  </td>
                  <td class="px-0 ">Transaction Date</td>
                  <td class="px-0 "><?php echo my_date_show_time(isset($transection->datetime)) ?>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      
        
        <?php if (isset($transaction->ministatement)) { ?>   
            <?php if($transaction->ministatement){ ?>      
                <div class="container">
                      <h2><Mini Statement</h2>
                      <!--<p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>            -->
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>TxnType</th>
                            <th>Amount</th>
                            <th>arration</th>
                          </tr>
                        </thead>
                    <?php foreach($transaction->ministatement as $value){ ?>
                        <tbody>
                          <tr>
                            <td><?php echo $value->date ?></td>
                            <td><?php echo $value->txnType ?></td>
                            <td><?php echo $value->amount ?></td>
                            <td><?php echo $value->narration ?></td>
                          </tr>
                    <?php } ?>
                        </tbody>
                      </table>
                </div>
            <?php } ?>    
       <?php  } ?>   
      
      
  </div>
    <?php }?>
  <div class="card-footer bg-transparent">
      
      
      
      
    <a href="<?php echo base_url('aeps2'); ?>" class=" ">Add New Transaction</a>
  </div>
</div>
