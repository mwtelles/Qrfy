<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemOption extends Controller {

    public function index() {

        die();

    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $item_option_id = (int) Database::clean_string($_POST['item_option_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$item_option = Database::get(['store_id', 'menu_id', 'category_id', 'item_id', 'item_option_id'], 'items_options', ['user_id' => $this->user->user_id, 'item_option_id' => $item_option_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the menu */
            Database::$database->query("DELETE FROM `items_options` WHERE `item_option_id` = {$item_option->item_option_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $item_option->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->item_option_delete_modal->success_message;

            redirect('item/' . $item_option->item_id);

        }

        die();
    }
}
