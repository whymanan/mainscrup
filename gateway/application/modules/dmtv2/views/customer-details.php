<div class="card">
  <!-- Card body -->
  <div class="card-body">
    <ul class="list-group list-group-flush list my--3">
      <li class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col-auto">
            <h5 class=" mt-3 mb-0"> Status </h5>
            <span class="badge badge-lg badge-success"><?php echo $response->Status; ?></span>
          </div>
          <div class="col-auto">
            <h5 class=" mt-3 mb-0"> Customer Name </h5>
            <h4 class="display-5 mb-0"><?php echo $response->first_name; ?></h4>
          </div>
          <div class="col-auto">
            <h5 class="mt-3 mb-0"> Customer Mobile </h5>
            <h4 class="display-5 mb-0"><?php echo $response->phone_no; ?></h4>
          </div>
          <div class="col-auto">
            <h5 class=" mt-3 mb-0"> Limit </h5>
            <h4 class="display-5 mb-0"><?php //echo $response->Limit; ?></h4>
          </div>
          <div class="col-auto">
            <h5 class=" mt-3 mb-0"> Balance </h5>
            <h4 class="display-5 mb-0"><?php //echo $response->Balance; ?></h4>
          </div>
          <div class="col-auto">
            <h5 class=" mt-3 mb-0"> Account Type </h5>
            <h4 class="display-5 mb-0"><?php //echo $response->resText ?></h4>
          </div>
        </div>
      </li>
      <li class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col-auto">
            <h3 class="mb-0">All Beneficiary List</h3>
          </div>
          <div class="col-auto">
            <button type="button" class="btn btn-slack btn-icon" data-toggle="modal" data-target="#modal-add-beneficiary">
              <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
              <span class="btn-inner--text">Add Beneficiary</span>
            </button>
          </div>
        </div>
      </li>
      <?php if (!empty($beneficiarylist)): ?>
        <li class="list-group-item px-0">
          <div class="row align-items-center">
            <div class="col-auto">
              <div class="table-responsive">
                  <table class="table align-items-center">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col" class="sort" data-sort="name">Beneficiary ID</th>
                        <th scope="col" class="sort" data-sort="budget">Account Number</th>
                        <th scope="col" class="sort" data-sort="budget">Account Name</th>
                        <th scope="col" class="sort" data-sort="budget">IFSC CODE</th>
                        <th scope="col" class="sort" data-sort="status">Status</th>
                        <th scope="col"> Action </th>
                       
                      </tr>
                    </thead>
                    <tbody class="list">
                    <?php if(isset($beneficiarylist->Status) != "Faild"): ?>    
                      <?php foreach ($beneficiarylist as $listData): ?>
                        <tr>
                          <td>
                            <?php echo $listData->ba_primary ?>
                          </td>
                          <td class='beneficiary_account_number'>
                            <?php echo $listData->beneficiary_account_number ?>

                          </td>
                          <td class='beneficiary_name'>
                            <?php echo $listData->beneficiary_name ?>

                          </td>
                          <td class='beneficiary_ifsc'>
                            <?php echo $listData->beneficiary_ifsc ?>

                          </td>
                          <!--<?php if ($listData->Status == 'SUCCESS'): ?>-->
                          <!--  <td> <button type="button" class="btn btn-success btn-sm" name="make-payment" data-bid="<?php echo $listData->beneficiaryId ?>" name="button"> Make payment</button> </td>-->
                          <!--<?php else: ?>-->
                          <!--  <td> <button type="button" class="btn btn-danger btn-sm" name="button" disabled> Not Active</button></td>-->
                          <!--<?php endif; ?>-->
                          
                          
                           <td> <button type="button" class="btn btn-success btn-sm" id="make-payment" name="make-payment" data-bid="<?php echo $listData->ba_primary ?>" name="button"> Make payment</button> </td>
                        
                           <td><?php if($listData->varification==1){ echo "<span class='badge badge-success'>Verified</span>";}else{?><button class='btn btn-sm btn-success 1varify'  value='<?php echo $listData->ba_primary?>'>Verify</button><?php }?></td> 
                           <td>
                          
                        </tr>
                      <?php endforeach; ?>
                      <?php else: ?>
                            <li class="list-group-item px-0">
                              <div class="row align-items-center">
                                <div class="col-auto">
                                  <p class="mb-0 text-danger">Beneficiary Not Found</p>
                                </div>
                              </div>
                            </div>
                          </li>
                      <?php endif; ?>
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
        </li>
      <?php else: ?>
        <li class="list-group-item px-0">
          <div class="row align-items-center">
            <div class="col-auto">
              <p class="mb-0 text-danger">Beneficiary Not Found</p>
            </div>
          </div>
        </div>
      </li>
      <?php endif; ?>
  </div>
</div>
<div class="modal fade" id="modal-add-beneficiary" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modal-title-default">New Beneficiary Details </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form name="addBeneficiaryForm">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input class="form-control" placeholder="Account Name" name="beneficiary_name" type="text" required>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                  </div>
                  <input class="form-control" placeholder="Account Number" name="beneficiary_account" type="text" required>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                  </div>
                  <input class="form-control" placeholder="Mobile Number" name="beneficiary_mobile" type="text" required>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                  </div>
                  <input class="form-control" style="text-transform:uppercase" placeholder="IFSC CODE" name="beneficiary_ifsc_code" type="text" required>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-code"></i></span>
                  </div>
                  <input class="form-control" placeholder="Bank Name" name="beneficiary_bank" type="text" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="beneficiary_submit" class="btn btn-success">Submit Beneficiary</button>
          <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-many-transfer" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modal-title-default">New Beneficiary Details </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form name="makeTransactionForm">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input class="form-control" placeholder="Amount" name="amount" type="text" required>
                </div>
              </div>
            </div>
            <!--<div class="col-md-12">-->
            <!--  <div class="form-group">-->
            <!--    <select class="form-control" name="mode">-->
            <!--      <option value="IMPS" selected>IMPS</option>-->
            <!--    </select>-->
            <!--  </div>-->
            <!--</div>-->
            <div id="additional">

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="beneficiary" class="btn btn-success">Make Payment</button>
          <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $('.1varify').click(function()
{
                var sureDel = confirm("Varification Charge 3.36 rupees");
               if (sureDel == true) {
                   var userid=$(this).val();
                   var beneficiary_ifsc=$(this).closest("tr").find('.beneficiary_ifsc').text();
                   var name=$(this).closest("tr").find('.beneficiary_name').text();
                   var account=$(this).closest("tr").find('.beneficiary_account_number').text();
                  
                $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('dmtv2/verify')?>",
                  datatype:JSON,
                  data:{'userid':userid,'name':name,'beneficiary_ifsc':beneficiary_ifsc,'account':account,"<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
                  success: function(response) {
                      console.log(response);
                    //  var response1=JSON.parse(response);
                    //  var list='';
                     
                        /*response1.beneficiary_list.forEach(data);
                        function data(item)
                        {
                            list+="<div><tr><td>"+item.ba_primary+"<td><td>"+item.beneficiary_account_number+"<td><td>"+item.beneficiary_name+"<td><td>"+item.beneficiary_ifsc+"<td><td><button type='button' class='btn btn-success btn-sm' id='make-payment' name='make-payment' data-bid='"+item.ba_primary+"' name='button'> Make payment</button> </td><td><?php if($listData->varification==1){ echo `<span class='badge badge-success'>Varified</span>`;}else{?><button class='btn btn-sm btn-success 1varify'  value='"+item.ba_primary+"'>Varify</button><?php }?></td><td>"+
                            +"<a class='btn btn-sm btn-primary' data-placement='bottom' title='Delete Menu Information' href='<?php echo base_url('dmtv2/DmtvtwoController/beneficial_delete/');?>"+item.ba_primary+"'><i class='fa fa-trash-alt'></i></a></td></tr></div>";
                        }
                        $('.list').html(list);*/
                        Swal.fire({
                              position: 'top-end',
                              icon: 'error',
                              title: response ,
                              showConfirmButton: false,
                              timer: 1500
                            });
                      location.reload()
                  }
              });
        }
            }); 
</script>
