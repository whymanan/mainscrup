<?php get_section('aeps/app/Aeps'); ?>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>


<script type="text/javascript">
    $(document).ready(function() {

      const formData = new FormData();


      var $dmtTransactionPanel = $('#dmtTransactionPanel');
      var $dmtTransactionForm = $('#dmtTransactionForm');

      let body =   $('body');

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

      $('#mobileSearch').on('change', function(event){

        event.preventDefault();
        var thisForm = this;
        var mobileNm = $(this).val();

        if (typeof mobileNm !== 'undefined' && mobileNm) {

          $.ajax({
            url: '<?php echo base_url('dmt/addBeneficiaryForm'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {"mobile": mobileNm, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
            beforeSend: function(){
              thisForm.append('<img width="50" src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {

              // if (!data.error) {
              //   $dmtTransactionForm.serializeArray();
              // }else{
              //
              // }

            },
          })
        }

      });

// Step 2 Add transection Details like Mobile number, Aadhar Number transection Type, transection Amount


      body.on('submit', 'form[name="dmtTransactionForm"]', function(event){

        event.preventDefault();

        var BeneficiaryForm = $(this).serializeArray();


        if (typeof BeneficiaryForm !== 'undefined' && BeneficiaryForm) {

          $.ajax({
            url: '<?php echo base_url('dmt/submitBeneficiaryForm'); ?>',
            type: 'POST',
            data: BeneficiaryForm,
            beforeSend: function(){
                $dmtTransactionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {
              $dmtTransactionPanel.html(data);
            },
          })
        }

      });


// Step 3 Capture bioMetric Information form bioMetric device

      body.on('click', '#bioMetricCapture', function(event) {
          event.preventDefault();
          Capture();
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
          processData: false,
          contentType: false,
          type: 'POST',
          data: formData,
          dataType: 'json',
          beforeSend: function() {
            $aepsTransactionPanel.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
          },
          success: function(data) {

          }
        });
      })


      function Capture() {
        var quality = 60; //(1 to 100) (recommanded minimum 55)
        var timeout = 10; // seconds (minimum=10(recommanded), maximum=60, unlimited=0 )

        var info = GetMFS100Info();
        var capture = CaptureFinger(quality, timeout);

        if (capture.httpStaus) {

          if (capture.data.ErrorCode == 0) {

            formData.append('biodata', JSON.stringify(capture.data));
            formData.append('biodevice', JSON.stringify(info.data));

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
      
    //   ak
         $("[name ='beneficiary_ifsc']").on('change', function(event){
        
        event.preventDefault();
        var thisForm = this;
        var ifsc = $(this).val();
       

        if (typeof ifsc !== 'undefined' && ifsc) {

          $.ajax({
            url: '<?php echo base_url('ifscdata'); ?>',
            type: 'GET',
            dataType: 'json',
            data: {"search": ifsc, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
            beforeSend: function(){
              thisForm.append('<img width="50" src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
            },
            success: function(data) {
                
               
              if (!data.error) {
                 $("[name ='beneficiary_bank_name']").val(data['bank']);
              }else{
              
              console.log(data['bank']);
              }

            },
          })
        }

      });   
      
   $("[name ='transaction_amount'], [name ='beneficiary_account_number'], [name ='beneficiary_mobile']").on('change', function(event){
       
      
      if (isNaN( $("[name ='transaction_amount'], [name ='beneficiary_account_number'], [name ='beneficiary_mobile']").val() )) {
            alert('Some Errors related to something'); 
        } else {
            // $("[name ='transaction_amount']").addClass('is-invalid'); 
        }
   });
//Datatable
 var $transectionlist = $('#transectionlist');
           var duid = '<?php echo $this->session->userdata("member_id") ?>';
          
      var Api = '<?php echo base_url('dmt/DmtController/'); ?>';
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
    
 $("#simplefilter").click(function(event) {
      event.preventDefault();
      
        // console.log($(searchValue).val())
    url = Api + "get_history?key=" + duid + "&date_from=" + $('#date_from').val() + "&date_to=" + $('#date_to').val() + "&searchValue="+  $('#searchValue').val() +"&searchByCat=" + $('#searchByCat').val() + "&search=simple&list=all";
       $table.ajax.url(url).load();

    });
    
     body.on('change' , "#amount",function(event) {
      var amount=Number($(this).val());
      var total = Number($('#total').val());
      if(amount<=total){
          
      
       $.ajax({
            url: '<?php echo base_url('dmt/get_surcharge_value'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {"service": 2,'amount' :amount ,"<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
           
            success: function(data) {
                
               
              if (data) {
                 $("#surcharge").val(data);
              }else{
              
             $("#surcharge").val('Error');
              }

            },
          })
      }else{
           Swal.fire({
              title: "Insufficient Balance !!",
              html: "Error Information " ,
              type: 'error',
              showCancelButton: true,
            });
      }

    });
    
$("#clear").click(function(event) {
      event.preventDefault();

   url = Api + "get_history?key=" + duid + "&list=all";
       $table.ajax.url(url).load();

    });
    
    
// 
// 
  });
</script>
