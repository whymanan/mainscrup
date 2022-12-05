
text/x-generic invoice.php ( HTML document, ASCII text )
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
if($fetch_invoice_details->num_rows() > 0)
  { 
  $i= 1;
 foreach($fetch_invoice_details->result() as $row)
 {
$case_id = $row->case_id;
$c_date=$row->c_date;
}
}

if($fetch_u_details->num_rows() > 0)
  { 
  $i= 1;
 foreach($fetch_u_details->result() as $row)
 {
$organisation = $row->organisation;
$address=$row->address;
}
}
?>
<div class="container-fluid mt-5">
    <div class="d-flex justify-content-center row">
        <div class="col-md-12">
              <div class="row">
                        <div class="col-md-12 text-center">


                                               <h4 class="">Reciept</h4>
                    </div>
                    
                </div>
            <div class="p-3 bg-white rounded border">
                
                <div class="row">
                    <div class="col-md-6">
                          <img src="<?php echo base_url('optimum/click.png'); ?>" alt="Thank You"  style="width:150px;">
                    </div>
                    <div class="col-md-6 text-right mt-3">

                        <div class="billed"><span class="font-weight-bold text-uppercase"></span><h4 class="ml-1 font-weight-bold">Click India Digital Services Pvt Ltd</h4></div>
                        <div class="billed"><span class="font-weight-bold text-uppercase"></span><span class="ml-1">Phone no.: 9792355052<br> Email: info@clickindiadigital.com</span></div>
                        <div class="billed"><span class="font-weight-bold text-uppercase"></span><span class="ml-1">State: 09-Uttar Pradesh</span></div>
                    </div>
                </div>

                  <div class="row">
                        <div class="col-md-12 text-center">
<hr>
<span><i class="fa fa-check text-success" style="margin-left:300px;font-size:50px;" ></i></span>

                                               <h4 class="text-success">Registration Successfull</h4>
                    </div>
                    
                </div>
                
                <div class="mt-3 row">
                    <div class="col-md-6">
                         <div class="table-responsive">
                        <table class="table">
                            <thead>

                            </thead>
                            <tbody>
                                                                <tr>
                                    <td class="border">Sl No.</td>
                                    <td class="border">Service</td>
                                    <td class="border">Qty</td>
                                    <td>RefNo,:<?php echo $case_id; ?></td>
                                </tr>
                                <tr>
                                    <td class="border">1</td>
                                    <td class="border">GST Registration</td>
                                    <td class="border">1</td>
                                    <td> <br>Shop Name:<?php echo $organisation; ?><br>Address: <?php  echo $address; ?><br> Applied Date:<?php echo $c_date; ?><br> Expected Date:</td>
                                </tr>
                              
                            </tbody>
                        </table>
                    </div>
                   
                        
                    </div>
                    <div class="col-md-6">
                        
                        
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <hr>
                                              <a href="https://www.vitefintech.in/">
                                                  <img src="<?php echo base_url('optimum/Black.png'); ?>" class="navbar-brand-img" alt="..." class="img-responsive" style="width:80px;">
                                                  <h6 class="text-primary">Powered By: Vite fintech Private Limited<br>www.vitefintech.in</h6><br><h6></h6></a>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>