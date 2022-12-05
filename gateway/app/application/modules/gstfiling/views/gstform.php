<div class="row">

    <div class="col-xl-12 ">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0"></h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="pl-lg-6">
                  <div class="row">
                      <div class="col-lg-4">
                          <div class="form-group">
                              <label class="form-control-label">Select Comapany Type</label>
                              <select name="com_type" id="com_type" class="form-control"   required>
                                  <option value="">Select Company Type</option>
                                  <option value="privatelimited">Private limited</option>
                                  <option value="Partenership">Partenership</option>
                                  <option value="Proprietorship">Proprietorship</option>
                              </select>
                          </div>

                      </div>
                                                 <div class="col-lg-4">
                               <span><div class="text-danger"> * </div>marked fields are mandatory!</span>
                               </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- <div id="addCommissionForm"></div> -->
                    </div>
                  </div>
              </div>
              
            </div>
        </div>
        
           
    </div>
     
</div>

<div id="addCommissionForm"></div>



<script>

 
<!-- application/x-httpd-php script.php ( HTML document, ASCII text, with CRLF line terminators )
 --><script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>


<script type="text/javascript">
$(document).ready(function() {



    


      const formData = new FormData();
    //   var query = {
    //             id: 94,
    //             type: 'public',
    //             "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
    //           }
    //     getlist(query);

      var $addCommissionForm = $('#addCommissionForm');

      let body  =   $('body');

      body.on('change', 'select[name="com_type"]', function() {

        //   var bioMetricCapture = $('#bioMetricCapture');

          var onload = $(this).val();
      


          if (typeof onload !== 'undefined' && onload) {

            $.ajax({
              url: '<?php echo base_url('gstfiling/addForm'); ?>',
              type: 'POST',
              data: {"gstForm": onload, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
              beforeSend: function(){
                $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                $addCommissionForm.html(data);
                $('#role_id').val(onload);
                if(onload ==  'privatelimited'){
                  $(".filediv").show();
                   $(".din_div").show();
                    $(".add").show(); 
                     $('#typename').addClass("text-success").html("Enter Directors Details");
                                          $('#firm_name_div').addClass(".text-dark").html("Pvt. Ltd. Firm Name");
               $("#com").append("<div><br><input type='hidden' name='com_typ' value='"+ onload +"'/><br></div>");
                
                }else if(onload ==  'Partenership'){
                       $(".add").show(); 
                        $('#typename').addClass(".text-success").html("Enter Partner Details");
                         $('#firm_name_div').addClass("text-dark").html("Partner Firm Name");
                        $("#com").append("<div><br><input type='hidden' name='com_typ' value='"+ onload +"'/><br></div>");
                }else{
                    $('#typename').addClass("text-success").html("Enter Proprietor Details");
                     $('#firm_name_div').addClass("text-dark").html("Proprietor Firm Name");
                    $("#com").append("<div><br><input type='hidden' name='com_typ' value='"+ onload +"'/><br></div>");
                }
           
              },
            })
          }

        });
  
     $(document).on('click', '#submit', function() {

          

          var onload = $(this).val();

          if (typeof onload !== 'undefined' && onload) {

            $.ajax({
              url: '<?php echo base_url('superadmin/superadmin/addCommissionForm'); ?>',
              type: 'POST',
              data: {"aepsCommissionForm": onload, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
              beforeSend: function(){
                $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
              },
              success: function(data) {
                $addCommissionForm.html(data);
              },
            })
          }

        });


        $('#role').select2({
          ajax: {
            url: '<?php echo base_url('autorole'); ?>',
            type: "GET",
            dataType: 'json',
            data: function(params) {
              var query = {
                search: params.term,
                type: 'public',
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
              }
              return query;
            },
            processResults: function(data) {
              return {
                results: data
              };
            },
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
          }
        });
    // distributor list


  body.on('change', 'select[name="user_role"]', function() {
        
         var id =  $(this).val();
         $('#role_id').val(id);
         var query = {
                id: id,
                type: 'public',
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
              }
              $('#list').DataTable().destroy();
        // getlist(query);
         
    
     });
     
    body.on('change', 'select[name="user_role"]', function() {
         var id =  $(this).val()
         var query = {
                id: id,
                type: 'public',
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
              }
         var Api = '<?php echo base_url('superadmin/superadmin/get_list'); ?>';
         var $squadlist = $('#list');


    var $table = $squadlist.DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No distributors data available ...",
      },
      "order": [],
      "ajax": {
        url: Api,
        type: "GET",
          dataType: 'json',
            data: query
      },

      "pageLength": 10
    });
     });



});


function Delete(id) {

var sureDel = confirm("Are you sure want to delete");
if (sureDel == true) {
  $.ajax({
    type: "GET",
    url: "<?php echo base_url('superadmin/superadmin/delete/')?>" + id,

    success: function(response) {
      if (response == 1) {
        $('#squadlist').DataTable().ajax.reload();

        Swal.fire({
          position: 'top-end',
          type: 'success',
          title: 'Deleted Successfully',
          showConfirmButton: false,
          timer: 3500
          
        });
      } else {
        Swal.fire({
          position: 'top-end',
          type: 'error',
          title: 'Something went wrong',
          showConfirmButton: false,
          timer: 3500
        });
      }
     location.reload()
    }
  });

}

}


function Edit(id) {


var $addCommissionForm = $('#addCommissionForm');
$('#menu_id').val(id);
var sureDel = confirm("Are you sure want to edit");
if (sureDel == true) {
  $.ajax({
    type: "GET",
    url: "<?php echo base_url('superadmin/superadmin/editComm/') ?>" + id,

    success: function(response) {
      response = JSON.parse(response);
      if (response != "") {
        
             
    var role_id = $('#role').val();
        $.ajax({
          url: '<?php echo base_url('superadmin/superadmin/addupdate'); ?>',
          type: 'POST',
          data: {"addupdate": role_id, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"},
         beforeSend: function(){
            $addCommissionForm.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
          },
          success: function(data) {
            $addCommissionForm.html(data);
            $('#role_id').val(role_id);
            
            $('#start').val(response.start_range);

           $('#end').val(response.end_range);
          
           $('#commision').val(response.g_commission);
       
           $('#max').val(response.max_commission);
          
           $('#flat').val(response.c_flate);
           
           $('#service_commission_id').val(response.service_commission_id);
           
          
          },
        })
           
      }


    }
  });

}



}
    



</script>



 


