<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class ItemVariantCreate extends Controller {

    public function index() {

        Authentication::guard();

        $item_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item = Database::get(['item_id', 'category_id', 'menu_id', 'store_id', 'url'], 'items', ['item_id' => $item_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        /* Get all the available options for this item */
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

        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item->category_id, 'user_id' => $this->user->user_id]);

        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item->menu_id, 'user_id' => $this->user->user_id]);

        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $item->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['price'] = (float) trim(Database::clean_string($_POST['price']));
            $is_enabled = 1;

            /* Process the submitted options */
            $item_options_ids = [];

            foreach($_POST['item_options_ids'] as $key => $value) {
                if(isset($item_options[$key])) {
                    $item_options_ids[] = [
                        'item_option_id' => (int) $key,
                        'option' => (int) $value
                    ];
                } else {
                    unset($_POST['item_options_ids'][$key]);
                }
            }

            $item_options_ids = json_encode($item_options_ids);

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `items_variants` (`item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `price`, `item_options_ids`, `is_enabled`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssssss', $item->item_id, $category->category_id, $menu->menu_id, $store->store_id, $this->user->user_id, $_POST['price'], $item_options_ids, $is_enabled, \Altum\Date::$date);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_variant_create->success_message;

                redirect('item/' . $item->item_id);
            }

        }

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
            'item_options' => $item_options
        ];

        $view = new \Altum\Views\View('item-variant-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
