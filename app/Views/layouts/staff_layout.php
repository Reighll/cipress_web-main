<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $this->renderSection('title') ?? 'Staff Dashboard' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" />
    <?= $this->renderSection('styles') ?>
</head>
<body>
<div class="container-scroller">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
            <a class="sidebar-brand brand-logo" href="/staff/dashboard" style="text-decoration: none;">
                <div class="text-center">
                    <h4 class="text-white font-weight-bold">N.J CIPRESS</h4>
                </div>
            </a>
            <a class="sidebar-brand brand-logo-mini" href="/staff/dashboard">
                <div class="text-center">
                    <h4 class="text-white font-weight-bold">NJC</h4>
                </div>
            </a>
        </div>
        <ul class="nav">
            <li class="nav-item profile">
                <div class="profile-desc">
                    <div class="profile-pic">
                        <div class="count-indicator">
                            <?php
                            $username = session()->get('staff_username') ?? 'Staff';
                            $initial = strtoupper(substr($username, 0, 1));
                            ?>
                            <div class="img-xs rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="color: #000; font-size: 1.2rem;">
                                <?= esc($initial) ?>
                            </div>
                            <span class="count bg-success"></span>
                        </div>
                        <div class="profile-name">
                            <h5 class="mb-0 font-weight-normal"><?= esc($username) ?></h5>
                            <span>Staff Member</span>
                        </div>
                    </div>
                </div>
            </li>
            <li class="nav-item nav-category">
                <span class="nav-link">Navigation</span>
            </li>

            <?php
            $uri = service('uri');
            $current_segment = $uri->getSegment(2);
            ?>

            <li class="nav-item menu-items <?= ($current_segment === 'dashboard') ? 'active' : '' ?>">
                <a class="nav-link" href="/staff/dashboard">
                    <span class="menu-icon"><i class="mdi mdi-view-dashboard"></i></span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item menu-items <?= ($current_segment === 'attendance') ? 'active' : '' ?>">
                <a class="nav-link" href="/staff/attendance">
                    <span class="menu-icon"><i class="mdi mdi-clock-fast"></i></span>
                    <span class="menu-title">Clock In / Out</span>
                </a>
            </li>

            <!-- [THE FIX] Added the Receipt link back -->
            <li class="nav-item menu-items <?= ($current_segment === 'receipt') ? 'active' : '' ?>">
                <a class="nav-link" href="#">
                    <span class="menu-icon"><i class="mdi mdi-receipt"></i></span>
                    <span class="menu-title">Receipt</span>
                </a>
            </li>

        </ul>
    </nav>

    <div class="container-fluid page-body-wrapper">
        <nav class="navbar p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                <a class="navbar-brand brand-logo-mini" href="#"><img src="<?= base_url('assets/images/logo-mini.svg') ?>" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                            <div class="navbar-profile">
                                <div class="img-xs rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="color: #000; font-size: 1.2rem;">
                                    <?= esc($initial) ?>
                                </div>
                                <p class="mb-0 d-none d-sm-block navbar-profile-name"><?= esc($username) ?></p>
                                <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                            <h6 class="p-3 mb-0">Profile</h6>
                            <div class="dropdown-divider"></div>
                            <a href="<?= site_url('staff/settings') ?>" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Settings</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= site_url('staff/logout') ?>" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-logout text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Log out</p>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-format-line-spacing"></span>
                </button>
            </div>
        </nav>
        <div class="main-panel">
            <div class="content-wrapper">
                <?= $this->renderSection('content') ?>
            </div>
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © N.J Cipress 2024</span>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
<script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
<script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
<script src="<?= base_url('assets/js/misc.js') ?>"></script>
<script src="<?= base_url('assets/js/settings.js') ?>"></script>
<script src="<?= base_url('assets/js/todolist.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
