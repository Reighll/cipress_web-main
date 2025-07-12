<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Staff Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">STAFF MANAGEMENT</h2>
            <h4 class="card-subtitle mb-4 text-muted">ALL APPROVED USERS</h4>

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

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>NO.</th>
                        <th>USER</th>
                        <th>LAST CLOCK IN</th>
                        <th>LAST CLOCK OUT</th>
                        <th class="text-center">ACTIONS</th>
                    </tr>
                    </thead>
                    <tbody id="staff-tbody">
                    <?php if (!empty($approved_staff)): ?>
                        <?php foreach ($approved_staff as $index => $staff): ?>
                            <tr id="staff-row-<?= $staff['staff_id'] ?>">
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($staff['staff_firstname'] . ' ' . $staff['staff_lastname'] . ' (/' . $staff['staff_username'] . ')') ?></td>
                                <td><?= esc($staff['last_clock_in'] ?? 'N/A') ?></td>
                                <td><?= esc($staff['last_clock_out'] ?? 'N/A') ?></td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-info">Edit</a>
                                    <a href="<?= site_url('owner/staff/delete/' . $staff['staff_id']) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No approved staff members found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // You can add other scripts here if needed in the future.
    </script>
<?= $this->endSection() ?>