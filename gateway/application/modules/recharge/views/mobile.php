<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
// filter
<div class="collapse" id="collapseExample">
  <div class="card card-body">
    <form method="post" id="filter">
      <div class="form-row">
        <div class="col-2">
          <input type="date"  id="date_from" name="date_from" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
        </div>
        <div class="col-2">
          <input type="date" id="date_to" name="date_to" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
        </div>
       <?php if (isAdmin($this->session->userdata('user_roles'))){?>
        <div class="col-2">
          <select id="searchBymember" name="searchBymeber" class="form-control form-control-sm" >
            <option value="">-- Select Member Id --</option>
            <?php foreach($member_list as $value){?>
            <option value="<?php echo $value['member_id'];?>"><?php  echo $value['member_id'];?></option>
            }
            <?php }?>
          </select>
        </div>
        <?php }?>
        <div class="col-2">
          <select id="searchByCat" name="searchByCat" class="form-control form-control-sm" >
            <option value="">-- Select Category --</option>
            <option value="reference_number">Reference Id</option>
            <option value="transection_id">TRANSECTION ID</option>
            <option value="transection_mobile">PHONE</option>
          </select>
        </div>
      
        <div class="col-2">
          <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search" >
        </div>
         <div class="col-2">
          <select id="searchBystatus" name="searchBystatus" class="form-control form-control-sm" >
            <option value="">-- Select Status --</option>
            <option value="success">Success</option>
            <option value="failure">Failure</option>
            <option value="other">Other</option>
          </select>
        </div>
        </br>
        </br>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <div style="float:right">
        <button id='simplefilter' class="btn btn-primary btn-xs"> <i class="fas fa-search"></i> Search</button>
        <button  id='export' class="btn btn-primary btn-xs"> <i class="fas fa-eraser"></i> Export</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-xl-4 order-xl-1">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-8">
            <h3 class="mb-0">Mobile Recharge</h3>
          </div>
          <div class="col-4 text-right">
            <h3 class="mb-0">Balance :- Rs : <?php echo $bal ?></h3>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="form" action="<?php echo base_url('recharge/mobile_submit'); ?>" method="post" enctype="multipart/form-data">


          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="customRadioInline1" name="opt_type" value="prepaid" class="custom-control-input" checked>
                  <label class="custom-control-label" for="customRadioInline1">Prepaid</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="customRadioInline2" name="opt_type" value="postpaid" class="custom-control-input">
                  <label class="custom-control-label" for="customRadioInline2">Postpaid</label>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Mobile</label>
                <input type="text" name="mobile" id="mobile" class="form-control clear" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Operator</label>
                <select name="operator" class="form-control" id="operator">
                  <option value="">Select Operator</option>
                  <option value="11">Airtel</option>
                  <option value="13">BSNL</option>
                  <option value="18">Jio</option>
                  <option value="33">MTNL DELHI</option>
                  <option value="34">MTNL MUMBAI</option>
                  <option value="4">IDEA</option>
                  <option value="22">Vodafone</option>


                </select>
              </div>
            </div>
          </div>
          <div class="row" id="circle1">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Circle</label>
                <select name="circle" class="form-control" id="circle">
                  <option value="">Select Circle</option>
                  <option value="1">Andhra Pradesh</option>
                  <option value="2">Assam</option>
                  <option value="3">Bihar Jharkhand</option>
                  <option value="4">Chennai</option>
                  <option value="5">Delhi</option>
                  <option value="26">Goa</option>
                  <option value="6">Gujarat</option>
                  <option value="7">Haryana</option>
                  <option value="8">Himachal Pradesh</option>
                  <option value="9">Jammu & Kashmir</option>
                  <option value="10">Karnataka</option>
                  <option value="11">Kerala</option>
                  <option value="12">Kolkata</option>
                  <option value="14">Madhya Pradesh & Chhattisgarh </option>
                  <option value="13">Maharashtra</option>
                  <option value="27">Manipur</option>
                  <option value="15">Mumbai</option>
                  <option value="16">North East </option>
                  <option value="17">Orissa</option>
                  <option value="18">Punjab</option>
                  <option value="19">Rajasthan</option>
                  <option value="20">Tamil Nadu </option>
                  <option value="21">UP East</option>
                  <option value="22">UP West</option>
                  <option value="23">West Bengal </option>

                </select>
              </div>
            </div>
          </div>
          <div class="row amount">
            <div class="col-lg-12">
              <div class="form-group">
                <label class="form-control-label">Amount</label>
                <input type="text" name="amount" id="amount" class="form-control clear" value="" required>
              </div>
            </div>
          </div>


          <div class="fetch"></div>

          <div class="text-center" id="submit">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" class="btn btn-primary my-4" id="submit_btn">Recharge</button>
            <button class="btn btn-primary my-4" id="fetch">Fetch Bill</button>
            <input type="hidden" name="type" value="prepaid" id="recharge_type">
            <input type="hidden" name="duedate" value="" id="duedate">
            <input type="hidden" name="username" value="" id="name">
            <input type="hidden" name="account" value="" id="account">
            <input type="hidden" name="service" value="13" id="serviceid">
            <button type="button" class="btn btn-primary my-4" data-toggle="modal" data-target="#exampleModal" id="view_plan">view R offer</button>

          </div>


        </form>
      </div>
    </div>
  </div>
  <div class="col-xl-8 order-xl-2">
    <div class="card">
      <div class="card-header border-0">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="mb-0">Transaction List</h3>
          </div>

        </div>
       
      </div>
      <div class="table-responsive">
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="transectionlist">
          <thead class="thead-light">
            <tr>
              <th scope="col">Print</th>
              <th scope="col">Refresh</th>
              <th scope="col">Member id</th>
              <th scope="col">Trn id</th>
              <th scope="col">Details</th>
              <th scope="col">Mobile</th>
              <th scope="col">Amount</th>
              <th scope="col">Status</th>
              <th scope="col">Created At</th>

            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!--modal2-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Plans</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="body">
        <div class="nav-wrapper">
          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">

          </ul>
        </div>
        <div class="card shadow">
          <div class="card-body">
            <div class="tab-content" id="myTabContent">

            </div>
          </div>
        </div>
        <div id="plans">

        </div>
      </div>
    </div>

  </div>
</div>
</div>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#view_plan').hide();
    $('#submit_btn').hide();
    $("#fetch").hide();

    $('input[type=radio][name=opt_type]').change(function() {
      $('.fetch').empty();
      if (this.value == 'prepaid') {
        var x = '<option value="">Select Operator</option>' +
          '<option value="11">Airtel</option>' +
          '<option value="13">BSNL</option>' +
          '<option value="18">Jio</option>' +
          '<option value="33">MTNL DELHI</option>' +
          '<option value="34">MTNL MUMBAI</option>' +
          '<option value="4">IDEA</option>' +
          '<option value="22">Vodafone</option>';
        $("#operator").html(x);
        $('#recharge_type').val('prepaid');
        $('#serviceid').val(13);
        $('#circle1').show();
        $(".amount").show();
        $('#submit_btn').text('Recharge');
        $("#submit_btn").hide();
        $("#form").attr('action', '<?php echo base_url('recharge/mobile_submit') ?>');
        $("#fetch").hide();
        $("#mobile").val('');
        $("#transectionlist").dataTable().fnDestroy();
        var $transectionlist = $('#transectionlist');
        var duid = '<?php echo $this->session->userdata("member_id") ?>';
        var type = $('#recharge_type').val();
        var serviceid=$('#serviceid').val();
        var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
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
            url: Api + "get_history?key=" + duid + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid,
            type: "GET",
          },

          "pageLength": 10
        });
      } else if (this.value == 'postpaid') {
        var x = '<option value="">Select Operator</option>' +
          '<option value="9">Airtel</option>' +
          '<option value="13">BSNL</option>' +
          '<option value="62">Jio</option>' +
          '<option value="41">Tata Docomo CDMA</option>' +
          '<option value="15">IDEA</option>' +
          '<option value="23">Vodafone</option>'
        $("#operator").html(x);
        $(".amount").hide();
        $('#view_plan').hide();
        $('#submit_btn').hide();
        $('#recharge_type').val('postpaid');
        $('#serviceid').val(49);
        $("#mobile").val('');
        $("#form").attr('action', '<?php echo base_url('recharge/bill_submit') ?>');
        $("#transectionlist").dataTable().fnDestroy();
        var $transectionlist = $('#transectionlist');
        var duid = '<?php echo $this->session->userdata("member_id") ?>';
        var type = $('#recharge_type').val();
         var serviceid=$('#serviceid').val();
        console.log(type);

        var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
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
            url: Api + "get_history?key=" + duid + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid,
            type: "GET",
          },

          "pageLength": 10
        });
        //   $('#circle1').hide();
      }
    });
    $('#mobile').on('change', function() {

      var mobile = $(this).val();
      var area = [];
      $.ajax({

        url: '<?php echo base_url('recharge/get_mobile') ?>', //Mobile info
        type: 'POST',
        dataType: 'json',
        data: {
          "mobile": mobile,
          'service': 7,
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
        },
        beforeSend: function() {
          $('input[name="mobile"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          console.log(data.info);
          if ($('input[name="opt_type"]:checked').val() == "prepaid") {
            $('#view_plan').show();
            $('#submit_btn').show();
          } else {
            $("#fetch").show();
          }
          $("#operator option:contains('" + data.info.operator + "')").attr('selected', 'selected');
          $("#circle option:contains('" + data.info.circle + "')").attr('selected', 'selected');
        },
        complete: function() {
          $('#mobile').parent().find('span').remove();
        },
      })

    });
    $('#view_plan').click(function() {
      var operator = $('#operator option:selected').text();
      var circle = $('#circle option:selected').text();
      var tap = [];
      var store = [];
      var plan = [];
      var allplan = [];
      $.ajax({

        url: '<?php echo base_url('recharge/fetch_plan') ?>', //mobile plan      
        type: 'POST',
        dataType: 'json',
        data: {
          "operator": operator,
          'circle': circle,
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
        },
        beforeSend: function() {
          $('#myTabContent').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          for (var property in data.info) {
            plan.push(property);
            tap += '<li class="nav-item">' +
              '<a class="nav-link mb-sm-3 mb-md-0" id="p' + property.replace(/[^A-Z0-9]/ig, "") + '-tap" data-toggle="tab" href="#p' + property.replace(/[^A-Z0-9]/ig, "") + '" role="tab"' + 'aria-controls="p' + property.replace(/[^A-Z0-9]/ig, "") + '"><i class="ni' + ' ni-cloud-upload-96 mr-2"></i>' + property + '</a>' +
              '</li>';
            store += '<div class="tab-pane fade" id="p' + property.replace(/[^A-Z0-9]/ig, "") + '"' + 'role="tabpanel" aria-labelledby="p' + property.replace(/[^A-Z0-9]/ig, "") + '-tap"></div>';
          }
          $('#tabs-icons-text').html(tap);
          $('#myTabContent').html(store);
          // console.log(store);
          var i = 0;
          for (var make in data.info) {
            allplan[i] = '';
            for (var x in data.info[make]) {
              allplan[i] += '<p class="description"><a style="font-size:large" href="javascript:void(0)"' + 'class="planamount" value="' + data.info[make][x].rs + '">' + data.info[make][x].desc + '<input type="hidden" value="' + data.info[make][x].rs + '"></a><h4>' + 'Price:' + data.info[make][x].rs + '</h4><h4>' + 'Validity:' + data.info[make][x].validity + '</h4></p><hr>';
            }
            i++;
          }
          for (var j = 0; j < plan.length; j++) {
            document.getElementById('p' + plan[j].replace(/[^A-Z0-9]/ig, "")).innerHTML = allplan[j];
            if (j == 0) {
              document.getElementById('p' + plan[j].replace(/[^A-Z0-9]/ig, "") + '-tap').removeAttribute('aria-selected');
            } else {
              document.getElementById('p' + plan[j].replace(/[^A-Z0-9]/ig, "") + '-tap').removeAttribute('aria-selected');
            }
          }
          $('#p' + plan[0].replace(/[^A-Z0-9]/ig, "")).addClass('show active');
          $('#p' + plan[0].replace(/[^A-Z0-9]/ig, "") + '-tap').addClass('active');
          //  console.log($('#myTabContent').attr('id'));
        },
      })
    });
    $('#fetch').on('click', function() {

      var account = $('#mobile').val();
      var operator = $('#operator').val();
      console.log(operator);
      console.log(account);
      if (account != '' && operator != "") {
        $.ajax({

          url: '<?php echo base_url('recharge/fetch_bill') ?>', //Mobile info
          type: 'POST',
          dataType: 'json',
          data: {
            "account": account,
            'operator': operator,
            'mode': 'online',
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
          },
          beforeSend: function() {
            $('.fetch').append('<br><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
          },
          success: function(data) {
            if (data.status == false && data.respons == 0) {
              Swal.fire(data.message);
              $('.fetch').html('<div class="container text-center">Massage:' + data.message + '</div>');
            } else {
              console.log(data);
              $('#amount').val(data.bill_fetch.billAmount);
              $('#name').val(data.bill_fetch.userName);
              $('#duedate').val(data.bill_fetch.dueDate);
              $('#account').val(account);
              $('.fetch').html('<br><div class="container">Name : ' + data.bill_fetch.userName + '<br> Due Amount : ' + data.bill_fetch.billAmount + '<br>Due Date : ' + data.bill_fetch.dueDate + '</div>');
              $('#submit_btn').show();
              $('#fetch').hide();
              $('#submit_btn').text('Bill Pay');
            }



          },
          complete: function() {
            $('#account').parent().find('span').remove();
          },
        })
      } else {
        alert("please select operator or account id");
      }
    });

    $(document).on('click', '#clear', function() {
      $('#circle').val("");
      $('#operator').val("");
      $("#amount").val("");
      $("#mobile").val("");
    });

    var $transectionlist = $('#transectionlist');
    var duid = '<?php echo $this->session->userdata("member_id") ?>';
    var type = $('#recharge_type').val();
    var serviceid=$('#serviceid').val();

    var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
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
        url: Api + "get_history?key=" + duid + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid,
        type: "GET",
      },

      "pageLength": 10
    });
  });

  $(document).on('click', '.planamount', function() {
    var x = $(this).find('input').val();
    $('#amount').val(x);
    $('#exampleModal').modal('toggle');
  });

  $(document).on('change', 'select[name="plan_type"]', function() {

    var type = $(this).val();
    var operator = $('#operator').val();
    var circle = $('#circle').val();
    console.log(operator);
    $.ajax({
      url: '<?php echo base_url('recharge/fetch_plan'); ?>',
      type: 'POST',
      data: {
        'operator': operator,
        'circle': circle,
        'type': type,
        "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

      },
      beforeSend: function() {
        $('#plans').html('<center><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span></center>');
      },
      success: function(data) {
        $('#plans').html(data)

      },

    })

  });

//   $("#filter").click(function()
//   {
//       var serviceid=$("#serviceid").val();
//       var from=$("#from").val();
//       var to=$("#to").val();
//       var status=$("#status").val();
//       var $transectionlist = $('#transectionlist');
//       var duid = '<?php echo $this->session->userdata("member_id") ?>';
//       var type = $('#recharge_type').val();
//       var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
//       var table = $transectionlist.DataTable();
//       table.destroy();
//       var $table = $transectionlist.DataTable({
//         "searching": false,
//         "processing": true,
//         "serverSide": true,
//         "deferRender": true,
//         "language": {
//           "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
//           "emptyTable": "No distributors data available ...",
//       },
//       "order": [],
//       "ajax": {
//         url: Api + "get_history_filter?key=" + duid + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid+"&list=all&from="+from+"&list=all&to="+to+"&list=all&staus="+status,
//         type: "GET",
//       },

//       "pageLength": 10
//       });
      
//   })
  
//   $('#export').click(function()
//          {
//              var serviceid=$("#serviceid").val();
//              var from=$("#from").val();
//              var to=$("#to").val();
//              var status=$("#status").val();
//              var type = $('#recharge_type').val();
//              var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
//              console.log(from);
//              $.ajax({
//                 url: Api + "export?"+"&from="+from+"&to="+to+"&status="+status+"&serviceid="+serviceid+"&type="+type,
//                 type: "get", //send it through get method
//             //   data: { 
//             //       ajaxid: 4, 
//             //       UserID: UserID, 
//             //       EmailAddress: EmailAddress
//             //         },
//               success: function(response) {
//               //Do Something
//             //   console.log(response);
               
//               window.location=response;
//                   },
//               error: function(xhr) {
//               //Do Something to handle error
//             }
//               });
//          })
  function Print(id) {

    var sureDel = confirm("Are you sure want to Print Reciept");
    var $dmtTransactionPanel = $('#print');
    if (sureDel == true) {
      window.location.replace("<?php echo base_url('/recharge/RechargeController/print/') ?>" + id);
    }
  }
  function ref(id)
    {
        var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
        
        // console.log($(this).next('.ref'));
         $.ajax({
                url: Api + "refresh?"+"&id="+id,
                type: "get", //send it through get method
                beforeSend: function() {
                 $('#ref_'+id).addClass('fa-spin');
                 $('#but'+id).prop('disabled', true);
              },
               success: function(response) {
               //Do Something
               $('#ref_'+id).removeClass('fa-spin');
               $('#but'+id).prop('disabled', false);
               if(response==true)
               {
               var $transectionlist = $('#transectionlist');
               var duid = '<?php echo $this->session->userdata("member_id") ?>';
               var type = $('#recharge_type').val();
               var serviceid=$('#serviceid').val();
               var table = $transectionlist.DataTable();
               table.destroy();
               var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
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
                             url: Api + "get_history?key=" + duid + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid,
                            type: "GET",
                           },

                    "pageLength": 10
                 });  
               }
               else
               {
                    var $transectionlist = $('#transectionlist');
                    var duid = '<?php echo $this->session->userdata("member_id") ?>';
                   var type = $('#recharge_type').val();
                  var serviceid=$('#serviceid').val();
                  var table = $transectionlist.DataTable();
                  table.destroy();
                  var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
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
                             url: Api + "get_history?key=" + duid + "&list=all&type=" + type + "&list=all&serviceid=" + serviceid,
                            type: "GET",
                           },

                    "pageLength": 10
                 });  
               }
            //   console.log(response);
               
            //   window.location=response;
                  },
              error: function(xhr) {
              //Do Something to handle error
            }
              });
    }
</script>