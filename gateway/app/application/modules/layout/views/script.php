<!-- Argon Scripts -->
<!-- Core -->
<script async src="<?php echo base_url(ASSETS); ?>/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script  src="<?php echo base_url(ASSETS); ?>/js/components/vendor/jquery.validate.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/js-cookie/js.cookie.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

<!-- Argon JS --><!-- Optional JS -->
<script  src="<?php echo base_url(ASSETS); ?>/vendor/chart.js/dist/Chart.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/chart.js/dist/Chart.extension.js"></script>

<script async src="<?php echo base_url(ASSETS); ?>/js/argon.js?v=1.2.0"></script>

<?php get_section('notify'); ?>

<?php  if(isset($is_script)): echo $is_script; endif?>

</body>

</html>
