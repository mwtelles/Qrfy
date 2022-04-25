<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemVariant extends Controller {

    public function index() {

        die();

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $item_variant_id = (int) Database::clean_string($_POST['item_variant_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$item_variant = Database::get(['store_id', 'menu_id', 'category_id', 'item_id', 'item_variant_id'], 'items_variants', ['user_id' => $this->user->user_id, 'item_variant_id' => $item_variant_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the menu */
            Database::$database->query("DELETE FROM `items_variants` WHERE `item_variant_id` = {$item_variant->item_variant_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $item_variant->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->item_variant_delete_modal->success_message;

            redirect('item/' . $item_variant->item_id);

        }

        die();
    }
}
