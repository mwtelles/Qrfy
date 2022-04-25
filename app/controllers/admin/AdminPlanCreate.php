<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;

class AdminPlanCreate extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Get the available taxes from the system */
        $taxes = [];

        $result = $this->database->query("SELECT `tax_id`, `internal_name`, `name`, `description` FROM `taxes`");

        while($row = $result->fetch_object()) {
            $taxes[] = $row;
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['monthly_price'] = (float) $_POST['monthly_price'];
            $_POST['annual_price'] = (float) $_POST['annual_price'];
            $_POST['lifetime_price'] = (float) $_POST['lifetime_price'];

            $_POST['settings'] = json_encode([
                'stores_limit'                      => (int) $_POST['stores_limit'],
                'menus_limit'                       => (int) $_POST['menus_limit'],
                'categories_limit'                  => (int) $_POST['categories_limit'],
                'items_limit'                       => (int) $_POST['items_limit'],
                'domains_limit'                      => (int) $_POST['domains_limit'],

                'ordering_is_enabled'               => (bool) isset($_POST['ordering_is_enabled']),
                'additional_domains_is_enabled'     => (bool) isset($_POST['additional_domains_is_enabled']),
                'analytics_is_enabled'              => (bool) isset($_POST['analytics_is_enabled']),
                'removable_branding_is_enabled'     => (bool) isset($_POST['removable_branding_is_enabled']),
                'custom_url_is_enabled'             => (bool) isset($_POST['custom_url_is_enabled']),
                'password_protection_is_enabled'    => (bool) isset($_POST['password_protection_is_enabled']),
                'search_engine_block_is_enabled'    => (bool) isset($_POST['search_engine_block_is_enabled']),
                'custom_css_is_enabled'             => (bool) isset($_POST['custom_css_is_enabled']),
                'custom_js_is_enabled'              => (bool) isset($_POST['custom_js_is_enabled']),
                'email_reports_is_enabled'          => (bool) isset($_POST['email_reports_is_enabled']),
                'no_ads'                            => (bool) isset($_POST['no_ads'])
            ]);

            $_POST['status'] = (int) $_POST['status'];
            $_POST['taxes_ids'] = json_encode(array_keys($_POST['taxes_ids'] ?? []));

            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {
                /* Update the database */
                $stmt = Database::$database->prepare("INSERT INTO `plans` (`name`, `monthly_price`, `annual_price`, `lifetime_price`, `settings`, `taxes_ids`, `status`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssss', $_POST['name'], $_POST['monthly_price'], $_POST['annual_price'], $_POST['lifetime_price'], $_POST['settings'], $_POST['taxes_ids'], $_POST['status'], Date::$date);
                $stmt->execute();
                $stmt->close();

                /* Set a nice success message */
                $_SESSION['success'][] = $this->language->global->success_message->basic;

                redirect('admin/plans');
            }
        }


        /* Main View */
        $data = [
            'taxes' => $taxes ?? null
        ];

        $view = new \Altum\Views\View('admin/plan-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
