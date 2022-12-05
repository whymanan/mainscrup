<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>

<script type="text/javascript">
  $(document).ready(function() {

      const formData = new FormData();
      var $addpvtltdForm = $('#addpvtltdForm');
      let body =   $('body');

      body.on('change', 'select[name="com_type"]', function() {

        var onload = $(this).val();

        function readURL(input){
            
            if(input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function(e) {
                    img = $(input).attr('data-id');
                    $(img).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        if (typeof onload !== 'undefined' && onload) {

              $.ajax({
                url: '<?php echo base_url('legalservice/LegalController/addForm'); ?>',
                type: 'POST',
                data: {"company_type": onload, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
                beforeSend: function(){
                  $addpvtltdForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
                },
                success: function(data) {
                  $addpvtltdForm.html(data);
                },
              })
        }

      });


//       var Api = '<?php echo base_url('legalservice/LegalController/'); ?>';

//         var $gstlist = $('#gstlist');
//          var service = $('#service_type').val();
//         console.log(service);
//         var $table = $gstlist.DataTable({
//         "processing": true,
//         "serverSide": true,
//         "deferRender": true,
//         "language": {
//             "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
//             "emptyTable": "No data available ...",
//         },
//         "order": [],
//         "ajax": {
//             url: Api + "get_gstlist?"  + "&list=all&service="+service,
//             type: "GET",
//         },

//         "pageLength": 10
//         });

  });

</script>
