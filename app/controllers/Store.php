<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Routing\Router;
use Altum\Title;

class Store extends Controller {

    public function index() {

        Authentication::guard();

        $store_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        $is_qr = isset($_GET['qr']) ? (in_array($_GET['qr'], ['png', 'svg']) ? $_GET['qr'] : 'png') : null;

        if(!$store = Database::get('*', 'stores', ['store_id' => $store_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Return the QR code if requested via direct link */
        if($is_qr) {
            $qr = new \Endroid\QrCode\QrCode(url('s/' . $store->url));
            $qr->setSize(500);
            $qr->setWriterByName($is_qr);
            $qr->setEncoding('UTF-8');

            header('Content-Type: ' . $qr->getContentType());

            echo $qr->writeString();

            die();
        }

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `menus` WHERE `store_id` = {$store->store_id} AND `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('store/' . $store->store_id . '?page=%d')));

        /* Get the changelog posts */
        $menus = [];
        $menus_result = Database::$database->query("
            SELECT
                *
            FROM
                `menus`
            WHERE
                `store_id` = {$store->store_id}
                AND `user_id` = {$this->user->user_id}
            LIMIT
                {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}
        ");
        while($row = $menus_result->fetch_object()) $menus[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('store/store_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('menu/menu_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'store_id' => $store->store_id,
            'external_url' => url('s/' . $store->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->store->title, $store->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menus' => $menus,
            'total_menus' => $total_rows,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('store/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $store_id = (int) Database::clean_string($_POST['store_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$store = Database::get(['store_id', 'image', 'logo', 'favicon'], 'stores', ['user_id' => $this->user->user_id, 'store_id' => $store_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the items images */
            $result = $this->database->query("SELECT `image` FROM `items` WHERE `store_id` = {$store->store_id}");
            while($item = $result->fetch_object()) {
                if(!empty($item->image) && file_exists(UPLOADS_PATH . 'item_images/' . $item->image)) {
                    unlink(UPLOADS_PATH . 'item_images/' . $item->image);
                }
            }

            /* Delete the menu images */
            $result = $this->database->query("SELECT `image` FROM `menus` WHERE `store_id` = {$store->store_id}");
            while($menu = $result->fetch_object()) {
                if(!empty($menu->image) && file_exists(UPLOADS_PATH . 'menu_images/' . $menu->image)) {
                    unlink(UPLOADS_PATH . 'menu_images/' . $menu->image);
                }
            }

            /* Delete the image if needed */
            if(!empty($store->image) && file_exists(UPLOADS_PATH . 'store_images/' . $store->image)) {
                unlink(UPLOADS_PATH . 'store_images/' . $store->image);
            }

            if(!empty($store->favicon) && file_exists(UPLOADS_PATH . 'store_favicons/' . $store->favicon)) {
                unlink(UPLOADS_PATH . 'store_favicons/' . $store->favicon);
            }

            if(!empty($store->logo) && file_exists(UPLOADS_PATH . 'store_logos/' . $store->logo)) {
                unlink(UPLOADS_PATH . 'store_logos/' . $store->logo);
            }

            /* Delete the store */
            Database::$database->query("DELETE FROM `stores` WHERE `store_id` = {$store->store_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->store_delete_modal->success_message;

            redirect('dashboard');

        }

        die();
    }
}
