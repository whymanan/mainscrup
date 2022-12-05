<?php get_section('aeps2/app/Aeps'); ?>
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
                        
                            biodeviceInfo = "mantra-mfs-100";
                            bioMetricCapture.removeAttr( "disabled", "disabled" );
                              
    
                    });
                break;
                
            // Morpho

                case 'mph-se002a':
                    $.getScript( '<?php echo base_url("optimum/biometric/morpho/mph-se002a.js") ?>', function( data, textStatus, jqxhr ) {
                            
                            biodeviceInfo = "mph-se002a";
                            bioMetricCapture.removeAttr( "disabled", "disabled" );
                      
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
          } else {
            tBox.show();
          }
          if (transection === 'MS') {
            tBox.hide();
          }
      });



      
        body.on('click', '#bioMetricCapture', function(event) {
          event.preventDefault();
          
            if(biodeviceInfo == "mph-se002a"){
           
              var path = "http://127.0.0.1:11100/";
              var device = "mph-se002a";
    
            }else if(biodeviceInfo == "mantra-mfs-100"){
    
              var path = "http://127.0.0.1:11100/";
              var device = "mantra-mfs-100";
    
            }

            Capture(path,device);

        });

      body.on('submit', 'form[name="transection-submit"]', function(event){
        event.preventDefault();
        formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
        console.log(formData.get('transectionAmount'));
        $.ajax({
          url: '<?php echo base_url('aeps/submitTransection2'); ?>',
            type: 'POST',
            data: formData,
            cache : false,
            contentType: false,
            processData: false,
            timeout: 50000,
          beforeSend: function() {
            $aepsTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
          },
            error: function(){
                if(formData.get('selectTransactionsTypes') != "M"){
                    $.ajax({
                      url: '<?php echo base_url('aeps2/PaySprintAepsController/check_status'); ?>',
                      type: 'POST',
                      data: formData,
                      cache : false,
                      contentType: false,
                      processData: false,
                      success: function(data) {
                          Swal.fire({
                                          type: 'warning',
                                          text: "Done click on Ok! Check Your Transaction Status"
                                        });
                                        
                                        $('.swal2-confirm').click(function(){
                                                window.location.href = "aeps/thistory2";
                                          });
                        }
                    });
                }
            },
          success: function(data) {
            $aepsTransactionPanel.html(data);
          }
        });
      })


      function Capture(path,device) {

        if(device == "mph-se002a"){
          
          var capture = Capturemorpho(path);

        }else if(device == "mantra-mfs-100"){

          var capture = CaptureAvdm(path);

        }
          
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
      
            formData.append('bankCode', $('#bankselect').val() );
            formData.append('selectTransactionsTypes', $('#selectTransactionsTypes').val() );
            formData.append('adharCardNumber', $("input[name=adharCardNumber]").val() );
            formData.append('tmobilenumber', $("input[name=tmobilenumber]").val() );
            formData.append('transectionAmount', $('#transectionAmount').val() );
            formData.append('referenceno', $("input[name=referenceno]").val() );


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
      "searching": true,
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
