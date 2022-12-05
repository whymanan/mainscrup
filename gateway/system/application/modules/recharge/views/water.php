<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Water Bill Pay</h3>
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
                                <option value="BWSS">Bangalore Water Supply and Sewerage Board (BWSSB)	</option>
                                <option value="BMCW">Bhopal Municipal Corporation	</option>
                                <option value="DDAW">Delhi Development Authority (DDA)	</option>
                                <option value="DLBW">Delhi Jal Board	</option>
                                <option value="DPHE">Department of Public Health Engineering-Water, Mizoram	</option>
                                <option value="GWMC">Greater Warangal Municipal Corporation	</option>
                                <option value="GMCW">Gwalior Municipal Corporation	</option>
                                <option value="HUDA">Haryana Urban Development Authority	</option>
                                <option value="HMWS">Hyderabad Metropolitan Water Supply and Sewerage Board (HMWSSB)	</option>
                                <option value="IMCW">Indore Municipal Corporation	</option>

                                <option value="JMCW">Jabalpur Municipal Corporation		</option>
                                <option value="JVNN">Jalkal Vibhag Nagar Nigam Prayagraj		</option>
                                <option value="KWAW">Kerala Water Authority (KWA)		</option>
                                <option value="LWBW">Ludhiana Water Board	</option>
                                <option value="MPUN">Madhya Pradesh Urban (e-Nagarpalika)	</option>
                                <option value="MUCC">Municipal Corporation Chandigarh		</option>
                                <option value="MCJW">Municipal Corporation Jalandhar		</option>
                                <option value="MCAW">Municipal Corporation of Amritsar	</option>
                                <option value="MCGW">Municipal Corporation of Gurugram (MCG)		</option>
                                <option value="MCCW">Mysuru City Corporation	</option>
                                
                                <option value="NDMW">New Delhi Municipal Council (NDMC)		</option>
                                <option value="PBMC">Port Blair Municipal Council		</option>
                                <option value="PNMW">Pune Municipal Corporation	</option>
                                <option value="PMCW">Punjab Municipal Corporation/Council	</option>
                                <option value="RMCW">Ranchi Municipal Corporation		</option>
                                <option value="SMCW">Silvassa Municipal Council		</option>
                                <option value="SUMC">Surat Municipal Corporation		</option>
                                <option value="UITW">Urban Improvement Trust (UIT) - Bhiwadi		</option>
                               
                            </select>
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