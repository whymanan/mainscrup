<div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(../../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-lg-8 col-md-10">
                <h1 class="display-2 text-white">Hello <?php echo ($user[0]['first_name']) ?></h1>
                <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p>
                <a href="#!" class="btn btn-neutral">Edit profile</a>
            </div>
             <div class="col-lg-4 col-md-10">
              <div class="card">
                <!-- Card header -->
                <div class="card-header text-center">
                    <!-- Title -->
                    <h5 class="h3 mb-0"> Wallet</h5>
                </div>
                <!-- Card body -->
                <div class="card-body text-center text-white bg-success">
                    <!-- List group -->
                  
                   <p>Current Balance</p>
                   <h3>0.00</h3>
                </div>
                 </div>
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
                                <img src="<?php echo base_url() . '/assets/img/theme/avtar.png' ?>" class="rounded-circle">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-sm btn-info  mr-4 text-white"><?php echo ($user[0]['kyc_status']) ?></a>
                        <a class="btn btn-sm btn-default float-right text-white"><?php echo ($user[0]['user_type']) ?></a>
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
                            Customer Id <i class="ni location_pin mr-2"></i><?php echo ($user[0]['customer_id']) ?>
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
                                        <img alt="Image placeholder" src="<?php echo base_url() . '/assets/img/brand/favicon.png' ?>">
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
                                        <img alt="Image placeholder" src="<?php echo base_url() . '/assets/img/theme/adhar.png' ?>">
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
                                        <img alt="Image placeholder" src="<?php echo base_url() . '/assets/img/theme/pan.png' ?>">
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

                    </ul>
                </div>
            </div>
          
        </div>
        <div class="col-xl-8 order-xl-1">

           
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
                                            <input class="form-control" name="name" value="<?php echo ($bank[0]['account_holder_name']) ?>" type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group"><label class="form-control-label">Account Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni ni-credit-card"></i></span>
                                            </div>
                                            <input class="form-control" name="account_no" value="<?php echo ($bank[0]['account_no']) ?>" type="number" required>
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
                                            <input class="form-control" name="bank_name" value="<?php echo ($bank[0]['bank_name']) ?>" type="text" required>
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
                                            <input class="form-control" name="ifsc" value="<?php echo ($bank[0]['ifsc_code']) ?>" type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group"> <label class="form-control-label">Phone Number</label>
                                        <div class="input-group input-group-alternative mb-3">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                            </div>
                                            <input class="form-control" name="phone" value="<?php echo ($bank[0]['phone_no']) ?>" type="tel" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <a class="btn btn-primary" href="<?php echo base_url('wallet/verify_wallet')?>">
                    Verify 
                   </a>
                            
                            <input class="btn btn-primary" name="Update" type="button" value="ADD">
                            

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
  