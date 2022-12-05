
 <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel"
                                aria-labelledby="tabs-icons-text-1-tab">
                                <?php 
                                if(!isset($data[0]->error)){
                                   
                                foreach($data as $row){?>
                                <div class="row " >
                                    <input name="custom-radio-1" class="form-control-input plan" id="<?php echo $row->Amount ?>" data-price="<?php echo $row->Amount ?>" type="radio">
                                     <div class="col-sm-3"> Amount Rs:<?php echo $row->Amount ?></div>
                               <div class="col-sm-4"> PlanName : <?php echo $row->PlanName ?></div>
                                 <div class="col-sm-4"> Validity : <?php echo $row->Validity ?></div>
                                </div> <div class="row">
                               <div class="col-sm-12"> <p> Detail : <?php echo $row->Description ?></p></div>
                               </div>
                               <hr>
                                <?php }//loop for
                                }else {?>
                                <div class="row">
                                     <!--echo "inside else";exit;-->
                                    <div class="col-sm-4"><?php echo $data[0]->error ?></div>
                                 
                               </div>
                                
                                
                                <?php } ?>
                            </div>
                            
                        </div>
                    </div>
                </div>             
                
                <script type="text/javascript">
 $(document).ready(function() {

     
     
 $(body).on('click','.plan', function(){
      
      var amount = $(this).attr('data-price');
      console.log(amount);
     $("#amount").val(amount);
       $('#exampleModal').modal('hide');
    });
    
     
 });
 
    </script>