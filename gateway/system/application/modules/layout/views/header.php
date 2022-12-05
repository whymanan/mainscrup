<?php
$query = "SELECT  notification_title,log_date,notification_id  FROM notification_callback AS menu  ";
if (!isAdmin($this->session->userdata('user_roles'))) {
 $query.="where mamber_id ='" .$this->session->userdata('user_id')."'";
    
}
$sql = $this->db->query($query);
if( $sql->num_rows()>0)
$count = 0;
else
$count = $sql->num_rows();

$query.=" limit 10 ";
$sql = $this->db->query($query);
$notification = $sql->result_array();
// $sql = $this->db->get('services');
// $service = $sql->result_array();
?>
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Search form -->
       
      <!-- Navbar links -->
      <ul class="navbar-nav align-items-center  ml-md-auto ">
        <li class="nav-item d-xl-none">
          <!-- Sidenav toggler -->
          <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </li>
        <li class="nav-item d-sm-none">
          <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
            <i class="ni ni-zoom-split-in"></i>
          </a>
        </li>

      </ul>
      
   <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
   
    <li class="nav-item dropdown">
        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
            <!-- Dropdown header -->
            <div class="px-3 py-3">
                <h6 class="text-sm text-muted m-0">You have <strong class="text-primary"><?php echo $count?></strong> notifications.</h6>
            </div>
            <!-- List group -->
            <div class="list-group list-group-flush">
            <?php  foreach($notification as $row){
                
            
               ?>
                <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      
                        <div class="col ml--2">
                            <div class="d-flex justify-content-between align-items-center">
                                
                                <div class="text-right text-muted">
                                    <h5><?php echo  $row['log_date']
                                    ?></h5>
                                </div>
                            </div>
                            <p class="text-sm mb-0"><?php echo $row['notification_title'] ?></p>
                        </div>
                    </div>
                </a>
             <?php }?>
            </div>
            <!-- View all -->
            <a href="<?php echo base_url('notification')?>" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
        </div>
    </li>

</ul>
      <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
        <li class="nav-item dropdown">
          <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="<?php echo $profile ?>">
              </span>
              <div class="media-body  ml-2  d-none d-lg-block">
                <span class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->userdata('user_name'); ?></span>
              </div>
            </div>
          </a>
          <div class="dropdown-menu  dropdown-menu-right ">
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="<?php echo base_url('profile') ?>" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo base_url('logout'); ?>" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <?php if (isset($breadcrumbs)) :
              ?>
                <?php foreach ($breadcrumbs as $key => $value) : ?>
                  <?php if (count($breadcrumbs) == ($key + 1)) : ?>
                    <li class="breadcrumb-item"><?php echo substr($value['name'], 0, 40); ?></li>
                  <?php else : ?>
                    <li class="breadcrumb-item active" aria-current="page">><a href="<?php echo $value['url']; ?>"> <?php echo $value['name']; ?> </a></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </ol>
          </nav>
        </div>

        <?php if (isset($param)) : ?>
          <div class="col-lg-6 col-5 text-right">
            <a href="<?php echo base_url($active . '/' . $param->link) ?>" class="btn btn-sm btn-neutral"><?php echo $param->label ?></a>
            <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Filters</a>
          </div>
        <?php endif; ?>
      </div>
      <!-- Row Card -->
      <?php if (($_SESSION['kyc_status'] == "pending" || $_SESSION['kyc_status'] == "new") && $_SESSION['user_roles']!= ROLE_ADMIN) { ?>
        <div class="row">
          <div class="col-12">
            <div class="alert row alert-info" role="alert">
              <div class="col-9">
                <strong>KYC Pending !!</strong> Update your KYC </div>
              <div class="col-3 text-center"> <a href="<?php echo base_url('users/kyc') ?>" class="btn btn-primary text-white">UPDATE HERE</a></div>
            </div>
          </div>
        </div> <?php } else if ($_SESSION['kyc_status'] == "reject" && $_SESSION['user_roles'] != ROLE_ADMIN) { ?>
        <div class="row">
          <div class="col-12">
            <div class="alert row alert-secondary" role="alert">
              <div class="col-9">
                <strong>KYC Rejected !!</strong> Update your KYC </div>
              <div class="col-3 text-center"> <a href="<?php echo base_url('users/kyc') ?>" class="btn btn-primary text-white">UPDATE HERE</a></div>
            </div>
          </div>
        </div><?php } ?>
      <!-- Row Card -->
    </div>
  </div>
</div>
