<!-- Navbar -->
<nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="<?php echo base_url('/optimum/logoside.png'); ?>">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
      <div class="navbar-collapse-header">
        <div class="row">
          <div class="col-6 collapse-brand">
            <a href="#">
              <img src="<?php echo base_url('/optimum/logoside.png'); ?>">
            </a>
          </div>
          <div class="col-6 collapse-close">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
              <span></span>
              <span></span>
            </button>
          </div>
        </div>
      </div>
      <hr class="d-lg-none" />
      <ul class="navbar-nav align-items-lg-center ml-lg-auto">
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" data-original-title="Like us on Facebook">
            <i class="fab fa-facebook-square"></i>
            <span class="nav-link-inner--text d-lg-none">Facebook</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" data-original-title="Follow us on Instagram">
            <i class="fab fa-instagram"></i>
            <span class="nav-link-inner--text d-lg-none">Instagram</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" data-original-title="Follow us on Twitter">
            <i class="fab fa-twitter-square"></i>
            <span class="nav-link-inner--text d-lg-none">Twitter</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-icon" href="#" target="_blank" data-toggle="tooltip" data-original-title="Star us on Github">
            <i class="fab fa-github"></i>
            <span class="nav-link-inner--text d-lg-none">Github</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Main content -->
<div class="main-content">
  <!-- Header -->
  <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
    <div class="separator separator-bottom separator-skew zindex-100">
      <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
      </svg>
    </div>
  </div>
  <!-- Page content -->
  <div class="container mt--7 pb-5" id="varify-port">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
          <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-3"><small>Sign in with</small></div>
            <div class="btn-wrapper text-center">
              <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="<?php echo base_url(ASSETS); ?>/img/icons/common/facebook-circular-logo.svg"></span>
                <span class="btn-inner--text">FaceBook</span>
              </a>
              <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="<?php echo base_url(ASSETS); ?>/img/icons/common/search.svg"></span>
                <span class="btn-inner--text">Google</span>
              </a>
            </div>
          </div>
          <div class="card-body px-lg-5 py-lg-5">
            <div class="text-center text-muted mb-4">
              <small>Or sign in with credentials</small>
            </div>
            <form role="form" name="logform" action="<?php echo base_url('auth'); ?>"  method="post" autocomplete="off">
              <div class="form-group mb-3">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-badge"></i></span>
                  </div>
                  <input class="form-control" oninput="this.value = this.value.toUpperCase()" name="member_id" placeholder="Enter Your Mamber Id" type="text" value="" autocomplete="off">
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
