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
  <link rel="icon" href="<?php echo base_url('optimum/favicon.ico');?>" type="image/png">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&family=Ubuntu:wght@500&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="<?php echo base_url(ASSETS);?>/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(ASSETS);?>/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="<?php echo base_url(ASSETS);?>/css/argon.css?v=1.2.0" type="text/css">
  <style>
      body {
      font-family: 'Ubuntu', sans-serif;
      font-size: 1.2rem;
      font-weight: 600;
      line-height: 1.5;
      
    }
  </style>
</head>

<body class="bg-default">
