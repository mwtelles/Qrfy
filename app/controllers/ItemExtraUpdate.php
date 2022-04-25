<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemExtraUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $item_extra_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item_extra = Database::get('*', 'items_extras', ['item_extra_id' => $item_extra_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $item = Database::get(['item_id', 'url'], 'items', ['item_id' => $item_extra->item_id, 'user_id' => $this->user->user_id]);
        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item_extra->category_id, 'user_id' => $this->user->user_id]);
        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item_extra->menu_id, 'user_id' => $this->user->user_id]);
        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $item_extra->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['price'] = (float) trim(Database::clean_string($_POST['price']));
            $_POST['is_enabled'] = (int) (bool) isset($_POST['is_enabled']);

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `items_extras` SET `name` = ?, `description` = ?, `price` = ?, `is_enabled` = ?, `last_datetime` = ? WHERE `item_extra_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssssss', $_POST['name'], $_POST['description'], $_POST['price'], $_POST['is_enabled'], \Altum\Date::$date, $item_extra->item_extra_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_extra_update->success_message;

                redirect('item-extra-update/' . $item_extra->item_extra_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('item-extra/item_extra_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'item_extra_id' => $item_extra->item_extra_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url . '/' . $item->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->item_extra_update->title, $item_extra->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
            'item_extra' => $item_extra
        ];

        $view = new \Altum\Views\View('item-extra-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
