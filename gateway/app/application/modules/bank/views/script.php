 <script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>

<script type="text/javascript">
  $(document).ready(function() {
      var Api = '<?php echo base_url('bank/bank/'); ?>';

    var duid = '<?php echo isset($duid) ? $duid : ''; ?>'

    var $squadlist = $('#banklist');


    var $table = $squadlist.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No retailer data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_squadlist?list=all",
        type: "GET",
      },

      "pageLength": 10
    }); 
      
  });