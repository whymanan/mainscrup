<div class="container">
    <form method="post" id="filter" action="" enctype="multipart/form-data"> 
       <div class="row"> 
 <div class="col-lg-12 card card-body"> 
       <span id="typename"></span>
            <div class="row">

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>"
                                autocomplete="off">
                <div class="col-sm-3">
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Email<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email[]" placeholder="demo@email.com" required>
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Name of Person<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name[]" placeholder="Full Name" required>
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Date Of Birth<span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" name="dob[]" placeholder="" required>
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">PAN NO.<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="pan_no[]" placeholder="XXXXX2154X" maxlength="10" minlength='10' required>
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Adress<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="address[]" placeholder="">
                    </div>
                    
                    </div>
                    <div class="col-sm-3">
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Mobile No.<span class="text-danger">*</span>
                    </label>
                        <input type="text" class="form-control" name="mobile[]" placeholder="Mobile No." maxlength="12" minlength='12' required>
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Father/Husband Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="m_name[]" placeholder="Father/Husband Name">
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Designation</label>
                        <input type="text" class="form-control" name="designation[]" placeholder="Designation">
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Adhar No.<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="adhar_no[]" placeholder="0000-0000-0000" maxlength="12" minlength='12' required>
                    </div>
                     <div class="din_div"  style="display:none">
                        <label class="form-control-label" for="example3cols1Input">Din Number<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="din[]" placeholder="Din No.">
                    </div>
                </div>
                <div class="col-sm-3">
                <div class="">
                   <img src="<?php echo base_url('assets'). '/img/theme/avtar.png'; ?>" class="img-responsive  w-50">
                    </div>
                    <div class="">
                    <img src="<?php echo base_url('assets'). '/img/theme/adhar_front.jpg'; ?>" class="img-responsive w-50">
                        <!-- <br><label class="form-control-label" for="example3cols1Input">Adhar Front</label>
                        <input type="file" class="form-control" name="start" placeholder=""> -->
                    </div>
                    <div class="">
                   <img src="<?php echo base_url('assets'). '/img/theme/adhar_back.jpg'; ?>" class="img-responsive  w-50">
                    </div>
                    <div class="">
                   <img src="<?php echo base_url('assets'). '/img/theme/pan.png'; ?>" class="img-responsive  w-50">
                    </div>
                                        <div class="">
                   <img src="<?php echo base_url('assets'). '/img/theme/pan.png'; ?>" class="img-responsive  w-50">
                    </div>
                    </div>
                    <div class="col-sm-3">
                   
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input"> Photo<span class="text-danger">*</span><label>
                        <input type="file" class="form-control" name="userfile[]" placeholder="">
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Adhar Front <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="adhar_front[]" placeholder="">
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">Adhar Back <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="adhar_back[]" placeholder="">
                    </div>
                    <div class="">
                        <label class="form-control-label" for="example3cols1Input">PAN <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="pan_file[]" placeholder="">
                    </div>
                        <div class="">
                        <label class="form-control-label" for="example3cols1Input">Latest Bank Statment <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="statement[]" placeholder="">
                    </div>

                   
                    
                </div>
                
            </div> <!--drector 1 fields-->
<hr>
<!--director2 fileds-->

        <div class="container" >
          
            <div class='element' id='div_1'>
               
            </div>
        </div>
        <div class="row">   <span class='btn btn-primary add'  style="display:none;">Add New Fields</span>
        <button type="submit" class="btn btn-success pull-right"  id="submitbtn">Submit</button>
        </div>
        <div class="row">
         <span style="display:none;" id="spanmessage"></span>
        </div>
       </div>
   
       </div>
    </form>

    <!-- row start -->
    <div class="row">
    <div class="col-xl-12 order-xl-1">
        <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Upload Documents</h3>
                        </div>

                    </div>
                </div>
            <div class="card-body">
            <form method="post" action="<?php echo base_url('gstfiling/docs'); ?>" enctype='multipart/form-data'>
                <div class="pl-lg-4">
                                    <div class="row">
                <div class="col-lg-4 ">
                    <label id="firm_name_div"> </label><span class="text-danger">*</span>
                     <input type="text" name="firm_name" class="form-control" value="Firm Name" required> 
                    </div>
                    <div class="col-lg-4">
                        <label>Nature Of Property</label>
                        <select class="form-control"  name="nop">
                            <option value="Rented">Rented</option>
                             <option value="Owned">Owned</option>
                        </select>
                        </div>
                    <div class="col-lg-4 ">
                    <label>Nature Of Business(5 Points)</lable>
              <textarea class="form-control" name="nob"></textarea>
                        </div>
                    </div>
                <div class="row">
                <div class="col-lg-4 ">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <label>State</lable>
                <input type="text" name="state" id="state" class="form-control" value=""> 
<div id="com"></div>
                </div>
                <div class="col-lg-4 ">
                <label>District</lable>
                <input type="text" name="district" class="form-control" value=""> 
                </div>
                <div class="col-lg-4 ">
                <label>Business Address</lable>
                <input type="text" name="business_adress" class="form-control" value=""> 
                </div>
               
                </div><!-- adressrow end-->
                    <div class="row " style="display:none">
                       <!--<div class="col-lg-4 ">-->
                           
                       <!--         <div class="card " >-->
                       <!--             <div class="card-header">-->
                       <!--                 <div class="form-group">-->
                       <!--                     <label class="form-control-label">Certificate Of Incorporation.</label>-->
                       <!--                     <input type="file" class="form-control finput" data-id="#blah1"-->
                       <!--                         name="coi" multiple="true" accept="image/*"-->
                       <!--                         onchange="readURL(this);">-->
                                          


                       <!--                 </div>-->
                       <!--             </div>-->

                       <!--             <div class="card-body">-->



                       <!--                 <div class="container text-center">-->
                       <!--                     <img id="blah1"-->
                       <!--                         src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"-->
                       <!--                         alt="your image" width="150px" />-->
                       <!--                 </div>-->

                             
                       <!--             </div>-->
                                   

                       <!--         </div>-->
                            
                       <!--  </div>-->

                         <div class="col-lg-4 ">
                           
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">MOA</label>
                                            <input type="file" class="form-control finput" data-id="#blah1"
                                                name="moa_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                          


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah1"
                                                src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"
                                                alt="your image" width="150px" />
                                        </div>

                                   
                                    </div>
                                   

                                </div>
                            
                         </div>
                         <div class="col-lg-4 ">
                           
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">AOA</label>
                                            <input type="file" class="form-control finput" data-id="#blah1"
                                                name="aoa_file" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                          


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah1"
                                                src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"
                                                alt="your image" width="150px" />
                                        </div>

                                    
                                    </div>
                                   

                                </div>
                            
                         </div>
                    </div>
                    <div class="row" >
                    <div class="col-lg-4 ">
                           
                           <div class="card ">
                               <div class="card-header">
                                   <div class="form-group">
                                       <label class="form-control-label">Electricity Bill <span class="text-danger">*</span></</label>
                                       <input type="file" class="form-control finput" data-id="#blah1"
                                           name="electricity_bill" multiple="true" accept="image/*"
                                           onchange="readURL(this);" required>
                                     


                                   </div>
                               </div>

                               <div class="card-body">



                                   <div class="container text-center">
                                       <img id="blah1"
                                           src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"
                                           alt="your image" width="150px" />
                                   </div>

                             
                               </div>
                              

                           </div>
                       
                    </div>

                    <div class="col-lg-4 ">
                           
                                <div class="card ">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Rent Agreement</label>
                                            <input type="file" class="form-control finput" data-id="#blah1"
                                                name="rent_agreement" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                          


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah1"
                                                src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"
                                                alt="your image" width="150px" />
                                        </div>


                                    </div>
                                   

                                </div>
                            
                         </div>
                             <div class="col-lg-4 filediv" style='display:none'>
                           
                                <div class="card " >
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label class="form-control-label">Certificate Of Incorporation.</label>
                                            <input type="file" class="form-control finput" data-id="#blah1"
                                                name="coi" multiple="true" accept="image/*"
                                                onchange="readURL(this);">
                                          


                                        </div>
                                    </div>

                                    <div class="card-body">



                                        <div class="container text-center">
                                            <img id="blah1"
                                                src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"
                                                alt="your image" width="150px" />
                                        </div>

                             
                                    </div>
                                   

                                </div>
                            
                         </div>
                         <!--<div class="col-lg-4">-->
                         <!--<div class="card ">-->
                         <!--           <div class="card-header">-->
                         <!--               <div class="form-group">-->
                         <!--                   <label class="form-control-label">Authorised Signitory</label>-->
                         <!--                   <input type="file" class="form-control finput" data-id="#blah1"-->
                         <!--                       name="auth_sign_file" multiple="true" accept="image/*"-->
                         <!--                       onchange="readURL(this);">-->
                                          


                         <!--               </div>-->
                         <!--           </div>-->

                         <!--           <div class="card-body">-->



                         <!--               <div class="container text-center">-->
                         <!--                   <img id="blah1"-->
                         <!--                       src="<?php if(isset($doc[0]['type']) && $doc[0]['type'] == 'photo'){echo base_url('uploads/'). $doc[0]['type'].'/'. $doc[0]['name'] ;}else{ echo base_url('assets').'/img/theme/avtar.png' ;}?>"-->
                         <!--                       alt="your image" width="150px" />-->
                         <!--               </div>-->


                         <!--           </div>-->
                                   

                         <!--       </div>-->
                         <!--</div>-->
                    </div>
                    <div class="row">
                    <button type="submit" class="btn btn-primary" id="regbtn" style="display:none">Submit</button>
                    </div>
                    <!-- col-4 ends -->
                        
                       <!-- col-4 ends -->

                          <!-- col-4 ends -->
                </div><!--pl-4-->
                </form>
                </div>
    </div>
    </div>
    </div>
    <!-- row ends -->

   
</div>

<script>
$(document).ready(function(){

// Add new element
$(".add").click(function(){

    // Finding total number of elements addeddin_div
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
        
    $("#div_" + nextindex).append(" <div class='row'> <div class='col-sm-3'> <div> <label class=form-control-label' for='example3cols1Input'>Email <span class='text-danger'>*</span></label> <input type='text' class='form-control email' name='email[]' placeholder='demo@email.com' required> </div><div> <label class='form-control-label' for='example3cols1Input'>Name of Person <span class='text-danger'>*</span></label> <input type='Name' class='form-control name' name='name[]' placeholder='Full Name' required> </div><div> <label class='form-control-label' for='example3cols1Input'>Date Of Birth<span class='text-danger'>*</span> </label> <input type='date' class='form-control dob' name='dob[]' required> </div><div> <label class='form-control-label' for='example3cols1Input'>PAN NO. <span class='text-danger'>*</span> </label> <input type='text' class='form-control pan_no' name='pan_no[]' placeholder='XXXXX1333X' required> </div><div> <label class='form-control-label'>Adress <span class='text-danger'>*</span></label> <input type='text' class='form-control adress' name='address[]' placeholder='Address' required> </div></div><div class='col-sm-3'> <div> <label class='form-control-label'>Mobile No.<span class='text-danger'>*</span> </label> <input type='text' class='form-control mobile' name='mobile[]' placeholder='Mobile No.' maxlength='10' minlegth='10' required></div><div> <label class='form-control-label' >Father/Husband Name  <span class='text-danger'>*</span></label> <input type='text' class='form-control m_name' name='m_name[]' placeholder='Father/Husband Name' required> </div><div> <label class='form-control-label' >Designation</label> <input type='text' class='form-control designation' name='designation[]' placeholder='Designation'> </div><div> <label class='form-control-label'>Adhar No.<span class='text-danger'>*</span></label> <input type='text' class='form-control adhar_no' name='adhar_no[]' placeholder='1111-1111-1111' required> </div><div class='' style='display:none'> <label class='form-control-label' >Din Number<span class='text-danger'>*</span> </label> <input type='text' class='form-control din' name='din[]' placeholder='Din No.'></div>   </div><div class='col-sm-3'> <div > <img src='<?php echo base_url('assets'). '/img/theme/avtar.png'; ?>' class='img-responsive w-50'> </div><div > <img src='<?php echo base_url('assets'). '/img/theme/adhar_front.jpg'; ?>' class='img-responsive w-50'> </div><div> <img src='<?php echo base_url('assets'). '/img/theme/adhar_back.jpg'; ?>' class='img-responsive w-50'> </div><div> <img src='<?php echo base_url('assets'). '/img/theme/pan.png'; ?>' class='img-responsive w-50'> </div><div> <img src='<?php echo base_url('assets'). '/img/theme/pan.png'; ?>'' class='img-responsive w-50'> </div><div> <img src='<?php echo base_url('assets'). '/img/theme/pan.png'; ?>' class='img-responsive w-50'></div></div><div class='col-sm-3'> <div> <label class='form-control-label' for='example3cols1Input'> Photo</label> <input type='file' class='form-control' name='userfile[]' placeholder=''> </div><div> <label class='form-control-label' >Adhar Front</label> <input type='file' class='form-control' name='adhar_front[]' placeholder=''> </div><div > <label class='form-control-label' for='example3cols1Input'>Adhar Back</label> <input type='file' class='form-control' name='adhar_back[]' placeholder=''> </div><div> <label class='form-control-label' >PAN</label> <input type='file' class='form-control' name='pan_file[]' placeholder=''> </div><div> <label class='form-control-label'>Latest Bank Statment <span class='text-danger'>*</span></label> <input type='file' class='form-control statement' name='statement[]' ></div></div></div>");
        $("#div_" + nextindex).append("<span id='remove_" + nextindex + "' class='remove'>X</span>");
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

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#filter").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('gstfiling/reg'); ?>',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
          
           success: function(response) {
           
           if (response == 100) {
                $('#spanmessage').show();
                $('#regbtn').show();
                $('#submitbtn').hide();
                $('#spanmessage').addClass("alert alert-success text-center").html("Director Details Added Successfully Please fillUp below Details To continue");
               
                
            } else {
                // $('#error1').show();
                // $('#error1').addClass("alert alert-danger").html("already exist!");
               
              
            }
            
        }
        
        });
    });
});
</script>