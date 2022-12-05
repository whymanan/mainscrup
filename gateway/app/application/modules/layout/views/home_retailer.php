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
</style>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/transfer-banner.png';?>" alt="First slide">
    </div>
    <div class="carousel-item">
      <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/services.png'?>" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img style="height: 300px;" class="d-block w-100" src="<?php echo base_url('assets/').'img/slide/mt.png'?>" alt="Third slide">
    </div>
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
                       <?php echo $status_amount['percent'];?>%
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
                       <?php echo $status_amount['percent'];?>%
                       <?php if($status_amount['current_status']=='up'){?>
                       <i class="fa fa-long-arrow-alt-up"></i>
                       <?php }elseif($status_amount['current_status']=='down'){?><i class="fa fa-long-arrow-alt-down"></i>
                       <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <div class="row">
     <?php foreach($services as $row){?>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4" >
         <div class="card " >
            
             <div class="card-body text-center" style="height : 12rem">
                 <a href="<?php echo base_url($row['url'])?>"><img
                         src="<?php echo base_url('assets').'/img/services/'.$row['id'].'.png';?>" width="50%"
                         alt=""></a>
                    <p><?php echo $row['name']; ?></p>
                         
                         
             </div>
             
            
         </div>
     </div>
     <?php } ?>

 </div>