<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
Account Settings
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">ACCOUNT SETTINGS</h2>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="password-tab" data-toggle="tab" href="#changePassword" role="tab" aria-controls="changePassword" aria-selected="true">Change Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="username-tab" data-toggle="tab" href="#changeUsername" role="tab" aria-controls="changeUsername" aria-selected="false">Change Username</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="changePassword" role="tabpanel" aria-labelledby="password-tab">
                        <form action="<?= site_url('owner/settings/update-password') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="current-password">Current Password</label>
                                <input type="password" class="form-control" id="current-password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new-password">New Password</label>
                                <input type="password" class="form-control" id="new-password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Update Password</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="changeUsername" role="tabpanel" aria-labelledby="username-tab">
                        <form action="<?= site_url('owner/settings/update-username') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="new-username">New Username</label>
                                <input type="text" class="form-control" id="new-username" name="new_username" required value="<?= old('new_username') ?>">
                            </div>
                            <div class="form-group">
                                <label for="password-for-username">Confirm with Password</label>
                                <input type="password" class="form-control" id="password-for-username" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Update Username</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
