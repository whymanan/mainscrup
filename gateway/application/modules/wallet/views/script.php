<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
      
             var Api = '<?php echo base_url('wallet/wallet/'); ?>';
               var $summary_list = $('#summary_list');
               
                var $table = $summary_list.DataTable({
                  "processing": true,
                  "serverSide": true,
                  "deferRender": true,
                  "language": {
                    "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
                    "emptyTable": "<?php if(isAdmin($this->session->userdata("user_roles"))){ echo 'No distributors data available ...';}else{ echo 'This session only for admin....';}?>",
                    "sZeroRecords":"<?php if(isAdmin($this->session->userdata("user_roles"))){ echo 'No matching records found ...';}else{ echo 'This session only for admin....';}?>",
                  },
                  "order": [],
                  "ajax": {
                    url:Api + "get_summary_list",
                    type: "GET",
                  },
            
                  "pageLength": 10
                });


  });
</script>