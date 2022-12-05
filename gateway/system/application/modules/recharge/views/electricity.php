<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Electricity Bill Pay</h3>
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
                                        <select name="operator" id="operator" class="form-control select2">
                                            <option value="">Select Provider</option>
                                            <option value="ADEM">Adani Electricity	</option>
                                            <option value="AVVR">Ajmer Vidyut Vitran Nigam Limited</option>
                                            <option value="APDC">APDCL (Non-RAPDR) - ASSAM	)</option>
                                            <option value="APDR">APDCL (RAPDR) - ASSAM	</option>
                                            <option value="APEP">APEPDCL - Andhra Pradesh	</option>
                                            <option value="APSP">APSPDCL - Andhra Pradesh	</option>
                                            
                                            <option value="BESC">Bangalore Electricity supply company Ltd		</option>
                                            <option value="BEUM">BEST Undertaking	</option>
                                            <option value="BKEB">Bikaner Electricity Supply Limited	</option>
                                            <option value="BSRD">BSES Rajdhani Power Limited		</option>
                                            <option value="BSYD">BSES Yamuna Power Limited		</option>
                                            <option value="CESC">Calcutta Electric Supply Corporation Limited		</option>
                                            
                                            <option value="CESK">CESCOM - KARNATAKA		</option>
                                            <option value="CSPD">Chhattisgarh State Power Distribution Company Ltd	</option>
                                            <option value="DGVC">Dakshin Gujarat Vij Company Limited	</option>
                                            <option value="DHBH">Dakshin Haryana Bijli Vitran Nigam	</option>
                                            <option value="DADE">Daman And Diu Electricity		</option>
                                            <option value="DNHP">DNH Power Distribution Company Limited	</option>
                                            
                                            <option value="GOED">Goa Electricity Department		</option>
                                            <option value="GESK">Gulbarga Electricity Supply Company Limited	</option>
                                            <option value="HPSE">Himachal Pradesh State Electricity Board Ltd	</option>
                                            <option value="HESK">Hubli Electricity Supply Company Ltd	</option>
                                            <option value="JVVR">Jaipur Vidyut Vitran Nigam		</option>
                                            <option value="JKPD">Jammu and Kashmir Power Development Department		</option>
                                            
                                            <option value="JUSC">Jamshedpur Utilities & Services (JUSCO)			</option>
                                            <option value="JBVN">Jharkhand Bijli Vitran Nigam Limited	</option>
                                            <option value="JDVR">Jodhpur Vidyut Vitran Nigam Limited		</option>
                                            <option value="KESC">Kanpur Electricity Supply Company			</option>
                                            <option value="KSEB">Kerala State Electricity Board Ltd			</option>
                                            <option value="KEDR">Kota Electricity Distribution Limited			</option>
                                            
                                            <option value="MGVG">Madhya Gujarat Vij Company Limited	</option>
                                            <option value="MKMP">Madhya Kshetra Vitaran (Rural) - Madhya Pradesh		</option>
                                            <option value="MKVU">Madhya Kshetra Vitaran (Urban) - Madhya Pradesh		</option>
                                            <option value="MPDC">Meghalaya Power Dist Corp Ltd		</option>
                                            <option value="MSEM">MSEDCL		</option>
                                            <option value="MZVV">Muzaffarpur Vidyut Vitran		</option>
                                            
                                            <option value="NESO">NESCO Utility			</option>
                                            <option value="NDMC">New Delhi Municipal Council (NDMC)		</option>
                                            <option value="NOPN">Noida Power Copmpany Limited		</option>
                                            <option value="NBBR">North Bihar Power Distribution Co. Ltd		</option>
                                            <option value="PGVG">Paschim Gujarat Vij Company Limited			</option>
                                            <option value="PVMP">Paschim Kshetra Vidyut Vitaran - Madhya Pradesh			</option>
                                            
                                             <option value="PKVU">Poorv Kshetra Vitaran (NBG-Urban) - MADHYA PRADESH		</option>
                                            <option value="PKVR">Poorv Kshetra Vitaran (Rural) - MADHYA PRADESH		</option>
                                            <option value="PSPC">Punjab State Power Corporation Limited		</option>
                                            <option value="SNPN">SNDL Power - NAGPUR		</option>
                                            <option value="SBBR">South Bihar Power Distribution Co. Ltd			</option>
                                            <option value="SOTO">Southern Electricity Supply Company Of Odisha Limited	</option>
                                            
                                            <option value="TNEB">Tamil Nadu Electricity Board			</option>
                                            <option value="TAPM">Tata Power - MUMBAI		</option>
                                            <option value="TAPM">Tata Power -Mumbai		</option>
                                            <option value="TPAR">Tata Power AJMER - RAJASTHAN		</option>
                                            <option value="TAPD">Tata Power Delhi Distribution Limited		</option>
                                            <option value="TESS">Telangana Co-Operative Electric Supply Society Ltd		</option>
                                            
                                            <option value="TSSP">Telangana State Southern Power Distribution Compan				</option>
                                            <option value="TPAG">Torrent Power Limited - Agra		</option>
                                            <option value="TPAH">Torrent Power Limited - Ahmedabad			</option>
                                            <option value="TPBW">Torrent Power Limited - Bhiwandi			</option>
                                            <option value="TPSR">Torrent Power Limited - Surat		</option>
                                            <option value="TPCO">TP Central Odisha Distribution Limited (TPCODL)			</option>
                                            
                                            <option value="TSTP">Tripura Electricity Corp Ltd		</option>
                                            <option value="UGVG">Uttar Gujarat Vij Company Limited		</option>
                                            <option value="UHBV">Uttar Haryana Bijli Vitran Nigam		</option>
                                            <option value="URUP">Uttar Pradesh Power Corporation Ltd (RURAL)			</option>
                                            <option value="UUUP">Uttar Pradesh Power Corporation Ltd (Urban - Smart Meter)			</option>
                                            <option value="UPUK">Uttarakhand Power Corporation Ltd - UPCL		</option>
                                            
                                            <option value="WESO">WESCO Utility				</option>
                                            <option value="WBSE">West Bengal State Electricity Distribution Company Limited		</option>
                                           
                                        </select>
                                </div>
                            </div>
                            
                              <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Account Id</label>
                                    <input type="text" name="account" id="account" class="form-control clear" required>
                                     <button type="button" class="btn btn-primary my-4 text-center" id="fetch">Fetch Bill</button>
                                </div>
                            </div>
                       
                    </div>
                        <div class="row fetch">
                             
                       </div>
    


                    <div  id="submit">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                         <input type="hidden" name="amount" value="" id="amount">
                         <input type="hidden" name="type" value="1" id="amount">
                        <button type="submit" class="btn btn-primary my-4" id="submit_btn">Proceed to pay</button>
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
  $('#view_plan').hide();
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
            alert("please select operator or account id");
        }
    });
    
     $(document).on('click','#clear', function(){
      $('#circle').val("");
       $('#operator').val("");
     $("#amount").val("");
        $("#mobile").val("");
    });
    
    var $transectionlist = $('#transectionlist');
           var duid = '<?php echo $this->session->userdata("member_id") ?>';
          var type =1;
          
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
        url: Api + "get_history?key=" + duid + "&list=all&type="+type,
        type: "GET",
      },

      "pageLength": 10
    });
     var $transectionlist = $('#billtransectionlist');
           var duid = '<?php echo $this->session->userdata("member_id") ?>';
          var type =1;
          
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
        url: Api + "get_bill_history?key=" + duid + "&list=all&type="+type,
        type: "GET",
      },

      "pageLength": 10
    });
     
 });
 
    </script>