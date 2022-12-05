<div class="row">

    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Loan Bill Pay</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form name="validate" role="form" action="<?php echo base_url('recharge/bill_submit'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                   



                   
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Service Provider</label>
                            <select name="operator" class="form-control">
                                <option value="">AU Small Finance Bank Limited</option>
                                <option value="">Aadhar Housing Finance Ltd.</option>
                                <option value="">Aavas Financiers Limited</option>
                                <option value="">Achiievers Gold Loan</option>
                                <option value="">Adani Capital Private Limited</option>
                                <option value="">Adani Housing Finance</option>
                                <option value="">Aditya Birla Capital</option>
                                <option value="">Aditya Birla Finance Limited</option>
                                <option value="">Aditya Birla Housing Finance Limited</option>
                                <option value="">Aeon Credit </option>
                                <option value="">Agora Microfinance India Ltd</option>
                                <option value="">Altum Credo Home Finance</option>
                                <option value="">Annapurna Finance Private Limited </option>
                                <option value="">Aptus Finance India Private Limited </option>
                                <option value="">Arohan Financial Services Limited </option>
                                <option value="">Ascend Capital </option>
                                <option value="">Avanse Financial Services Ltd </option>
                                <option value="">Axis Bank Limited - Retail Loan</option>
                                <option value="">Axis Finance Limited</option>
                                <option value="">Ayaan Finserve India Private LTD </option>
                                <option value="">BERAR Finance Limited</option>
                                <option value="">Baid Leasing and Finance</option>
                                <option value="">Bajaj Auto Finance</option>
                                <option value="">Bajaj Finance Limited</option>
                                <option value="">Bajaj Housing Finance Ltd</option>
                                <option value="">Bharat Financial Inclusion</option>
                                <option value="">Bussan Auto Finance</option>
                                <option value="">Capital Float</option>
                                <option value="">Capri Global Capital Limited</option>
                                <option value="">Cars24 Financial Services Private Limited</option>
                                <option value="">Centrum Microcredit Limited</option>
                                <option value="">Chaitanya India Fin Credit Pvt Ltd</option>





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
                            <th scope="col">#</th>
                               <th scope="col">Trn id</th>
                            <th scope="col">Details</th>
                             <th scope="col">Transition Mobile</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                             <th scope="col">Created At</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>