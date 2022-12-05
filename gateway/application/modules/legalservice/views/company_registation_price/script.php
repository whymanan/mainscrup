
<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>

<script type="text/javascript">
    function Delete(id) {

    var sureDel = confirm("Are you sure want to delete");
    if (sureDel == true) {
      $.ajax({
        type: "GET",
        url: "<?php echo base_url('legalservice/LegalController/company_price_delete/')?>?id="+id,

        success: function(response) {
          if (response == 1) {
            // $('#squadlist').DataTable().ajax.reload();

            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'Deleted Successfully',
              showConfirmButton: false,
              timer: 3500
              
            });
          } else {
            Swal.fire({
              position: 'top-end',
              type: 'error',
              title: 'Something went wrong',
              showConfirmButton: false,
              timer: 3500
            });
          }
         location.reload()
        }
      });

    }

  }


//  function Edit(id) {
//   var $addCommissionForm = $('#addCommissionForm');
//     $('#menu_id').val(id);
//     var sureDel = confirm("Are you sure want to edit");
//     if (sureDel == true) {
//       $.ajax({
//         type: "GET",
//         url: "<?php echo base_url('legalservice/LegalController/company_price_delete/') ?>?id=" + id,
//         success: function(response) {
               
//           }
//       });

//     }
    
    

//   }
</script>
