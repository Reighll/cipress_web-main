<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Inventory
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="card-title mb-0">INVENTORY LIST</h2>
                <div class="form-group mb-0" style="width: 300px;">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-inventory" placeholder="Search by name, category...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th class="text-center">Quantity</th>
                        <th>Size</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="inventory-tbody">
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

            const inventoryTbody = document.getElementById('inventory-tbody');
            const searchInput = document.getElementById('search-inventory');
            const loadingIndicator = document.getElementById('loading-indicator');
            let inventoryData = []; // Stores the full list for client-side filtering

            /**
             * Renders the provided items into the table.
             */
            function renderTable(items) {
                inventoryTbody.innerHTML = ''; // Clear the table first

                if (items.length === 0) {
                    inventoryTbody.innerHTML = '<tr><td colspan="7" class="text-center">No inventory items found.</td></tr>';
                    return;
                }

                items.forEach((item, index) => {
                    const row = `
                <tr id="item-row-${item.id}">
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.category}</td>
                    <td>${item.description}</td>
                    <td class="text-center">${item.quantity}</td>
                    <td>${item.size}</td>
                    <td class="text-center">
                        <a href="/owner/inventory/edit/${item.id}" class="btn btn-info btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                    inventoryTbody.insertAdjacentHTML('beforeend', row);
                });
            }

            /**
             * Fetches inventory data from the server.
             */
            async function fetchInventory() {
                loadingIndicator.style.display = 'block';
                inventoryTbody.innerHTML = '';
                try {
                    // This is the backend endpoint you need to create.
                    const response = await fetch('/api/owner/inventory', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                    inventoryData = await response.json();
                    renderTable(inventoryData);

                } catch (error) {
                    console.error('Failed to fetch inventory:', error);
                    inventoryTbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading inventory data.</td></tr>';
                } finally {
                    loadingIndicator.style.display = 'none';
                }
            }


            /**
             * Handles the delete action.
             */
            async function handleDelete(itemId) {
                if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                    return;
                }

                try {
                    const response = await fetch(`/api/owner/inventory/delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify({ id: itemId })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Remove item from the data array and re-render the table
                        inventoryData = inventoryData.filter(item => item.id != itemId);
                        handleSearch();
                        alert(result.message || 'Item deleted successfully.');
                    } else {
                        alert(result.message || 'Could not delete the item.');
                    }
                } catch (error) {
                    console.error('Error deleting item:', error);
                    alert('An error occurred. Please try again.');
                }
            }

            /**
             * Filters the table based on the search input.
             */
            function handleSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                if (searchTerm === '') {
                    renderTable(inventoryData);
                    return;
                }

                const filteredData = inventoryData.filter(item => {
                    return item.name.toLowerCase().includes(searchTerm) ||
                        item.category.toLowerCase().includes(searchTerm) ||
                        item.description.toLowerCase().includes(searchTerm);
                });

                renderTable(filteredData);
            }

            // Event listener for the search input
            searchInput.addEventListener('input', handleSearch);

            // Event delegation to handle clicks on delete buttons
            inventoryTbody.addEventListener('click', function(event) {
                const deleteButton = event.target.closest('.delete-btn');
                if (deleteButton) {
                    const itemId = deleteButton.dataset.id;
                    handleDelete(itemId);
                }
            });

            // Initial data fetch when the page loads
            fetchInventory();

        });
    </script>
<?= $this->endSection() ?>