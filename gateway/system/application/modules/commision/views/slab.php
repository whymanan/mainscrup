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
                    ?>

                        <td>
                            <input type="number" min='0' class="form-control" name="rate[<?php echo $serve['id'] ?>][<?php echo $slabr['slab_id'] ?>]"> </td>

                    <?php
                    } ?>
                </tr>
            <?php
            } ?>
        </table>
</div>