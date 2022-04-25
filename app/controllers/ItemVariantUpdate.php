<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemVariantUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $item_variant_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item_variant = Database::get('*', 'items_variants', ['item_variant_id' => $item_variant_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $item_variant->item_options_ids = json_decode($item_variant->item_options_ids);

        /* Get all the available options for this item */
        $item_options = [];
        $item_options_result = Database::$database->query("
            SELECT
                *
            FROM
                `items_options`
            WHERE
                `item_id` = {$item_variant->item_id}
                AND `user_id` = {$this->user->user_id}
        ");
        while($row = $item_options_result->fetch_object()) {
            $row->options = json_decode($row->options);
            $item_options[$row->item_option_id] = $row;
        }

        $item = Database::get(['item_id', 'url'], 'items', ['item_id' => $item_variant->item_id, 'user_id' => $this->user->user_id]);
        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item_variant->category_id, 'user_id' => $this->user->user_id]);
        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item_variant->menu_id, 'user_id' => $this->user->user_id]);
        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $item_variant->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['price'] = (float) trim(Database::clean_string($_POST['price']));
            $_POST['is_enabled'] = (int) (bool) isset($_POST['is_enabled']);

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
                $stmt = Database::$database->prepare("UPDATE `items_variants` SET `item_options_ids` = ?, `price` = ?, `last_datetime` = ? WHERE `item_variant_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssss', $item_options_ids, $_POST['price'], \Altum\Date::$date, $item_variant->item_variant_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_variant_update->success_message;

                redirect('item-variant-update/' . $item_variant->item_variant_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('item-variant/item_variant_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'item_variant_id' => $item_variant->item_variant_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url . '/' . $item->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
            'item_variant' => $item_variant,
            'item_options' => $item_options
        ];

        $view = new \Altum\Views\View('item-variant-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
