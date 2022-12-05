 <div class="col-xl-12">
      <div class="card">
          <div class="card-header border-0">
              <div class="row align-items-center">
                  <div class="col">
                      <h3 class="mb-0" id="tabs">PAY Amount</h3>
                  </div>
              </div>
          </div>
          <div class="">
              <!-- Projects table -->
              <table class="table align-items-center table-flush" id="gstlist">
                  <thead class="thead-light">
                      <tr>
                          <th scope="col">Gst R.No</th>
                          <th scope="col">MEMBER ID</th>
                          <th scope="col">Service Type</th>
                          <th scope="col">Amount</th>
                          <th scope="col">GST Charge</th>
                          <th scope="col">SubTotal</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php $tempstore=[]; $i=0; $total=count($gst_data); foreach($gst_data as $value) { 
                         $tempstore[$i]=$value['gst_id']; ?>
                       
                      <tr>
                          <td><?php echo $value['referance_number'];?></td>
                          <td><?php echo $value['member_id'];?></td>
                          <td><?php echo $value['service_type'];?></td>
                          <td><?php echo "250" ;?></td>
                          <td><?php echo "18%";?></td>
                          <td><?php echo 250+(250*18/100);?></td>
                      </tr>
                      <?php $i++; }?>
                        <td colspan="8" style="text-align: center;">
                        <form method="post" action="<?php echo base_url('legalservice/LegalController/details')?>">
                        <p class="row justify-content-center">
                          <input type="email" name="email" class="form-control col-6" placeholder="@gmail.com" required/>
                      </p>
                      <p class="row justify-content-center">
                            <span style="font-size:16px;font-weight:bold;text-align:center">TOTAL&nbsp&nbsp:</span>&nbsp&nbsp₹<?php echo (250+(250*18/100))*$total;?>
                      </p>
                      <p class="row justify-content-center">
                         <?php foreach($tempstore as $value){ ?>
                           <input type="hidden" name="array_gst[]" value="<?php echo $value?>">
                          <?php }?>
                          <input type="hidden" name="price" value="<?php echo (250+(250*18/100))*$total;?>">
                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>"
                            value="<?php echo $this->security->get_csrf_hash();?>" autocomplete="off">
                          <input type="hidden" id="type" value="add" autocomplete="off">
                          <input type="hidden" name="service" value="<?php echo $gst_data[0]['service_type']?>">
                          <input type="submit" class="btn btn-primary" id="PAY"
                          [pp value="PAY">
                        </p>
                        </form>
                       </td>
                     </tr>
                  </tbody>
              </table>
              </hr>
          </div>
      </div>
  </div>
  