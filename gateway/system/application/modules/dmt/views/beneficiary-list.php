<div class="row">
  <div class="col-xl-12">
    <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush">
            <?php foreach ($beneficiary as $value):
              ?>
              <tr>
                <th>Sender</th>
                <td><?php echo $value->title . " " .  $value->first_name . " " . $value->last_name ?></td>
                <th>Mobile Number</th>
                <td><?php echo $value->beneficiary_mobile ?></td>
                <th>Monthly Balance</th>
                <td><?php echo "25000"; ?></td>
              </tr>
              <tr>
                <th>Sender Type</th>
                <td><?php echo "NON-KYC"; ?></td>
                <th>KYC Status</th>
                <td><?php echo "PENDING" ?></td>
                <th>Available Balance</th>
                <td><?php echo "25000"; ?></td>
              </tr>
              <tr>
                <th>Verification Status</th>
                <td><?php echo "Customer validate"; ?></td>
                <th>KYC Type</th>
                <td><?php echo "PENDING" ?></td>
                <th>Address</th>
                <td><?php echo "-"; ?></td>
              </tr>

              <?php
              endforeach;
              ?>
        </table>
      </div>
  </div>
</div>
