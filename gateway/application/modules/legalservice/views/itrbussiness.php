<div class="container">

<form method="post" id="filter" action="<?php echo base_url('legalservice/LegalController/itr_bussiness')?>" enctype="multipart/form-data"> 
    <div class="row"> 
        <div class="col-lg-12 card card-body"> 
            <span>ITR Bussiness</span>

                <div class="row">

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="service_id" value="57">
                    <input type="hidden" name="service_type" value="itr_bussiness">

                  
                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label class="form-control-label" for="example3cols1Input">Email
                            </label>
                            <input type="Name" class="form-control" name="email" placeholder="" required>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label class="form-control-label" for="example3cols1Input">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile" placeholder="">
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label class="form-control-label" for="example3cols1Input">Annual Income
                            </label>
                            <input type="test" class="form-control" name="annualincome" placeholder="">
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label class="form-control-label" for="example3cols1Input">Bussiness Details
                            </label>
                            <input type="text" class="form-control" name="bussinessdetails" placeholder="" required>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-lg-6 col-md-6 col-sm-5">
                            <label class="form-control-label" for="example3cols1Input">Pan Card
                            </label>
                            <input type="file" class="form-control" name="pan" placeholder="" required>
                         </div>
                         <div class="col-lg-5 col-md-5 col-sm-5">
                            <label class="form-control-label" for="example3cols1Input">Adhar Card(font)
                            </label>
                            <input type="file" class="form-control" name="Adharf" placeholder="" required>
                         </div>
                         <div class="col-lg-6 col-md-6 col-sm-6">
                            <label class="form-control-label" for="example3cols1Input">Adhar Card(Back)
                            </label>
                            <input type="file" class="form-control" name="Adharb " placeholder="" required>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-lg-6 col-md-6 col-sm-5">
                            <label class="form-control-label" for="example3cols1Input">Photo
                            </label>
                            <input type="file" class="form-control" name="photo" placeholder="" required>
                         </div>
                         <div class="col-lg-5 col-md-5 col-sm-5">
                            <label class="form-control-label" for="example3cols1Input">Bussiness Details
                            </label>
                            <input type="file" class="form-control" name="bussiness " placeholder="" required>
                         </div>
                    </div>
                <hr>
                
                <!-- <div class="row justify-content-center">   
                    
                    <span class='btn btn-primary add'>Add New director</span>
                
                </div> -->
                
                <div class="row">
                    <span style="display:none;" id="spanmessage"></span>
                </div>
                <div class="row" style="justify-content: center;">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>

        </div>
    </div> 
</form>    

</div>

<script>
$(document).ready(function(){


var $gstFormSubmit = $('#gstFormSubmit');

$(".add").click(function(){

    var total_element = $(".element").length;
    
    var lastid = $(".element:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[1]) + 1;

    var max = 5;
    
    if(total_element < max ){
        
        $(".element:last").after("<div class='element' id='div_"+ nextindex +"'></div>");
                    
        $("#div_" + nextindex).append('<br>  <div class="row"><div class="col-lg-6 col-md-6 col-sm-5"><label class="form-control-label" for="example3cols1Input">Purchase Details</label><input type="file" class="form-control" name="purchase[]" placeholder="" required></div><div class="col-lg-5 col-md-5 col-sm-5"><label class="form-control-label" for="example3cols1Input">Sale Details</label><input type="file" class="form-control" name="sale[]" placeholder="" required></div><div class="col-lg-1 col-md-1 col-sm-1"><label class="form-control-label" for="example3cols1Input">Add</label><button class="add btn btn-primary pull-right" >+</button></div></div><br>');
        
        $("#div_" + nextindex).append("<button id='remove_" + nextindex + "' class='remove btn btn-danger pull-right' >X</button>");
    }
                
});


$('.container').on('click','.remove',function(){
            
    var id = this.id;
    var split_id = id.split("_");
    var deleteindex = split_id[1];

    // Remove <div> with id
    $("#div_" + deleteindex).remove();
});  

// $("#filter").on('submit', function(e){
//     e.preventDefault();

//     $.ajax({
//         type: 'POST',
//         url: '<?php echo base_url('legalservice/Legalcontroller/submit_gst'); ?>',
//         data: new FormData(this),
//         dataType: 'json',
//         contentType: false,
//         cache: false,
//         processData:false,
//         beforeSend: function(){
//           $gstFormSubmit.html('<img src="<?php echo base_url("optimum/greay-loading.svg") ?>"/>');
//         },
//         success: function(data) {
//           console.log("hdhfihd")
//             $gstFormSubmit.html(data);
//         },
    
//     });

// });

});

</script>