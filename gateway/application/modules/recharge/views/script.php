<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script type="text/javascript">

  var Api = '<?php echo base_url('kyc/kyc/'); ?>';
  var duid = '<?php echo $this->session->userdata("user_id") ?>';

    var $squadlist = $('#squadlist');
  $(document).ready(function() {
      
      $('#simplefilter').click(function()
      {
          
        var Api1 = '<?php echo base_url('recharge/RechargeController/'); ?>';  
        var $transectionlist = $('#transectionlist');
        var duid1 = '<?php echo $this->session->userdata("member_id") ?>';
        var type = $('#recharge_type').val();
        var serviceid=$('#serviceid').val(); 
        var member=$("#searchBymember").val();
        var from=$("#date_from").val();
        var to=$("#date_to").val();
        var default_a=$("#searchByCat").val();
        var default_v=$("#searchValue").val();
        var status=$("#searchBystatus").val();
        if(member==null)
        {
                    member=0;
                }
        if(from==null)
        {
                    from=0;
                }
        if(to==null)
        {
                    to=0;
                }
        if(default_a==null)
        {
                    default_a=0;
                }
        if(default_v==null)
        {
                    default_v=0;
                }
        if(status==null)
        {
                    status=0;
        }
        console.log(Api1+","+$transectionlist+","+duid1+","+type+","+serviceid+","+member+","+from+","+to+","+default_a+","+default_v+","+status);
        $transectionlist.dataTable().fnDestroy();
        var $table = $transectionlist.DataTable({
          "searching": false,
          "processing": true,
          "serverSide": true,
          "deferRender": true,
          "language": {
            "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
            "emptyTable": "No distributors data available ...",
          },
          "order": [],
          "ajax": {
            url: Api1 + "get_history?key=" + duid1 + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid+"&member="+member+"&from="+from+"&to="+to+"&default_a="+default_a+"&default_v="+default_v+"&status="+status,
            type: "GET",
          },

          "pageLength": 10
        });
      })
      $("#export").click(function()
      {
        var Api1 = '<?php echo base_url('recharge/RechargeController/'); ?>';  
        var $transectionlist = $('#transectionlist');
        var duid1 = '<?php echo $this->session->userdata("member_id") ?>';
        var type = $('#recharge_type').val();
        var serviceid=$('#serviceid').val(); 
        var member=$("#searchBymember").val();
        var from=$("#date_from").val();
        var to=$("#date_to").val();
        var default_a=$("#searchByCat").val();
        var default_v=$("#searchValue").val();
        var status=$("#searchBystatus").val();
        if(member==null)
        {
                    member=0;
                }
        if(from==null)
        {
                    from=0;
                }
        if(to==null)
        {
                    to=0;
                }
        if(default_a==null)
        {
                    default_a=0;
                }
        if(default_v==null)
        {
                    default_v=0;
                }
        if(status==null)
        {
                    status=0;
        }
          $.ajax({
                url: Api1 +"export?key=" + duid1 + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid+"&member="+member+"&from="+from+"&to="+to+"&default_a="+default_a+"&default_v="+default_v+"&status="+status,
                type: "GET", //send it through get method
               success: function(response) {
               //Do Something
              window.location=response;
                //  console.log(response);
                  },
              error: function(xhr) {
              //Do Something to handle error
            }
              });
      });
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
        url: Api + "get_users?list=new",
        type: "GET",
      },

      "pageLength": 10
    });
 $('.node').click(function() {

        var tab = $(this).attr('data-info');
        var url = '';
        switch (tab) {
            case 'new':
                url = Api + "get_users?key=" + duid + "&list=new";
                break;
            case 'verify':
                url = Api + "get_users?key=" + duid + "&list=verify";
                break;
            case 'reject':
                url = Api + "get_users?key=" + duid + "&list=reject";
                break;
            case 'pending':

                url = Api + "get_users?key=" + duid + "&list=pending";

                break;
            default:
                url = Api + "get_users?key=0&list=null";

        }

        $table.ajax.url(url).load();

    });

    $('.refresh').on('click', function() {
        $table.ajax.reload();
        angular.element(document.querySelector('[ng-controller="executor"]')).scope().live();

    });
  });
  
  EmoApp.controller('squad', function($scope, $http) {
    $scope.active = "";
    $scope.new = "";
    $scope.reject = "";
    $scope.pending = "";
    $scope.live = function() {
        $http({
            method: 'GET',
            url: Api + 'live_count?key=' + duid,
        }).then(function success(response) {
            console.log(response);
            $scope.active = response.data.verify;
            $scope.new = response.data.new;
            $scope.reject = response.data.reject;
            $scope.pending = response.data.pending;


        }, function error(response) {

            // this function will be called when the request returned error status
            $scope.active = 'N/A';
            $scope.new = 'N/A';
            $scope.reject = 'N/A';
            $scope.pending = 'N/A';
        });
    }
    $scope.live();
});
</script>