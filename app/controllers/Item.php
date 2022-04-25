<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Item extends Controller {

    public function index() {

        Authentication::guard();

        $item_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item = Database::get('*', 'items', ['item_id' => $item_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item->category_id, 'user_id' => $this->user->user_id]);

        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item->menu_id, 'user_id' => $this->user->user_id]);

        $store = Database::get(['store_id', 'domain_id', 'url', 'currency'], 'stores', ['store_id' => $item->store_id, 'user_id' => $this->user->user_id]);

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Get the item extras */
        $item_extras = [];
        $item_extras_result = Database::$database->query("
            SELECT
                *
            FROM
                `items_extras`
            WHERE
                `item_id` = {$item->item_id}
                AND `user_id` = {$this->user->user_id}
        ");
        while($row = $item_extras_result->fetch_object()) $item_extras[] = $row;

        /* We need extra data if the product has variants enabled */
        if($item->variants_is_enabled) {

            /* Get the item options */
            $item_options = [];
            $item_options_result = Database::$database->query("
                SELECT
                    *
                FROM
                    `items_options`
                WHERE
                    `item_id` = {$item->item_id}
                    AND `user_id` = {$this->user->user_id}
            ");
            while($row = $item_options_result->fetch_object()) {
                $row->options = json_decode($row->options);
                $item_options[$row->item_option_id] = $row;
            }

            /* Get the item variants */
            $item_variants = [];
            $item_variants_result = Database::$database->query("
                SELECT
                    *
                FROM
                    `items_variants`
                WHERE
                    `item_id` = {$item->item_id}
                    AND `user_id` = {$this->user->user_id}
            ");
            while($row = $item_variants_result->fetch_object()) {
                $row->item_options_ids = json_decode($row->item_options_ids);

                $item_variants[] = $row;
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('item/item_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('item-extra/item_extra_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('item-option/item_option_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('item-variant/item_variant_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'item_id' => $item->item_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url . '/' . $item->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->item->title, $item->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
            'item_extras' => $item_extras,
            'item_options' => $item_options ?? null,
            'item_variants' => $item_variants ?? null,
        ];

        $view = new \Altum\Views\View('item/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $item_id = (int) Database::clean_string($_POST['item_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$item = Database::get(['store_id', 'menu_id', 'category_id', 'item_id', 'image'], 'items', ['user_id' => $this->user->user_id, 'item_id' => $item_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the image if needed */
            if(!empty($item->image) && file_exists(UPLOADS_PATH . 'item_images/' . $item->image)) {
                unlink(UPLOADS_PATH . 'item_images/' . $item->image);
            }

            /* Delete the item */
            Database::$database->query("DELETE FROM `items` WHERE `item_id` = {$item->item_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $item->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->item_delete_modal->success_message;

            redirect('category/' . $item->category_id);

        }

        die();
    }
}
