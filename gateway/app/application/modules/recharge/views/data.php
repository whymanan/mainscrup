 <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel"
                                aria-labelledby="tabs-icons-text-1-tab">
                                <?php foreach($data as $row){?>
                                <div class="row">
                                     <div class="col-sm-4"> Amount :<?php echo $row->amount ?></div>
                               <div class="col-sm-4"> Talktime : <?php echo $row->talktime ?></div>
                                 <div class="col-sm-4"> Validity : <?php echo $row->validity ?></div>
                                </div> <div class="row">
                               <div class="col-sm-12"> <p> Detail : <?php echo $row->detail ?></p></div>
                               </div>
                               <hr>
                                <?php }?>
                            </div>
                            
                        </div>
                    </div>
                </div>