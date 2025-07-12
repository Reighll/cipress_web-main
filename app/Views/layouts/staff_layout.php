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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-container {
            background-color: #434343;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }
        /* Customer Info Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: #1f2937;
            padding: 2rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 400px;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper" style="padding-left: 0; width: 100%;">

        <nav class="navbar p-0 fixed-top d-flex flex-row" style="left: 0;">
            <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                <a class="navbar-brand brand-logo-mini" href="#"><img src="<?= base_url('assets/images/logo-mini.svg') ?>" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                <a class="navbar-brand" href="/staff/dashboard" style="text-decoration: none;">
                    <div class="text-left">
                        <h4 class="text-white font-weight-bold">N.J CIPRESS</h4>
                        <p class="text-muted" style="font-size: 0.7rem;">GENERAL MERCHANDISE</p>
                    </div>
                </a>
                <ul class="navbar-nav navbar-nav-right ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                            <div class="navbar-profile">
                                <?php
                                $username = session()->get('staff_username') ?? 'Staff';
                                $initial = strtoupper(substr($username, 0, 1));
                                ?>
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
        <div class="main-panel" style="width: 100%;">
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