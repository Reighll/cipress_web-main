<?= $this->extend('layouts/staff_layout') ?>

<?= $this->section('title') ?>
    Clock In / Out
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="card-title">Attendance</h2>

                    <!-- Display Success/Error Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <?php if ($is_clocked_in): ?>
                        <p class="text-success" style="font-size: 1.2rem;">You are currently CLOCKED IN.</p>
                        <p>Clocked in at: <?= date('M d, Y h:i A', strtotime($last_clock_in)) ?></p>
                        <form action="<?= site_url('staff/attendance/clock-out') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-lg btn-danger">Clock Out</button>
                        </form>
                    <?php else: ?>
                        <p class="text-warning" style="font-size: 1.2rem;">You are currently CLOCKED OUT.</p>

                        <!-- [THE FIX] Display the last clock-out time if it exists -->
                        <?php if ($last_clock_out): ?>
                            <p>Last clocked out at: <?= date('M d, Y h:i A', strtotime($last_clock_out)) ?></p>
                        <?php endif; ?>

                        <p>Ready to start your shift?</p>
                        <form action="<?= site_url('staff/attendance/clock-in') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-lg btn-success">Clock In</button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>