<div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(../../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-lg-7 col-md-10">
                <h1 class="display-2 text-white">Hello <?php echo ($user[0]['first_name']) ?></h1>
                <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p>
                <a href="#!" class="btn btn-neutral">Edit profile</a>
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

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Edit profile </h3>
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
                                        <label class="form-control-label" for="input-username">CustomerId</label>
                                        <input type="text" id="input-username" class="form-control" value="<?php echo $user[0]['customer_id'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Email address</label>
                                        <input type="email" id="input-email" class="form-control" value="<?php echo $user[0]['email'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">First name</label>
                                        <input type="text" id="input-first-name" class="form-control" value="<?php echo $user[0]['first_name'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-last-name">Last name</label>
                                        <input type="text" id="input-last-name" class="form-control" value="<?php echo $user[0]['last_name'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <!-- Address -->
                        <h6 class="heading-small text-muted mb-4">Contact information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-address">Address</label>
                                        <input id="input-address" class="form-control" value="<?php echo $user[0]['address'] ?>" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">City</label>
                                        <input type="text" id="input-city" class="form-control" value="<?php echo $user[0]['city'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">State</label>
                                        <input type="text" id="input-country" class="form-control" value="<?php echo $user[0]['state'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">Postal code</label>
                                        <input type="number" id="input-postal-code" class="form-control" value="<?php echo $user[0]['pincode'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <!-- Description -->
                        <h6 class="heading-small text-muted mb-4">About me</h6>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">About Me</label>
                                <textarea rows="4" class="form-control" placeholder="A few words about you ...">A beautiful premium dashboard for Bootstrap 4.</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>