<style>
    .carousel
    {
        border: 8px solid white;
       margin-bottom:25px;
    }
    .pz
    {
        padding:0px;
    }
    .status{
        display:flex;
        justify-content: space-around;
        margin-bottom: 25px;
    }
    .status_c{
            background-color: white;
            padding-top: 10px;
            box-shadow: 3px 7px 10px 0px;
           }
            @media only screen and (min-width: 768px)
{
    .card-body{
            height : 12rem;
          }
}
</style>
<div class="slide">
    <marquee style="color:white">Micro ATM available at only 2999 Rupees.</marquee>
</div>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
      <?php  $i=0; foreach($slider as $value){?>
      <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i;?>" class="<?php if($i==0){ echo "active";}?>"></li>
      <?php $i++;?>
      <?php }?>
      
    <!--<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>-->
    <!--<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>-->
    <!--<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>-->
  </ol>
  <div class="carousel-inner">
     <?php $i=0; foreach($slider as $value){ ?>
     <div class="carousel-item <?php if($i==0){ echo "active";}?>">
      <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/'.$value['slider'];?>" alt="First slide">
    </div>
    <?php $i++;}?>
    <!--<div class="carousel-item active">-->
    <!--  <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/transfer-banner.png';?>" alt="First slide">-->
    <!--</div>-->
    <!--<div class="carousel-item">-->
    <!--  <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/services.png'?>" alt="Second slide">-->
    <!--</div>-->
    <!--<div class="carousel-item">-->
    <!--  <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/mt.png'?>" alt="Third slide">-->
    <!--</div>-->
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


    <div class="row status">
        <div class="col-4 status_c">
            <div class="">
                <div class="row no-gutters">
                    <div class="col-5 pz">
                    <h4> AEPS Bussiness Today:</h4>
                    </div>
                    <div class="col-4 pz">
                        <?php echo $status_amount['today_amount'];?>/-
                    </div>
                    <div class="col-3 pz">
                       <?php echo round($status_amount['percent'],1);?>%
                       <?php if($status_amount['current_status']=='up'){?>
                       <i class="fa fa-long-arrow-alt-up"></i>
                       <?php }elseif($status_amount['current_status']=='down'){?><i class="fa fa-long-arrow-alt-down"></i>
                       <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
        <div class="col-4 status_c">
            <div class="">
                <div class="row no-gutters">
                    <div class="col-5 pz">
                    <h4> AEPS Earning Today:</h4>
                    </div>
                    <div class="col-4 pz">
                        <?php echo $status_amount['today_amount'];?>/-
                    </div>
                    <div class="col-3 pz">
                       <?php echo round($status_amount['percent'],1);?>%
                       <?php if($status_amount['current_status']=='up'){?>
                       <i class="fa fa-long-arrow-alt-up"></i>
                       <?php }elseif($status_amount['current_status']=='down'){?><i class="fa fa-long-arrow-alt-down"></i>
                       <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <!--<div class="row">-->
 <!--    <?php foreach($services as $row){?>-->
 <!--    <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4" >-->
 <!--        <div class="card " >-->
            
 <!--            <div class="card-body text-center" style="height : 12rem">-->
 <!--                <a href="<?php echo base_url($row['url'])?>"><img-->
 <!--                        src="<?php echo base_url('assets').'/img/services/'.$row['id'].'.png';?>" width="50%"-->
 <!--                        alt=""></a>-->
 <!--                   <p><?php echo $row['name']; ?></p>-->
                         
                         
 <!--            </div>-->
             
            
 <!--        </div>-->
 <!--    </div>-->
 <!--    <?php } ?>-->
 <!--</div>-->
 <h1>Banking services</h1>
 <div class="row">
     <!--<div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">-->
     <!--    <div class="card ">-->
            
     <!--        <div class="card-body text-center">-->
     <!--            <a href="<?php echo base_url('aeps2')?>"><img src="<?php echo base_url('assets/img/services/1.png')?>" width="50%" alt=""></a>-->
     <!--               <p>SBM AEPS</p>-->
                         
                         
     <!--        </div>-->
             
            
     <!--    </div>-->
     <!--</div>-->
         <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('aeps2')?>"><img src="<?php echo base_url('assets/img/services/1.png')?>" width="60%" alt=""></a>
                    <p>AEPS</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('aeps2/aadharpay')?>"><img src="<?php echo base_url('assets/img/services/4.png')?>" width="50%" alt=""></a>
                    <p>AdharPay</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('dmtv2')?>"><img src="<?php echo base_url('assets/img/services/7.png')?>" width="50%" alt=""></a>
                    <p>DMT</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('')?>"><img src="<?php echo base_url('assets/img/services/8.png')?>" width="50%" alt=""></a>
                    <p>Micro ATM</p>
                         
                         
             </div>
             
            
         </div>
     </div>
</div>

<h1>Bill Payment and Recharges</h1>
<div class="row">
       <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/dth')?>"><img src="<?php echo base_url('assets/')?>img/services/9.png" width="50%" alt=""></a>
                    <p>DTH</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/mobile')?>"><img src="<?php echo base_url('assets/')?>img/services/13.png" width="50%" alt=""></a>
                    <p>Recharge</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/datacard')?>"><img src="<?php echo base_url('assets/')?>img/services/14.png" width="50%" alt=""></a>
                    <p>Data Card</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/landline')?>"><img src="<?php echo base_url('assets/')?>img/services/16.png" width="50%" alt=""></a>
                    <p>Landlline</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/electricity')?>"><img src="<?php echo base_url('assets/')?>img/services/18.png" width="50%" alt=""></a>
                    <p>Electricity</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/water')?>"><img src="<?php echo base_url('assets/')?>img/services/19.png" width="50%" alt=""></a>
                    <p>Water</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/insurance');?>"><img src="<?php echo base_url('assets/')?>img/services/20.png" width="50%" alt=""></a>
                    <p>Insurance</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/fastag');?>"><img src="<?php echo base_url('assets/')?>img/services/21.png" width="50%" alt=""></a>
                    <p>Fastag</p>
                         
                         
             </div>
             
            
         </div>
     </div>
          <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/gas');?>"><img src="<?php echo base_url('assets/')?>img/services/22.png" width="50%" alt=""></a>
                    <p>GAS</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/emi');?>"><img src="<?php echo base_url('assets/')?>img/services/41.png" width="94%" alt=""></a>
                    <p>EMI</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/broadband');?>"><img src="<?php echo base_url('assets/')?>img/services/42.png" width="50%" alt=""></a>
                    <p>Broadband</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/cable');?>"><img src="<?php echo base_url('assets/')?>img/services/43.png" width="70%" alt=""></a>
                    <p>Cable</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/challan');?>"><img src="<?php echo base_url('assets/')?>img/services/44.png" width="70%" alt=""></a>
                    <p>Traffic Challan</p>
                         
                         
             </div>
             
            
         </div>
     </div>
      <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/municipaltax');?>"><img src="<?php echo base_url('assets/')?>img/services/45.png" width="66%" alt=""></a>
                    <p>Municipal Taxes</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/municipality');?>"><img src="<?php echo base_url('assets/')?>img/services/46.png" width="66%" alt=""></a>
                    <p>Municipality</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('recharge/hospital');?>"><img src="<?php echo base_url('assets/')?>img/services/47.png" width="66%" alt=""></a>
                    <p>Hospital</p>
                         
                         
             </div>
             
            
         </div>
     </div>
</div>
<h1>Legal Services</h1>
<div class="row">
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('legalservice/LegalController/')?>"><img src="<?php echo base_url('assets/img/services/gst.png')?>" style="height:80% !important" alt="" class="img-fluid"></a>
                    <p>GST Registration</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('legal/gst/return')?>" ><img style="height:80% !important" src="<?php echo base_url('assets/img/services/gst1.png')?>" height="60%" alt="" class="img-fluid"></a>
                    <p>GST Return</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/gst.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>GST Amendmet</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/gst1.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>GST Surrender</p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="<?php echo base_url('legal/gst/company_registation');?>"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/company.png')?>" style="height:80% !important" alt="" class="img-fluid"></a>
                    <p>Company Registration</p>
             </div>
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/45.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>Income TAX Returns -Bussiness</p>
             </div>
         </div>
     </div>        
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/45.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>Income TAX Return- Salary</p>
             </div>
         </div>
     </div>        
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/MSME.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>MSME Registration</p>
             </div>
         </div>
     </div>        
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/ebill.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>E way Bill</p>
             </div>
         </div>
     </div>        
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/balancesheet.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>Projected Balance Sheet</p>
             </div>
         </div>
     </div>        
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/director.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>Add Directors</p>
             </div>
         </div>
     </div>         
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/tally.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>Accounting In Tally</p>
             </div>
         </div>
     </div>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4">
         <div class="card ">
            
             <div class="card-body text-center">
                 <a href="javascript:void(0)" title="Comming Sooner......"><img style="height:80% !important" src="<?php echo base_url('assets/img/services/14.png')?>" height="80%" alt="" class="img-fluid"></a>
                    <p>DSC Token</p>
             </div>
         </div>
     </div> 
</div>