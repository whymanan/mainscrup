
<script type="text/javascript">

    EmoApp.controller('AepsBanks', function( $scope, $http ) {

      $scope.aepsTransactionPanel = document.querySelector('#aepsTransactionPanel');
      $scope.bankCode = '';
      $scope.bankList = [];

      $http.get('http://localhost/public/api/aepsbanks').then(function (response) {

        $scope.bankList = response.data.codeValues;

      }, function (response) {});


    });


</script>
