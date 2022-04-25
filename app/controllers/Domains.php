<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class Domains extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->settings->stores->domains_is_enabled) {
            redirect('dashboard');
        }

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `domains` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('domains?page=%d')));

        /* Get the domains list for the user */
        $domains = [];
        $domains_result = Database::$database->query("SELECT * FROM `domains` WHERE `user_id` = {$this->user->user_id} LIMIT {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}");
        while($row = $domains_result->fetch_object()) $domains[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Delete Modal */
        $view = new \Altum\Views\View('domains/domain_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'domains' => $domains,
            'total_domains' => $total_rows,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('domains/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $domain_id = (int) Database::clean_string($_POST['store_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        if(!$domain = Database::get(['domain_id'], 'domains', ['domain_id' => $domain_id, 'user_id' => $this->user->user_id])) {
            redirect('domains');
        }

        if(empty($_SESSION['error'])) {

            /* Delete everything related to the stores that the user owns */
            $result = $this->database->query("SELECT `store_id`, `image`, `logo`, `favicon` FROM `stores` WHERE `domain_id` = {$domain->domain_id}");

            while($store = $result->fetch_object()) {

                /* Delete the items images */
                $result = $this->database->query("SELECT `image` FROM `items` WHERE `store_id` = {$store->store_id}");
                while ($item = $result->fetch_object()) {
                    if (!empty($item->image) && file_exists(UPLOADS_PATH . 'item_images/' . $item->image)) {
                        unlink(UPLOADS_PATH . 'item_images/' . $item->image);
                    }
                }

                /* Delete the menu images */
                $result = $this->database->query("SELECT `image` FROM `menus` WHERE `store_id` = {$store->store_id}");
                while ($menu = $result->fetch_object()) {
                    if (!empty($menu->image) && file_exists(UPLOADS_PATH . 'menu_images/' . $menu->image)) {
                        unlink(UPLOADS_PATH . 'menu_images/' . $menu->image);
                    }
                }

                /* Delete the image if needed */
                if (!empty($store->image) && file_exists(UPLOADS_PATH . 'store_images/' . $store->image)) {
                    unlink(UPLOADS_PATH . 'store_images/' . $store->image);
                }

                if (!empty($store->favicon) && file_exists(UPLOADS_PATH . 'store_favicons/' . $store->favicon)) {
                    unlink(UPLOADS_PATH . 'store_favicons/' . $store->favicon);
                }

                if (!empty($store->logo) && file_exists(UPLOADS_PATH . 'store_logos/' . $store->logo)) {
                    unlink(UPLOADS_PATH . 'store_logos/' . $store->logo);
                }

                /* Delete the store */
                Database::$database->query("DELETE FROM `stores` WHERE `store_id` = {$store->store_id}");

                /* Clear cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);
            }

            /* Delete the domain */
            $this->database->query("DELETE FROM `domains` WHERE `domain_id` = {$domain->domain_id}");

            /* Success message */
            $_SESSION['success'][] = $this->language->domain_delete_modal->success_message;

            redirect('dashboard');

        }

        die();
    }
}
