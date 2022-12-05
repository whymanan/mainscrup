<div class="card card-body">
  <form method="post" id="filter">
    <div class="form-row">
      <div class="col-2">
        <input type="date"  id="date_from" name="date_from" class="form-control form-control-sm" value="<?php echo date('Y-04-01') ?>">
      </div>
      <div class="col-2">
        <input type="date" id="date_to" name="date_to" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
      </div>
      <div class="col-2">
        <select id="searchByCat" name="searchByCat" class="form-control form-control-sm" required>
          <option value="">-- Select Category --</option>
          <option value="member_id">MEMBER ID</option>
          <option value="parent">PARENT</option>
          <option value="email">EMAIL</option>
          <option value="phone">PHONE</option>
          <option value="roles_id">User Type</option>
        </select>
      </div>

      <div class="col-2">
        <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search" required>
      </div>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
      <button id='simplefilter' class="btn btn-primary btn-xs"> <i class="fas fa-search"></i> Search</button>
      <button id='clear' class="btn btn-primary btn-xs"> <i class="fas fa-eraser"></i> Clear</button>
    </div>
  </form>
</div>
