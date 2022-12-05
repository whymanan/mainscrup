
<script type="text/javascript">

    EmoApp.controller('AepsBanks', function( $scope, $http ) {

      $scope.aepsTransactionPanel = document.querySelector('#aepsTransactionPanel');
      $scope.bankCode = '';
      $scope.bankList = [];

      var $api = '<?php echo _SERVICE_API_ . 'aepsbanks'; ?>';

      var $token = '<?php echo  $this->session->userdata('token') ?>';

      $http.get($api, {
        headers: {
          'Content-Type': 'application/json',
           'Authorization': 'Bearer ' + $token
        }
      }) .then(function (response) {

        $scope.bankList = response.data.codeValues;

      }, function (response) {});


    });


</script>
