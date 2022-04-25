<?php
define('ROOT', realpath(__DIR__ . '/..') . '/');
if(file_exists(ROOT . 'install/installed')) {
    @header('Location:../index.php');
    exit;
}
require_once ROOT . 'app/includes/product.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicons/favicon-16x16.png">
    <link rel="manifest" href="./assets/favicons/site.webmanifest">
    <link rel="mask-icon" href="./assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="./assets/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/assets/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <title><?= PRODUCT_NAME ?> Installation</title>
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="d-flex">
                <div class="mr-3">
                    <img src="./assets/images/logo.png" class="img-fluid logo" alt="AltumCode logo" />
                </div>

                <div class="d-flex flex-column justify-content-center">
                    <h1>Installation</h1>
                    <p class="subheader d-flex flex-row">
                        <span class="text-muted">
                            <a href="<?= PRODUCT_URL ?>" target="_blank" class="text-gray-500"><?= PRODUCT_NAME ?></a> by <a href="https://altumco.de/site" target="_blank" class="text-gray-500">AltumCode</a>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="row">

                <div class="col col-md-3 d-none d-md-block">

                    <nav class="nav sidebar-nav">
                        <ul class="sidebar" id="sidebar-ul">
                            <li class="nav-item">
                                <a href="#welcome" class="navigator nav-link">Welcome</a>
                            </li>

                            <li class="nav-item">
                                <a href="#agreement" class="navigator nav-link" style="display: none">Agreement</a>
                            </li>

                            <li class="nav-item">
                                <a href="#requirements" class="navigator nav-link" style="display: none">Requirements</a>
                            </li>

                            <li class="nav-item">
                                <a href="#setup" class="navigator nav-link" style="display: none">Setup</a>
                            </li>

                            <li class="nav-item">
                                <a href="#finish" class="navigator nav-link" style="display: none">Finish</a>
                            </li>
                        </ul>
                    </nav>

                </div>

                <div class="col" id="content">

                    <section id="welcome" style="display: none">
                        <h2>Welcome</h2>
                        <p>The installation process should take less than <strong>5 minutes</strong> if you've done everything else from the documentation.</p>

                        <p>Thank you for choosing to use the product.</p>

                        <a href="#requirements" id="welcome_start" class="navigator btn btn-primary">Start the installation</a>
                    </section>

                    <section id="requirements" style="display: none">
                        <?php $requirements = true ?>
                        <h2>Requirements</h2>
                        <p>Make sure everything is checked so that you do not run into problems.</p>

                        <table class="table mt-5">
                            <thead>
                                <th class="bg-gray-200"></th>
                                <th class="bg-gray-200">Required</th>
                                <th class="bg-gray-200">Current</th>
                                <th class="bg-gray-200"></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PHP Version</td>
                                    <td>7.4+</td>
                                    <td><?= PHP_VERSION ?></td>
                                    <td>
                                        <?php if(version_compare(PHP_VERSION, '7.4.0') >= 0): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>cURL</td>
                                    <td>Enabled</td>
                                    <td><?= function_exists('curl_version') ? 'Enabled' : 'Not Enabled' ?></td>
                                    <td>
                                        <?php if(function_exists('curl_version')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>OpenSSL</td>
                                    <td>Enabled</td>
                                    <td><?= extension_loaded('openssl') ? 'Enabled' : 'Not Enabled' ?></td>
                                    <td>
                                        <?php if(extension_loaded('openssl')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>mbstring</td>
                                    <td>Enabled</td>
                                    <td><?= extension_loaded('mbstring') && function_exists('mb_get_info') ? 'Enabled' : 'Not Enabled' ?></td>
                                    <td>
                                        <?php if(extension_loaded('mbstring') && function_exists('mb_get_info')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>MySQLi</td>
                                    <td>Enabled</td>
                                    <td><?= function_exists('mysqli_connect') ? 'Enabled' : 'Not Enabled' ?></td>
                                    <td>
                                        <?php if(function_exists('mysqli_connect')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table mt-5">
                            <thead>
                                <th class="bg-gray-200">Path / File</th>
                                <th class="bg-gray-200">Status</th>
                                <th class="bg-gray-200"></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>/uploads/favicon/</td>
                                    <td><?= is_writable(ROOT . 'uploads/favicon/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/favicon/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/logo/</td>
                                    <td><?= is_writable(ROOT . 'uploads/logo/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/logo/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/cache/</td>
                                    <td><?= is_writable(ROOT . 'uploads/cache/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/cache/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/offline_payment_proofs/</td>
                                    <td><?= is_writable(ROOT . 'uploads/offline_payment_proofs/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/offline_payment_proofs/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/store_logos/</td>
                                    <td><?= is_writable(ROOT . 'uploads/store_logos/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/store_logos/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/store_favicons/</td>
                                    <td><?= is_writable(ROOT . 'uploads/store_favicons/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/store_favicons/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/store_images/</td>
                                    <td><?= is_writable(ROOT . 'uploads/store_images/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/store_images/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/menu_images/</td>
                                    <td><?= is_writable(ROOT . 'uploads/menu_images/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/menu_images/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/uploads/item_images/</td>
                                    <td><?= is_writable(ROOT . 'uploads/item_images/') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'uploads/item_images/')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>/config.php</td>
                                    <td><?= is_writable(ROOT . 'config.php') ? 'Writable' : 'Not Writable' ?></td>
                                    <td>
                                        <?php if(is_writable(ROOT . 'config.php')): ?>
                                            <img src="assets/svg/check-circle-solid.svg" class="img-fluid img-icon text-success" />
                                        <?php else: ?>
                                            <img src="assets/svg/times-circle-solid.svg" class="img-fluid img-icon text-danger" />
                                            <?php $requirements = false; ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <?php if(1 == 1): ?>
                                <a href="#setup" class="navigator btn btn-primary">Next</a>
                            <?php else: ?>
                                <div class="alert alert-danger" role="alert">
                                    Please make sure all the requirements listed on the documentation and on this page are met before continuing!
                                </div>
                                <p class="text-danger"></p>
                            <?php endif ?>
                        </div>
                    </section>

                    <section id="setup" style="display: none">
                        <h2>Setup</h2>

                        <form id="setup_form" method="post" action="" role="form">
                            <div class="form-group d-none" style="display:none;">
                                <label for="license">License*</label>
                                <input type="text" class="form-control" id="license" name="license" aria-describedby="license_help" placeholder="123abc45-6789de-fgh10-ijkl11-mno1213pq">
                                <small id="license_help" class="form-text text-muted">The Purchase Code you got after purchasing the product.</small>
                            </div>

                            <h3 class="mt-5">Database Details</h3>
                            <p>Here are the connection details of the database that you want to use for this product.</p>

                            <div class="form-group row">
                                <label for="database_host" class="col-sm-2 col-form-label">Host*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="database_host" name="database_host" value="localhost" required="required">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="database_name" class="col-sm-2 col-form-label">Name*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="database_name" name="database_name" required="required">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="database_username" class="col-sm-2 col-form-label">Username*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="database_username" name="database_username" required="required">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="database_password" class="col-sm-2 col-form-label">Password*</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="database_password" name="database_password">
                                </div>
                            </div>

                            <h3 class="mt-5">General</h3>

                            <div class="form-group row">
                                <label for="url" class="col-sm-2 col-form-label">Website URL*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com/" aria-describedby="url_help" required="required">
                                    <small id="url_help" class="form-text text-muted">Make sure to specify the full url of the installation path of the product.<br /> Subdomain example: <code>https://subdomain.domain.com/</code> <br />Subfolder example: <code>https://domain.com/product/</code></small>
                                </div>
                            </div>

                            <h3 class="mt-5">Get exclusive updates & discounts</h3>
                            <p>Sign up for the exclusive mail list for verified customers only (optional).</p>
                            <p>I'm going to send you stuff like: <strong>exclusive discounts</strong>, <strong>updates</strong> and <strong>new products</strong>.</p>

                            <div class="form-group row">
                                <label for="client_email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="client_email" name="client_email" placeholder="Your valid email address">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="client_name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Your name">
                                </div>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary mt-5">Finish Installation</button>
                        </form>
                    </section>

                    <section id="finish" style="display: none">
                        <h2>Installation Completed</h2>
                        <p class="text-success">Congratulations! The installation has been successful!</p>

                        <p>You can now login with the following information:</p>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>URL</th>
                                    <td><a href="" id="final_url"></a></td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>admin</td>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <td>admin</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                </div>

            </div>
        </div>
    </main>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
