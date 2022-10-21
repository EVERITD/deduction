<div id="new-vendor-assignment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>New Site Group Vendors</h3>
  </div>
  <div class="modal-body">
      <form name="frmNewVendors" action="" method="post" class="form-horizontal">

        <div class="control-group">
          <label for="cmbMainSite" class="control-label">Main Site</label>
          <div class="controls">
            <select name="cmbMainSite" id="cmbMainSite">
              <?php foreach($siteGroups as $k => $sg): ?>
                <option value="<?php echo $sg; ?>"><?php echo $sg; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="control-group">
          <label for="txtVendors" class="control-label">Vendors</label>
          <div class="controls">
            <input type="text" name="txtVendors" id="txtVendors">
          </div>
        </div>

      </form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <a href="#" class="btn btn-primary" id="save-new-vendor">Save changes</a>
  </div>
</div>
