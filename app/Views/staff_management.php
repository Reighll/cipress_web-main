<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Staff Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">STAFF MANAGEMENT</h2>
            <h4 class="card-subtitle mb-4 text-muted">ALL APPROVED USERS</h4>

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
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $staff['staff_id'] ?>">Delete</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const staffTbody = document.getElementById('staff-tbody');

            async function handleDelete(staffId) {
                if (!confirm('Are you sure you want to delete this staff member? This will permanently remove them.')) {
                    return;
                }

                try {
                    const response = await fetch('/owner/api/staff/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify({ id: staffId })
                    });

                    const result = await response.json();

                    if (result.success) {
                        const rowToRemove = document.getElementById(`staff-row-${staffId}`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }
                        alert(result.message || 'Staff member deleted successfully.');
                    } else {
                        alert(result.message || 'Could not delete the staff member.');
                    }

                } catch (error) {
                    console.error('Error deleting staff:', error);
                    alert('An error occurred. Please try again.');
                }
            }

            staffTbody.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('delete-btn')) {
                    const staffId = event.target.dataset.id;
                    handleDelete(staffId);
                }
            });
        });
    </script>
<?= $this->endSection() ?>