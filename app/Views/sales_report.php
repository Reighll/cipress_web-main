<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Sales Report
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">Today's Sales</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₱<?= number_format($todays_sales ?? 0, 2) ?></h3>
                        <i class="ti-calendar icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">This Week's Sales</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₱<?= number_format($this_weeks_sales ?? 0, 2) ?></h3>
                        <i class="ti-bar-chart-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">This Month's Sales</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₱<?= number_format($this_months_sales ?? 0, 2) ?></h3>
                        <i class="ti-pie-chart icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-md-center text-xl-left">This Year's Sales</p>
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₱<?= number_format($this_years_sales ?? 0, 2) ?></h3>
                        <i class="ti-stats-up icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Filter Report</h4>
                    <div class="btn-group mb-3" role="group">
                        <a href="<?= site_url('owner/sales-report?filter=today') ?>" class="btn btn-primary">Today</a>
                        <a href="<?= site_url('owner/sales-report?filter=week') ?>" class="btn btn-primary">This Week</a>
                        <a href="<?= site_url('owner/sales-report?filter=month') ?>" class="btn btn-primary">This Month</a>
                        <a href="<?= site_url('owner/sales-report?filter=year') ?>" class="btn btn-primary">This Year</a>
                    </div>

                    <form action="<?= site_url('owner/sales-report') ?>" method="get" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="start_date" class="sr-only">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= esc($start_date) ?>">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="end_date" class="sr-only">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= esc($end_date) ?>">
                        </div>
                        <button type="submit" class="btn btn-success mb-2">Filter by Date</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title"><?= esc($report_title) ?></h4>
                        <h4 class="text-success">Total Sales: ₱<?= number_format($total_sales_for_period ?? 0, 2) ?></h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Date of Sale</th>
                                <th>Item Sold</th>
                                <th>Initial Price</th>
                                <th>Selling Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Served By</th>
                                <th>Receipt</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($sales_data)): ?>
                                <?php foreach ($sales_data as $sale): ?>
                                    <?php foreach ($sale['items'] as $item): ?>
                                        <tr>
                                            <td><?= date('M d, Y h:i A', strtotime($sale['sale_date'])) ?></td>
                                            <td><?= esc($item['item_name']) ?></td>
                                            <td>₱<?= number_format($item['item_initial_price'], 2) ?></td>
                                            <td>₱<?= number_format($item['item_price'], 2) ?></td>
                                            <td><?= esc($item['quantity']) ?></td>
                                            <td>₱<?= number_format($item['subtotal'], 2) ?></td>
                                            <td><?= esc($sale['staff_name']) ?></td>
                                            <td>
                                                <a href="<?= site_url('owner/receipt/' . $sale['sale_id']) ?>" class="btn btn-info btn-sm" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No sales data found for the selected period.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>