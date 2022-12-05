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
          if (transection === 'MS') {
            tBox.hide();
            // tBox.addClass('hide');
          }
      });


// Step 1 Select Bank List

      $('#bankList').on('submit', function(event){

        event.preventDefault();

        var bankCode = $('select[name="bank-select"]').val();
        if (typeof bankCode !== 'undefined' && bankCode) {
          formData.append('bankCode', bankCode);

          $.ajax({
            url: '<?php echo base_url('aeps/transection2'); ?>',
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
          url: '<?php echo base_url('aeps/submitTransection2'); ?>',
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
         
        $PidData = new XMLSerializer().serializeToString(xmlDoc.documentElement);
      
                  console.log($PidData);


          $info = $xml.find( "DeviceInfo" );
          if ($resp == 0) {
            formData.append('biodata', JSON.stringify(capture.biomatric));
            formData.append('biodevice', JSON.stringify($info));
            formData.append('pidData', capture.data);
            

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
      var Api = '<?php echo base_url('aeps2/PaySprintAepsController/'); ?>';
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
