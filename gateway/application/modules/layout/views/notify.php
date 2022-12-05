<script type="text/javascript">
$(document).ready(function(){
            
            
        // document.onkeydown = function(e) {
        //     if(event.keyCode == 123) {
        //         return false;
        //     }
        //     if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
        //         return false;
        //     }
        //     if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
        //         return false;
        //     }
        //     if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
        //       return false;
        //     }
        //     if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
        //       return false;
        //     }
        // }
        
        
        // if (document.addEventListener) {
        //     document.addEventListener('contextmenu', function(e) {
        //         // mouse block
        //       e.preventDefault();
        //     }, false);
        // } else {
        //     document.attachEvent('oncontextmenu', function() {
        //       window.event.returnValue = false;
        //     });
        // }
        
      <?php if ($this->session->flashdata()):?>
        <?php if ($this->session->flashdata('error')): ?>
          Swal.fire({
            position: 'top-end',
            type: 'error',
            title: '<?php echo $this->session->flashdata('msg') ?>',
            showConfirmButton: true,
            // timer: 3500
          });
        <?php  elseif($this->session->flashdata('error') == 2): ?>
            Swal.fire({
              position: 'top-end',
              type: 'warning',
              title: '<?php echo $this->session->flashdata('msg') ?>',
              showConfirmButton: true,
            //   timer: 3500
            });
        <?php else: ?>
          Swal.fire({
            position: 'top-end',
            type: 'success',
            title: '<?php echo $this->session->flashdata('msg') ?>',
            showConfirmButton: true,
            // timer: 3500
          });
          <?php  unset($_SESSION['msg'])?>
        <?php endif; ?>
      <?php endif; ?>
});
</script>
