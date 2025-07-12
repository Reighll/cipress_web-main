<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
Owner Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">HI, <?= esc(strtoupper(session()->get('owner_username') ?? 'OWNER')) ?></h2>
    </div>
</div>

<!-- Display success or error messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">STAFF REGISTRATIONS</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>NO.</th>
                            <th>USER</th>
                            <th class="text-center">STATUS / ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($staff_for_review)): ?>
                            <?php foreach ($staff_for_review as $index => $staff): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($staff['staff_firstname'] . ' ' . $staff['staff_lastname']) ?> (<?= esc($staff['staff_username']) ?>)</td>
                                    <td class="text-center">
                                        <?php if ($staff['staff_status'] === 'pending'): ?>
                                            <!-- Actions for pending staff -->
                                            <a href="<?= site_url('owner/staff/approve/' . $staff['staff_id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this staff member?')">Approve</a>
                                            <a href="<?= site_url('owner/staff/decline/' . $staff['staff_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to decline this staff member?')">Decline</a>

                                        <?php elseif ($staff['staff_status'] === 'declined'): ?>
                                            <!-- Status and action for declined staff -->
                                            <span class="badge badge-danger mr-2">Declined</span>
                                            <a href="<?= site_url('owner/staff/approve/' . $staff['staff_id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('This staff member was previously declined. Are you sure you want to approve them now?')">Approve</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No staff registrations to review.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Your System Key</h4>
                <p class="card-description">Provide this key to new owners for registration.</p>
                <div class="form-group d-flex">
                    <input type="text" class="form-control form-control-lg mr-2" id="system-key-input" value="<?= esc($owner['owner_systemkey'] ?? 'No key found') ?>" readonly>
                    <button type="button" class="btn btn-secondary" id="copy-key-btn" title="Copy to Clipboard">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyBtn = document.getElementById('copy-key-btn');
        const keyInput = document.getElementById('system-key-input');

        copyBtn.addEventListener('click', function() {
            if (!keyInput.value || keyInput.value === 'No key found') {
                alert("No key available to copy.");
                return;
            }

            const textArea = document.createElement("textarea");
            textArea.value = keyInput.value;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                alert("System Key copied to clipboard!");
            } catch (err) {
                console.error('Failed to copy text: ', err);
                alert("Failed to copy key.");
            }
            document.body.removeChild(textArea);
        });
    });
</script>
<?= $this->endSection() ?>
