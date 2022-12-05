<div class="row">

    <div class="col-xl-12 order-xl-1">
         <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                    <h4 class="heading-large text-muted mb-4">Upload GST Rerurn File</h4>
                    </div>
                    <div class="col-4 text-right">
                    
                    </div>
                </div>
            </div>
            <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
            <form action="<?php echo base_url('gstfiling/saleReturn'); ?>" method="post" enctype="multipart/form-data">
            <div class="container">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"
                                autocomplete="off">
            <input type='file' class='form-control' name='userfile[]'> 
            <div class='element' id='div_1'>
         </div>
               <div class="form-group">
               <strong class='text-success add'>Add New file</strong>
               </div>
            <div class="form-group">
            <button type="submit" class="btn btn-success float-right">Submit</button>
            </div>


               </div>
            </form>
            
             
            </div>

            <div class="col-sm-6">
            <form  action="<?php echo base_url('gstfiling/purchaseReturn'); ?>" method="post" enctype="multipart/form-data">
            <div class="container1">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"
                                autocomplete="off">
            <input type='file' class='form-control' name='userfile[]'> 
            <div class='element1' id='div_2'>
        
               </div>
               <div class="form-group">
               <strong class='text-success add1'>Add New file</strong>
               </div>
            <div class="form-group">
            <button type="submit" class="btn btn-success float-right">Submit</button>
            </div>

               </div>
            </form>
            </div>
          </div>
            </div>
        </div>
          </div>
          </div>

  
   <script type="text/javascript">
 $(document).ready(function(){

// Add new element
$(".add").click(function(){

    // Finding total number of elements added
    var total_element = $(".element").length;
                
    // last <div> with element class id
    var lastid = $(".element:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[1]) + 1;

    var max = 5;
    // Check total number elements
    if(total_element < max ){
        // Adding new div container after last occurance of element class
        $(".element:last").after("<div class='element' id='div_"+ nextindex +"'></div>");
                    
        // Adding element to <div>
        // $("#div_" + nextindex).append(" <div class='row'><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols1Input'>Email</label> <input type='text' class='form-control' name='start' placeholder=''> </div><div class=''> <label class='form-control-label' for='example3cols1Input'>Date Of Birth</label> <input type='date' class='form-control' name='start' placeholder=''> </div></div><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols1Input'>Mobile No.</label> <input type='text' class='form-control' name='start' placeholder=''> </div><div class=''> <label class='form-control-label' for='example3cols1Input'>Designation</label> <input type='text' class='form-control' name='start' placeholder=''> </div></div><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols2Input'>Name of Person</label> <input type='text' class='form-control' name='end' placeholder=''> </div><div class=''> <label class='form-control-label' for='example3cols1Input'>Adress</label> <input type='text' class='form-control' name='start' placeholder=''> </div></div><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols1Input'>Father/Husband Name</label> <input type='text' class='form-control name='start' placeholder=''> </div><div><br><span  id='remove_" + nextindex + "' class='remove btn btn-danger  '>X</span></div></div></div>");
        $("#div_" + nextindex).append("<input type='file' class='form-control'  name='userfile[]'><span id='remove_" + nextindex + "' class='remove'>X</span>");
        // $("#div_" + nextindex).append("");
    }
                
});

// Remove element
$('.container').on('click','.remove',function(){
            
    var id = this.id;
    var split_id = id.split("_");
    var deleteindex = split_id[1];

    // Remove <div> with id
    $("#div_" + deleteindex).remove();
});                
});


$(document).ready(function(){

// Add new element
$(".add1").click(function(){

    // Finding total number of elements added
    var total_element = $(".element1").length;
                
    // last <div> with element class id
    var lastid = $(".element1:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[1]) + 1;

    var max = 5;
    // Check total number elements
    if(total_element < max ){
        // Adding new div container after last occurance of element class
        $(".element1:last").after("<div class='element1' id='div_"+ nextindex +"'></div>");
                    
        // Adding element to <div>
        // $("#div_" + nextindex).append(" <div class='row'><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols1Input'>Email</label> <input type='text' class='form-control' name='start' placeholder=''> </div><div class=''> <label class='form-control-label' for='example3cols1Input'>Date Of Birth</label> <input type='date' class='form-control' name='start' placeholder=''> </div></div><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols1Input'>Mobile No.</label> <input type='text' class='form-control' name='start' placeholder=''> </div><div class=''> <label class='form-control-label' for='example3cols1Input'>Designation</label> <input type='text' class='form-control' name='start' placeholder=''> </div></div><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols2Input'>Name of Person</label> <input type='text' class='form-control' name='end' placeholder=''> </div><div class=''> <label class='form-control-label' for='example3cols1Input'>Adress</label> <input type='text' class='form-control' name='start' placeholder=''> </div></div><div class='col-sm-3'> <div class=''> <label class='form-control-label' for='example3cols1Input'>Father/Husband Name</label> <input type='text' class='form-control name='start' placeholder=''> </div><div><br><span  id='remove_" + nextindex + "' class='remove btn btn-danger  '>X</span></div></div></div>");
        $("#div_" + nextindex).append("<input type='file' class='form-control'  name='userfile[]'>");
        $("#div_" + nextindex).append("<span id='remove_" + nextindex + "' class='removes'>X</span>");
    }
                
});

// Remove element
$('.container1').on('click','.removes',function(){
            
    var id = this.id;
    var split_id = id.split("_");
    var deleteindex = split_id[1];

    // Remove <div> with id
    $("#div_" + deleteindex).remove();
});                
});
  </script>
   