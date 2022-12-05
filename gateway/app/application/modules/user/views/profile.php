<?php if($this->session->userdata('user_id')==1){ ?>

    <div class="container">
    <div class="main-body">
    
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
              <li class="breadcrumb-item active" aria-current="page">User Profile</li>
            </ol>
          </nav>
          <!-- /Breadcrumb -->
    
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                      <?php if($profile){ ?>
                    <img src="<?php echo $profile?>" alt="Admin" class="rounded-circle" width="150">
                    <?php } ?>
                    <?php if(!$profile){ ?>
                     <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                    <?php } ?>
                    <div class="mt-3">
                      <?php echo $user[0]['first_name']?>
                      <p class=" mb-1"> Admin Id: <?php echo $user[0]['member_id']?></p>
                      <p class=" mb-1"> Mobile: <?php echo $user[0]['phone']?></p>
                      <p class="text-muted font-size-sm"><?php echo $user[0]['address']?></p>
                      <button class="btn btn-primary">Follow</button>
                      <button class="btn btn-outline-primary">Message</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe mr-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Website</h6>
                    <span class="">https://demo.co.in</span>
                  </li>
                  <!--<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
                  <!--  <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github mr-2 icon-inline"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>Github</h6>-->
                  <!--  <span class="">demo</span>-->
                  <!--</li>-->
                  <!--<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
                  <!--  <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter mr-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>-->
                  <!--  <span class="">@demo</span>-->
                  <!--</li>-->
                  <!--<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
                  <!--  <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram mr-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>-->
                  <!--  <span class="">demo</span>-->
                  <!--</li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook mr-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                    <span class="">demo</span>
                  </li>
                </ul> -->
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h4 class="mb-0">Full Name</h4>
                    </div>
                    <div class="col-sm-9 ">
                    <?php echo ($user[0]['first_name']) . " " . $user[0]['last_name'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h4 class="mb-0">Email</h4>
                    </div>
                    <div class="col-sm-9 ">
                    <?php echo $user[0]['email']  ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h4 class="mb-0">Phone</h4>
                    </div>
                    <div class="col-sm-9 ">
                    <?php echo $user[0]['phone']  ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h4 class="mb-0">Admin ID</h4>
                    </div>
                    <div class="col-sm-9 ">
                    <?php echo ($user[0]['member_id'])  ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h4 class="mb-0">Address</h4>
                    </div>
                    <div class="col-sm-9 ">
                    <?php echo $user[0]['address']  ?>
                    </div>
                  </div>
                </div>
              </div>
              <!--<div class="row gutters-sm">-->
              <!--  <div class="col-sm-6 mb-3">-->
              <!--    <div class="card h-100">-->
              <!--      <div class="card-body">-->
              <!--        <h4 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h4>-->
              <!--        <small>Web Design</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>Website Markup</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>One Page</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>Mobile Template</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>Backend API</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--      </div>-->
              <!--    </div>-->
              <!--  </div>-->
              <!--  <div class="col-sm-6 mb-3">-->
              <!--    <div class="card h-100">-->
              <!--      <div class="card-body">-->
              <!--        <h4 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h4>-->
              <!--        <small>Web Design</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>Website Markup</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>One Page</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>Mobile Template</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--        <small>Backend API</small>-->
              <!--        <div class="progress mb-3" style="height: 5px">-->
              <!--          <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>-->
              <!--        </div>-->
              <!--      </div>-->
              <!--    </div>-->
              <!--  </div>-->
              <!--</div>-->
            </div>
          </div>
        </div>
    </div>
    <?php  } ?>



<!-- user profile -->

<?php if($this->session->userdata('user_id')!=1){ ?>
<div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(<?php echo base_url() .' /assets/img/theme/profile-cover.jpg'?>); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-lg-7 col-md-10">
                <h1 class="display-2 text-white">Hello <?php echo ($user[0]['first_name']) ?></h1>
                <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p>
                <a href="<?php echo base_url('user/profile/edit') ?>" class="btn btn-neutral">Edit profile</a>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="<?php echo base_url() . '/assets/img/theme/img-1-1000x600.jpg' ?>" alt="Image placeholder" class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="<?php echo $profile?>" class="rounded-circle">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                        <hr>
                        <hr>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">

                    </div>
                    <div class="text-center">
                        <h5 class="h3">
                            <?php echo ($user[0]['first_name']) . " " . $user[0]['last_name'] ?><span class=" font-weight-light"></span>
                        </h5>
                        <div class="h5 font-weight-300">
                            Admin Id <i class="ni location_pin mr-2"></i><?php echo ($user[0]['member_id'])  ?>
                        </div>
                          <div class="h5 font-weight-300">
                            Mobile <i class="ni location_pin mr-2"></i><?php echo ($user[0]['phone']) ?>
                        </div>
                        <div class="h5 mt-4">
                            <i class="ni business_briefcase-24 mr-2"></i><?php echo ($user[0]['organisation']) ?> - <?php echo ($user[0]['address']) ?>
                        </div>
                        <div>
                            <i class="ni education_hat mr-2"></i><?php echo $user[0]['city'] ?> - <?php echo $user[0]['pincode'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0">KYC Status</h5>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- List group -->
                    <ul class="list-group list-group-flush list my--3">
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="<?php if(!empty($image[0]['name'])){echo base_url('/uploads/photo/').$image[0]['name'];}else{echo base_url() . '/assets/img/brand/favicon.png';} ?>">
                                    </a>
                                </div>
                               
                                <div class="col">
                                    <h5>Profile image Upload Status</h5>
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar <?php if ($photo == 1) {
                                                                        echo 'bg-green';
                                                                    } else echo "bg-red"; ?>" role="progressbar" aria-valuenow="<?php if ($photo == 1) {
                                                                                                                                    echo '100';
                                                                                                                                } else echo "15"; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php if ($photo == 1) {
                                                                                                                                                                                                                echo '100';
                                                                                                                                                                                                            } else echo "15"; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="<?php if(!empty($image[0]['name'])){echo base_url('/uploads/adhar_front/').$image[1]['name'];}else{echo base_url() . '/assets/img/theme/adhar.png';} ?>">
                                    </a>
                                </div>
                                <div class="col">
                                    <h5>Adhar image Upload Status</h5>
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar <?php if ($adhar == 1) {
                                                                        echo 'bg-green';
                                                                    } else echo "bg-red"; ?>" role="progressbar" aria-valuenow="" aria-valuemin="<?php if ($adhar == 1) {
                                                                                                                                                        echo '100';
                                                                                                                                                    } else echo "15"; ?>" aria-valuemax="100" style="width: <?php if ($pan == 1) {
                                                                                                                                                                                                                echo '100';
                                                                                                                                                                                                            } else echo "15"; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="<?php if(!empty($image[0]['name'])){ echo base_url('/uploads/pan/').$image[3]['name'];} else{echo base_url() . '/assets/img/theme/pan.png';} ?>">
                                    </a>
                                </div>
                                <div class="col">
                                    <h5>Pan Image Upload Status</h5>
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar <?php if ($pan == 1) {
                                                                        echo 'bg-green';
                                                                    } else echo "bg-red"; ?>" role="progressbar" aria-valuenow="5" aria-valuemin="<?php if ($pan == 1) {
                                                                                                                                                        echo '100';
                                                                                                                                                    } else echo "15"; ?>" aria-valuemax="100" style="width: <?php if ($pan == 1) {
                                                                                                                                                                                                                echo '100';
                                                                                                                                                                                                            } else echo "15"; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="<?php if(!empty($image[0]['name'])){echo base_url('/uploads/passbook/').$image[4]['name'];} else{echo base_url() . '/assets/img/theme/passbook.jpg';} ?>">
                                    </a>
                                </div>
                                <div class="col">
                                    <h5>Passbook Image Upload Status</h5>
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar <?php if ($pass == 1) {
                                                                        echo 'bg-green';
                                                                    } else echo "bg-red"; ?>" role="progressbar" aria-valuenow="5" aria-valuemin="<?php if ($pass == 1) {
                                                                                                                                                        echo '100';
                                                                                                                                                    } else echo "15"; ?>" aria-valuemax="100" style="width: <?php if ($pass == 1) {
                                                                                                                                                                                                                echo '100';
                                                                                                                                                                                                            } else echo "15"; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            
        </div>
        <div class="col-xl-8 order-xl-1">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Profile </h3>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <h6 class="heading-small text-muted mb-4">User information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Member Id</label>
                                        <input type="text" id="input-username" class="form-control" value="<?php echo $user[0]['member_id'] ?>" >
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Email address</label>
                                        <input type="email" id="input-email" class="form-control" value="<?php echo $user[0]['email'] ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">First name</label>
                                        <input type="text" id="input-first-name" class="form-control" value="<?php echo $user[0]['first_name'] ?>" >
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-last-name">Last name</label>
                                        <input type="text" id="input-last-name" class="form-control" value="<?php echo $user[0]['last_name'] ?>" >
                                    </div>
                                </div>
                            </div>
                              <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Mobile</label>
                                    <input type="text" name="phone_no" class="form-control" required   value="<?php echo $user[0]['phone'] ?>" >
                                </div>
                            </div>
                           

                        </div>
                        </div>
                        <hr class="my-4" />
                        <!-- Address -->
                        <h6 class="heading-small text-muted mb-4">Shop Address</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Aadhar Number/
                                            आधार नंबर</label>
                                        <input type="text" name="adharcard" class="form-control" required  value="<?php echo $user[0]['aadhar'] ?>" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">PAN Number/
                                            पैन नंबर</label>
                                        <input type="text" name="pancard" class="form-control uppercase" value="<?php echo $user[0]['pan'] ?>" >
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Shop Name/
                                            दुकान का नाम</label>
                                        <input type="text" name="organization_name" class="form-control" value="<?php echo $user[0]['organisation'] ?>" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">GST Number</label>
                                        <input type="text" name="gst_no" class="form-control uppercase" value="<?php echo $user[0]['gstno'] ?>" >
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Full Shop Address</label>
                                        <input name="address" class="form-control" required   value="<?php echo $user[0]['address'] ?>" type="text" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">State</label>
                                         <input type="text" name="states" class="form-control states" value="<?php echo $user[0]['state'] ?>" required  >
                                         

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">City</label>
                                        <input type="text" name="city" class="form-control cities" value="<?php echo $user[0]['city'] ?>" required  >
                                          
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Postal code</label>
                                        <input type="number" name="pincode" class="form-control" value="<?php echo $user[0]['pincode'] ?>" required  >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Second Address -->
                        <hr class="my-4" />
                        <h6 class="heading-small text-muted mb-4">Home Address</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Full Home Address</label>
                                        <input name="home_address" class="form-control" required   value="<?php echo $user[0]['home_address'] ?>" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">State</label>
                                        <input type="text" name="home_states" class="form-control states" value="<?php echo $user[0]['home_state'] ?>" required  >
                                         

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">City</label>
                                        <input type="text" name="home_city" class="form-control cities" value="<?php echo $user[0]['home_city'] ?>" required  >
                                           
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Postal code</label>
                                        <input type="number" name="home_pincode" class="form-control" value="<?php echo $user[0]['home_pincode'] ?>" required  >
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <?php if (isset($bank[0])) { ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Bank Details </h3>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group"> <label class="form-control-label">Account Holder Name</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                            </div>
                                            <input  class="form-control" name="name" value="<?php echo ($bank[0]['account_holder_name']) ?>" type="text" required  >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group"><label class="form-control-label">Account Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni ni-credit-card"></i></span>
                                            </div>
                                            <input class="form-control" name="account_no" value="<?php echo ($bank[0]['account_no']) ?>" type="number" required  >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group"><label class="form-control-label">Bank Name</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni fa-university"></i></span>
                                            </div>
                                            <input class="form-control" name="bank_name" value="<?php echo ($bank[0]['bank_name']) ?>" type="text" required  >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group"> <label class="form-control-label">Bank IFSC Code</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                            </div>
                                            <input class="form-control" name="ifsc" value="<?php echo ($bank[0]['ifsc_code']) ?>" type="text" required  >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group"> <label class="form-control-label">Phone Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                            </div>
                                            <input class="form-control" name="phone" value="<?php echo ($bank[0]['phone_no']) ?>" type="tel" required  >
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php  } ?>





