<link rel="stylesheet" href="<?php echo base_url('assets/js/components/Dropify/jQuery-Plugin-To-Beautify-File-Inputs-with-Custom-Styles-Dropify/dist/css/')?>dropify.min.css">
<link rel="stylesheet" href="<?php echo base_url('assets/js/components/Dropify/jQuery-Plugin-To-Beautify-File-Inputs-with-Custom-Styles-Dropify/dist/css/')?>/demo.css">
<style>
.text-muted
{
    display:none;
}
</style>
<div class="row">

    
    <div class="col-xl-8 order-xl-2">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Add Slider</h3>
                    </div>
                   
                </div>
            </div>
            <div style="margin:20px;">
            <form id="upload" action="<?php echo base_url('/slideradd')?>" enctype="multipart/form-data" method="post">
                    <input type="file" id="input-file-now" class="dropify" name="file" />
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="submit" class="btn btn-primary my-4" value="submit">
             </form>
             </div>
        </div>
    </div>
    <div class="col-xl-4 order-xl-1">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Slider List</h3>
                    </div>
                    <div class="col-4 text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush" id="transectionlist">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="thead-light">
                            <?php if(isset($slider)){ foreach($slider as $value){?> 
                              <tr>
                                <td scope="col" ><img style="height:50px !important;width:100px !important" src="<?php echo base_url('assets/img/slide/'.$value['slider']);?>"></td>
                                <td scope="col"><a href="<?php echo base_url('/edit/'.$value['id'])?>"> <button type="button" class="btn btn-sm btn-secondary" data-placement="bottom" title="Edit Menu Information"><i class="fa fa-pencil-alt"></i></button></a>

             <a href="<?php echo base_url('/delete/'.$value['id'])?>"><button type="button" class="btn btn-sm btn-primary" data-placement="bottom"  title="Delete Menu Information"><i class="fa fa-trash-alt"></i></button></a></td>
                            </tr>
                            <?php }}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?php echo base_url('assets/js/components/Dropify/jQuery-Plugin-To-Beautify-File-Inputs-with-Custom-Styles-Dropify/')?>dist/js/dropify.min.js"></script>
<script>
            $(document).ready(function(){
                // Basic
                $('.dropify').dropify();
                $('#transectionlist').Datatable();

            });
        </script>