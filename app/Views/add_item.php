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

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session('errors') as $error): ?>
                                <p><?= esc($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('owner/add-item') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="item-name">ITEM NAME</label>
                            <input type="text" class="form-control form-control-lg" id="item-name" name="name" value="<?= old('name') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="item-category">DESCRIPTION / CATEGORY</label>
                            <input type="text" class="form-control" id="item-category" name="category" value="<?= old('category') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-quantity">QUANTITY</label>
                                    <input type="number" class="form-control" id="item-quantity" name="quantity" value="<?= old('quantity') ?>" required min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-price">INITIAL PRICE (PHP)</label>
                                    <input type="number" class="form-control" id="item-price" name="initial_price" value="<?= old('initial_price') ?>" required min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary mr-2">ADD ITEM</button>
                            <a href="<?= site_url('owner/inventory') ?>" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // This space can be used for other scripts in the future.
    </script>
<?= $this->endSection() ?>