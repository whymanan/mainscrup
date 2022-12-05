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
                            <h3 class="mb-0">DTH Recharge</h3>
                        </div>
                        <div class="col-4 text-right">
    
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('recharge/dth_submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                   
                            <div class="row">
                              
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Service Provider</label>
                                        <select name="operator" class="form-control">
                                             <option value="none" selected disabled hidden>Select Service Provider</option>
                                            <option value="12">Airtel Digital TV</option>
                                            <option value="14">Dish TV</option>
                                            <option value="27">Sun Direct</option>
                                            <option value="8">Tata Sky</option>
                                            <option value="10">Videocon d2h</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                  <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Registered Mobile No. or Subscriber ID</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control clear" placeholder="Registered Mobile No. or Subscriber ID" required>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control clear" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" id="submit" style="display:flex;justify-content: center;">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <button type="submit" class="btn btn-primary my-4" id="submit_btn">PAY</button>
                                <input type="hidden"  name="type" id="recharge_type" value="dth"> 
                                 <!--<input type="hidden"  name="service" value="7"> -->
                                  <input type="hidden" name="service" value="9" id="serviceid">
                                <button type="button" class="btn btn-primary my-4"  data-toggle="modal" data-target="#exampleModal" id="view_plan">VIEW OFFER</button>
                                <button type="button" class="btn btn-primary my-4"  data-toggle="modal"  id="DTHINFO">DTH INFO</button>
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
                             <th scope="col">#</th>
                             <th scope="col">Refresh</th>
                             <th scope="col">Member Id</th>
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
<!--plan modal-->
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
        
        </div>
      </div>
     
    </div>
  </div>
  
<!--DTH INFO-->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">DTH INFO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="body1">
        
        </div>
      </div>
     
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>

 <script type="text/javascript">
   $(document).ready(function() {
   var $transectionlist = $('#transectionlist');
    var duid = '<?php echo $this->session->userdata("member_id") ?>';
    var type = $('#recharge_type').val();
    var serviceid=$('#serviceid').val();
    console.log(duid);

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

      $(document).on('change','select[name="operator"]', function() {

      var operator = $(this).val();
      var circle=$('select[name="circle"]').val();
      var canumber=$('#mobile').val()
      console.log(operator);
      console.log(circle);
      console.log(canumber);
      
       console.log(operator);
     $.ajax({
        url: '<?php echo base_url('recharge/fetch_dth_plan'); ?>',
        type: 'POST',
        data: {
          'operator': operator,
          
          "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",

        },
        beforeSend: function() {
          $('#body').html('<center><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span></center>');
        },
        success: function(data) {
          $('#body').html(data)

        },

      });
      });
      
     
  
    
         
      $("#DTHINFO").click(function()
      {
         var operator=$('select[name="operator"]').children(':selected').text();
         var canumber=$('#mobile').val();
         var string='';
         if(operator=='' || canumber=='' ||operator=='Select Service Provider')
         {
            Swal.fire("Field require for Info  Subscriber ID and Provider");  
         }
         else
         {   
             $('#body1').empty();
             $('#DTHINFO').attr('data-target','#exampleModal1');
             if(operator=='Videocon d2h')
             {
                 operator='Videocon';
             }
             else if(operator=='Airtel Digital TV')
             {
                 operator='Airteldth'
             }
             else if(operator=='Dish TV')
             {
                 operator='Dishtv'
             }
              else if(operator=='Sun Direct')
             {
                 operator='Sundirect'
             }
             else if(operator=='Tata Sky')
             {
                 operator='TataSky'
             }
             console.log(operator);
             $.ajax({
                    url: '<?php echo base_url('recharge/dthinfo') ?>', //mobile plan      
                    type: 'POST',
                    dataType: 'json',
                    data: {
                           "operator": operator,
                           'canumber': canumber,
                            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                           },
        beforeSend: function() {
          $('#body1').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
    
            if(data.info.status==0 && data.status==true && data.response_code==1)
            {
                string=data.info.desc;
            }
            else if(data.response_code==0 && data.message==null)
            {
                string='Invaild Provider Name And Subscriber  Id';
            }
            else
            {
              for(var value in data.info[0]){
                 string+='<span style="font-weight:bolder;font-size:largest;text-transform: capitalize;">'+value+': </span><span>'+data.info[0][value]+'</span></br><hr>';
              }
            }
            $('#body1').html(string);
          
        },
      })
         }
      })
   });
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
               else if(response==false)
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
