<script type="text/javascript">
$(document).ready(function(){
      <?php if ($this->session->flashdata()):?>
        <?php if ($this->session->flashdata('error')): ?>
          Swal.fire({
            position: 'top-end',
            type: 'error',
            title: '<?php echo $this->session->flashdata('msg') ?>',
            showConfirmButton: false,
            timer: 3500
          });
        <?php  elseif($this->session->flashdata('error') == 2): ?>
            Swal.fire({
              position: 'top-end',
              type: 'warning',
              title: '<?php echo $this->session->flashdata('msg') ?>',
              showConfirmButton: false,
              timer: 3500
            });
        <?php else: ?>
          Swal.fire({
            position: 'top-end',
            type: 'success',
            title: '<?php echo $this->session->flashdata('msg') ?>',
            showConfirmButton: false,
            timer: 3500
          });
        <?php endif; ?>
      <?php endif; ?>
});
</script>
