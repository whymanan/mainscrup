<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {

      const formData = new FormData();
   
    var Api = '<?php echo base_url('dmt/DmtController/get_list'); ?>';



    var $squadlist = $('#list');


    var $table = $squadlist.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No distributors data available ...",
      },
      "order": [],
      "ajax": {
        url: Api,
        type: "GET",
          dataType: 'json',
            data: query
      },

      "pageLength": 10
    });


      let body  =   $('body');

     
     $(document).on('click', '#submit', function() {

          

          var onload = $(this).val();

          if (typeof onload !== 'undefined' && onload) {

            $.ajax({
              url: '<?php echo base_url('dmt/DmtController/addCommissionForm'); ?>',
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
    // distributor list


  
        
        
           
      
  
    });
</script>

