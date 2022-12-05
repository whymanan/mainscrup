  <div class="card card-body">
    <form method="post" id="filter" action="<?php echo base_url('legalservice/LegalController/company_price_submit'); ?>">
        <div class="form-row">
            <div class="row">
                <div class="col-6">
                      <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">State</label>
                        <select class="form-control" name="state" id="state">
                            <option value="">Select State</option>
                            <?php foreach($price as $value){?>
                               <option value="<?php echo $value->state; ?>"><?php echo $value->state; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Set Amount(BY Super Admin)</label>
                        <input type="number" class="form-control" id="amounts" name="amount" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols1Input">Set Amount</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="">
                    </div>
                </div>
            <div class="col-2">
              <!--<input type="hidden" name="<? $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />-->
                   <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                  
                    <div class="text-center" id="submit_btn">  
                    <button type="Submit" class="btn btn-primary my-4" >Submit</button>
                   </div>
                  
           
            </div>
        </div>
    </form>
    
</div>
<div class="card">
    <div class="card-body">
     <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush" id="list">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">State</th>
                             <th scope="col">Price</th>
                        </tr>
                    </thead>
                </table>
        </div>
     </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function()
{
  $('#state').on('change',function()
  {
      var data=$(this).val();
      $.ajax(
        {
          url:'<?php echo base_url("legalservice/LegalController/company_price_show");?>',
          type:'GET',
         data:{'data':data},
          success:function(data)
            {  
              $('#amounts').val(data);
            }
        });
  })  
  var Api = '<?php echo base_url('legalservice/LegalController/'); ?>';
  $gstlist=$('#list');
  var $table = $gstlist.DataTable({
  "processing": true,
  "serverSide": true,
  "deferRender": true,
  "language": {
      "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
      "emptyTable": "No data available ...",
  },
  "order": [],
  "ajax": {
      url: Api + "company_registation_price_list?"  + "&list=all",
      type: "GET",
  },

  "pageLength": 10
  });
})
</script>
