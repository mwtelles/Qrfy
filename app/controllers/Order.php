<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Order extends Controller {

    public function index() {

        Authentication::guard();

        $order_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$order = Database::get('*', 'orders', ['order_id' => $order_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $order->details = json_decode($order->details);

        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $order->store_id, 'user_id' => $this->user->user_id]);

        /* Get the categories */
        $order_items = [];
        $order_items_result = Database::$database->query("
            SELECT
                *
            FROM
                `orders_items`
            WHERE
                `order_id` = {$order->order_id}
        ");
        while($row = $order_items_result->fetch_object()) {

            /* Get the item details */
            $row->item = (new \Altum\Models\Item())->get_item_by_store_id_and_item_id($store->store_id, $row->item_id);

            /* Get the item_variant details */
            $row->item_variant = $row->item_variant_id ? (new \Altum\Models\ItemVariants())->get_item_variant_by_store_id_and_item_variant_id($store->store_id, $row->item_variant_id) : null;

            /* Get the item_extras details */
            $row->item_extras_ids = $row->item_extras_ids ? json_decode($row->item_extras_ids) : null;
            $row->item_extras = $row->item_extras_ids ? (new \Altum\Models\ItemExtras())->get_item_extras_by_store_id_and_item_extras_ids($store->store_id, $row->item_extras_ids) : null;

            $order_items[] = $row;
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('order/order_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub order view */
        $data = [
            'order_id' => $order->order_id,
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'order' => $order,
            'order_items' => $order_items,
        ];

        $view = new \Altum\Views\View('order/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function complete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $order_id = (int) Database::clean_string($_POST['order_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$order = Database::get(['store_id', 'order_id'], 'orders', ['user_id' => $this->user->user_id, 'order_id' => $order_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the order */
            Database::$database->query("UPDATE `orders` SET `status` = 1 WHERE `order_id` = {$order->order_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $order->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->order->success_message_complete;

            redirect('orders/' . $order->store_id);

        }

        die();
    }

    public function delete() {

        Authentication::guard();

        if(empty($_POST)) {
            die();
        }

        $order_id = (int) Database::clean_string($_POST['order_id']);

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('dashboard');
        }

        /* Make sure the store id is created by the logged in user */
        if(!$order = Database::get(['store_id', 'order_id'], 'orders', ['user_id' => $this->user->user_id, 'order_id' => $order_id])) {
            redirect('dashboard');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the order */
            Database::$database->query("DELETE FROM `orders` WHERE `order_id` = {$order->order_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $order->store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->order_delete_modal->success_message;

            redirect('orders/' . $order->store_id);

        }

        die();
    }
}
