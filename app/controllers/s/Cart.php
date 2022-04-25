<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Meta;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Cart extends Controller {
    public $store;
    public $store_user = null;

    public $cart;

    public function index() {

        /* Parse & control the store */
        require_once APP_PATH . 'controllers/s/Store.php';
        $store_controller = new \Altum\Controllers\Store((array) $this);

        $store_controller->init();

        /* Check if the user has access */
        if(!$store_controller->has_access) {
            redirect('s/' . $store_controller->store->url);
        }

        /* Set the needed variables for the wrapper */
        $this->store_user = $store_controller->store_user;
        $this->store = $store_controller->store;

        if(!$this->store->cart_is_enabled) {
            redirect('s/' . $store_controller->store->url);
        }

        if(!empty($_POST)) {
            $_POST['type'] = in_array($_POST['type'], ['on_premise', 'takeaway', 'delivery']) ? trim(Database::clean_string($_POST['type'])) : 'on_premise';
            $final_price = 0;

            $details = null;

            switch($_POST['type']) {
                case 'on_premise':
                    $details = [
                        'number' => (int) $_POST['number']
                    ];
                    break;

                case 'takeaway':
                    $details = [
                        'phone' => trim(Database::clean_string($_POST['phone']))
                    ];
                    break;

                case 'delivery':
                    $details = [
                        'phone' => trim(Database::clean_string($_POST['phone'])),
                        'address' => trim(Database::clean_string($_POST['address']))
                    ];
                    break;
            }

            $details['name'] = trim(Database::clean_string($_POST['name']));
            $details['message'] = trim(Database::clean_string($_POST['message']));

            $details = json_encode($details);

            /* Go through each ordered item to make sure everything is in order */
            if(is_array($_POST['items'])) {
                foreach($_POST['items'] as $item_key => $item_value) {
                    $_POST['items'][$item_key]['item_id'] = (int)$_POST['items'][$item_key]['item_id'];
                    $_POST['items'][$item_key]['quantity'] = (int)$_POST['items'][$item_key]['quantity'];
                    if($_POST['items'][$item_key]['quantity'] <= 0) {
                        $_POST['items'][$item_key]['quantity'] = 1;
                    }

                    /* Check the item */
                    $item = Database::get(['item_id', 'category_id', 'menu_id', 'store_id', 'price'], 'items', ['item_id' => $item_value['item_id'], 'is_enabled' => '1', 'store_id' => $this->store->store_id]);

                    /* Make sure the item is enabled and exists */
                    if(!$item) {
                        unset($_POST['items'][$item_key]);
                        continue;
                    }

                    $_POST['items'][$item_key]['item'] = $item;
                    $_POST['items'][$item_key]['price'] = 0;

                    /* Iterate over the extras if needed */
                    if(isset($_POST['items'][$item_key]['extras']) && is_array($_POST['items'][$item_key]['extras']) && count($_POST['items'][$item_key]['extras'])) {
                        foreach($_POST['items'][$item_key]['extras'] as $item_extra_key => $item_extra_value) {
                            $_POST['items'][$item_key]['extras'][$item_extra_key] = (int) $_POST['items'][$item_key]['extras'][$item_extra_key];

                            /* Check the item */
                            $item_extra = Database::get(['item_extra_id', 'price'], 'items_extras', ['item_extra_id' => $item_extra_value, 'is_enabled' => '1', 'store_id' => $this->store->store_id]);

                            /* Make sure the item extra is enabled and exists */
                            if(!$item_extra) {
                                unset($_POST['items'][$item_key]['extras'][$item_extra_key]);
                                continue;
                            }

                            /* Add to the price */
                            $_POST['items'][$item_key]['price'] += (float) $item_extra->price;

                        }

                        $_POST['items'][$item_key]['item_extras_ids'] = json_encode($_POST['items'][$item_key]['extras']);

                    } else {
                        $_POST['items'][$item_key]['item_extras_ids'] = null;
                    }

                    /* Check the item variant if any */
                    if($_POST['items'][$item_key]['item_variant_id']) {
                        $_POST['items'][$item_key]['item_variant_id'] = (int) $_POST['items'][$item_key]['item_variant_id'];

                        /* Check the item */
                        $item_variant = Database::get(['item_variant_id', 'price'], 'items_variants', ['item_variant_id' => $_POST['items'][$item_key]['item_variant_id'], 'is_enabled' => '1', 'store_id' => $this->store->store_id]);

                        /* Make sure the item variant is enabled and exists */
                        if(!$item_variant) {
                            unset($_POST['items'][$item_key]);
                            continue;
                        }

                        /* Add to the price */
                        $_POST['items'][$item_key]['price'] += (float) $item_variant->price;
                    } else {

                        $_POST['items'][$item_key]['item_variant_id'] = null;

                        /* Add to the price */
                        $_POST['items'][$item_key]['price'] += (float) $item->price;
                    }

                    /* Add to the price */
                    $_POST['items'][$item_key]['price'] = $_POST['items'][$item_key]['price'] * $_POST['items'][$item_key]['quantity'];

                    /* Add to the final price */
                    $final_price += (float) $_POST['items'][$item_key]['price'];
                }
            }

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(!is_array($_POST['items']) || (is_array($_POST['items']) && !count($_POST['items']))) {
                redirect('s/' . $store_controller->store->url . '?page=cart');
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `orders` (`store_id`, `user_id`, `type`, `details`, `price`, `datetime`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssss', $this->store->store_id, $this->store->user_id, $_POST['type'], $details, $final_price, \Altum\Date::$date);
                $stmt->execute();
                $order_id = $stmt->insert_id;
                $stmt->close();

                /* Insert all the ordered items */
                foreach($_POST['items'] as $row) {

                    /* Prepare the statement and execute query */
                    $stmt = Database::$database->prepare("INSERT INTO `orders_items` (`order_id`, `item_variant_id`, `item_id`, `category_id`, `menu_id`, `store_id`, `item_extras_ids`, `price`, `quantity`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('ssssssssss', $order_id, $row['item_variant_id'], $row['item']->item_id, $row['item']->category_id, $row['item']->menu_id, $row['item']->store_id, $row['item_extras_ids'], $row['price'], $row['quantity'], \Altum\Date::$date);
                    $stmt->execute();
                    $stmt->close();

                }

                redirect('s/' . $store_controller->store->url . '?page=cart&order=done');
            }
        }

        /* Set a custom title */
        Title::set(sprintf($this->language->s_cart->title, $this->store->name));

        /* Prepare the header */
        $view = new \Altum\Views\View('s/partials/header', (array) $this);
        $this->add_view_content('header', $view->run(['store' => $this->store]));

        /* Main View */
        $data = [
            'store' => $this->store,
            'store_user' => $this->store_user,
        ];

        $view = new \Altum\Views\View('s/cart/' . $this->store->theme . '/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


}
