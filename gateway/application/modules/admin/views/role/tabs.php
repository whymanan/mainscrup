<div class="col-md-6 parent_div" id="<?php echo $name ?>_settings-<?php echo $roles_id ?>">
    <div class="card border-3 ">
        <div class="card-header with-border bg-<?php echo $color[array_rand($color)] ?>">
            <div class="row">
                <h3 class="card-title ft-600 text-white col-md-11">
                    <?php echo $name ?>
                </h3>

                <div class="card-tools pull-right">
                    <label class="cursor-pointer">
                        <input type="checkbox" <?php if (in_array($roles_id, $role)) echo "checked='checked'"; ?> name="<?php echo $name ?>_settings_checkbox" id="<?php echo $name ?>_settings_checkbox" value="<?php echo $roles_id ?>" class="permissions_check_all" style="height: 17px; width: 17px;">
                    </label>
                </div>
            </div>
        </div>

        <div class="card-body parent_body ">
            <div class="row">
                    <div class="col-2">
                        <input type="checkbox" <?php if (in_array($roles_id, $role)) echo "checked='checked'"; ?> name="<?php echo $name ?>_settings_checkbox" id="<?php echo $name ?>_settings_checkbox" value="<?php echo $roles_id ?>" class="permissions_check_children" style="height: 17px; width: 17px;">
                    </div>
                    <div class="col-10">
                        <label class="cursor-pointer"><?php echo $name ?></label>
                    </div>
                </div>
            <?php foreach ($menu as $row) { ?>
                <div class="row">
                    <div class="col-2">
                        <input type="checkbox" <?php if (in_array($row['menu_permission_id'], $role)) echo "checked='checked'"; ?> value="<?php echo $row['menu_permission_id'] ?>" class="permissions_check_children" style="height: 17px; width: 17px;">
                    </div>
                    <div class="col-10">
                        <label class="cursor-pointer"><?php echo $row['data_sub_menu'] ?></label>
                    </div>
                </div>

                <div class="clearfix"></div>
            <?php } ?>
        </div>
    </div>
</div>