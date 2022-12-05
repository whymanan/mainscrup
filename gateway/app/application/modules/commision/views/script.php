<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script type="text/javascript">
  var Api = '<?php echo base_url('commission/commission/'); ?>';

  var duid = '<?php echo $this->session->userdata("user_id") ?>';

  var $squadlist = $('#squadlist');

  $(document).ready(function() {
    var $submit = $('form[name="validate"] :submit');


    $('input[name="phone_no"]').on('change', function() {

      var search = $(this).val();

      $.ajax({
        url: '<?php echo base_url('vendorexist'); ?>',
        type: 'GET',
        data: {
          'search': search,
        },
        beforeSend: function() {
          $('input[name="phone_no"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          var result = JSON.parse(data);
          if (result.error) {
            $('input[name="phone_no"]').css({
              "border": "2px solid #ff5050",
              "color": "#ff5050",
            }).parent().find('label').css({
              "color": "#ff5050",
            }).html(result.msg);
            $submit.attr("disabled", "disabled");
          } else {
            $('input[name="phone_no"]').css({
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

    $('#vendor').select2({
      ajax: {
        url: '<?php echo base_url('autovendor'); ?>',
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
    
    $('#role').on('select2:select', function(e) {
      $.ajax({
        url: '<?php echo base_url('commission/get_slab'); ?>',
        type: 'GET',
       
        beforeSend: function() {
          $('#slab').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          $('#slab').html(data);
        },

      })
    });

    $('#cities').select2({
      minimumInputLength: 3, // only start searching when the user has input 3 or more characters
      ajax: {
        url: '<?php echo base_url('cities'); ?>',
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
    // Select states list
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

    $('#cities').select2({
      ajax: {
        url: '<?php echo base_url('cities'); ?>',
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
    // Select states list
    $('#states').select2({
      ajax: {
        url: '<?php echo base_url('states'); ?>',
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



    // var $table = $squadlist.DataTable({
    //   "searching": false,
    //   "processing": true,
    //   "serverSide": true,
    //   "deferRender": true,
    //   "language": {
    //     "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
    //     "emptyTable": "No distributors data available ...",
    //   },
    //   "order": [],
    //   "ajax": {
    //     url: Api + "get_squadlist?key=" + duid + "&list=all",
    //     type: "GET",
    //   },

    //   "pageLength": 10
    // });

    // $('.node').click(function() {

    //   var tab = $(this).attr('data-info');
    //   var url = '';
    //   switch (tab) {
    //     case 'all':
    //       url = Api + "get_squadlist?key=" + duid + "&list=all";
    //       break;
    //     case 'active':
    //       url = Api + "get_squadlist?key=" + duid + "&list=verify";
    //       break;
    //     case 'new':
    //       url = Api + "get_squadlist?key=" + duid + "&list=created";
    //       break;
    //     case 'pending':

    //       url = Api + "get_squadlist?key=" + duid + "&list=pending";

    //       break;
    //     default:
    //       url = Api + "get_squadlist?key=0&list=null";

    //   }

    //   $table.ajax.url(url).load();

    // });

    $('.refresh').on('click', function() {
      $table.ajax.reload();
      angular.element(document.querySelector('[ng-controller="executor"]')).scope().live();

    });

  });

  // EmoApp.controller('squad', function($scope, $http) {
  //   $scope.active = "";
  //   $scope.all = "";
  //   $scope.created = "";
  //   $scope.pending = "";
  //   $scope.live = function() {
  //     $http({
  //       method: 'GET',
  //       url: Api + 'live_count?key=' + duid,
  //     }).then(function success(response) {
  //       console.log(response);
  //       $scope.active = response.data.verify;
  //       $scope.all = response.data.all;
  //       $scope.created = response.data.created;
  //       $scope.pending = response.data.pending;


  //     }, function error(response) {

  //       // this function will be called when the request returned error status
  //       $scope.active = 'N/A';
  //       $scope.all = 'N/A';
  //       $scope.created = 'N/A';
  //       $scope.pending = 'N/A';
  //     });
  //   }
  //   $scope.live();
  // });
</script>