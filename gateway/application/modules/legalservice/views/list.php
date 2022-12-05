<div id="remover">
  <div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-header border-0">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="mb-0" id="tabs"> GST Registration List </h3>
          </div>
          <div class="col text-right">
            <a href="#!" id="notify" class="btn btn-sm btn-primary">See all</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <div class="row">
            <div class="col-6">
              <button class="btn btn-primary" id="approve" style="margin-left:20px;">Approve</button>
            </div>
            <div class="col-6">
              <button class="btn btn-primary" id="all_select" style="float: right;margin-right: 21px;">Check Maximum Ten Record</button>
            </div>
        </div>
        </br></br>
        <!-- Projects table -->
        <table class="table align-items-center table-flush" id="gstlist">
          <thead class="thead-light">
            <tr>
              
              <th scope="col">#</th>
              <th scope="col">Gst R.No</th>
              <th scope="col">MEMBER ID</th>
              <th scope="col">Firm Name</th>
              <th scope="col">Company Type</th>
              <th scope="col">Nature Of Property</th>
              <th scope="col">District</th>
              <th scope="col">State</th>
              <th scope="col">Nature Of Business</th>
              <th scope="col">Business Address</th>
              <th scope="col">status</th>
              <th scope="col">Notation</th>
              <th scope="col">created</th>
              
              
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script>
  $('#all_select').click(function()
  {
                $(this).remove('id');
                $(this).attr('id','non_select');
                var ele=document.getElementsByName('check'); 
                var length=0;
                if(ele.length>10)
                {
                  length=ele.length=10;
                }
                else
                {
                  length=ele.length
                }
                for(var i=0; i<length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=true;  
                }   
  })
  $("#non_select").click(function()
  {
                $(this).remove('id');
                $(this).attr('id','all_select');
    var ele=document.getElementsByName('check');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=false;  
                      
                }  
  })
  $('#approve').click(function()
  {
        var data;
        var array = [];
        var parent = $('.checkboxpre');
        var service='gst_registration';
        //check or uncheck sub-checkbox
        $(parent).find('.checksub').prop("checked", $(this).prop("checked"))
        //push checked sub-checkbox value to array
        $(parent).find('.checksub:checked').each(function () {
            array.push($(this).val());
        })
        console.log(array);

        // window.location.replace("legalservice/LegalController/payment_list/"+array);
        if(array.length!=0)
        {
        $.ajax(
        {
          url:'<?php echo base_url("legalservice/LegalController/payment_list");?>',
          type:'GET',
         data:{'data':array,'service':service},
          success:function(data)
            {  
           $('#remover').html('');
           $('#remover').html(data);
          //   console.log(data);
            }
        });
      }
    })
      var Api = '<?php echo base_url('legalservice/LegalController/'); ?>';

        var $gstlist = $('#gstlist');
         var service = $('#service_type').val();
        console.log(service);
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
            url: Api + "get_gstlist?"  + "&list=all&service=53",
            type: "GET",
        },

        "pageLength": 10
        });

</script>
