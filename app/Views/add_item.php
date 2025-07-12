<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Add Item
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">ADD NEW ITEM</h2>

                    <div id="form-feedback"></div>

                    <form id="add-item-form">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="item-name">ITEM NAME</label>
                            <input type="text" class="form-control form-control-lg" id="item-name" name="name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-category">CATEGORY</label>
                                    <input type="text" class="form-control" id="item-category" name="category" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-size">SIZE</label>
                                    <input type="text" class="form-control" id="item-size" name="size">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="item-description">DESCRIPTION</label>
                            <textarea class="form-control" id="item-description" name="description" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-qty">QTY (QUANTITY)</label>
                                    <input type="number" class="form-control" id="item-qty" name="quantity" required min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-price">PRICE</label>
                                    <input type="number" class="form-control" id="item-price" name="price" required min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg mt-3" id="submit-btn">ADD ITEM</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemForm = document.getElementById('add-item-form');
            const submitBtn = document.getElementById('submit-btn');
            const feedbackDiv = document.getElementById('form-feedback');

            addItemForm.addEventListener('submit', async function(event) {
                // Prevent the default browser form submission
                event.preventDefault();

                // Disable button to prevent multiple clicks
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving...';
                feedbackDiv.innerHTML = ''; // Clear previous feedback

                // Get form data
                const formData = new FormData(addItemForm);

                // --- Client-Side Validation (Basic) ---
                let isValid = true;
                if (!formData.get('name').trim()) {
                    isValid = false;
                }
                if (!formData.get('quantity') || parseFloat(formData.get('quantity')) < 0) {
                    isValid = false;
                }
                if (!formData.get('price') || parseFloat(formData.get('price')) < 0) {
                    isValid = false;
                }

                if (!isValid) {
                    feedbackDiv.innerHTML = '<div class="alert alert-danger">Please fill out all required fields with valid values.</div>';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'ADD ITEM';
                    return;
                }

                try {
                    // This is the backend API endpoint you need to create
                    const response = await fetch('/api/owner/inventory/add', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Show success message
                        feedbackDiv.innerHTML = `<div class="alert alert-success">${result.message || 'Item added successfully!'}</div>`;
                        // Reset the form
                        addItemForm.reset();
                        // Optional: Redirect to inventory list after a delay
                        setTimeout(() => {
                            window.location.href = '/owner/inventory';
                        }, 2000); // 2-second delay
                    } else {
                        // Show error message from server
                        let errorMessage = result.message || 'An unknown error occurred.';
                        if (result.errors) {
                            errorMessage = Object.values(result.errors).join('<br>');
                        }
                        feedbackDiv.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                    }

                } catch (error) {
                    console.error('Submission error:', error);
                    feedbackDiv.innerHTML = '<div class="alert alert-danger">A network error occurred. Please try again.</div>';
                } finally {
                    // Re-enable the button if not redirecting immediately
                    if (!feedbackDiv.querySelector('.alert-success')) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'ADD ITEM';
                    }
                }
            });
        });
    </script>
<?= $this->endSection() ?>