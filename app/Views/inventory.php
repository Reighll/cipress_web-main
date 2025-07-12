<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Inventory
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">INVENTORY LIST</h2>

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

            <div class="d-flex justify-content-end mb-3">
                <a href="<?= site_url('owner/add-item') ?>" class="btn btn-primary">Add New Item</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th>No.</th>
                        <th>Item Name</th>
                        <th>Description / Category</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Initial Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($item['item_name']) ?></td>
                                <td><?= esc($item['item_category']) ?></td>
                                <td class="text-center"><?= esc($item['item_quantity']) ?></td>
                                <td class="text-right">₱<?= number_format($item['item_initial_price'], 2) ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('owner/inventory/edit/' . $item['item_id']) ?>" class="btn btn-sm btn-info">Edit</a>
                                    <a href="<?= site_url('owner/inventory/delete/' . $item['item_id']) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this item?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No items found in inventory.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>