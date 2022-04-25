<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemExtra extends Controller {

    public function index() {

        die();

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $item_extra_id = (int) Database::clean_string($_POST['item_extra_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$item_extra = Database::get(['store_id', 'menu_id', 'category_id', 'item_id', 'item_extra_id'], 'items_extras', ['user_id' => $this->user->user_id, 'item_extra_id' => $item_extra_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the menu */
            Database::$database->query("DELETE FROM `items_extras` WHERE `item_extra_id` = {$item_extra->item_extra_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $item_extra->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->item_extra_delete_modal->success_message;

            redirect('item/' . $item_extra->item_id);

        }

        die();
    }
}
