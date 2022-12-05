

<?php if ($transaction->transection_status):
  $icon = '<i class="fas fa-check text-success"></i>';

  ?>
  <div class="card card-pricing border-0 text-center mb-4">

<?php else:
  $icon = '<i class="fas fa-exclamation-triangle text-danger"></i>';
?>
  <div class="card card-pricing border-0 text-center mb-4">
<?php endif; ?>
  <div class="card-header bg-transparent">
    <h4 class="text-uppercase ls-1  py-3 mb-0"><?php echo $transaction->transection_msg; ?> - Code: <?php echo $transaction->transection_respcode; ?></h4>
  </div>
  <div class="card-body">
    <div class="display-2 "><?php echo $icon; ?></div>
    <div class="display-2 ">Rs. <?php echo number_format($transaction->transection_amount, 2) ?></div>
    <span class="">Transaction ID: <span class="text-dark"><?php echo $transaction->transection_id ?></span> </span>
    <span class="">Reference Number: <span class="text-dark"><?php echo $transaction->reference_number ?></span></span>
    <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="table-responsive">
            <table class="table mt-5 text-left">
              <tbody>
                <tr>
                  <td class="px-0 ">Account Name</td>
                  <td class="px-0 ">
                    <?php echo $transaction->beneficiary->beneficiary_name;  ?>
                  </td>
                  <td class="px-0 ">Bank Account</td>
                  <td class="px-0 ">
                    <?php echo $transaction->beneficiary->beneficiary_account_number;  ?>
                  </td>

                </tr>
                <tr>
                  <td class="px-0 ">Bank Name</td>
                  <td class="px-0 ">
                    <?php echo $transaction->beneficiary->beneficiary_bank_name;  ?>
                  </td>
                  <td class="px-0 ">IFSC Code</td>
                  <td class="px-0 ">
                    <?php echo $transaction->transection_bank_ifsc;  ?>
                  </td>

                </tr>
                <tr>
                  <td class="px-0 ">Mobile Number</td>
                  <td class="px-0 ">
                    <?php echo $transaction->transection_mobile; ?>
                  </td>
                  <td class="px-0 ">Transection Type</td>
                  <td class="px-0 "><?php echo $transaction->transection_type; ?>
                  </td>

                </tr>

                <tr>
                  <td class="px-0 ">Bank Reference</td>
                  <td class="px-0 ">
                    <?php
                      if (isJson($transaction->transection_response)) {
                        $transection_response = json_decode($transaction->transection_response);
                      }
                      echo $transection_response->bankRef;
                     ?>
                  </td>
                  <td class="px-0 ">Transaction Date</td>
                  <td class="px-0 "><?php echo my_date_show_time($transection_response->transDate) ?>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
  <div class="card-footer bg-transparent">
    <a href="<?php echo base_url('dmt'); ?>" class=" ">Add New Transaction</a>
  </div>
</div>
