 <style>
 @media only screen and (max-width: 600px) {
 .navbar-horizontal .navbar-collapse
 {
         opacity: 1 !important;
         
 }
 .collapse:not(.show) {
     display: inherit !important;
     
}
.navbar-expand-lg .navbar-nav {
    flex-direction: row !important;
}
.nav-item
{
    width: 25px !important;
}
.navbar-collapse {
    flex-basis: fit-content !important;
    flex-grow: inherit !important;
    align-items: center !important;
}
.fab
{
    font-size: 15px;
}
}
 </style>
 <!--Navbar -->
 <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
  <div class="container">
    <!--<a class="navbar-brand" href="#">-->
    <!--  <img src="<?php echo base_url('/optimum/logoside.png'); ?>">-->
    <!--</a>-->
    <!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">-->
    <!--  <span class="navbar-toggler-icon"></span>-->
    <!--</button>-->
    <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
      <div class="navbar-collapse-header">
        <div class="row">
          <div class="col-6 collapse-brand">
            <!--<a href="#">-->
            <!--  <img src="<?php echo base_url('/optimum/logoside.png'); ?>">-->
            <!--</a>-->
          </div>
        </div>
      </div>
      <hr class="d-lg-none" />
      <ul class="navbar-nav align-items-lg-center ml-lg-auto">
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="https://www.facebook.com/" target="_blank" data-toggle="tooltip" data-original-title="Like us on Facebook">
            <i class="fab fa-facebook-square"></i>
            <!--<span class="nav-link-inner--text d-lg-none">Facebook</span>-->
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="https://instagram.com target="_blank" data-toggle="tooltip" data-original-title="Follow us on Instagram">
            <i class="fab fa-instagram"></i>
            <!--<span class="nav-link-inner--text d-lg-none">Instagram</span>-->
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="https://twitter.com/explore" target="_blank" data-toggle="tooltip" data-original-title="Follow us on Twitter">
            <i class="fab fa-twitter-square"></i>
            <!--<span class="nav-link-inner--text d-lg-none">Twitter</span>-->
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="https://youtube.com/" target="_blank" data-toggle="tooltip" data-original-title="Star us on Youtube">
            <i class="fab fa-youtube"></i>
            <!--<span class="nav-link-inner--text d-lg-none">Github</span>-->
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main content -->
<div class="main-content">
  <!-- Header -->
  <div class="header bg-gradient-primary py-5" style="padding-top:70px !important;">
    <div class="separator separator-bottom separator-skew zindex-100">
      <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
      </svg>
    </div>
  </div>
  <!-- Page content -->
  <div class="row">
    <div style="margin-top:-40px;padding-left:20px;" class="d-none d-md-block col-lg-6 col-md-6 col-sm-12 col-xs-12">
      <div style="border:solid black 5px;" id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <!-- <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> -->
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100" style="height:550px;" src="<?php echo base_url('/optimum/bpps.gif'); ?>" alt="First slide">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100" style="height:550px;" src="<?php echo base_url('/optimum/onlinebanking.png'); ?>" alt="Second slide">
          </div>
          <!-- <div class="carousel-item">
            <img class="d-block w-100" style="height:550px;" src="<?php echo base_url('/optimum/aeps (1).jpg'); ?>" alt="Third slide">
          </div> -->
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
      <div class="container mt--5 pb-5" id="varify-port">
        <div class="row justify-content-center">
          <div class="col-lg-10 col-md-7">
            <div class="card bg-secondary border-0 mb-0">
              <div class="card-body px-lg-5 py-lg-5">
                <div class="text-center text-muted mb-4">
                   
                  <a class="navbar-brand" href="#">
                    <img style="height: 108px;margin-top: 50px;" src="<?php echo base_url('/optimum/logoside.png'); ?>">
                  </a></br>
                  <small>Sign in with credentials</small>
                </div>
                <form role="form" name="logform" action="<?php echo base_url('auth'); ?>" method="post" autocomplete="off">
                  <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-badge"></i></span>
                      </div>
                      <input class="form-control" oninput="this.value = this.value.toUpperCase()" name="member_id" placeholder="Enter Your Member Id" type="text" value="" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group input-group-merge input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                      </div>
                      <input class="form-control" type="password" name="password" placeholder="Password" value="" autocomplete="off">
                    </div>
                  </div>
                  <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" id="customCheckLogin" type="checkbox">
                    <label class="custom-control-label" for="customCheckLogin">
                      <span class="text-muted">Remember me</span>
                    </label>
                  </div>
                  <div class="text-center">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-primary my-4">Sign in</button>
                  </div>
                </form>
              </div>
             
            </div>
            <div class="row mt-3">
              <div class="col-6">
                <a id="forget" class="text-light btn btn-link"><small>Forgot password?</small></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>