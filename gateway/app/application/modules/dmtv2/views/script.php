<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {

      const formData = new FormData();


      var $dmtTransactionPanel = $('#dmtTransactionPanel');

      let body =   $('body');

      body.on('change', 'input[name="beneficiary_ifsc_code"]', function() {
        var $this = $(this);
          var ifsc = $this.val();
          var bankName = $('input[name="beneficiary_bank"]');
          var submit = $('button[name="beneficiary_submit"]');
          if (typeof ifsc !== 'undefined' && ifsc) {

            $.ajax({
              url: '<?php echo base_url('ifscdata'); ?>',
              type: 'GET',
              dataType: 'json',
              data: {"search": ifsc, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
              beforeSend: function(){
                bankName.append('<img width="50" src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                if (!data.error) {
                  submit.parent().find('#error').remove();
                   bankName.val(data.bank);
                   submit.prop('disabled', false);
                }else{
                  submit.parent().append('<span id="error" class="text-danger">'+data.error+'<span/>');
                  submit.prop('disabled', true);
                }

              },
            })
          }
      });


// Step 1 Select Bank List

      $('#mobileSearch').on('submit', function(event){

        event.preventDefault();

        var mobileNm = $('input[name="phone_no"]').val();

        if (typeof mobileNm !== 'undefined' && mobileNm) {
          formData.append('mobile', mobileNm);

          $.ajax({
            url: '<?php echo base_url('dmtv2/customers'); ?>',
            type: 'GET',
            data: {"mobile": mobileNm, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
            beforeSend: function() {
              $dmtTransactionPanel.html('<img class="ajaxpreload" src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {
              $dmtTransactionPanel.html(data);
            },
            complete: function (data) {
              $dmtTransactionPanel.find('.ajaxpreload').remove();
            }
          })
        }

      });


      body.on('submit', 'form[name="addBeneficiaryForm"]', function(event){

        event.preventDefault();

        var addBeneficiary = $(this).serializeArray();

        if (typeof addBeneficiary !== 'undefined' && addBeneficiary) {

          $.each(addBeneficiary,  function(key, value) {
            formData.append(value['name'], value['value']);
          });

          $.ajax({
            url: '<?php echo base_url('dmtv2/addBeneficiary'); ?>',
            processData: false,
            contentType: false,
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function(){
              $dmtTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {

              if (data.error) {

                Swal.fire({
                  type: 'error',
                  text: data.msg
                });

              }else{
                // AddBeneficiaryOtpVarify(data.Mobile);
                location.reload();
              }

            },
            complete: function (data) {
              $dmtTransactionPanel.find('.ajaxpreload').remove();
            }
          })
        }

      });

      body.on('submit', '#AddCustomer', function(event){

        event.preventDefault();

        var AddCustomer = $(this).serializeArray();


        if (typeof AddCustomer !== 'undefined' && AddCustomer) {

          $.ajax({
            url: '<?php echo base_url('dmtv2/addCustomer'); ?>',
            type: 'POST',
            data: AddCustomer,
            dataType: 'json',
            beforeSend: function(){
              $dmtTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {
                
                console.log(data)
            
              if (data.error) {
               location.reload();
                Swal.fire({
                  type: 'error',
                  text: data.msg
                });

              }else{
                 location.reload();
                // AddCustomerOtpVarify(data.Mobile);
              }

            },
            complete: function (data) {
                location.reload();
              $dmtTransactionPanel.find('.ajaxpreload').remove();
            }
          })
        }

      });




      function AddBeneficiaryOtpVarify(mobile){
        var url = '<?php echo base_url('dmtv2/beneficiaryOtpVarify/') ?>' + mobile
        Swal.fire({
          title: 'Enter OTP Send Customer Mobile -' + mobile,
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Look up',
          showLoaderOnConfirm: true,
          preConfirm: (login) => {
            return fetch(url + "/" + login)
              .then(response => {
                console.log(response);
                if (!response.ok) {
                  throw new Error(response.statusText)
                }
                return response.json();
              })
              .catch(error => {
                Swal.showValidationMessage(
                  `Request failed: ${error}`
                )
              })
          },
          allowOutsideClick: () => Swal.isLoading()
        }).then((result) => {
          if (result.value.error) {
            Swal.fire({
              type: 'error',
              title: result.value.Status,
              text: result.value.resText,
            })
          }else{
            Swal.fire({
              type: 'success',
              title: result.value.Status,
              text: result.value.resText,
            })
            $('#mobileSearch').trigger( "submit" );
          }
        })
      }
      
        body.on('click', 'button[name="make-payment"]', function(event) {
        event.preventDefault();
        var $this = $(this);
        var bId = $this.attr('data-bid');
        if (typeof bId !== 'undefined' && bId) {
          $('#additional').load('<?php echo base_url('dmtv2/dmtTForm') ?>?bId='+bId,function(){
               $('#modal-many-transfer').modal({show:true});
           });
        }else{

        }
      });
// modal-many-transfer
      body.on('submit', 'form[name="makeTransactionForm"]', function(event) {
        event.preventDefault();
        var tFormData = new FormData();

        var addTransaction = $(this).serializeArray();

        if (typeof addTransaction !== 'undefined' && addTransaction) {

          $.each(addTransaction,  function(key, value) {
            tFormData.append(value['name'], value['value']);
          });

          $.ajax({
            url: '<?php echo base_url('dmtv2/mTransfer'); ?>',
            processData: false,
            contentType: false,
            type: 'POST',
            data: tFormData,
            dataType: 'json',
            beforeSend: function() {
              $dmtTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {

               if (data.error) {
                    swal({icon: 'error',
                          title: 'Oops...',
                          text: data.msg,}).then(function(){ 
                            location.reload();
                            }
                        );
                                  
               }else{
                  swal({title: "Good job", text: data.msg, type: 
                        "success"}).then(function(){ 
                            location.reload();
                            }
                        );
                   
               }

            },
            complete: function (data) {
              $dmtTransactionPanel.find('.ajaxpreload').remove();
            }
          })

        }else{

        }

      });

         var $transectionlist = $('#transectionlist');
           var duid = '<?php echo $this->session->userdata("user_id") ?>';
      var Api = '<?php echo base_url('dmtv2/DmtvtwoController/'); ?>';
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
        url: Api + "get_history?key=" + duid + "&list=all",
        type: "GET",
      },

      "pageLength": 10
    });
    


  });
</script>
