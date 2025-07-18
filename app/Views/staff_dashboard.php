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
            background-color: #2A3038; /* Match theme */
            color: #ffffff;
            padding: 2rem; border-radius: 0.5rem;
            width: 90%; max-width: 500px;
        }
        .table-responsive::-webkit-scrollbar { width: 8px; }
        .table-responsive::-webkit-scrollbar-track { background: #2A3038; }
        .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
        .table-responsive::-webkit-scrollbar-thumb:hover { background: #777; }
        .inventory-card { cursor: pointer; transition: transform 0.2s; }
        .inventory-card:hover { transform: scale(1.03); }
    </style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
    <form id="sale-form" action="<?= site_url('staff/dashboard/process_sale') ?>" method="post">
        <?= csrf_field() ?>
        <div id="hidden-inputs-container"></div>

        <div class="row">
            <div class="col-md-7 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">INVENTORY</h4>
                        <input type="text" id="inventory-search" class="form-control mb-4" placeholder="Search for items...">
                        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                            <div id="inventory-list" class="row">
                                <?php foreach ($items as $item): ?>
                                    <div class="col-md-4 mb-3 inventory-item" data-name="<?= strtolower(esc($item['item_name'])) ?>">

                                        <div class="card bg-dark text-white inventory-card" data-id="<?= $item['item_id'] ?>" data-name="<?= esc($item['item_name']) ?>" data-price="<?= esc($item['item_initial_price']) ?>" data-stock="<?= $item['item_quantity'] ?>">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><?= esc($item['item_name']) ?></h5>
                                                <p class="card-text">Stock: <?= $item['item_quantity'] ?></p>

                                                <p class="card-text font-weight-bold">₱<?= number_format($item['item_initial_price'], 2) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">CART</h4>
                        <div class="table-responsive" style="max-height: 45vh; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="cart-items">
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="text-right">
                            <h3>Total: <span id="total-price">₱0.00</span></h3>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="add-customer-btn" class="btn btn-info btn-block">ADD CUSTOMER</button>
                            <button type="button" id="checkout-btn" class="btn btn-primary btn-block mt-2">CHECKOUT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div id="customer-modal" class="modal-overlay">
        <div class="modal-content">
            <h4 class="card-title mb-4">CUSTOMER INFO</h4>
            <form id="customer-form">
                <div class="form-group">
                    <label for="customer-name">Name</label>
                    <input type="text" class="form-control" id="customer-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="customer-number">Contact Number</label>
                    <input type="text" class="form-control" id="customer-number" name="number">
                </div>
                <div class="form-group">
                    <label for="customer-address">Address</label>
                    <input type="text" class="form-control" id="customer-address" name="address">
                </div>
                <button type="submit" class="btn btn-primary">Save Customer</button>
                <button type="button" id="close-customer-modal" class="btn btn-light">Cancel</button>
            </form>
        </div>
    </div>

    <div id="payment-modal" class="modal-overlay">
        <div class="modal-content">
            <h4 class="card-title mb-4">PAYMENT</h4>
            <div class="form-group">
                <label>Total Amount</label>
                <input type="text" class="form-control" id="payment-total" readonly>
            </div>
            <div class="form-group">
                <label for="payment-received">Payment Received</label>
                <input type="number" class="form-control" id="payment-received" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Change</label>
                <input type="text" class="form-control" id="payment-change" readonly>
            </div>
            <button type="button" id="submit-payment-btn" class="btn btn-success">Confirm & Submit Sale</button>
            <button type="button" id="cancel-payment-btn" class="btn btn-light">Cancel</button>
        </div>
    </div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inventoryList = document.getElementById('inventory-list');
            const cartItemsEl = document.getElementById('cart-items');
            const totalPriceEl = document.getElementById('total-price');
            const checkoutBtn = document.getElementById('checkout-btn');
            const addCustomerBtn = document.getElementById('add-customer-btn');

            // Modals and Forms
            const customerModal = document.getElementById('customer-modal');
            const closeCustomerModalBtn = document.getElementById('close-customer-modal');
            const customerForm = document.getElementById('customer-form');
            const paymentModal = document.getElementById('payment-modal');
            const cancelPaymentBtn = document.getElementById('cancel-payment-btn');
            const submitPaymentBtn = document.getElementById('submit-payment-btn');
            const paymentTotalEl = document.getElementById('payment-total');
            const paymentReceivedInput = document.getElementById('payment-received');
            const paymentChangeEl = document.getElementById('payment-change');
            const saleForm = document.getElementById('sale-form');
            const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
            const searchInput = document.getElementById('inventory-search');

            let cart = [];
            let currentCustomer = null;

            // --- FUNCTIONS ---

            const updateCart = () => {
                cartItemsEl.innerHTML = '';
                let total = 0;
                cart.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>
                            <input type="number" value="${item.quantity}" min="1" max="${item.stock}" class="form-control cart-quantity-input" data-index="${index}" style="width: 70px;">
                        </td>
                        <td>₱${(item.price * item.quantity).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-from-cart" data-index="${index}">X</button>
                        </td>
                    `;
                    cartItemsEl.appendChild(row);
                    total += item.price * item.quantity;
                });
                totalPriceEl.textContent = `₱${total.toFixed(2)}`;
            };

            const addToCart = (itemData) => {
                const existingItem = cart.find(item => item.id === itemData.id);
                if (existingItem) {
                    if (existingItem.quantity < existingItem.stock) {
                        existingItem.quantity++;
                    } else {
                        alert('Maximum stock reached for this item.');
                    }
                } else {
                    if (itemData.stock > 0) {
                        cart.push({ ...itemData, quantity: 1 });
                    } else {
                        alert('This item is out of stock.');
                    }
                }
                updateCart();
            };

            const showPaymentModal = () => {
                if (cart.length === 0) {
                    alert('Your cart is empty.');
                    return;
                }
                paymentTotalEl.value = totalPriceEl.textContent;
                paymentReceivedInput.value = '';
                paymentChangeEl.value = '';
                paymentModal.style.display = 'flex';
            };

            const prepareAndSubmitSale = () => {
                // Clear any old hidden inputs
                hiddenInputsContainer.innerHTML = '';

                // Add cart items as hidden inputs
                cart.forEach((item, index) => {
                    Object.keys(item).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `cart_items[${index}][${key}]`;
                        input.value = item[key];
                        hiddenInputsContainer.appendChild(input);
                    });
                });

                // Add customer info as hidden inputs
                if (currentCustomer) {
                    Object.keys(currentCustomer).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `customer_${key}`;
                        input.value = currentCustomer[key];
                        hiddenInputsContainer.appendChild(input);
                    });
                }

                // Add payment received as hidden input
                const paymentInput = document.createElement('input');
                paymentInput.type = 'hidden';
                paymentInput.name = 'payment_received';
                paymentInput.value = paymentReceivedInput.value || 0;
                hiddenInputsContainer.appendChild(paymentInput);

                // Submit the form
                saleForm.submit();
            };

            // --- EVENT LISTENERS ---

            inventoryList.addEventListener('click', (e) => {
                const card = e.target.closest('.inventory-card');
                if (card) {
                    const itemData = {
                        id: card.dataset.id,
                        name: card.dataset.name,
                        price: parseFloat(card.dataset.price),
                        stock: parseInt(card.dataset.stock, 10),
                    };
                    addToCart(itemData);
                }
            });

            cartItemsEl.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-from-cart')) {
                    const index = e.target.dataset.index;
                    cart.splice(index, 1);
                    updateCart();
                }
            });

            cartItemsEl.addEventListener('change', (e) => {
                if (e.target.classList.contains('cart-quantity-input')) {
                    const index = e.target.dataset.index;
                    const newQuantity = parseInt(e.target.value, 10);
                    if (newQuantity > 0 && newQuantity <= cart[index].stock) {
                        cart[index].quantity = newQuantity;
                    } else {
                        e.target.value = cart[index].quantity; // Revert to old value
                        alert(`Quantity must be between 1 and ${cart[index].stock}.`);
                    }
                    updateCart();
                }
            });

            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.inventory-item').forEach(item => {
                    const itemName = item.dataset.name;
                    if (itemName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            addCustomerBtn.addEventListener('click', () => customerModal.style.display = 'flex');
            closeCustomerModalBtn.addEventListener('click', () => customerModal.style.display = 'none');
            customerModal.addEventListener('click', (e) => { if (e.target === customerModal) customerModal.style.display = 'none'; });
            customerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                currentCustomer = { name: customerForm.name.value, number: customerForm.number.value, address: customerForm.address.value };
                addCustomerBtn.textContent = 'CUSTOMER ADDED';
                addCustomerBtn.classList.replace('btn-info', 'btn-success');
                alert(`Customer "${currentCustomer.name}" added to sale.`);
                customerModal.style.display = 'none';
            });

            checkoutBtn.addEventListener('click', showPaymentModal);
            cancelPaymentBtn.addEventListener('click', () => paymentModal.style.display = 'none');
            paymentModal.addEventListener('click', (e) => { if (e.target === paymentModal) paymentModal.style.display = 'none'; });

            submitPaymentBtn.addEventListener('click', prepareAndSubmitSale);

            paymentReceivedInput.addEventListener('input', () => {
                const total = parseFloat(totalPriceEl.textContent.replace('₱', ''));
                const received = parseFloat(paymentReceivedInput.value) || 0;
                const change = received - total;
                paymentChangeEl.value = `₱${change >= 0 ? change.toFixed(2) : '0.00'}`;
            });
        });
    </script>
<?= $this->endSection() ?>