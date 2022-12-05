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
                        <h3 class="mb-0">GAS Bill Pay</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('recharge/bill_submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                   



                   
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                            <select name="operator" id="operator" class="form-control">
                                <option value="101">Vadodara Gas Limited	</option>
                                <option value="100">Unique Central Piped Gases Pvt Ltd (UCPGPL)</option>
                                <option value="86">Tripura Natural Gas</option>
                                <option value="283">Torrent Gas Moradabad Limited Formerly Siti Energy Limited	</option>
                                <option value="68">Siti Energy</option>
                                <option value="194">Sanwariya Gas Limited	</option>
                                <option value="84">Sabarmati Gas Limited (SGL)		</option>
                                <option value="282">Megha Gas</option>
                                <option value="107">Maharashtra Natural Gas Limited</option>
                                <option value="17">Mahanagar Gas Limited	</option>
                                 <option value="141">IRM Energy Private Limited	</option>
                                <option value="34">Indraprastha Gas</option>
                                <option value="129">Indian Oil-Adani Gas Private Limited</option>
                                <option value="286">Indane Gas</option>
                                <option value="80">Haryana City Gas</option>
                                 <option value="35">Gujarat Gas Company Limited</option>
                                <option value="156">Green Gas Limited(GGL)</option>
                                <option value="139">Gail Gas Limited		</option>
                                <option value="113">Charotar Gas Sahakari Mandali Ltd</option>
                                <option value="124">Central U.P. Gas Limited		</option>
                                <option value="177">Bhagyanagar Gas Limited		</option>
                                <option value="172">Assam Gas Company Limited		</option>
                                <option value="49">Adani Gas</option>
                                <option value="122">Aavantika Gas Ltd</option>
                                <option value="176">Indian Oil Corporation Limited</option>
                                <option value="286">Indane Gas</option>
                                <option value="168">Hindustan Petroleum Corporation Ltd (HPCL)</option>
                                <option value="173">Bharat Petroleum Corporation Limited (BPCL)</option>
                                
                                   
                                   
                                
                                
                               
                            </select>
                            </div>
                        </div>
                    </div>
                    
                     <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Customer No/Account No</label>
                                <input type="text" name="account" id="account" class="form-control clear" required>
                                 <input type="hidden" name="amount" id="amount"  >
                                 <input type="hidden" name="duedate" value="" id="duedate">
                                  <input type="hidden" name="username" value="" id="name">
                                  <button type="button" class="btn btn-primary my-4" id="fetch">Fetch Bill</button>
                            </div>
                        </div>
                    </div>
                       <div class="row fetch">
                             
                       </div>   
                       

                    <div >
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        
                        <button type="submit" class="btn btn-primary my-4" id="submit_btn">Proceed</button>
                         <input type="hidden" id="recharge_type" name="type" value="gas" >
                         <input type="hidden" id="serviceid" name="service" value="22" >
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
<script type="text/javascript">
 $(document).ready(function() {
     
     $('#submit_btn').hide();
 $('#fetch').on('click', function(){
      
      var account = $('#account').val();
       var operator = $('#operator').val();
       //console.log(operator);
       if(account!='' && operator!=""){
      $.ajax({
        
        url: '<?php echo base_url('recharge/fetch_bill') ?>', //Mobile info
        type: 'POST',
        dataType: 'json',
        data: {"account":account,'operator' : operator,'mode':'online',"<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
        beforeSend: function() {
          $('.fetch').append('<br><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
           console.log(data);
          if(data.status==false){
              Swal.fire(data.message)
          $('.fetch').html('<div class="container text-center">Massage:'+ data.message +'</div>');
           
          }
          else{
              $('#amount').val(data.bill_fetch.billAmount);
              $('#name').val(data.bill_fetch.userName);
               $('#duedate').val(data.bill_fetch.dueDate);
              $('.fetch').html('<br><div class="container">Name : '+ data.bill_fetch.userName+'<br> Due Amount : '+data.bill_fetch.billAmount); 
                $('#submit_btn').show();
          }
        
     
          
        },
        complete: function() {
          $('#account').parent().find('span').remove();
        },
      })
        }else{
            alert("please fill account id");
        }
    });
    
     var $transectionlist = $('#transectionlist');
           var duid = '<?php echo $this->session->userdata("member_id") ?>';
          var type ='gas';
          var service_id=22;
      var Api = '<?php echo base_url('recharge/RechargeController/'); ?>';
     var $table = $transectionlist.DataTable({
      "searching": false,
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_bill_history?key=" + duid + "&list=all&type="+type+"&list=all&service_id="+service_id,
        type: "GET",
      },

      "pageLength": 10
    });
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
        
        // console.log(serviceid);
         $.ajax({
                url: Api + "refresh_bill?"+"&id="+id,
                type: "get", //send it through get method
                beforeSend: function() {
                 $('#ref_'+id).addClass('fa-spin');
                 $('#but'+id).prop('disabled', true);
              },
               success: function(response) {
                   console.log(response);
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