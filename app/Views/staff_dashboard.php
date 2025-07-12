<?= $this->extend('layouts/staff_layout') ?>

<?= $this->section('title') ?>
    Cart View
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <style>
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7); justify-content: center; align-items: center; z-index: 1000;
        }
        .modal-content {
            background-color: #2c2c2c; padding: 2rem; border-radius: 0.5rem;
            width: 90%; max-width: 500px; color: white;
        }
        .table-responsive::-webkit-scrollbar { width: 8px; }
        .table-responsive::-webkit-scrollbar-track { background: #2c2c2c; }
        .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
        .table-responsive::-webkit-scrollbar-thumb:hover { background: #777; }
    </style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
    <div id="customer-modal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4">CUSTOMER INFO</h2>
            <form id="customer-form">
                <div class="space-y-4">
                    <input type="text" id="customer-name" name="name" placeholder="Customer name" class="form-control bg-gray-800 text-white border-gray-600 w-full" required>
                    <input type="tel" id="customer-number" name="number" placeholder="Number (Optional)" class="form-control bg-gray-800 text-white border-gray-600 w-full">
                    <input type="text" id="customer-address" name="address" placeholder="Address (Optional)" class="form-control bg-gray-800 text-white border-gray-600 w-full">
                </div>
                <div class="text-center mt-6 flex justify-between">
                    <button type="button" class="btn btn-secondary" id="cancel-customer-btn">Cancel</button>
                    <button type="submit" class="btn btn-light">Save Customer</button>
                </div>
            </form>
        </div>
    </div>

    <div id="payment-modal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4">PAYMENT</h2>
            <div id="payment-cart-summary" class="mb-4"></div>
            <h3 class="text-xl font-bold mb-2">Total Amount: <span id="payment-total-amount"></span></h3>
            <div class="form-group">
                <label for="payment-received">Payment Received</label>
                <input type="number" id="payment-received" class="form-control bg-gray-800 text-white border-gray-600 w-full" min="0" step="0.01">
            </div>
            <h3 class="text-lg font-bold mt-2">Change: <span id="payment-change">₱0.00</span></h3>
            <div class="text-center mt-6 flex justify-between">
                <button type="button" class="btn btn-secondary" id="cancel-payment-btn">Cancel</button>
                <button type="button" class="btn btn-success" id="submit-payment-btn">Submit Payment</button>
            </div>
        </div>
    </div>

    <div class="text-white">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-xl font-semibold">INVENTORY:</h2>
                    <input type="text" id="search-inventory" class="form-control bg-gray-700 text-white border-gray-600" style="width: 250px;" placeholder="Search items...">
                </div>
                <div class="table-container bg-gray-800 rounded-lg p-1">
                    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                        <table class="table text-white">
                            <thead>
                            <tr><th>ID</th><th>ITEM NAME</th><th class="text-center">QTY</th><th class="text-right">PRICE</th><th class="text-center">ACTION</th></tr>
                            </thead>
                            <tbody id="inventory-tbody">
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <tr class="inventory-row" data-name="<?= strtolower(esc($item['item_name'])) ?>">
                                        <td><?= esc($item['item_id']) ?></td>
                                        <td><?= esc($item['item_name']) ?></td>
                                        <td class="text-center"><?= esc($item['item_quantity']) ?></td>
                                        <td class="text-right">₱<?= number_format(esc($item['item_initial_price']), 2) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary add-to-cart-btn"
                                                    data-id="<?= esc($item['item_id']) ?>"
                                                    data-name="<?= esc($item['item_name']) ?>"
                                                    data-price="<?= esc($item['item_initial_price']) ?>"
                                                    data-stock="<?= esc($item['item_quantity']) ?>"
                                                <?= $item['item_quantity'] <= 0 ? 'disabled' : '' ?>>+</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr id="no-results-row" style="display: none;"><td colspan="5" class="text-center">No items match your search.</td></tr>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No inventory items found.</td></tr>
                            <?php endif; ?>
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
                    <h3 class="text-lg font-bold flex justify-between"><span>TOTAL PRICE :</span><span id="total-price">₱0.00</span></h3>
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
            // === ELEMENTS ===
            const searchInput = document.getElementById('search-inventory');
            const inventoryTbody = document.getElementById('inventory-tbody');
            const noResultsRow = document.getElementById('no-results-row');
            const cartItemsContainer = document.getElementById('cart-items-container');
            const cartEmptyMsg = document.getElementById('cart-empty-msg');
            const totalPriceEl = document.getElementById('total-price');
            const checkoutBtn = document.getElementById('checkout-btn');
            const addCustomerBtn = document.getElementById('add-customer-btn');

            // Modal Elements
            const customerModal = document.getElementById('customer-modal');
            const customerForm = document.getElementById('customer-form');
            const cancelCustomerBtn = document.getElementById('cancel-customer-btn');
            const paymentModal = document.getElementById('payment-modal');
            const paymentCartSummary = document.getElementById('payment-cart-summary');
            const paymentTotalAmount = document.getElementById('payment-total-amount');
            const paymentReceivedInput = document.getElementById('payment-received');
            const paymentChangeEl = document.getElementById('payment-change');
            const cancelPaymentBtn = document.getElementById('cancel-payment-btn');
            const submitPaymentBtn = document.getElementById('submit-payment-btn');

            // === STATE ===
            let cart = [];
            let currentCustomer = null;

            // === SEARCH FUNCTIONALITY ===
            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();
                const inventoryRows = inventoryTbody.querySelectorAll('tr.inventory-row');
                let visibleCount = 0;

                inventoryRows.forEach(row => {
                    if (row.dataset.name.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                if (noResultsRow) noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
            });

            // === CART FUNCTIONS ===
            function addToCart(event) {
                const button = event.target;
                const itemId = button.dataset.id;
                const stock = parseInt(button.dataset.stock);

                const quantity = parseInt(prompt('Enter quantity:', 1));
                if (isNaN(quantity) || quantity <= 0) return;

                let totalInCart = 0;
                const itemInCart = cart.find(item => item.id == itemId);
                if (itemInCart) totalInCart = itemInCart.quantity;

                if (stock < totalInCart + quantity) {
                    alert('Not enough stock available.');
                    return;
                }

                if (itemInCart) {
                    itemInCart.quantity += quantity;
                } else {
                    cart.push({
                        id: itemId,
                        name: button.dataset.name,
                        price: parseFloat(button.dataset.price),
                        quantity: quantity
                    });
                }
                updateCartDisplay();
            }

            function removeFromCart(itemId) {
                cart = cart.filter(item => item.id != itemId);
                updateCartDisplay();
            }

            function updateCartDisplay() {
                cartItemsContainer.innerHTML = '';
                cartEmptyMsg.style.display = cart.length === 0 ? 'block' : 'none';

                let total = 0;
                cart.forEach(cartItem => {
                    cartItemsContainer.insertAdjacentHTML('beforeend', `
                <div class="flex items-center justify-between p-2 bg-gray-700 rounded">
                    <span>${cartItem.name} (x${cartItem.quantity})</span>
                    <button class="text-red-500 hover:text-red-700 remove-from-cart-btn" data-id="${cartItem.id}">X</button>
                </div>`);
                    total += cartItem.price * cartItem.quantity;
                });
                totalPriceEl.textContent = `₱${total.toFixed(2)}`;
            }

            // === MODAL AND CHECKOUT FUNCTIONS ===
            function showPaymentModal() {
                if (cart.length === 0) {
                    alert('Your cart is empty.');
                    return;
                }
                paymentCartSummary.innerHTML = cart.map(item => `<div>${item.name} (x${item.quantity})</div>`).join('');
                paymentTotalAmount.textContent = totalPriceEl.textContent;
                paymentReceivedInput.value = '';
                paymentChangeEl.textContent = '₱0.00';
                paymentModal.style.display = 'flex';
            }

            async function processCheckout() {
                submitPaymentBtn.disabled = true;
                submitPaymentBtn.textContent = 'Processing...';

                try {
                    // 1. Create the payload with all the data
                    const payload = {
                        cart: cart,
                        customer: currentCustomer,
                        payment: parseFloat(paymentReceivedInput.value) || 0,
                        // 2. Add the CSRF token directly to the payload
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    };

                    // 3. Make the fetch request (the header is no longer needed for CSRF)
                    const response = await fetch('<?= site_url('/staff/api/checkout') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest' // Good practice for CodeIgniter
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        alert(`Checkout successful!`);
                        window.location.href = `<?= site_url('/staff/receipt/') ?>${result.sale_id}`;
                    } else {
                        alert(`Checkout failed: ${result.message || 'An unknown error occurred.'}`);
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('A critical error occurred. Please check the browser console for details.');
                } finally {
                    submitPaymentBtn.disabled = false;
                    submitPaymentBtn.textContent = 'Submit Payment';
                }
            }

            // === EVENT LISTENERS ===
            inventoryTbody.addEventListener('click', (e) => {
                if (e.target.classList.contains('add-to-cart-btn')) addToCart(e);
            });

            cartItemsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-from-cart-btn')) removeFromCart(e.target.dataset.id);
            });

            // Modal Listeners
            addCustomerBtn.addEventListener('click', () => customerModal.style.display = 'flex');
            cancelCustomerBtn.addEventListener('click', () => customerModal.style.display = 'none');
            customerModal.addEventListener('click', (e) => { if (e.target === customerModal) customerModal.style.display = 'none'; });
            customerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                currentCustomer = { name: customerForm.name.value, number: customerForm.number.value, address: customerForm.address.value };
                addCustomerBtn.textContent = 'CUSTOMER ADDED';
                addCustomerBtn.classList.replace('btn-info', 'btn-warning');
                alert(`Customer "${currentCustomer.name}" added.`);
                customerModal.style.display = 'none';
            });

            checkoutBtn.addEventListener('click', showPaymentModal);
            cancelPaymentBtn.addEventListener('click', () => paymentModal.style.display = 'none');
            paymentModal.addEventListener('click', (e) => { if (e.target === paymentModal) paymentModal.style.display = 'none'; });
            submitPaymentBtn.addEventListener('click', processCheckout);
            paymentReceivedInput.addEventListener('input', () => {
                const total = parseFloat(totalPriceEl.textContent.replace('₱', ''));
                const received = parseFloat(paymentReceivedInput.value) || 0;
                const change = received - total;
                paymentChangeEl.textContent = `₱${change > 0 ? change.toFixed(2) : '0.00'}`;
            });
        });
    </script>
<?= $this->endSection() ?>