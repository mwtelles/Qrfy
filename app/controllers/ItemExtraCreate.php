<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class ItemExtraCreate extends Controller {

    public function index() {

        Authentication::guard();

        $item_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item = Database::get(['item_id', 'category_id', 'menu_id', 'store_id', 'url'], 'items', ['item_id' => $item_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item->category_id, 'user_id' => $this->user->user_id]);

        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item->menu_id, 'user_id' => $this->user->user_id]);

        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $item->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['price'] = (float) trim(Database::clean_string($_POST['price']));

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `items_extras` (`item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `name`, `description`, `price`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssssss', $item->item_id, $category->category_id, $menu->menu_id, $store->store_id, $this->user->user_id, $_POST['name'], $_POST['description'], $_POST['price'], \Altum\Date::$date);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_extra_create->success_message;

                redirect('item/' . $item->item_id);
            }

        }

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item
        ];

        $view = new \Altum\Views\View('item-extra-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
