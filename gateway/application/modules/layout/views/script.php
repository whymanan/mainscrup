<!-- Argon Scripts -->
<!-- Core -->
<script async src="<?php echo base_url(ASSETS); ?>/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script  src="<?php echo base_url(ASSETS); ?>/js/components/vendor/jquery.validate.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/js-cookie/js.cookie.js"></script>
<script src="<?php echo base_url(ASSETS); ?>/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
<!--<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->


<!-- Argon JS --><!-- Optional JS -->
<script  src="<?php echo base_url(ASSETS); ?>/vendor/chart.js/dist/Chart.min.js"></script>
<script async src="<?php echo base_url(ASSETS); ?>/vendor/chart.js/dist/Chart.extension.js"></script>

<script async src="<?php echo base_url(ASSETS); ?>/js/argon.js?v=1.2.0"></script>
<script>
$(document).ready(function()
{
    $("#cross").click(function()
    {
      $("body").removeClass('nav-open').removeClass('g-sidenav-pinned').removeClass('g-sidenav-show');
      $('.sidenav-toggler').removeClass('active');
      $("body").addClass('g-sidenav-hidden');
    });
});
</script>
<?php get_section('notify'); ?>

<?php  if(isset($is_script)): echo $is_script; endif?>
<!--<script type="text/javascript">-->
<!--    (function(c,l,a,r,i,t,y){-->
<!--        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};-->
<!--        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;-->
<!--        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);-->
<!--    })(window, document, "clarity", "script", "arj3dhaogx");-->
<!--</script>-->

</body>

</html>
