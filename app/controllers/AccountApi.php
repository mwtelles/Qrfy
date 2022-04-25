<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AccountApi extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $api_key = md5($_POST['email'] . microtime() . microtime());

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `users` SET `api_key` = ? WHERE `user_id` = ?");
                $stmt->bind_param('ss', $api_key, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                $_SESSION['success'][] = $this->language->account_api->success_message;

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('account-api');
            }

        }

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Prepare the View */
        $view = new \Altum\Views\View('account-api/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}
