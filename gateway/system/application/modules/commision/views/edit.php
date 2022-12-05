<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Edit User</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="col-xl-12 order-xl-1">
                      <table class=" table-bordered text-center">
                          <thead>
                              <tr>
                                  <th>Service/Slab</th>
                                  <?php
                                  foreach ($slab as $slabr) {

                                  ?>
                                      <th><?php echo $slabr['start'].' - '.$slabr['end'] ?>
                                      </th>
                                  <?php
                                  } ?>
                              </tr>
                          </thead>
                          <?php $row = 0;
                          foreach ($service as $serve) { ?>
                              <tr>

                                  <th>
                                      <?php echo  $serve['name'] ?>
                                  </th>

                                  <?php
                                  foreach ($slab as $slabr) {
                                    $commission = $this->commission_model->get_commision(['role' => $role, 'service' => $serve['id'], 'slab' =>  $slabr['slab_id']]);
                                  ?>

                                      <td>
                                          <input type="number" min='0' class="form-control" name="rate[<?php echo $serve['id'] ?>][<?php echo $slabr['slab_id'] ?>]" value="<?php echo $commission; ?>"> </td>

                                  <?php
                                  } ?>
                              </tr>
                          <?php
                          } ?>
                      </table>
              </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="vendor"] option[value="<?php echo $user->parent ?>"]').attr("selected", "selected");
    });
</script>
