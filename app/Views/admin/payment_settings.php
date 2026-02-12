<?= $this->extend('layouts/dashboard'); ?>

<?= $this->section('sidebar'); ?>
    <?= $this->include('admin/sidebar'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">Payment Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payment Settings</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Midtrans Configuration</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/settings/update-payment'); ?>" method="post">
                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Mode</label>
                        <div class="col-lg-9">
                            <select class="form-control" name="settings[midtrans_mode]">
                                <option value="sandbox" <?= (isset($settings['midtrans_mode']) && $settings['midtrans_mode'] == 'sandbox') ? 'selected' : ''; ?>>Sandbox</option>
                                <option value="production" <?= (isset($settings['midtrans_mode']) && $settings['midtrans_mode'] == 'production') ? 'selected' : ''; ?>>Production</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Merchant ID</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="settings[midtrans_merchant_id]" value="<?= $settings['midtrans_merchant_id'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Client Key</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="settings[midtrans_client_key]" value="<?= $settings['midtrans_client_key'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Server Key</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="password" class="form-control" id="server_key" name="settings[midtrans_server_key]" value="<?= $settings['midtrans_server_key'] ?? ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('server_key')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Instructions</h4>
            </div>
            <div class="card-body">
                <p>To configure Midtrans payment gateway:</p>
                <ol>
                    <li>Register at <a href="https://midtrans.com" target="_blank">Midtrans</a>.</li>
                    <li>Go to Settings > Access Keys to get your Client Key and Server Key.</li>
                    <li>Select "Sandbox" for testing and "Production" for live payments.</li>
                    <li>Ensure you have set the Notification URL in Midtrans Dashboard to: <br> <code><?= base_url('payment/notification'); ?></code></li>
                </ol>
                <div class="alert alert-info">
                     Note: Payments made via Midtrans will automatically have a service fee deducted from the Admin revenue in reports.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>

<?= $this->endSection(); ?>
