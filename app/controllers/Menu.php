<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Menu extends Controller {

    public function index() {

        Authentication::guard();

        $menu_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$menu = Database::get('*', 'menus', ['menu_id' => $menu_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $store = Database::get(['store_id', 'domain_id', 'url'], 'stores', ['store_id' => $menu->store_id, 'user_id' => $this->user->user_id]);

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `categories` WHERE `menu_id` = {$menu->menu_id} AND `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('menu/' . $menu->menu_id . '?page=%d')));

        /* Get the categories */
        $categories = [];
        $categories_result = Database::$database->query("
            SELECT
                *
            FROM
                `categories`
            WHERE
                `menu_id` = {$menu->menu_id}
                AND `user_id` = {$this->user->user_id}
            LIMIT
                {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}
        ");
        while($row = $categories_result->fetch_object()) $categories[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('menu/menu_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('category/category_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'menu_id' => $menu->menu_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->menu->title, $menu->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'categories' => $categories,
            'total_categories' => $total_rows,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('menu/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $menu_id = (int) Database::clean_string($_POST['menu_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$menu = Database::get(['store_id', 'menu_id', 'image'], 'menus', ['user_id' => $this->user->user_id, 'menu_id' => $menu_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the items images first */
            $result = $this->database->query("SELECT `image` FROM `items` WHERE `menu_id` = {$menu->menu_id}");
            while($item = $result->fetch_object()) {
                if(!empty($item->image) && file_exists(UPLOADS_PATH . 'item_images/' . $item->image)) {
                    unlink(UPLOADS_PATH . 'item_images/' . $item->image);
                }
            }

            /* Delete the image if needed */
            if(!empty($menu->image) && file_exists(UPLOADS_PATH . 'menu_images/' . $menu->image)) {
                unlink(UPLOADS_PATH . 'menu_images/' . $menu->image);
            }

            /* Delete the menu */
            Database::$database->query("DELETE FROM `menus` WHERE `menu_id` = {$menu->menu_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $menu->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->menu_delete_modal->success_message;

            redirect('store/' . $menu->store_id);

        }

        die();
    }
}
