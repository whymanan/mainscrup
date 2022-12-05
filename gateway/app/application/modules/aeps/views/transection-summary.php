

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
    <h4 class="text-uppercase ls-1  py-3 mb-0"><?php echo $transaction->msg; ?> - Code: <?php echo $transaction->respcode; ?></h4>
  </div>
  <div class="card-body">
    <div class="display-2 "><?php echo $icon; ?></div>
    <div class="display-2 ">Rs. <?php echo ($transaction->balance) ?></div>
    <span class="">Transaction ID: <span class="text-dark"><?php echo $transaction->transection_id ?></span> </span>
    <span class="">Reference Number: <span class="text-dark"><?php echo $transaction->aepsTransactionSbm->id->rrn ?></span></span>
    <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="table-responsive">
            <table class="table mt-5 text-left">
              <tbody>
                <tr>
                  <td class="px-0 ">Adhar Card Number</td>
                  <td class="px-0 ">
                    <?php echo $transaction->aepsTransactionSbm->aadharno;  ?>
                  </td>
                  <td class="px-0 ">Bank Account</td>
                  <td class="px-0 ">
                    <?php echo  $transaction->aepsTransactionSbm->invoiceNo;  ?>
                  </td>

                </tr>
                <tr>
                  <td class="px-0 ">Invoice Number</td>
                  <td class="px-0 ">
                    <?php echo $transaction->aepsTransactionSbm->invoiceNo;  ?>
                  </td>
                </tr>
                <tr>
                  <td class="px-0 ">Mobile Number</td>
                  <td class="px-0 ">
                    <?php //echo  ?>
                  </td>
                  <td class="px-0 ">Transection Type</td>
                  <td class="px-0 "><?php //if($transaction->aepsTransactionSbm->id->transactionType == "BE"){echo "Balance Enquery"}; ?>
                  </td>

                </tr>

                <tr>
                  <td class="px-0 ">Bank Reference</td>
                  <td class="px-0 ">
                    
                  </td>
                  <td class="px-0 ">Transaction Date</td>
                  <td class="px-0 "><?php //echo my_date_show_time($transection_response->transDate) ?>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
  <div class="card-footer bg-transparent">
    <a href="<?php echo base_url('aeps'); ?>" class=" ">Add New Transaction</a>
  </div>
</div>
