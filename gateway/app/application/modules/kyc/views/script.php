<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script type="text/javascript">

  var Api = '<?php echo base_url('kyc/kyc/'); ?>';


var duid = '<?php echo $this->session->userdata("user_id") ?>';

    var $squadlist = $('#squadlist');
  $(document).ready(function() {
    var $submit = $('form[name="validate"] :submit');


   

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


    // distributor list

$(document).on('change','.pincode', function(){
      var states = '';
      var city = '';
      var pin = $(this).val();
      var area = [];
      $.ajax({
        url: 'https://api.postalpincode.in/pincode/'+pin, // pincode to city , states list api
        type: 'GET',
        beforeSend: function() {
          $('input[name="pincode"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          $.each(data, function(index, value){
            if (value.Status === "Success") {
              $.each(value.PostOffice, function(index, value){
                states = value.State;
                city = value.District;
                area[index] = value.Name;
              });
              $('.states').html("<option selected value="+states+">"+states+"</option>");
              $('.cities').html("<option selected value="+city+">"+city+"</option>");
              html = '';
              $.each(area, function(index, value){
                html += "<option value="+value+">"+value+"</option>";
              });
              $('.area').html(html);
            }else{
            }
          });
        },
        complete: function() {
          $('input[name="pincode"]').parent().find('span').remove();
        },
      })

    });



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
      var tabName = $('#tabs');

        var tab = $(this).attr('data-info');
        var url = '';
        switch (tab) {
            case 'new':
                url = Api + "get_users?key=" + duid + "&list=new";
                tabName.html('New Join Users');

                break;
            case 'verify':
                url = Api + "get_users?key=" + duid + "&list=verify";
                tabName.html('Verified Users');

                break;
            case 'reject':
                url = Api + "get_users?key=" + duid + "&list=reject";
                tabName.html('Rejected Users');

                break;
            case 'pending':

                url = Api + "get_users?key=" + duid + "&list=pending";
                tabName.html('Pending Users');
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
