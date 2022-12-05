 <div class="row">
     <?php foreach($services as $row){?>
     <div class="col-sm-6 col-lg-3 col-xl-3 col-md-4" >
         <div class="card " >
            
             <div class="card-body text-center" style="height : 12rem">
                 <a href="<?php echo base_url($row['url'])?>"><img
                         src="<?php echo base_url('assets').'/img/services/'.$row['id'].'.png';?>" width="50%"
                         alt=""></a>
                         
                         
             </div>
             
            
         </div>
     </div>
     <?php } ?>

 </div>