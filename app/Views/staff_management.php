<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Staff Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">STAFF MANAGEMENT</h2>
            <h4 class="card-subtitle mb-4 text-muted">ALL USERS</h4>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>NO.</th>
                        <th>USER</th>
                        <th>CLOCK IN</th>
                        <th>CLOCK OUT</th>
                        <th class="text-center">ACTIONS</th>
                    </tr>
                    </thead>
                    <tbody id="staff-tbody">
                    </tbody>
                </table>
                <div id="loading-indicator" class="text-center my-4" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const staffTbody = document.getElementById('staff-tbody');
            const loadingIndicator = document.getElementById('loading-indicator');

            /**
             * Fetches staff data from the server and renders it in the table.
             */
            async function fetchStaff() {
                loadingIndicator.style.display = 'block';
                staffTbody.innerHTML = '';

                try {
                    // This is the backend endpoint you need to create.
                    // It should return a JSON array of approved staff members.
                    const response = await fetch('/api/owner/staff', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const staffList = await response.json();

                    if (staffList.length === 0) {
                        staffTbody.innerHTML = '<tr><td colspan="5" class="text-center">No staff members found.</td></tr>';
                    } else {
                        staffList.forEach((staff, index) => {
                            // Format dates nicely, or show 'N/A' if null
                            const clockIn = staff.clock_in ? new Date(staff.clock_in).toLocaleString() : 'N/A';
                            const clockOut = staff.clock_out ? new Date(staff.clock_out).toLocaleString() : 'N/A';

                            const row = `
                        <tr id="staff-row-${staff.id}">
                            <td>${index + 1}</td>
                            <td>${staff.username}</td>
                            <td>${clockIn}</td>
                            <td>${clockOut}</td>
                            <td class="text-center">
                                <a href="/owner/staff/edit/${staff.id}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${staff.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                            staffTbody.insertAdjacentHTML('beforeend', row);
                        });
                    }

                } catch (error) {
                    console.error('Failed to fetch staff:', error);
                    staffTbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading staff data.</td></tr>';
                } finally {
                    loadingIndicator.style.display = 'none';
                }
            }

            /**
             * Handles the delete action when a delete button is clicked.
             * @param {string} staffId The ID of the staff member to delete.
             */
            async function handleDelete(staffId) {
                if (!confirm('Are you sure you want to delete this staff member? This will permanently remove them.')) {
                    return;
                }

                try {
                    // This is the backend endpoint for deleting a staff member.
                    const response = await fetch('/api/owner/staff/delete', {
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

            // Use event delegation to handle clicks on delete buttons
            staffTbody.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('delete-btn')) {
                    const staffId = event.target.dataset.id;
                    handleDelete(staffId);
                }
            });

            // Initial fetch of staff data when the page loads
            fetchStaff();
        });
    </script>
<?= $this->endSection() ?>