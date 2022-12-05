<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8 order-xl-1">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">User Profile </h3>
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
                                        <input type="text" id="input-username" class="form-control"
                                            value="<?php echo $user[0]['member_id'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Email address</label>
                                        <input type="email" id="input-email" class="form-control"
                                            value="<?php echo $user[0]['email'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">First name</label>
                                        <input type="text" id="input-first-name" class="form-control"
                                            value="<?php echo $user[0]['first_name'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-last-name">Last name</label>
                                        <input type="text" id="input-last-name" class="form-control"
                                            value="<?php echo $user[0]['last_name'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Mobile</label>
                                        <input type="text" name="phone_no" class="form-control" required
                                            value="<?php echo $user[0]['phone'] ?>">
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
                                        <input name="home_address" class="form-control" required
                                            value="<?php echo $user[0]['home_address'] ?>" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">State</label>
                                        <input type="text" name="home_states" class="form-control states"
                                            value="<?php echo $user[0]['home_state'] ?>" required>


                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">City</label>
                                        <input type="text" name="home_city" class="form-control cities"
                                            value="<?php echo $user[0]['home_city'] ?>" required>

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Postal code</label>
                                        <input type="number" name="home_pincode" class="form-control"
                                            value="<?php echo $user[0]['home_pincode'] ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 order-xl-2">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">User Profile </h3>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo  base_url('kyc/update_info')?>">
                        <h6 class="heading-small text-muted mb-4">Bank Kyc</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">MID</label>
                                        <input type="text" name="mid" class="form-control"
                                            value="<?php echo $user[0]['mid'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">TID</label>
                                        <input type="text" name="tid" class="form-control"
                                            value="<?php echo $user[0]['tid'] ?>">
                                    </div>
                                </div>
                            </div>
                                                       <div class="row">
                                
                               <div class="col-12">
                                   <input type="hidden" name="id" value="<?php echo $user[0]['user_id']?>" />
                                   <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                            <button type="submit" class="btn btn-success" id="addkyc" style="margin-top: 38px;">Update
                                    Kyc</button>
                        </div>
                            </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
   
