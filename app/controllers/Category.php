<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Category extends Controller {

    public function index() {

        Authentication::guard();

        $category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$category = Database::get('*', 'categories', ['category_id' => $category_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $category->menu_id, 'user_id' => $this->user->user_id]);

        $store = Database::get(['store_id', 'domain_id', 'url', 'currency'], 'stores', ['store_id' => $category->store_id, 'user_id' => $this->user->user_id]);

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `items` WHERE `category_id` = {$category->category_id} AND `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('category/' . $category->category_id . '?page=%d')));

        /* Get the items */
        $items = [];
        $items_result = Database::$database->query("
            SELECT
                *
            FROM
                `items`
            WHERE
                `category_id` = {$category->category_id}
                AND `user_id` = {$this->user->user_id}
            LIMIT
                {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}
        ");
        while($row = $items_result->fetch_object()) $items[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('category/category_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('item/item_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'category_id' => $category->category_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->category->title, $category->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'items' => $items,
            'total_items' => $total_rows,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('category/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $category_id = (int) Database::clean_string($_POST['category_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$category = Database::get(['store_id', 'menu_id', 'category_id'], 'categories', ['user_id' => $this->user->user_id, 'category_id' => $category_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the menu */
            Database::$database->query("DELETE FROM `categories` WHERE `category_id` = {$category->category_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $category->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->category_delete_modal->success_message;

            redirect('menu/' . $category->menu_id);

        }

        die();
    }
}
