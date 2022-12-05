<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {

      const formData = new FormData();


      var $addCommissionForm = $('#addCommissionForm');

      let body  =   $('body');

      body.on('change', 'select[name="user_role"]', function() {

          var bioMetricCapture = $('#bioMetricCapture');

          var onload = $(this).val();

          if (typeof onload !== 'undefined' && onload) {

            $.ajax({
              url: '<?php echo base_url('aeps/commission/form'); ?>',
              type: 'POST',
              data: {"aepsCommissionForm": onload, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
              beforeSend: function(){
                $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                $addCommissionForm.html(data);
              },
            })
          }

        });


        $('#role').select2({
          ajax: {
            url: '<?php echo base_url('autorole'); ?>',
            type: "GET",
            dataType: 'json',
            data: function(params) {
              var query = {
                search: params.term,
                type: 'public',
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
              }
              return query;
            },
            processResults: function(data) {
              return {
                results: data
              };
            },
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
          }
        });


    });
</script>
