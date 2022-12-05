<form>
    <h4 class="heading-small text-muted mb-4">Bio Metric information</h4>
    <div class="pl-lg-4">
        <div class="row">
          <div class="col-lg-6">
              <div class="form-group">
                  <label class="form-control-label">Select Biometric Device</label>
                  <select name="device-select" id="device-select" class="form-control" required>
                    <option>Select Device</option>
                    <option value="mantra-mfs-100">Mantra MFS100</option>
                  </select>
                </div>
          </div>
        </div>
    </div>
    <hr class="my-4" />
    <div class="text-center">
        <button type="button" name="bioMetricCapture" id="bioMetricCapture" class="btn btn-primary my-4" disabled>Capture</button>
    </div>
</form>
