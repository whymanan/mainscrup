<?php $application_name = $this->db->get_where('setting', array('setting_name' => 'application_name'))->row()->setting_value; ?>
<?php $application_title = $this->db->get_where('setting', array('setting_name' => 'application_title'))->row()->setting_value; ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?php echo $application_title ?>">
  <meta name="author" content="EmoPay Tim">
  <title><?php echo $application_name; ?> - <?php echo $application_title ?></title>
  <!-- Favicon -->
  <link rel="icon" href="<?php echo base_url('optimum/favicon.ico'); ?>" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="<?php echo base_url(ASSETS); ?>/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/css/select2.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(ASSETS) ?>/vendor/sweetalert2/dist/sweetalert2.min.css" type="text/css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" type="text/css">

  <link rel="stylesheet" href="<?php echo base_url(ASSETS); ?>/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&family=Ubuntu:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url(ASSETS); ?>/css/argon.css?v=1.2.0" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(ASSETS); ?>/css/main.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(ASSETS); ?>/vendor/dropzone/dist/dropzone.css" type="text/css">

  <script src="<?php echo base_url(ASSETS); ?>/vendor/jquery/dist/jquery.min.js"></script>

  <script  src="<?php echo base_url(ASSETS); ?>/libs/angularjs/1.8.0/angular.min.js"></script>
  <script  src="<?php echo base_url(ASSETS); ?>/libs/angularjs/1.8.0/angular-route.js"></script>

  <script  src="<?php echo base_url('app/App.js'); ?>"></script>
  
  <!-- Hotjar Tracking Code for https://emopay.co.in -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:2272228,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>



</head>

<body ng-app="EmoApp">

  <?php get_section('side-bar'); ?>
