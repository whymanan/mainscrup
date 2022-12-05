<style>
    
    .error {
        border: solid 2px #FF0000;  
    }
    
</style>

<div class="row">

    <div class="col-xl-12 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0"> Agent Registration <div class="fetch"></div></h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
				
                    <form method="post" action="<?php echo base_url() ?>pancard/pancard/register_agent">
						
						<div class="row">

							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" > First Name  *</label>
                                        <input type="text" name="name" id="fname" class="form-control" value="" placeholder="Enter Your First Name" required>
                                </div>
                            </div>
							

							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Agent Id *</label>
                                        <input type="text" name="agent_id" class="form-control" value="<?php echo $this->session->userdata('member_id') ?>" readonly required >
                                </div>
                            </div>


							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Mobile Number *</label>
                                        <input type="text"  name="mobileNo" class="form-control" placeholder="Enter Your MObile Number" required >
                                </div>
                            </div>
							

							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Email Address *</label>
                                        <input type="email" name="email" class="form-control"  placeholder="Enter Your Email ID" required >
                                </div>
                            </div>

							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Company Name *</label>
                                        <input type="text" name="company" id="companyName" class="form-control" value="" placeholder="Enter Your Company Name/Shop Name" required >
                                </div>
                            </div>
							
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" >Pincode *</label>
                                        <input type="text" name="pincode" class="form-control" placeholder="Enter Your pincode" required >
                                </div>
                            </div>

					
							<div class="col-lg-6">
								<div class="form-group">
									<label class="form-control-label" >Address *</label>
										<input type="text" name="address" id="AddressName" class="form-control" placeholder="Enter Your Address" required >
								</div>
                            </div>
					
								<div class="col-lg-6">
									<div class="form-group">
										<label for="state">State *</label>
								
										<select class="form-control select" placeholder="State" name="state" value=""
											onkeyup="this.value = this.value.toUpperCase();" onblur="this.value = this.value.toUpperCase();" required>

											<option value=""> Select State </option>
											<option value="1">ANDAMAN AND NICOBAR ISLANDS</option>
											<option value="2">ANDHRA PRADESH</option>
											<option value="3">ARUNACHAL PRADESH</option>
											<option value="4">ASSAM</option>
											<option value="5">BIHAR</option>
											<option value="6">CHANDIGARH</option>
											<option value="33">CHHATTISGARH</option>
											<option value="7">DADRA AND NAGAR HAVELI</option>
											<option value="8">DAMAN AND DIU</option>
											<option value="9">DELHI</option>
											<option value="10">GOA</option>
											<option value="11">GUJARAT</option>
											<option value="12">HARYANA</option>
											<option value="13">HIMACHAL PRADESH</option>
											<option value="14">JAMMU AND KASHMIR</option>
											<option value="35">JHARKHAND</option>
											<option value="15">KARNATAKA</option>
											<option value="16">KERALA</option>
											<option value="17">LAKSHADWEEP</option>
											<option value="18">MADHYA PRADESH</option>
											<option value="19">MAHARASHTRA</option>
											<option value="20">MANIPUR</option>
											<option value="21">MEGHALAYA</option>
											<option value="22">MIZORAM</option>
											<option value="23">NAGALAND</option>
											<option value="24">ODISHA</option>
											<option value="99">OTHER</option>
											<option value="25">PONDICHERRY</option>
											<option value="26">PUNJAB</option>
											<option value="27">RAJASTHAN</option>
											<option value="28">SIKKIM</option>
											<option value="29">TAMILNADU</option>
											<option value="36">TELANGANA</option>
											<option value="30">TRIPURA</option>
											<option value="31">UTTAR PRADESH</option>
											<option value="34">UTTARAKHAND</option>
											<option value="32">WEST BENGAL</option>
					
					
										</select>
										
									</div>
								</div>
								
								<div class="col-lg-6">
									<div class="form-group">
										<label class="form-control-label" >Aadhar Card No. *</label>
											<input type="number" name="aadharNO" id="aadharNO" class="form-control"  placeholder="Enter Your Aadhar Card Number" required >
									</div>
                            	</div>

								<div class="col-lg-6">
									<div class="form-group">
										<label class="form-control-label" >Pan Card No. *</label>
											<input type="text" name="panNo" id="panno" class="form-control" placeholder="Enter Your Pancard Number" required >
									</div>
                            	</div>



							</div>
					
							<div class="form-group d-flex justify-content-center">
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
								<button type="submit" class="btn btn-success">Register</button>
							</div>
						
						</div>	
					</form>

            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('button[type="submit"]').attr('disabled', 'true'); 
        
		$('#panno').change(function (event) {     

			var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/; 
			var txtpan = $(this).val(); 
			if (txtpan.length == 10 ) { 
    			if( txtpan.match(regExp) ){ 
    				$('button[type="submit"]').removeAttr('disabled');
    			}
    			else {
        			alert('Not a valid PAN number');
        			$('button[type="submit"]').attr('disabled', 'true'); 
        			event.preventDefault(); 
    			} 
			} 
			else { 
				alert('Please enter 10 digits for a valid PAN number');
				event.preventDefault();  
				$('button[type="submit"]').attr('disabled', 'true'); 
			} 

		});
            
        $('#aadharNO').change(function (event) { 
            
             var aadhar = document.getElementById("aadharNO").value;
                var adharcardTwelveDigit = /^\d{12}$/;
                var adharSixteenDigit = /^\d{16}$/;
                if (aadhar != '') {
                    if (aadhar.match(adharcardTwelveDigit)) {
                        
                        $('button[type="submit"]').removeAttr('disabled'); 
                        
                    }
                    else if (aadhar.match(adharSixteenDigit)) {
                        
                        $('button[type="submit"]').removeAttr('disabled');
                        
                    }
                    else {
                        alert("Enter valid Aadhar Number");
                        $('button[type="submit"]').attr('disabled', 'true'); 
                    }
                }
        });
        
        $('#fname').change(function (event) {
            if ($('#fname').val().indexOf(' ')>=0) {
                alert("Please Enter First Name Only");
                $('#fname').addClass("error"); 
                $('button[type="submit"]').attr('disabled', 'true'); 
            }else{
                $('#fname').removeClass("error");  
                $('button[type="submit"]').removeAttr('disabled');
            }
        });
        
        $('#companyName').change(function (event) {
            if ($('#companyName').val().indexOf(' ')>=0) {
                alert("Please Enter Company First Name Only");
                $('#companyName').addClass("error"); 
                $('button[type="submit"]').attr('disabled', 'true'); 
            }else{
                $('#companyName').removeClass("error");  
                $('button[type="submit"]').removeAttr('disabled');
            }
        });
        
        $('#AddressName').change(function (event) {
            if ($('#AddressName').val().indexOf(' ')>=0) {
                alert("Please Enter Single Address Name");
                $('#AddressName').addClass("error"); 
                $('button[type="submit"]').attr('disabled', 'true'); 
            }else{
                $('#AddressName').removeClass("error");  
                $('button[type="submit"]').removeAttr('disabled');
            }
        });


        
	});

</script>