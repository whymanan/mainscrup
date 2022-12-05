<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <h3 class="mb-0">EMI Bill Pay</h3>
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
                                            <option value="415">Aadhar Housing Finance Limited</option>
                                            <option value="195">AAVAS FINANCIERS LIMITED</option>
                                            <option value="263">Adani Capital Pvt Ltd</option>
                                            <option value="350">Adani Housing Finance	</option>
                                            <option value="110">Aditya Birla Finance ltd. (ABFL)</option>
                                            
                                            <option value="188">Aditya Birla Housing Finance Limited		</option>
                                            <option value="353">Agora Microfinance India Ltd - AMIL</option>
                                            <option value="266">Altum Credo Home Finance</option>
                                            <option value="401">Annapurna Finance Private Limited-MFI		</option>
                                            <option value="237">Annapurna Finance Private Limited-MSME</option>
                                            <option value="385">Aptus Finance India Private Limited</option>
                                            
                                            <option value="384">Aptus Value Housing Finance India Limited</option>
                                            <option value="217">Arohan Financial Services Ltd</option>
                                            <option value="327">Ascend Capital     </option>
                                            <option value="445">AU Bank Loan Repayment</option>
                                            <option value="223">Avanse Financial Services Ltd</option>
                                            <option value="402">Axis Bank Limited - Retail Loan	</option>
                                            
                                            <option value="363">Axis Bank Limited-Microfinance</option>
                                            <option value="438">Axis Finance Limited</option>
                                            <option value="409">Ayaan Finserve India Private LTD</option>
                                            <option value="450">Aye Finance Pvt. Ltd.</option>
                                                <option value="198">Avail</option>
                                            <option value="234">Baid Leasing and Finance</option>
                                            <option value="72">Bajaj Finserv</option>
                                            
                                            <option value="417">Bajaj Housing Finance Limited</option>
                                            <option value="111">BajajAutoFinanceLtd</option>
                                            <option value="428">Belstar Microfinance Limited</option>
                                            <option value="232">BERAR Finance Limited	</option>
                                            <option value="356">Bharat Financial Inclusion Ltd</option>
                                            <option value="205">Capri Global Capital Limited</option>
                                            <option value="210">Capri Global Housing Finance</option>
                                            <option value="490">Care India Finvest Limited</option>
                                            <option value="440">Cars24 Financial Services Private Limited</option>
                                            <option value="418">Centrum Microcredit Limited</option>
                                            <option value="270">Chaitanya India Fin Credit Pvt Ltd</option>
                                            <option value="199">Clix</option>
                                            <option value="269">Credit Wise Capital			</option>
                                            <option value="347">CreditAccess Grameen - Microfinance</option>
                                            <option value="348">CreditAccess Grameen - Retail Finance</option>
                                            <option value="451">Criss Financial Holdings Ltd</option>
                                            <option value="332">DCB Bank Loan Repayment	</option>
                                            <option value="465">DCBS Loan			</option>
                                            
                                             <option value="271">Digamber Capfin Limited	</option>
                                            <option value="455">Diwakar Tracom Private Limited		</option>
                                            <option value="131">DMI FInance</option>
                                            <option value="368">Dvara Kshetriya Gramin Financials Private Limited</option>
                                            <option value="437">Easy Home Finance Limited		</option>
                                            <option value="333">Eduvanz Financing Pvt. Ltd.</option>
                                            
                                            <option value="491">Electronica Finance Limited</option>
                                            <option value="499">Equitas SFB – Microfinance Loan</option>
                                            <option value="488">Equitas Small Finance Bank - Retail Loan</option>
                                            <option value="393">ESAF Small Finance Bank (Micro Loans)</option>
                                            <option value="202">Ess Kay Fincorp Limited (Sk Finance)</option>
                                            <option value="203">Faircent		</option>
                                            
                                            <option value="262">Fincare Small Finance Bank				</option>
                                            <option value="467">Finova Capital Private Ltd</option>
                                            <option value="268">Flexi Loans</option>
                                            <option value="178">Flexsalary	</option>
                                            <option value="404">Fullerton India credit company limited</option>
                                            <option value="403">Fullerton India Housing Finance Limited</option>
                                            
                                            <option value="405">G U Financial Services Pvt Ltd</option>
                                            <option value="390">Gujarat State Petronet Limited</option>
                                            <option value="382">HDB Financial Services Limited</option>
                                            <option value="157">Hero FinCorp Ltd		</option>
                                            <option value="456">Hindon Mercantile Limited - Mufin</option>
                                            <option value="340">Hiranandani Financial Services Pvt  Ltd</option>
                                            
                                            <option value="324">Home Credit India Finance Pvt. Ltd				</option>
                                            <option value="341">Home First Finance Company India Limited</option>
                                            <option value="204">i2iFunding</option>
                                            <option value="227">ICICI Bank Ltd - Loans</option>
                                            <option value="416">IDF Financial Services Private Limited</option>
                                            <option value="273">IDFC FIRST Bank Ltd</option>
                                            <option value="344">IIFL Finance Limited</option>
                                            <option value="345">IIFL Home Finance</option>
                                            <option value="406">InCred</option>
                                            <option value="331">India Home Loan Limited</option>
                                            <option value="238">India Shelter Finance Corporation Limited</option>
                                            <option value="190">Indiabulls Consumer Finance Limited</option>
                                            <option value="196">Indiabulls Housing Finance Limited</option>
                                            <option value="421">Indostar Capital Finance Limited - CV</option>
                                            <option value="462">Indostar Capital Finance Limited - SME</option>
                                            <option value="457">Indostar Home Finance Private Limited</option>
                                            <option value="267">INDUSIND BANK - CFD</option>
                                            <option value="439">Jain Autofin</option>
                                              <option value="355">Jain Motor Finmart</option>
                                              <option value="219">Jana Small Finance Bank</option>
                                              <option value="351">Janakalyan Financial Services Private Limited</option>
                                              <option value="370">John Deere Financial India Private Limited</option>
                                              <option value="239">Kanakadurga Finance Limited</option>
                                              <option value="349">Khush Housing Finance Pvt Ltd</option>
                                              <option value="228">Kinara Capital</option>
                                              <option value="222">Kissht</option>
                                              <option value="377">Kotak Mahindra Bank Ltd.-Loans</option>
                                              <option value="365">Kotak Mahindra Prime Limited</option>
                                              <option value="502">Kredit Bee</option>
                                              <option value="180">L and T Financial Services</option>
                                              <option value="191">L and T Housing Finance</option>
                                              <option value="453">LIC Housing Finance Limited</option>
                                              <option value="343">Light Microfinance Private Limited</option>
                                              <option value="468">Loan2Wheels</option>
                                              <option value="220">LOANTAP CREDIT PRODUCTS PRIVATE LIMITED</option>
                                              <option value="181">Loksuvidha</option>
                                              <option value="359">Mahaveer Finance India Limited</option>
                                              <option value="366">Mahindra and Mahindra Financial Services Limited</option>
                                              <option value="240">Mahindra Rural Housing Finance</option>
                                              <option value="209">Manappuram Finance Limited-Vehicle Loan</option>
                                              <option value="264">Maxvalue Credits And Investments Ltd</option>
                                              <option value="470">MDFC Financiers Pvt Ltd</option>
                                              <option value="272">Midland Microfin Ltd</option>
                                              <option value="334">Mintifi Finserve Private Limited</option>
                                              <option value="386">Mitron Capital</option>
                                              <option value="503">Money View</option>
                                              <option value="407">MoneyTap</option>
                                              <option value="497">Moneywise Financial Services Private Limited</option>
                                              <option value="182">Motilal Oswal Home Finance</option>
                                              <option value="408">Muthoot Capital Services Ltd</option>
                                              <option value="369">Muthoot Finance</option>
                                              <option value="352">Muthoot Fincorp Ltd</option>
                                              <option value="492">Muthoot Homefin Limited</option>
                                              <option value="389">Muthoot Housing Finance Company Limited</option>
                                              <option value="435">Muthoot Microfin Limited</option>
                                              <option value="458">Muthoot Money</option>
                                              <option value="360">Nagar Nigam Aligarh- muncipality</option>
                                              <option value="376">Netafim Agricultural Financing Agency Pvt. Ltd</option>
                                              <option value="186">Netplus Broadband</option>
                                              <option value="328">Nidhilakshmi Finance</option>
                                              <option value="329">NM Finance</option>
                                              <option value="383">Novelty Finance Ltd</option>
                                              <option value="235">OHMYLOAN</option>
                                              <option value="236">OMLP2P.COM</option>
                                              <option value="330">Oroboro</option>
                                              <option value="443">Oxyzo Financial Services Pvt Ltd</option>
                                              <option value="469">Pahal Financial Services Pvt Ltd</option>
                                              <option value="183">Paisa Dukan-Borrower EMI</option>
                                              <option value="358">Pooja Finelease</option>
                                              <option value="396">Rander Peoples Co Operative Bank Ltd</option>
                                              <option value="466">Reliance ARC</option>
                                              <option value="424">RMK Fincorp Pvt Ltd</option>
                                              <option value="486">Rupee Circle</option>
                                              <option value="434">RupeeRedee</option>
                                              <option value="378">Samasta Microfinance Limited</option>
                                        <option value="410">Sarvjan India Fintech Private Limited</option>
                                        <option value="189">Shriram City Union Finance Ltd</option>
                                        <option value="221">Shriram Housing Finance Limited</option>
                                        <option value="436">Shriram Transport Finance Company Limited</option>
                                        <option value="454">Shubham Housing Development Finance Company Ltd</option>
                                        <option value="374">SMEcorner</option>
                                        <option value="427">Smile Microfinance Limited</option>
                                        <option value="184">SSnapmint</option>
                                        <option value="493">Spandana Rural And Urban Development Organisation</option>
                                        <option value="452">Spandana Sphoorty Financial Ltd</option>
                                        <option value="357">StashFin</option>
                                        <option value="496">Suryoday Small Finance Bank</option>
                                        <option value="265">Svatantra Microfin Private Limited</option>
                                        <option value="185">Tata Capital Financial Services Limited</option>
                                        <option value="373">Tata Capital Housing Finance Limited</option>
                                        <option value="429">Tata Motors Finance Limited</option>
                                        <option value="387">Thazhayil Nidhi Ltd</option>
                                        <option value="444">Toyota Financial Services</option>
                                        <option value="346">TVS Credit</option>
                                        <option value="342">Ujjivan Small Finance Bank</option>
                                        <option value="200">Varthana</option>
                                        <option value="362">Vistaar Financial services Private Limited</option>
                                        <option value="388">X10 Financial Services Limited</option>
                                        <option value="394">Yogakshemam Loans Ltd</option>
                                        <option value="218">ZestMoney</option>
                                        <option value="420">Ziploan</option>
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
                          <input type="hidden" name="duedate" value="" id="duedate">
                         <input type="hidden" name="username" value="" id="name">
                         <input type="hidden" id="recharge_type" name="type" value="EMI">
                         <input type="hidden" id="serviceid" name="service" value="41">
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
            
                            </br>
            <div class="table-responsive">
                <!-- Projects table -->
               <table class="table align-items-center table-flush" id="transectionlist">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Print</th>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
    //   $('.select2').select2();
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
        data: {"account":account,'operator' : operator,'mode':'online',"<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
        beforeSend: function() {
          $('.fetch').append('<br><span><img width="100" height="100" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
           console.log(data);
          if(data.status==false && data.response_code==1){
              Swal.fire(data.message)
          $('.fetch').html('<div class="container text-center">Massage:'+ data.message +'</div>');
          $('#submit_btn').hide();
           
          }
          else if(data.response_code==0 && data.status==false)
          {
              Swal.fire("Invaild Customer Id Or Operator Name");
          $('.fetch').html('<div class="container text-center">Massage:Invaild Customer Id Or Operator Name</div>');  
          $('#submit_btn').hide();
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
          var type ='EMI';
          var service_id=41;
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