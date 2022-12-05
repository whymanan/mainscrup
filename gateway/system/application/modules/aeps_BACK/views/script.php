<?php get_section('aeps/app/Aeps'); ?>

<script type="text/javascript">
    $(document).ready(function() {

      const formData = new FormData();


      var $aepsTransactionPanel = $('#aepsTransactionPanel');

      let body =   $('body');


      body.on('change', 'select[name="device-select"]', function() {

          var bioMetricCapture = $('#bioMetricCapture');

          var onload = $(this).val();
          bioMetricCapture.attr( "disabled", "disabled" );

          switch (onload) {
            case 'mantra-mfs-100':
                $.getScript( '<?php echo base_url("optimum/biometric/mantra/mantra-mfs-100.js") ?>', function( data, textStatus, jqxhr ) {

                    var info = GetMFS100Info();

                    if (info.httpStaus) {
                      if (info.data.ErrorCode == 0) {
                        var flag = 0;
                        Swal.fire({
                          title: info.data.DeviceInfo.Make + " " + info.data.DeviceInfo.Model,
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
                          title: info.data.ErrorDescription,
                          html: "Error Information " + info.data.ErrorCode.bold(),
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




      $('#bankList').on('submit', function(event){

        event.preventDefault();

        var bankCode = $('select[name="bank-select"]').val();
        if (typeof bankCode !== 'undefined' && bankCode) {
          formData.append('bankCode', bankCode);


          $.ajax({
            url: '<?php echo base_url('aeps/transection'); ?>',
            type: 'POST',
            data: {"bankCode": bankCode, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
            success: function(data) {

              $aepsTransactionPanel.html(data);

            },
          })
        }

      });

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
            success: function(data) {

              $aepsTransactionPanel.html(data);

            },
          })
        }

      });


      body.on('click', '#bioMetricCapture', function(event) {
          event.preventDefault();
          Capture();
      });

      body.on('submit', 'form[name="transection-submit"]', function(event){
        event.preventDefault();

        $.ajax({
          url: '<?php echo base_url('aeps/submitTransection'); ?>',
          type: 'POST',
          data: {"transectionCode": true, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
          success: function(data) {

            $aepsTransactionPanel.html(data);

          },
        })

      });


      function Capture() {
        var quality = 60; //(1 to 100) (recommanded minimum 55)
        var timeout = 10; // seconds (minimum=10(recommanded), maximum=60, unlimited=0 )

        var info = CaptureFinger(quality, timeout);

        if (info.httpStaus) {

          if (info.data.ErrorCode == 0) {

            formData.append('data', info.data);

            Swal.fire({
              title: "Successfully Data Capture",
              html: "Error Information " + info.data.ErrorCode.bold(),
              type: 'success',
              showCancelButton: true,
            });


            $('#bioMetricCapture').attr( "disabled", "disabled" ).parent().append('<button type="submit" name="bioMetricSubmit" id="bioMetricSubmit" class="btn btn-primary my-4">Submit</button>')
            .parent().parent().find('form').attr('name', 'transection-submit');

          }else{
            Swal.fire({
              title: info.data.ErrorDescription,
              html: "Error Information " + info.data.ErrorCode.bold(),
              type: 'error',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Try Agen'
            })
          }
        } else {

          Swal.fire({
            title: info.data.ErrorDescription,
            html: "Error Information " + info.data.ErrorCode.bold(),
            type: 'error',
            showCancelButton: true,
          })

        }
      }




    });
</script>
