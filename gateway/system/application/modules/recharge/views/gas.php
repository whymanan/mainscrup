<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">GAS Bill Pay</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('service/submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                   



                   
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                            <select name="operator" class="form-control">
                                <option value="AVTG">Aavantika Gas Ltd	</option>
                                <option value="ASSG">Assam Gas Company Limited	</option>
                                <option value="BHAG">Bhagyanagar Gas Limited	</option>
                                <option value="CUGL">Central U.P. Gas Limited	</option>
                                <option value="CGSM">Charotar Gas Sahakari Mandali Ltd	</option>
                                <option value="GAIG">GAIL Gas Limited	</option>
                                <option value="GREG">Green Gas Limited(GGL)		</option>
                                <option value="GUJG">Gujarat Gas Limited		</option>
                                <option value="HARG">Haryana City Gas	</option>
                                <option value="INAG">Indian Oil-Adani Gas Private Limited		</option>
                                 <option value="INPG">Indraprastha Gas Limited	</option>
                                <option value="IRMG">IRM Energy Private Limited		</option>
                                <option value="MEGG">Megha Gas		</option>
                                <option value="NAVG">Naveriya Gas Pvt Ltd		</option>
                                <option value="SABG">Sabarmati Gas Limited (SGL)	</option>
                                 <option value="TGML">Torrent Gas Moradabad Limited	</option>
                                <option value="TRIG">Tripura Natural Gas	</option>
                                <option value="UCPG">Unique Central Piped Gases Pvt Ltd (UCPGPL)		</option>
                               
                            </select>
                            </div>
                        </div>
                    </div>
                    
                     <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Customer No/Account No</label>
                                <input type="text" name="account" id="amount" class="form-control clear" required>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control clear" required>
                            </div>
                        </div>
                    </div>



                    <div class="text-center" id="submit">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="btn btn-primary my-4" id="submit_btn">Save</button>
                    </div>

                   
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-8 order-xl-2">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Transaction List</h3>
                    </div>

                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush" id="slablist">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Trn id</th>
                            <th scope="col">Details</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>