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

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">FOR APPROVAL (STAFFS)</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="staff-approval-table">
                            <thead>
                            <tr>
                                <th>NO.</th>
                                <th>USER</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                            </thead>
                            <tbody id="staff-approval-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">System Key</h4>
                    <p class="card-description">Generate a System Key for new staff registration.</p>
                    <div class="form-group d-flex">
                        <input type="text" class="form-control form-control-lg mr-2" id="system-key-input" placeholder="Click 'Generate' to create a key" readonly>
                        <button type="button" class="btn btn-secondary" id="copy-key-btn" title="Copy to Clipboard">
                            <i class="fas fa-copy"></i> </button>
                    </div>
                    <button type="button" class="btn btn-primary" id="generate-key-btn">Generate</button>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- DOM Element Selection ---
            const staffTableBody = document.getElementById('staff-approval-tbody');
            const generateBtn = document.getElementById('generate-key-btn');
            const copyBtn = document.getElementById('copy-key-btn');
            const keyInput = document.getElementById('system-key-input');

            /**
             * =================================================================
             * FOR APPROVAL (STAFFS)
             * =================================================================
             */

            // Function to fetch pending staff from the server and populate the table
            async function fetchPendingStaff() {
                try {
                    // This is the backend endpoint you need to create.
                    // It should return a JSON array of staff objects, e.g., [{id: 1, username: 'John Doe'}, ...]
                    const response = await fetch('/api/owner/pending-staff', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const staffList = await response.json();
                    staffTableBody.innerHTML = ''; // Clear existing rows

                    if (staffList.length === 0) {
                        staffTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No pending staff approvals.</td></tr>';
                    } else {
                        staffList.forEach((staff, index) => {
                            const row = `
                        <tr id="staff-row-${staff.id}">
                            <td>${index + 1}</td>
                            <td>${staff.username}</td>
                            <td class="text-center">
                                <button class="btn btn-success btn-sm approve-btn" data-id="${staff.id}">Approve</button>
                                <button class="btn btn-danger btn-sm decline-btn" data-id="${staff.id}">Decline</button>
                            </td>
                        </tr>
                    `;
                            staffTableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }
                } catch (error) {
                    console.error('Failed to fetch pending staff:', error);
                    staffTableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading staff data.</td></tr>';
                }
            }

            // Handles clicks on the "Approve" or "Decline" buttons
            async function handleStaffAction(staffId, action) {
                if (!confirm(`Are you sure you want to ${action} this staff member?`)) {
                    return;
                }

                try {
                    // This is the backend endpoint you need to create.
                    // It should handle the approval or decline action.
                    const response = await fetch(`/api/owner/handle-staff`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            // CodeIgniter 4's CSRF protection
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify({ id: staffId, action: action }) // e.g., {id: 12, action: 'approve'}
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Remove the row from the table on success
                        const rowToRemove = document.getElementById(`staff-row-${staffId}`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }
                        alert(result.message || `Staff successfully ${action}d.`);
                    } else {
                        alert(result.message || `Could not ${action} staff.`);
                    }

                } catch (error) {
                    console.error(`Error during staff ${action}:`, error);
                    alert(`An error occurred while trying to ${action} the staff. Please try again.`);
                }
            }

            // Event listener for the table body (uses event delegation)
            staffTableBody.addEventListener('click', function(event) {
                const target = event.target;
                const staffId = target.dataset.id;

                if (target.classList.contains('approve-btn')) {
                    handleStaffAction(staffId, 'approve');
                } else if (target.classList.contains('decline-btn')) {
                    handleStaffAction(staffId, 'decline');
                }
            });


            /**
             * =================================================================
             * SYSTEM KEY
             * =================================================================
             */

            // Event listener for the "Generate" button
            generateBtn.addEventListener('click', async function() {
                this.disabled = true;
                this.textContent = 'Generating...';

                // Generate a random 16-character alphanumeric key
                const generatedKey = [...Array(16)].map(() => Math.floor(Math.random() * 36).toString(36)).join('').toUpperCase();

                try {
                    // This is the backend endpoint you need to create to save the new key.
                    const response = await fetch('/api/owner/generate-key', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify({ system_key: generatedKey })
                    });

                    const result = await response.json();

                    if (result.success) {
                        keyInput.value = generatedKey;
                        alert('New system key generated and saved!');
                    } else {
                        alert(result.message || 'Server error: Could not save the new key.');
                    }

                } catch (error) {
                    console.error('Error generating system key:', error);
                    alert('An error occurred while generating the key.');
                } finally {
                    this.disabled = false;
                    this.textContent = 'Generate';
                }
            });

            // Event listener for the "Copy" button
            copyBtn.addEventListener('click', function() {
                if (!keyInput.value) {
                    alert("Please generate a key first.");
                    return;
                }
                navigator.clipboard.writeText(keyInput.value).then(() => {
                    alert("System Key copied to clipboard!");
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    alert("Failed to copy key.");
                });
            });


            // --- Initial Load ---
            // Fetch pending staff as soon as the page is ready
            fetchPendingStaff();

        });
    </script>
<?= $this->endSection() ?>