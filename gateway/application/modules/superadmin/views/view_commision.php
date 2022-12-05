<div class="row">
         <div class="col-xl-12">
             <div class="card">
                 <div class="card-header border-0">
                     <div class="row align-items-center">
                         <div class="col">
                             <h3 class="mb-0">New Join</h3>
                         </div>
                         <div class="col text-right">
                             <a href="#!" id="notify" class="btn btn-sm btn-primary">See all</a>
                         </div>
                     </div>
                 </div>
                 <div class="table-responsive">
                     <!-- Projects table -->
                     <table class="table align-items-center table-flush" id="squadlist">
                         <thead class="thead-light">
                             <tr>
                             <th scope="col">SLNO</th>
                             <th scope="col">Start Range</th>
             <th scope="col"> End range </th>
              <th scope="col"> Commission </th>
              <th scope="col"> Max Commission</th>
              <th scope="col"> Date</th>
              <th scope="col"> Edit</th>
              <th scope="col"> Delete</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php $i=1;
                             foreach($commision as $row){?>
                             <tr>
                             <td><?php echo $i?></td>
                             <td><?php echo $row['start_range']?></td>
                             <td><?php echo $row['end_range']?></td>
                             <td><?php echo $row['g_commission']?></td>
                             <td><?php echo $row['max_commission']?></td>
                             <td><?php echo $row['updated']?></td>
                             <?php  //base_url('superadmin/editCommission?q=') .echo $row['service_commission_id'] ?>
                             <td> <a
                                     href="<?php echo base_url() ?>superadmin/editCommission?q=<?php echo $row['service_commission_id'] ?>">
                                     <button type="button" class="btn btn-sm btn-secondary" data-placement="bottom"
                                         title="Edit Menu Information"><i class="fa fa-pencil-alt"></i></button></a>
                             </td>
                             <td>
                                     <button type="button" class="btn btn-sm btn-danger" data-placement="bottom"
                                         title="delete"><i class="fa fa-trash"></i></button>
                             </td></tr>
                             <?php $i++;} ?>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>