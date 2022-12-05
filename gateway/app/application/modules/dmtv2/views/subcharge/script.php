<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {
        
       

      const formData = new FormData();


      var $addCommissionForm = $('#addCommissionForm');


          var onload = 1;

            $.ajax({
              url: '<?php echo base_url('dmtv2/DmtvtwoController/addSubForm'); ?>',
              type: 'POST',
              data: {"aepsCommissionForm": onload, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
              beforeSend: function(){
                $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                $addCommissionForm.html(data);
              },
            })

   
        
        
    });


       
</script>

