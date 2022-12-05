<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Fastag Bill Pay</h3>
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
                                <option value="AXIF">Axis Bank	</option>
                                <option value="BOBF">Bank of Baroda		</option>
                                <option value="BHAG">Bhagyanagar Gas Limited	</option>
                                <option value="HDFF">HDFC Bank		</option>
                                <option value="IDFF">IDFC First Bank		</option>
                                <option value="IHMF">Indian Highways Management Company		</option>
                                <option value="IIBF">IndusInd Bank			</option>
                                <option value="KMBF">Kotak Mahindra Bank			</option>

                            </select>
                            </div>
                        </div>
                    </div>
                    
                     <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Vehicle No</label>
                                <input type="text" name="account" id="account" class="form-control clear" required>
                                 <input type="hidden" name="amount" id="amount"  >
                                  <button type="button" class="btn btn-primary my-4" id="fetch">Fetch Bill</button>
                            </div>
                        </div>
                    </div>
                       <div class="row fetch">
                             
                       </div>   
                       

                    <div >
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="btn btn-primary my-4" id="submit_btn">Proceed</button>
                         <input type="hidden" name="type" value="7" >
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
                <table class="table align-items-center table-flush" id="billtransectionlist">
                    <thead class="thead-light">
                        <tr>
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
        data: {"account":account,'operator' : operator, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
        beforeSend: function() {
          $('.fetch').append('<br><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
           console.log(data);
          if(data.status=="FAILURE"){
          $('.fetch').html('<div class="container text-center">'+ data.message +'</div>');
           
          }
          else{
              $('#amount').val(data.dueAmount);
              $('.fetch').html('<br><div class="container">Name : '+ data.customerName +'<br>Bill Period : '+ data.billPeriod +'<br> Due Amount : '+data.dueAmount+'<br>Due Date : '+ data.dueDate +'</div>'); 
                $('#submit_btn').show();
          }
        
     
          
        },
        complete: function() {
          $('#account').parent().find('span').remove();
        },
      })
        }else{
            alert("please fill vehicle no");
        }
    });
    
     var $transectionlist = $('#billtransectionlist');
           var duid = '<?php echo $this->session->userdata("member_id") ?>';
          var type =7;
          
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
        url: Api + "get_bill_history?key=" + duid + "&list=all&type="+type,
        type: "GET",
      },

      "pageLength": 10
    });
 });
    
    
     </script>