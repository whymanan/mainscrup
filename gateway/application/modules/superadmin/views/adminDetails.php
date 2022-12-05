<div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(../../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
  <!-- Mask -->
  <span class="mask bg-gradient-default opacity-8"></span>
  <!-- Header container -->
  <!-- <div class="container-fluid d-flex align-items-center">
    <div class="row">
      <div class="col-lg-7 col-md-10">
        <h1 class="display-2 text-white">Hello <?php echo (ucfirst($user[0]['first_name'])) . " " . (ucfirst($user[0]['last_name']))  ?></h1>
        <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p>
        
      </div>
    </div>
  </div>
</div> -->
<!-- Page content -->
<div class="container-fluid mt--6 pt-5">
  <div class="row pt-5">
    <div class="col-xl-4 order-xl-2">
      <div class="card card-profile">
        <img src="<?php

                            echo base_url() . '/assets/img/theme/img-1-1000x600.jpg' ?>" alt="Image placeholder" class="card-img-top">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <a href="#">
                <img src="<?php
                if(isset($docs['photo'])){
                  echo base_url('uploads/'). $docs['photo']['type'].'/'. $docs['photo']['name'] ;
                }else{
                  echo base_url('assets').'/img/theme/avtar.png' ;
                }
                                            ?>" class="rounded-circle">
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
            <hr>
          </div>
          <div class="text-center">
            <h5 class="h3">
              <?php echo (ucfirst($user[0]['first_name'])) . " " . ucfirst($user[0]['last_name']) ?><span class=" font-weight-light"></span>
            </h5>
            <div class="h5 font-weight-300">
              Member Id <i class="ni location_pin mr-2"></i><?php echo ($user[0]['member_id']) ?>
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
          <h5 class="h3 mb-0">Documents</h5>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="container">
            <h5 class="h3 mb-0">Adhaar Front Photo</h5>
            <hr>
             <?php if(isset($docs['adhar_front'])) { ?>
                <img id="blah3" src="<?php

                  echo base_url('uploads/'). $docs['adhar_front']['type'].'/'. $docs['adhar_front']['name'] ;


              ?>" alt="your image" width="150px" />
            <?php }else {
                                echo  '<p class=" mb-0">Adhaar Back Photo Not uploaded</p>';
                            }
                            ?>
              
          </div>
          <hr>

          <div class="container">
            <h5 class="h3 mb-0">Adhaar Front Photo</h5>
            <hr>
            <div class="container">
              <?php if(isset($docs['adhar_back'])) { ?>
                <img id="blah3" src="<?php

                  echo base_url('uploads/'). $docs['adhar_back']['type'].'/'. $docs['adhar_back']['name'] ;


              ?>" alt="your image" width="150px" />
            <?php } else {
                                echo  '<p class=" mb-0">Adhaar Back Photo Not uploaded</p>';
                            }
                            ?>
            </div>
          </div>
          <hr>
          <div class="container">
            <h5 class="h3 mb-0">PAN Photo</h5>
            <hr>
            <div class="container">
              <?php if(isset($docs['pan'])){ ?>
                <img src="<?php  echo base_url('uploads/'). $docs['pan']['type'].'/'. $docs['pan']['name'] ;
                ?>" alt="your image" width="150px" />
              <?php } else {
                                echo  '<p class=" mb-0">PAN Photo Not uploaded</p>';
                            }
                            ?>
            </div>
          </div>
       
          <hr>
          <div class="container">
            <h5 class="h3 mb-0">Passbook Photo</h5>
            <hr>
            <div class="container">
              <?php if(isset($docs['passbook'])){ ?>
                <img id="blah5" src="<?php  echo base_url('uploads/'). $docs['passbook']['type'].'/'. $docs['passbook']['name'] ;
              ?>"  alt="your image" width="150px" />
              <?php } else {
                                echo  '<p class=" mb-0">Passbook Photo Not uploaded</p>';
                            }
                            ?>
            </div>
          </div>
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
                    <input type="text" id="input-username" class="form-control" value="<?php echo $user[0]['member_id'] ?>">
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
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-control-label">Mobile</label>
                    <input type="text" name="phone_no" class="form-control" required value="<?php echo $user[0]['phone'] ?>">
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
                    <input type="text" name="adharcard" class="form-control" required value="<?php echo $user[0]['aadhar'] ?>">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-control-label">PAN Number/
                      पैन नंबर</label>
                    <input type="text" name="pancard" class="form-control uppercase" value="<?php echo $user[0]['pan'] ?>">
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-control-label">Shop Name/
                      दुकान का नाम</label>
                    <input type="text" name="organization_name" class="form-control" value="<?php echo $user[0]['organisation'] ?>">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-control-label">GST Number</label>
                    <input type="text" name="gst_no" class="form-control uppercase" value="<?php echo $user[0]['gstno'] ?>">
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="form-control-label">Full Shop Address</label>
                    <input name="address" class="form-control" required value="<?php echo $user[0]['address'] ?>" type="text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">State</label>
                    <input type="text" name="states" class="form-control states" value="<?php echo $user[0]['state'] ?>" required>


                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">City</label>
                    <input type="text" name="city" class="form-control cities" value="<?php echo $user[0]['city'] ?>" required>

                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">Postal code</label>
                    <input type="number" name="pincode" class="form-control" value="<?php echo $user[0]['pincode'] ?>" required>
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
                    <input name="home_address" class="form-control" required value="<?php echo $user[0]['home_address'] ?>" type="text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">State</label>
                    <input type="text" name="home_states" class="form-control states" value="<?php echo $user[0]['home_state'] ?>" required>


                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">City</label>
                    <input type="text" name="home_city" class="form-control cities" value="<?php echo $user[0]['home_city'] ?>" required>

                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">Postal code</label>
                    <input type="number" name="home_pincode" class="form-control" value="<?php echo $user[0]['home_pincode'] ?>" required>
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


        </div>
      </div>
      <?php } ?>
    </div>
  </div>
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Rejection Reason</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="<?php echo base_url('kyc/kyc/change_status/reject/') . $user[0]['user_id']; ?>" method="POST">

            <div class="form-group">
              <label class="form-control-label" for="input-username">Member Id</label>
              <input type="text" readonly class="form-control" value="<?php echo $user[0]['member_id'] ?>">
            </div>


            <div class="form-group">
              <label class="form-control-label" for="input-email">Reason</label>
              <input type="text" name="reason" class="form-control" value="">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            </div>


        </div>
        <div class="modal-footer"> 
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>
