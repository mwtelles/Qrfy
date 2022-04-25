<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Plan;
use Altum\Middlewares\Authentication;

class AdminUserUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $user_id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Check if user exists */
        if(!$user = Database::get('*', 'users', ['user_id' => $user_id])) {
            $_SESSION['error'][] = $this->language->admin_user_update->error_message->invalid_account;
            redirect('admin/users');
        }

        /* Get current plan proper details */
        $user->plan = (new Plan(['settings' => $this->settings]))->get_plan_by_id($user->plan_id);

        /* Check if its a custom plan */
        if($user->plan->plan_id == 'custom') {
            $user->plan->settings = json_decode($user->plan_settings);
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['is_enabled']	= (int) $_POST['is_enabled'];
            $_POST['type']	    = (int) $_POST['type'];
            $_POST['plan_trial_done'] = (int) $_POST['plan_trial_done'];

            switch($_POST['plan_id']) {
                case 'free':

                    $plan_settings = json_encode($this->settings->plan_free->settings);

                    break;

                case 'trial':

                    $plan_settings = json_encode($this->settings->plan_trial->settings);

                    break;

                case 'custom':

                    $plan_settings = json_encode([
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
                        'no_ads'                            => (bool) isset($_POST['no_ads']),
                    ]);

                    break;

                default:

                    $_POST['plan_id'] = (int) $_POST['plan_id'];

                    /* Make sure this plan exists */
                    if(!$plan_settings = Database::simple_get('settings', 'plans', ['plan_id' => $_POST['plan_id']])) {
                        redirect('admin/user-update/' . $user->user_id);
                    }

                    break;
            }

            $_POST['plan_expiration_date'] = (new \DateTime($_POST['plan_expiration_date']))->format('Y-m-d H:i:s');

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 32) {
                $_SESSION['error'][] = $this->language->admin_user_update->error_message->name_length;
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                $_SESSION['error'][] = $this->language->admin_user_update->error_message->invalid_email;
            }

            if(Database::exists('user_id', 'users', ['email' => $_POST['email']]) && $_POST['email'] !== Database::simple_get('email', 'users', ['user_id' => $user->user_id])) {
                $_SESSION['error'][] = $this->language->admin_user_update->error_message->email_exists;
            }

            if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                if(strlen(trim($_POST['new_password'])) < 6) {
                    $_SESSION['error'][] = $this->language->admin_user_update->error_message->short_password;
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    $_SESSION['error'][] = $this->language->admin_user_update->error_message->passwords_not_matching;
                }
            }


            if(empty($_SESSION['error'])) {

                /* Update the basic user settings */
                $stmt = Database::$database->prepare("
                    UPDATE
                        `users`
                    SET
                        `name` = ?,
                        `email` = ?,
                        `active` = ?,
                        `type` = ?,
                        `plan_id` = ?,
                        `plan_expiration_date` = ?,
                        `plan_settings` = ?,
                        `plan_trial_done` = ?
                    WHERE
                        `user_id` = ?
                ");
                $stmt->bind_param(
                    'sssssssss',
                    $_POST['name'],
                    $_POST['email'],
                    $_POST['is_enabled'],
                    $_POST['type'],
                    $_POST['plan_id'],
                    $_POST['plan_expiration_date'],
                    $plan_settings,
                    $_POST['plan_trial_done'],
                    $user->user_id
                );
                $stmt->execute();
                $stmt->close();

                /* Update the password if set */
                if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    $stmt = Database::$database->prepare("UPDATE `users` SET `password` = ?  WHERE `user_id` = {$user->user_id}");
                    $stmt->bind_param('s', $new_password);
                    $stmt->execute();
                    $stmt->close();
                }

                $_SESSION['success'][] = $this->language->global->success_message->basic;

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user->user_id);

                redirect('admin/user-update/' . $user->user_id);
            }

        }


        /* Get all the plans available */
        $plans_result = Database::$database->query("SELECT * FROM `plans` WHERE `status` = 1");

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/users/user_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Login Modal */
        $view = new \Altum\Views\View('admin/users/user_login_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'user'              => $user,
            'plans_result'   => $plans_result,
        ];

        $view = new \Altum\Views\View('admin/user-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
