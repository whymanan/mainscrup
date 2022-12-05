<?php
$query = "SELECT menu.menu_permission_id,menu.menu_name,menu.data_menu,menu.path ,menu.icon,( SELECT CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'menu_permission_id', CONCAT(sub_menu.menu_permission_id), 'data_menu_name', CONCAT(sub_menu.data_sub_menu), 'sub_menu_name', CONCAT(sub_menu.sub_menu_name), 'path', CONCAT(sub_menu.path) ) ), ']' ) AS menu FROM menu_permission AS sub_menu WHERE menu.menu_permission_id = sub_menu.parent_id ) AS submenu FROM menu_permission AS menu WHERE parent_id IS NULL";
$sql = $this->db->query($query);
$menu = $sql->result_array();
//pre($query);exit;
?>
<style>
@media only screen and (min-width: 768px)
{
    #cross{
            display:none;
          }
}
</style>
<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
  <div class="scrollbar-inner">
    <!-- Brand -->
    <span id="cross" style="float: right;margin-right: 10px;margin-top: 10px;">&#10005</span>
    <div class="sidenav-header  align-items-center">
      <a class="navbar-brand" href="javascript:void(0)">
        <img src="<?php echo base_url('optimum/logoside.png'); ?>" class="navbar-brand-img" alt="...">
      </a>
    </div>
    <div class="navbar-inner">
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Nav items -->
        <ul class="navbar-nav">
          <?php foreach ($menu as $row) {
            if ($this->session->userdata('user_roles') != ROLE_ADMIN) {
              if (in_array($row['menu_permission_id'], $this->session->userdata('menu_permission'))) { ?>

                <li class="nav-item"><?php if (!empty($row['submenu'])) { ?>
                    <a class="nav-link" data-target="#navbar-<?php echo $row['menu_name'] ?>" data-toggle="collapse" role="button">
                      <i  class="fa fa-<?php echo $row['icon']?> text-primary"></i>
                      <span class="nav-link-text"><?php echo $row['data_menu'] ?></span>
                    </a>
                    <div class="collapse" id="navbar-<?php echo $row['menu_name'] ?>">
                      <ul class="nav nav-sm flex-column">
                        <?php foreach (json_decode($row['submenu']) as $sub) { 

                              if (in_array($sub->menu_permission_id, $this->session->userdata('menu_permission'))){
                        ?>
                          <li class="nav-item">
                            <a href="<?php echo base_url($sub->path) ?>" class="nav-link">
                              <span class="sidenav-mini-icon"> <i class="ni ni-bold-right text-primary"></i> </span>
                              <span class="sidenav-normal"> <?php echo $sub->data_menu_name ?> </span>
                            </a>
                          </li>
                        <?php } } ?>
                      </ul>
                    </div>
                  <?php } else { ?>
                    <a class="nav-link" href="<?php echo base_url($row['path']) ?>">
                      <i class="fa fa-<?php echo $row['icon']?> text-green"></i>
                      <span class="nav-link-text"><?php echo $row['data_menu'] ?></span>
                    </a>
                  <?php } ?>
                </li>

              <?php    }
            } else { ?>
              <li class="nav-item"><?php if (!empty($row['submenu'])) { ?>
                  <a class="nav-link" data-target="#navbar-<?php echo $row['menu_name'] ?>" data-toggle="collapse" role="button">
                    <i class="fa fa-<?php echo $row['icon']?> text-info"></i>
                    <span class="nav-link-text"><?php echo $row['data_menu'] ?></span>
                  </a>
                  <div class="collapse" id="navbar-<?php echo $row['menu_name'] ?>">
                    <ul class="nav nav-sm flex-column">
                      <?php foreach (json_decode($row['submenu']) as $sub) { ?>
                        <li class="nav-item">
                          <a href="<?php echo base_url($sub->path) ?>" class="nav-link">
                            <span class="sidenav-mini-icon"> <i class="ni ni-bold-right text-primary"></i> </span>
                            <span class="sidenav-normal"> <?php echo $sub->data_menu_name ?> </span>
                          </a>
                        </li><?php }  ?>
                    </ul>
                  </div>
                <?php } else { ?>
                  <a class="nav-link" href="<?php echo base_url($row['path']) ?>">
                    <i class="fa fa-<?php echo $row['icon']?> text-primary"></i>
                    <span class="nav-link-text"><?php echo $row['data_menu'] ?></span>
                  </a>
                <?php } ?>
              </li>
          <?php }
          } ?>

        

        </ul>
      </div>
    </div>
  </div>
</nav>