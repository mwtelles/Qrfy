<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;

class AdminPlanUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $plan_id = isset($this->params[0]) ? $this->params[0] : false;

        /* Make sure it is either the trial / free plan or normal plans */
        switch($plan_id) {

            case 'free':

                /* Get the current settings for the free plan */
                $plan = $this->settings->plan_free;

                break;

            case 'trial':

                /* Get the current settings for the trial plan */
                $plan = $this->settings->plan_trial;

                break;

            default:

                $plan_id = (int) $plan_id;

                /* Check if plan exists */
                if(!$plan = Database::get('*', 'plans', ['plan_id' => $plan_id])) {
                    redirect('admin/plans');
                }

                /* Parse the settings of the plan */
                $plan->settings = json_decode($plan->settings);

                /* Parse the taxes */
                $plan->taxes_ids = json_decode($plan->taxes_ids);

                /* Get the available taxes from the system */
                $taxes = [];

                $result = $this->database->query("SELECT `tax_id`, `internal_name`, `name`, `description` FROM `taxes`");

                while($row = $result->fetch_object()) {
                    $taxes[] = $row;
                }

                break;

        }

        if(!empty($_POST)) {

            if (!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }


            /* Filter variables */
            $_POST['settings'] = [
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
            ];

            switch ($plan_id) {

                case 'free':

                    $_POST['name'] = Database::clean_string($_POST['name']);
                    $_POST['status'] = (int)$_POST['status'];

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) $this->settings->payment->is_enabled ? Database::$database->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` = 1")->fetch_object()->total ?? 0:0;

                        if(!$enabled_plans && !$this->settings->plan_trial->status) {
                            $_SESSION['error'][] = $this->language->admin_plan_update->error_message->disabled_plans;
                        }
                    }

                    $setting_key = 'plan_free';
                    $setting_value = json_encode([
                        'plan_id' => 'free',
                        'name' => $_POST['name'],
                        'days' => null,
                        'status' => $_POST['status'],
                        'settings' => $_POST['settings']
                    ]);

                    break;

                case 'trial':

                    $_POST['name'] = Database::clean_string($_POST['name']);
                    $_POST['days'] = (int)$_POST['days'];
                    $_POST['status'] = (int)$_POST['status'];

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) $this->settings->payment->is_enabled ? Database::$database->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` = 1")->fetch_object()->total ?? 0:0;

                        if(!$enabled_plans && !$this->settings->plan_free->status) {
                            $_SESSION['error'][] = $this->language->admin_plan_update->error_message->disabled_plans;
                        }
                    }

                    $setting_key = 'plan_trial';
                    $setting_value = json_encode([
                        'plan_id' => 'trial',
                        'name' => $_POST['name'],
                        'days' => $_POST['days'],
                        'status' => $_POST['status'],
                        'settings' => $_POST['settings']
                    ]);

                    break;

                default:

                    $_POST['name'] = Database::clean_string($_POST['name']);
                    $_POST['monthly_price'] = (float) $_POST['monthly_price'];
                    $_POST['annual_price'] = (float) $_POST['annual_price'];
                    $_POST['lifetime_price'] = (float) $_POST['lifetime_price'];
                    $_POST['status'] = (int) $_POST['status'];
                    $_POST['taxes_ids'] = json_encode(array_keys($_POST['taxes_ids'] ?? []));

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) Database::$database->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` = 1")->fetch_object()->total ?? 0;

                        if(
                            (
                                !$enabled_plans ||
                                ($enabled_plans == 1 && $plan->status))
                            && !$this->settings->plan_free->status
                            && !$this->settings->plan_trial->status
                        ) {
                            $_SESSION['error'][] = $this->language->admin_plan_update->error_message->disabled_plans;
                        }
                    }

                    break;

            }


            if(empty($_SESSION['error'])) {

                /* Update the plan in database */
                switch ($plan_id) {

                    case 'free':
                    case 'trial':

                        $stmt = Database::$database->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = ?");
                        $stmt->bind_param('ss', $setting_value, $setting_key);
                        $stmt->execute();
                        $stmt->close();

                        /* Clear the cache */
                        \Altum\Cache::$adapter->deleteItem('settings');

                        break;

                    default:

                        $settings = json_encode($_POST['settings']);

                        $stmt = Database::$database->prepare("UPDATE `plans` SET `name` = ?, `monthly_price` = ?, `annual_price` = ?, `lifetime_price` = ?, `settings` = ?, `taxes_ids` = ?, `status` = ? WHERE `plan_id` = ?");
                        $stmt->bind_param('ssssssss', $_POST['name'], $_POST['monthly_price'], $_POST['annual_price'], $_POST['lifetime_price'], $settings, $_POST['taxes_ids'], $_POST['status'], $plan_id);
                        $stmt->execute();
                        $stmt->close();

                        break;

                }

                /* Update all users plan settings with these ones */
                if(isset($_POST['submit_update_users_plan_settings'])) {

                    $plan_settings = json_encode($_POST['settings']);

                    $stmt = Database::$database->prepare("UPDATE `users` SET `plan_settings` = ? WHERE `plan_id` = ?");
                    $stmt->bind_param('ss', $plan_settings, $plan_id);
                    $stmt->execute();
                    $stmt->close();

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItemsByTag('users');

                }

                /* Set a nice success message */
                $_SESSION['success'][] = $this->language->global->success_message->basic;

                /* Refresh the page */
                redirect('admin/plan-update/' . $plan_id);

            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/plans/plan_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'plan_id'    => $plan_id,
            'plan'       => $plan,
            'taxes'      => $taxes ?? null
        ];

        $view = new \Altum\Views\View('admin/plan-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
