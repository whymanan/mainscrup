<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#update').hide();

    var $submit = $('#submit_btn');
    $('input[name="name"]').on('change', function() {

      var search = $(this).val();

      $.ajax({
        url: '<?php echo base_url('menuexist'); ?>',
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
            $('input[name="name"]').parent().find('label').css({
              "color": "#525f7f",
            }).html('Menu');
            $submit.removeAttr("disabled", "disabled");
          }
        },
        complete: function() {
          $('input[name="parent"]').parent().find('img').remove();
        },
      })

    });
    $('#parent').on('select2:select', function(e) {

      var search = $(this).val();
      var menu = $('input[name="name"]').val();
      $.ajax({
        url: '<?php echo base_url('submenuexist'); ?>',
        type: 'POST',
        data: {
          'menu': menu,
          'parent': search,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        beforeSend: function() {
          $(this).parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          var result = JSON.parse(data);
          if (result.error) {
            $(this).css({
              "border": "2px solid #ff5050",
              "color": "#ff5050",
            }).parent().find('label').css({
              "color": "#ff5050",
            }).html(result.msg);
            $submit.attr("disabled", "disabled");
          } else {
            $(this).css({
              "border": "1px solid #2dce89",
              "color": "#2dce89",
            }).parent().append('<span><i class="zmdi zmdi-check"></i></span>');
            $(this).parent().find('label').css({
              "color": "#525f7f",
            }).html('Parent');
            $submit.removeAttr("disabled", "disabled");
          }
        },
        complete: function() {
          $(this).parent().find('img').remove();
        },
      })

    });
    $('#parent').select2({
      ajax: {
        url: '<?php echo base_url('external/get_menu'); ?>',
        type: "GET",
        dataType: 'json',

        data: function(params) {

          var queryParameters = {
            term: params.term,
            type: 'public',
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
          }
          return queryParameters;
        },
        processResults: function(data) {
          // console.log(data);
          return {
            results: $.map(data, function(item) {
              return {
                text: item.text,
                id: item.id
              }
            })
          };
        },
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
      }
    });

    $("#update_btn").on('click', function() {
      var form = $("form").serializeArray();
      console.log(form);
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/menu/update') ?>",
        data: {
          'form': form,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        datatype: 'json',
        success: function(data) {
          if (data = 1) {
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'Update Successfully',
              showConfirmButton: false,
              timer: 3500
            });
            $('#update').hide();
            $('#submit').show();
            $('.clear').val("");
          } else {
            Swal.fire({
              position: 'top-end',
              type: 'error',
              title: 'Something went wrong',
              showConfirmButton: false,
              timer: 3500
            });
          }
        }
      });
    });

    // distributor list

    var Api = '<?php echo base_url('admin/menu/'); ?>';

    var duid = '<?php echo isset($duid) ? $duid : ''; ?>'

    var $squadlist = $('#squadlist');


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
        
        $('#select_service').select2({
      ajax: {
        url: '<?php echo base_url('autoservice'); ?>',
        type: "GET",
        dataType: 'json',

        data: function(params) {

          var queryParameters = {
            search: params.term,
            type: 'public',
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
          }
          return queryParameters;
        },
        processResults: function(data) {
          // console.log(data);
          return {
            results: $.map(data, function(item) {
              return {
                text: item.text,
                id: item.id
              }
            })
          };
        },
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
      }
    });
    
       $("#is_service_menu").on('change', function(){
      if(this.checked) {
            $('#service_box').show();
        }else{
            $(this).val('null');
            $('#service_box').hide();
        }
    });

  });
</script>