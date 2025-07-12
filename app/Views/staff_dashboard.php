<?= $this->extend('layouts/staff_layout') ?>

<?= $this->section('title') ?>
    Cart View
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div id="customer-modal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4">CUSTOMER INFO</h2>
            <form id="customer-form">
                <div class="space-y-4">
                    <input type="text" id="customer-name" name="name" placeholder="Customer name" class="form-control bg-gray-800 text-white border-gray-600 w-full" required>
                    <input type="tel" id="customer-number" name="number" placeholder="Number" class="form-control bg-gray-800 text-white border-gray-600 w-full">
                    <input type="text" id="customer-address" name="address" placeholder="Address" class="form-control bg-gray-800 text-white border-gray-600 w-full">
                </div>
                <div class="text-center mt-6">
                    <button type="submit" class="btn btn-light">Next</button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-white">
        <h1 class="text-3xl font-bold mb-4">CART VIEW</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-xl font-semibold">LIST:</h2>
                    <div class="flex items-center">
                        <label for="search-inventory" class="mr-2">SEARCH</label>
                        <input type="text" id="search-inventory" class="form-control bg-gray-700 text-white border-gray-600" style="width: 250px;">
                    </div>
                </div>
                <div class="table-container bg-gray-800 rounded-lg p-1">
                    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                        <table class="table text-white">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>ITEM NAME</th>
                                <th class="text-center">QTY</th>
                                <th class="text-right">PRICE</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                            </thead>
                            <tbody id="inventory-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">YOUR CART:</h2>
                <div id="cart-items-container" class="space-y-2 bg-gray-800 rounded-lg p-4" style="min-height: 45vh;">
                    <p class="text-gray-400 text-center" id="cart-empty-msg">Cart is empty</p>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-600">
                    <h3 class="text-lg font-bold flex justify-between">
                        <span>TOTAL PRICE :</span>
                        <span id="total-price">₱0.00</span>
                    </h3>
                </div>

                <div class="flex justify-between mt-4">
                    <button class="btn btn-info" id="add-customer-btn">ADD CUSTOMER</button>
                    <button class="btn btn-success" id="checkout-btn">CHECK OUT</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- DOM Elements ---
            const inventoryTbody = document.getElementById('inventory-tbody');
            const searchInput = document.getElementById('search-inventory');
            const cartItemsContainer = document.getElementById('cart-items-container');
            const cartEmptyMsg = document.getElementById('cart-empty-msg');
            const totalPriceEl = document.getElementById('total-price');
            const checkoutBtn = document.getElementById('checkout-btn');

            // --- NEW Modal DOM Elements ---
            const customerModal = document.getElementById('customer-modal');
            const addCustomerBtn = document.getElementById('add-customer-btn');
            const customerForm = document.getElementById('customer-form');

            // --- State Management ---
            let inventory = [];
            let cart = [];
            let currentCustomer = null; // To hold the added customer's info


            /* ================================================================== */
            /* MODAL AND CUSTOMER FORM LOGIC (NEW)                                */
            /* ================================================================== */
            addCustomerBtn.addEventListener('click', () => {
                customerModal.style.display = 'flex'; // Show the modal
            });

            // Hide modal if user clicks the dark overlay
            customerModal.addEventListener('click', (event) => {
                if (event.target === customerModal) {
                    customerModal.style.display = 'none';
                }
            });

            customerForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(customerForm);
                currentCustomer = {
                    name: formData.get('name'),
                    number: formData.get('number'),
                    address: formData.get('address')
                };

                // Update the button to show customer is added
                addCustomerBtn.textContent = 'CUSTOMER ADDED';
                addCustomerBtn.classList.remove('btn-info');
                addCustomerBtn.classList.add('btn-warning');

                alert(`Customer "${currentCustomer.name}" has been added to the order.`);
                customerModal.style.display = 'none'; // Hide modal after submission
            });


            /* ================================================================== */
            /* CHECKOUT, INVENTORY, and CART LOGIC (MODIFIED & EXISTING)          */
            /* ================================================================== */

            async function processCheckout() {
                if (cart.length === 0) {
                    alert('Your cart is empty.');
                    return;
                }

                checkoutBtn.disabled = true;
                checkoutBtn.textContent = 'Processing...';

                try {
                    // UPDATED: Now sends both cart and customer info
                    const payload = {
                        cart: cart,
                        customer: currentCustomer
                    };

                    const response = await fetch('/api/staff/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Checkout successful!');
                        cart = [];
                        currentCustomer = null; // Reset customer

                        // Reset "Add Customer" button
                        addCustomerBtn.textContent = 'ADD CUSTOMER';
                        addCustomerBtn.classList.remove('btn-warning');
                        addCustomerBtn.classList.add('btn-info');

                        updateCartDisplay();
                        fetchInventory();
                    } else {
                        alert(`Checkout failed: ${result.message || 'Unknown error'}`);
                    }

                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('An error occurred during checkout.');
                } finally {
                    checkoutBtn.disabled = false;
                    checkoutBtn.textContent = 'CHECK OUT';
                }
            }

            // The rest of your existing JS functions remain the same...
            // fetchInventory(), renderInventory(), updateCartDisplay(),
            // calculateTotal(), addToCart(), removeFromCart(), and their event listeners
            async function fetchInventory() {
                try {
                    const response = await fetch('/api/staff/inventory');
                    if (!response.ok) throw new Error('Failed to fetch inventory');
                    inventory = await response.json();
                    renderInventory(inventory);
                } catch (error) {
                    console.error(error);
                    inventoryTbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Could not load inventory.</td></tr>`;
                }
            }
            function renderInventory(items) {
                inventoryTbody.innerHTML = '';
                if (items.length === 0) {
                    inventoryTbody.innerHTML = `<tr><td colspan="5" class="text-center">No items found.</td></tr>`;
                    return;
                }
                items.forEach(item => {
                    const isOutOfStock = item.quantity <= 0;
                    const row = `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td class="text-center">${item.quantity}</td>
                    <td class="text-right">₱${parseFloat(item.price).toFixed(2)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary add-to-cart-btn"
                                data-id="${item.id}"
                                ${isOutOfStock ? 'disabled title="Out of stock"' : ''}>
                            <i class="mdi mdi-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
                    inventoryTbody.insertAdjacentHTML('beforeend', row);
                });
            }
            function updateCartDisplay() {
                cartItemsContainer.innerHTML = '';
                if (cart.length === 0) {
                    cartItemsContainer.appendChild(cartEmptyMsg);
                    cartEmptyMsg.style.display = 'block';
                } else {
                    cartEmptyMsg.style.display = 'none';
                    cart.forEach(cartItem => {
                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'flex items-center justify-between p-2 bg-gray-700 rounded';
                        itemDiv.innerHTML = `
                    <span class="flex-grow">${cartItem.name} (x${cartItem.quantity})</span>
                    <span class="mr-4">₱${(cartItem.price * cartItem.quantity).toFixed(2)}</span>
                    <button class="text-red-500 hover:text-red-700 remove-from-cart-btn" data-id="${cartItem.id}">
                        <i class="mdi mdi-delete mdi-24px"></i>
                    </button>
                `;
                        cartItemsContainer.appendChild(itemDiv);
                    });
                }
                calculateTotal();
            }
            function calculateTotal() {
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                totalPriceEl.textContent = `₱${total.toFixed(2)}`;
            }
            function addToCart(itemId) {
                const itemInInventory = inventory.find(item => item.id == itemId);
                const itemInCart = cart.find(item => item.id == itemId);

                if (!itemInInventory) return;

                const currentStock = itemInInventory.quantity;
                const qtyInCart = itemInCart ? itemInCart.quantity : 0;
                if (qtyInCart >= currentStock) {
                    alert('Not enough stock available.');
                    return;
                }

                if (itemInCart) {
                    itemInCart.quantity++;
                } else {
                    cart.push({ ...itemInInventory, quantity: 1 });
                }
                updateCartDisplay();
            }
            function removeFromCart(itemId) {
                cart = cart.filter(item => item.id != itemId);
                updateCartDisplay();
            }

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const filteredInventory = inventory.filter(item =>
                    item.name.toLowerCase().includes(searchTerm)
                );
                renderInventory(filteredInventory);
            });
            inventoryTbody.addEventListener('click', function(event) {
                const addButton = event.target.closest('.add-to-cart-btn');
                if (addButton) {
                    addToCart(addButton.dataset.id);
                }
            });
            cartItemsContainer.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.remove-from-cart-btn');
                if (removeButton) {
                    removeFromCart(removeButton.dataset.id);
                }
            });
            checkoutBtn.addEventListener('click', processCheckout);

            fetchInventory();
        });
    </script>
<?= $this->endSection() ?>