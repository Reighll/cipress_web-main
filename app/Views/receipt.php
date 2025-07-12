<?= $this->extend('layouts/staff_layout') ?>

<?= $this->section('title') ?>
    Transaction Receipt
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <div class="container-fluid d-flex justify-content-between">
                <div class="col-lg-3 ps-0">
                    <h3 class="mt-5 mb-2"><b>Cipress</b></h3>
                    <p>Sale ID: #<?= esc($sale['sale_id']) ?><br>
                        Date: <?= date('M d, Y h:i A', strtotime($sale['sale_date'])) ?><br>
                        Customer: <?= esc($sale['customer_name']) ?></p>
                </div>
            </div>

            <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                <div class="table-responsive w-100">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Item Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sale['items'] as $index => $item): ?>
                            <tr class="text-end">
                                <td class="text-start"><?= $index + 1 ?></td>
                                <td class="text-start"><?= esc($item['item_name']) ?></td>
                                <td><?= esc($item['quantity']) ?></td>
                                <td>₱<?= number_format($item['item_price'], 2) ?></td>
                                <td>₱<?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="container-fluid mt-5 w-100">
                <div class="row">
                    <div class="col-md-6 ms-auto">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Total Price</td>
                                    <td class="text-end">₱<?= number_format($sale['total_price'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Payment Received</td>
                                    <td class="text-end">₱<?= number_format($sale['payment_received'], 2) ?></td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Change Due</td>
                                    <td class="text-end">₱<?= number_format($sale['change_due'], 2) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid w-100 text-center mt-5">
                <a href="<?= site_url('/staff/dashboard') ?>" class="btn btn-secondary">
                    <i class="mdi mdi-cart"></i> Back to Cart
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="mdi mdi-printer"></i> Print
                </button>
            </div>

        </div>
    </div>
<?= $this->endSection() ?>