<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#update').hide();
    var $submit = $('#submit_btn');
    $('#name').on('change', function() {

      var search = $(this).val();

      $.ajax({
        url: '<?php echo base_url('role_exist'); ?>',
        type: 'GET',
        data: {
          'search': search,
        },
        beforeSend: function() {
          $('input[name="name"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          var result = JSON.parse(data);
          if (result.error) {
            $('input[name="name"]').css({
              "border": "2px solid #ff5050",
              "color": "#ff5050",
            }).parent().find('label').css({
              "color": "#ff5050",
            }).html(result.msg);
            $submit.attr("disabled", "disabled");
          } else {
            $('input[name="name"]').css({
              "border": "1px solid #2dce89",
              "color": "#2dce89",
            }).parent().append('<span><i class="zmdi zmdi-check"></i></span>');
            $('input[name="phone_no"]').parent().find('label').css({
              "color": "#525f7f",
            }).html('Mobile');
            $submit.removeAttr("disabled", "disabled");
          }
        },
        complete: function() {
          $('input[name="ifscCode"]').parent().find('img').remove();
        },
      })

    });

    $('.tab').on('click', function() {

      var id = $(this).attr('id');
      var name = $(this).text();
      var role = $("#role_id").val();
      $.ajax({
        url: '<?php echo base_url('role/get_tabs'); ?>',
        type: 'POST',
        data: {
          'id': id,
          'name': name,
          'role': role,
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

        },
        beforeSend: function() {
          $('#myTabContent').append('<span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          $('#myTabContent').html(data)

        },

      })

    });

    $(document).on('change', '.permissions_check_children', function() {
      $(this).attr('checked', this.checked)
      //console.log($(this).prop('checked'));
      var menu = $(this).val();
      var role = $("#role_id").val();
      if ($(this).prop('checked') == true) {
        var type = "insert";
      } else {
        var type = "delete";
      }
      $.ajax({
        url: '<?php echo base_url('role/update_role_permission  '); ?>',
        type: 'POST',
        data: {
          'id': menu,
          'role': role,
          'type': type,
         
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

        },

        success: function(data) {
          if (data == 1) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Saved Successfully !!',
              showConfirmButton: false,
              timer: 500
            });
          } else {
            Swal.fire({
              position: 'top-end',
              icon: 'error',
              title: 'Something went wrong !!',
              showConfirmButton: false,
              timer: 500
            });
          }

        },

      })

    });
    $(document).on('change', '.permissions_check_all', function() {
      $(this).attr('checked', this.checked)
      //console.log($(this).prop('checked'));
      var menu = $(this).val();
      var role = $("#role_id").val();
      if ($(this).prop('checked') == true) {
        var type = "insert";
      } else {
        var type = "delete";
      }
      $.ajax({
        url: '<?php echo base_url('role/update_role_permission2'); ?>',
        type: 'POST',
        data: {
          'id': menu,
          'role': role,
          'type': type,
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

        },

        success: function(data) {
          // if (data == 1) {
          //   Swal.fire({
          //     position: 'top-end',
          //     icon: 'success',
          //     title: 'Saved Successfully !!',
          //     showConfirmButton: false,
          //     timer: 500
          //   });
          // } else {
          //   Swal.fire({
          //     position: 'top-end',
          //     icon: 'error',
          //     title: 'Something went wrong !!',
          //     showConfirmButton: false,
          //     timer: 500
          //   });
          // }

        },

      })

    });
    // distributor list

    var Api = '<?php echo base_url('admin/role/'); ?>';

    var duid = '<?php echo isset($duid) ? $duid : ''; ?>'

    var $squadlist = $('#squadlist');


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
        url: Api + "get_squadlist?list=all",
        type: "GET",
      },

      "pageLength": 10
    });

    $('#notify').click(function() {
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Hello World',
        showConfirmButton: false,
        timer: 1500
      });
    });
    $(document).on('click', '.permissions_check_all', function() {


      $('.permissions_check_children').prop('checked', this.checked);
    });


  });
</script>