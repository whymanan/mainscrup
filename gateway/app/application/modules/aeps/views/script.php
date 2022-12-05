<?php get_section('aeps/app/Aeps'); ?>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {

      const formData = new FormData();


      var $aepsTransactionPanel = $('#aepsTransactionPanel');

      let body =   $('body');

      let biodeviceInfo = '';




      body.on('change', 'select[name="device-select"]', function() {

          var bioMetricCapture = $('#bioMetricCapture');

          var onload = $(this).val();
          bioMetricCapture.attr( "disabled", "disabled" );

          switch (onload) {
            case 'mantra-mfs-100':
                $.getScript( '<?php echo base_url("optimum/biometric/mantra/RDService.js") ?>', function( data, textStatus, jqxhr ) {
                    var info = discoverAvdm();
                    //console.log(info);
                    if (info && info!== 'undefined') {
                      if (info.data.status === "READY") {
                        biodeviceInfo = info;
                        var flag = 0;
                        Swal.fire({
                          title: info.data.info,
                          text: "You won't be able to revert this!",
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Next'
                        }).then((result) => {
                          if (result.value) {
                            bioMetricCapture.removeAttr( "disabled", "disabled" );
                          } else {
                              console.log(result);
                          }
                        });
                      }else{
                        Swal.fire({
                          title: "Device Not Connected",
                          html: "Error Information",
                          showCancelButton: true,
                        })
                      }
                    }else{
                      Swal.fire({
                        title: "Device Not Connected",
                        html: "Error Information -1",
                        showCancelButton: true,
                      })
                    }

                });
              break;
            default:

          }

        });

      body.on('change', 'select[name="selectTransactionsTypes"]', function() {
          var transection = $(this).val();
          var tBox = $('#tAmountBox');
          if (transection === 'BE') {
            tBox.hide();
            // tBox.addClass('hide');
          } else {
            tBox.show();
          }
      });


// Step 1 Select Bank List

      $('#bankList').on('submit', function(event){

        event.preventDefault();

        var bankCode = $('select[name="bank-select"]').val();
        if (typeof bankCode !== 'undefined' && bankCode) {
          formData.append('bankCode', bankCode);

          $.ajax({
            url: '<?php echo base_url('aeps/transection'); ?>',
            type: 'POST',
            data: {"bankCode": bankCode, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
            beforeSend: function(){
              $aepsTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {

              $aepsTransactionPanel.html(data);

            },
          })
        }

      });

// Step 2 Add transection Details like Mobile number, Aadhar Number transection Type, transection Amount

      body.on('submit', '#AddTransaction', function(event){

        event.preventDefault();

        var AddTransaction = $(this).serializeArray();


        if (typeof AddTransaction !== 'undefined' && AddTransaction) {

          $.each(AddTransaction,  function(key, value) {
            formData.append(value['name'], value['value']);
          });

          $.ajax({
            url: '<?php echo base_url('aeps/biometric'); ?>',
            type: 'POST',
            data: {"bioMetric": true, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
            beforeSend: function(){
              $aepsTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {

              $aepsTransactionPanel.html(data);

            },
          })
        }

      });


// Step 3 Capture bioMetric Information form bioMetric device

      body.on('click', '#bioMetricCapture', function(event) {
          event.preventDefault();
          if (biodeviceInfo.data.status === "READY") {
            var path = biodeviceInfo.url + biodeviceInfo.data.methodCapture;
            Capture(path);
          }
      });

// step 4 Prepare transection FormData


      body.on('submit', 'form[name="transection-submit"]', function(event){

        event.preventDefault();

          var html = `<div class="card">
            <div class="card-body">
              <div class="my-4">
                <div class="h1">Aadhaar Enabled Payment System (AePS)</div>
                <span class="h5 surtitle text-muted">
                   Please Confirm Your AePS Transaction for succeeding.
                </span>

              </div>
              <div class="row">
              <div class="col-sm-6">
                <span class="h6 surtitle text-muted">Aadhaar Number</span>
                <span class="d-block h3">` + formData.get('adharCardNumber')  + `</span>
              </div>
                <div class="col-sm-6">
                  <span class="h6 surtitle text-muted">Bank Code</span>
                  <span class="d-block h3">` + formData.get('bankCode')  + `</span>
                </div>
                <div class="col-sm-6">
                  <span class="h6 surtitle text-muted">Transactions Types</span>
                  <span class="d-block h3">` + formData.get('selectTransactionsTypes') + `</span>
                </div>
                <div class="col-sm-6">
                  <span class="h6 surtitle text-muted">Mobile Number</span>
                  <span class="d-block h3">` + formData.get('tmobilenumber') + `</span>
                </div>
                <div class="col-sm-6">
                  <span class="h6 surtitle text-muted">Transection Amount</span>
                  <span class="d-block h3">` + formData.get('transectionAmount')  + `</span>
                </div>
              </div>
              <div class="my-4">
                <button type="button" class="btn btn-md btn-success " id="transectionConfirm" >Confirm</button>
                <button type="button" class="btn btn-md btn-primary">Cancel</button>
              </div>
            </div>
          </div>`;

          $aepsTransactionPanel.html(html);

      });

      body.on('click', '#transectionConfirm', function(event){
        event.preventDefault();
        formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
        $.ajax({
          url: '<?php echo base_url('aeps/submitTransection'); ?>',
        //   processData: false,
          type: 'POST',
          data: formData,
        //   dataType: 'json',
            cache : false,
            contentType: false,
            processData: false,
          beforeSend: function() {
            $aepsTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
          },
          success: function(data) {
            $aepsTransactionPanel.html(data);
          }
        });
      })


      function Capture(path) {

          var capture = CaptureAvdm(path);
          var data = $.parseXML(capture.data);

          capture.biomatric.aadharno = formData.get('adharCardNumber');
          capture.biomatric.iCount = "0";
           capture.biomatric.iType = "0";
           capture.biomatric.pCount = "0";
           capture.biomatric.pType = "0";

          $xml = $( data );
          $resp = $xml.find( "Resp" ).attr('errCode');
          $errInfo = $xml.find( "Resp" ).attr('errInfo');

        if (capture.httpStaus) {
          $bioData = $xml.find( "Data" ).text();
      
        $PidData =capture.data
          
         var xmlDoc = new DOMParser().parseFromString($PidData, "application/xml");
         
        //  $PidData = new XMLSerializer().serializeToString(xmlDoc);
        // alert(new XMLSerializer().serializeToString(xmlDoc.documentElement));

        // document.getElementById("doc").textContent = new XMLSerializer().serializeToString(xmlDoc);

        $PidData = new XMLSerializer().serializeToString(xmlDoc.documentElement);
        //  $PidData =   '<PidData><Resp errCode="0" errInfo="Success" fCount="1" fType="0" nmPoints="40" qScore="66"/><DeviceInfo dpId="MANTRA.MSIPL" rdsId="MANTRA.WIN.001" rdsVer="1.0.2" mi="MFS100" mc="MIIEFzCCAv+gAwIBAgIDD0JAMA0GCSqGSIb3DQEBCwUAMIHqMSowKAYDVQQDEyFEUyBNYW50cmEgU29mdGVjaCBJbmRpYSBQdnQgTHRkIDcxQzBBBgNVBDMTOkIgMjAzIFNoYXBhdGggSGV4YSBvcHBvc2l0ZSBHdWphcmF0IEhpZ2ggQ291cnQgUyBHIEhpZ2h3YXkxEjAQBgNVBAkTCUFobWVkYWJhZDEQMA4GA1UECBMHR3VqYXJhdDEdMBsGA1UECxMUVGVjaG5pY2FsIERlcGFydG1lbnQxJTAjBgNVBAoTHE1hbnRyYSBTb2Z0ZWNoIEluZGlhIFB2dCBMdGQxCzAJBgNVBAYTAklOMB4XDTIxMDUxODA2MjM0MloXDTIxMDUyNDEwNTgwMVowgbAxJTAjBgNVBAMTHE1hbnRyYSBTb2Z0ZWNoIEluZGlhIFB2dCBMdGQxHjAcBgNVBAsTFUJpb21ldHJpYyBNYW51ZmFjdHVyZTEOMAwGA1UEChMFTVNJUEwxEjAQBgNVBAcTCUFITUVEQUJBRDEQMA4GA1UECBMHR1VKQVJBVDELMAkGA1UEBhMCSU4xJDAiBgkqhkiG9w0BCQEWFXN1cHBvcnRAbWFudHJhdGVjLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALTSkgaGunQTPYLjVuzQ+dtFhVMvVxDYyn1oFdg2Ufl4SpTV9nhjxyqCSq8fLt1z0YOXoNh0YHMkISAr+xfQvCO9dJVPKRKPW9HbT0Vm7szgrNNWIF1myI+NF/pwVYMQ5dUdX68+3ihrv6vtdrW7o59a03l5x6MoHlBfwqiay3m1TVfs4jJ0Q972DlZ6hCPk5zZkk20mJuNQKdU1KAF8LfQEb8yytLM6MmvBqvGnWmYCxH1YcyGK9kXCS2YSQJVll8FM2DRkR0xPS9nOhMrTx+wi6HnuvdW5GYFAJ2adcpVlCuSXQpVtzNpLrJuz4+sQ3kLQUXjBUtm+rnKV9KAnEbkCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAGRFGiGvhUHTXM18HrCgnmar2rvlpR5xuGkeV4yQRbqyDpKuqGewm4J6GRMAG0B5cfFGuYGmvpPA+wG1rJwSPIv/utRIwY8q02Bl5BUHk7JX0rq9NqCKO06lwYweEYznrX4yM7Mifx3buc6MVRtq7zlBx98YJkQQI/y7pr40h1SDvpp7f7wpA3xT4hvli3YjSt3cGFBV18SaHVeMOg3Fpf8Y+ZqGD96EyWgEmbEx32Vgl1+CfTzwUeSwPmfcjdPcXbDZ2ywPzPTz+V34BiMG67+5XiH5wPRy0lfoVdpteCsfnR8qF7tN+kVLO5W0z926PwmB4Gqs0NT5ceZbblANsnA==" dc="1f3e74da-db2b-4dbd-be82-09557767ba6b"><additional_info><Param name="srno" value="2927950"/><Param name="sysid" value="6A9FEBF6EBDAB48FBFF0"/><Param name="ts" value="2021-05-22T00:10:48+05:30"/></additional_info></DeviceInfo><Skey ci="20221021">N27sh4gFHL5t0TStyTJ9J1LD0aOuVnmj6foETG8C7vXlA+MElCnrY33Z94oSkDDTTybU0s2W71CSaZPeHcA7390kvgu62VV6G/VF6kgqYuAhdwHbzk8rj97RDQlhd/OjIwg0LuQRWF2X9x/tIfmhoMB5MGWpUx5xWv8t9Qi2Y+cJ1+bsweTYC6NE9EGAGX0X18KAzTsKTUb2/LPgOxC8cfirVrfEuH2g48yr05UOkiTNn+MvqLCYYCCO4y5Ni34qhnRza5W/PYjd/KVMElTzO+KZ+1x3jvLacp9TPUOtdONNddKmDShM21s/T7N4pvGSHnWr8RbhsyL8oBRA644PLg==</Skey><Hmac>pE5AvlZp8wIJYXNmt5c+2TXnbNQfarLoaEq1T8d7fi6dbeXFwgNfrMuD/eUR9y32</Hmac><Data type="X">MjAyMS0wNS0yMlQwMDoxMDo0OEAR6/G9KGGWGbdTCmXEqID4IAuhF4BcDp+8mAHzkFlbGAndLLQ8qiOsVgSQ0G6HKMm021fnY9T6qR4qLSUBF/uqUdOFJ+OyDt8Z8NaVIg2BAkoT+YDl4299yl3p6eU9CJR1vIGUNtAuARUzYEQJ9jOc2+4sXcWtNBKJQ/qz7apuX27mn5q8ruv6XzfpPY2QSbRGjMRV5hUYv2COauBsnQ2DKIXFVGjkESkQ0O84EifWYHUfPV+7DkcJLIDVGh3MWSkwHgYJIrF/6X/XaA+jsWQBa/iN19TzgKKbcIekLPK0odSlogjIe943lw+WoRRjGTXh7dHmmV6Y30gdMg85IHzvgJ1v/SS9FqFws/BmocWe1gekReYrbiZuDBvlr8WbIXnzi6lBcla0sYKMGwugxQw7QMskpHTQsDaO1/RDzg4Wmc1be4IssekOQQbUKyYGLdzqTMEJo1MQLDhbmZdhqjzE3EiXD6pkCy7XULOHJd2Tg2iAecPHKsRehEuhc6E6O8NiPA+t5b+CgZVAkJP/Nb25u/jNpXc1J+RazskamqCHtnKE18lUu8Y61EvsM8H5pnAVBXVnyvpr2gbx7DntbtpZsLTc8U5/hT1W/JOVj3kv/vs2YmAply34ayd/9ArejLoqNDfam6L1PiGEQPJUnMng6LESBA65/erglOE42hoQOwx5HgGjbw1gq74QOqQgr2Y7YMqiYSQzUq5rXs30Wftt3k4uPDQRgwcN4/9+HDOM23xK59wOwXSznAOKO76QK4b4hDhZUL10myX8yKz4uicXUAcSJdzO9CKgmBb4co4L7tGjG726A6kQ3bkxLIfw4K7RRid7tqRlOsOHR3WJ1OhqAPPyV5eJPuK5r68PhlvwdrS+hALBRNdX1jTohsdbBHaLBH5gABbTVyoMfoTNE3FPxr8at7+Jr9Zp3ZmFnqsLKbJP1hxI127lDpgzGpWPmX7I2C/vEB2rUoAfIpq9ZaL7JOzVuNc+5189HNZxv3uU0zHcAOtEJC7E1ZZ0GOXeLhjti97bxkQwi7L0Jggs5LApTpkuLvnziPbPugZrBlEPl8K68YR6cr6K0JwhDpQtXEGrCgcxAblirHm8F86EwH0iwjvMrABwDYGCpS2cZKXAI/WIEaY6aerFMs0QzBVbd5yS5FoKny3jJ0zPfeHdjgy9yDX5097y+n0FIpoL4xcQW4x1dI4wmDB0u/kc</Data></PidData>';
          
        //   $PidData =capture.data;
        //  console.log(jQuery.parseXML(capture.data));
        //   $PidData = $PidData.find( "Data" ).text();
        //   $PidData = '<PidData><Resp errCode="0" errInfo="Success" fCount="1" fType="0" nmPoints="29" qScore="65" /><DeviceInfo dpId="MANTRA.MSIPL" rdsId="MANTRA.WIN.001" rdsVer="1.0.2" mi="MFS100" mc="MIIEFzCCAv+gAwIBAgIDD0JAMA0GCSqGSIb3DQEBCwUAMIHqMSowKAYDVQQDEyFEUyBNYW50cmEgU29mdGVjaCBJbmRpYSBQdnQgTHRkIDcxQzBBBgNVBDMTOkIgMjAzIFNoYXBhdGggSGV4YSBvcHBvc2l0ZSBHdWphcmF0IEhpZ2ggQ291cnQgUyBHIEhpZ2h3YXkxEjAQBgNVBAkTCUFobWVkYWJhZDEQMA4GA1UECBMHR3VqYXJhdDEdMBsGA1UECxMUVGVjaG5pY2FsIERlcGFydG1lbnQxJTAjBgNVBAoTHE1hbnRyYSBTb2Z0ZWNoIEluZGlhIFB2dCBMdGQxCzAJBgNVBAYTAklOMB4XDTIxMDUxODA2MjM0MloXDTIxMDUyNDEwNTgwMVowgbAxJTAjBgNVBAMTHE1hbnRyYSBTb2Z0ZWNoIEluZGlhIFB2dCBMdGQxHjAcBgNVBAsTFUJpb21ldHJpYyBNYW51ZmFjdHVyZTEOMAwGA1UEChMFTVNJUEwxEjAQBgNVBAcTCUFITUVEQUJBRDEQMA4GA1UECBMHR1VKQVJBVDELMAkGA1UEBhMCSU4xJDAiBgkqhkiG9w0BCQEWFXN1cHBvcnRAbWFudHJhdGVjLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALTSkgaGunQTPYLjVuzQ+dtFhVMvVxDYyn1oFdg2Ufl4SpTV9nhjxyqCSq8fLt1z0YOXoNh0YHMkISAr+xfQvCO9dJVPKRKPW9HbT0Vm7szgrNNWIF1myI+NF/pwVYMQ5dUdX68+3ihrv6vtdrW7o59a03l5x6MoHlBfwqiay3m1TVfs4jJ0Q972DlZ6hCPk5zZkk20mJuNQKdU1KAF8LfQEb8yytLM6MmvBqvGnWmYCxH1YcyGK9kXCS2YSQJVll8FM2DRkR0xPS9nOhMrTx+wi6HnuvdW5GYFAJ2adcpVlCuSXQpVtzNpLrJuz4+sQ3kLQUXjBUtm+rnKV9KAnEbkCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAGRFGiGvhUHTXM18HrCgnmar2rvlpR5xuGkeV4yQRbqyDpKuqGewm4J6GRMAG0B5cfFGuYGmvpPA+wG1rJwSPIv/utRIwY8q02Bl5BUHk7JX0rq9NqCKO06lwYweEYznrX4yM7Mifx3buc6MVRtq7zlBx98YJkQQI/y7pr40h1SDvpp7f7wpA3xT4hvli3YjSt3cGFBV18SaHVeMOg3Fpf8Y+ZqGD96EyWgEmbEx32Vgl1+CfTzwUeSwPmfcjdPcXbDZ2ywPzPTz+V34BiMG67+5XiH5wPRy0lfoVdpteCsfnR8qF7tN+kVLO5W0z926PwmB4Gqs0NT5ceZbblANsnA==" dc="1f3e74da-db2b-4dbd-be82-09557767ba6b"><additional_info><Param name="srno" value="2927950" /><Param name="sysid" value="6A9FEBF6EBDAB48FBFF0" /><Param name="ts" value="2021-05-20T22:50:45+05:30" /></additional_info></DeviceInfo><Skey ci="20221021">BQmjOJcVYSuwUWuyclpCtPq3660midbBrmDTzNJ3Ct76jjAkWmfjbJXlb/RxaJo+u5zc0AHY+iJaO8+E8vHp/qGVKoDNAMrGBrIbAiPwCNLTHKTl7Sk/Zv1jysEICE6SKdK3IuDQd1awSN9LdVlAyYxSQ6yYTI26GYMVfnIshXEHX8epaWiLwvo+MPPzbjGTWFR3ZsKqa8KCQqSoMVqYBabW58mwmix1soxyAlVkBJAWZQkIpVwLv5dnZ+CRLJFgWXk3YvxBuNRheOlWCvMCvA3cdwc3af5Pfb8/I19RJBwphWGuVMmpgqlwlMmHkINl46s4Hf1omwzLTpHxyHbYtg==</Skey><Hmac>Y+GWX38HQ5TiWNxssJDNd93JTTBjYMvVFL+Oc/sEuY4BBYMeWphUrhHEIMgnEU7i</Hmac><Data type="X">MjAyMS0wNS0yMFQyMjo1MDo0NZMeSFfhtXNx6+F54e2iKBafT6Ba1/8lZH2R7e0/U8tBpnkDzfOIaFTD/X0egKBtbG27eynguJzKOhLFM97NxvKx/Fu5JGaY5anIFvvSlMX1BRhU3ROQgjk4vRsONptnAXrxCJsSqbS3ZzP24cFR+U61tWLWufjetfomsIx8fvkV5VAY48y34//y5O1Azr6OiPG4EYBF39TQ/PH0sB5tVqg3q8NeAq1zzNWqO1CZUojFKsWVkT34peB+LarfjQhBkZCgKO4XuKPbsFacw51IP6yc71GtLUc4OXL14R6ijFqYTZ7pg3z624fphqtgC1a9qNftAfnBwoXRrhwgVwMqCVEJ2W2vMwQKoqyaWsh8KSUHkDYiiJC2h2O6X3qVZ0QYjWfpl7f2hQUY1Qv2ZBh+C+wj6CEroivVcFMIgTHpo5hxwE8dbvwQhBDDcSxkUNVycrFgC/o9jJNsgZOgdWVYaWeyxcP03KmivwkF1VFrcO6f7IXD5qsSiXFyac/sokgM/4Swm309hq84bxqeh/RESlW8nD1GZA7j76VhmpF2SYidM2Uo7QkUVtksiHEIB5CZXz7hkjexYYxdqChFZeQRF2lpNdk0eNvxhTnAltWIqc4eRD/nZkSf4DDVLSCKu1+xCKvHm4R/EHG/XkiPgawswuml2sfhcV7JRTiWuBtQgV/WymGi7fnwHYrBOMMUi3B4vZJn1H5H4wFZZCyVLdu776Dmix4oqmh4q/RDem5cd680mFwTUcW6QUkuMwWO3pmlaNSEkbZSXik2XA2sk8H/JDUxz2lpIt43u73KInAj/j7x8Yd87aPorYNvJlgafsV01dq5ZUlDS8Eu6zcYWzVkyjIfWizizmWgR47QNNoFWuzcjuZPzk2XbfDxbN9jrW/HuU4Q5kCyD9flAMiFcm4NGecQfJ4Fbt2oQNKf0mUHufKlDBVgYP5js9Q95tz/p+yuMxev0H3S2R1M7Lmgrbvfz6PXHhf9nWVpP8GyPMC7YFliShxCKMc6REW8dW4bh9S8ieeolqKx7Yjflq2wGyxgo1igqIvK5w5OS6f2oqrtB13HZu26+RQjGdy4AhB8MvDH5dWJEto=</Data></PidData>';
         console.log(capture.biomatric);
                  console.log($PidData);

        //  alert(capture.biomatric)

          $info = $xml.find( "DeviceInfo" );
          if ($resp == 0) {
            formData.append('biodata', JSON.stringify(capture.biomatric));
            formData.append('biodevice', JSON.stringify($info));
            formData.append('pidData', JSON.stringify(capture.data));
            formData.append('pidData1', capture.data);
            

            Swal.fire({
              title: "Successfully Data Capture",
              html: "Error Information " + $resp.bold(),
              type: 'success',
              showCancelButton: true,
            });

            $('#bioMetricCapture').attr( "disabled", "disabled" ).parent().append('<button type="submit" name="bioMetricSubmit" id="bioMetricSubmit" class="btn btn-primary my-4">Submit</button>')
            .parent().parent().find('form').attr('name', 'transection-submit');

          }else{
            Swal.fire({
              title: $errInfo,
              html: "Error Information " + $resp.bold(),
              type: 'error',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Try Agen'
            })
          }
        } else {

          Swal.fire({
            title: capture,
            html: "Error Information " + capture.bold(),
            type: 'error',
            showCancelButton: true,
          })

        }
      }

// Ak



         var $transectionlist = $('#transectionlist');
           var duid = '<?php echo $this->session->userdata("user_id") ?>';
      var Api = '<?php echo base_url('aeps/AepsController/'); ?>';
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
        url: Api + "get_transectionlist?key=" + duid + "&list=all",
        type: "GET",
      },

      "pageLength": 10
    });

 $("#simplefilter").click(function(event) {
      event.preventDefault();
        // console.log($(searchValue).val())
    url = Api + "get_transectionlist?key=" + duid + "&date_from=" + $('#date_from').val() + "&date_to=" + $('#date_to').val() + "&searchValue="+  $('#searchValue').val() +"&searchByCat=" + $('#searchByCat').val() + "&search=simple&list=all";
       $table.ajax.url(url).load();

    });
$("#clear").click(function(event) {
      event.preventDefault();

   url = Api + "get_transectionlist?key=" + duid + "&list=all";
       $table.ajax.url(url).load();

    });


//

    });
</script>
