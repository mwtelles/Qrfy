<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemOptionUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $item_option_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item_option = Database::get('*', 'items_options', ['item_option_id' => $item_option_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $item_option->options = json_decode($item_option->options);

        $item = Database::get(['item_id', 'url'], 'items', ['item_id' => $item_option->item_id, 'user_id' => $this->user->user_id]);
        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item_option->category_id, 'user_id' => $this->user->user_id]);
        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item_option->menu_id, 'user_id' => $this->user->user_id]);
        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $item_option->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['options'] = explode(',', Database::clean_string($_POST['options']));
            $_POST['options'] = array_map('trim', $_POST['options']);
            $_POST['options'] = json_encode($_POST['options']);

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `items_options` SET `name` = ?, `options` = ?, `last_datetime` = ? WHERE `item_option_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssss', $_POST['name'], $_POST['options'], \Altum\Date::$date, $item_option->item_option_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_option_update->success_message;

                redirect('item-option-update/' . $item_option->item_option_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('item-option/item_option_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'item_option_id' => $item_option->item_option_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url . '/' . $item->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->item_option_update->title, $item_option->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
            'item_option' => $item_option
        ];

        $view = new \Altum\Views\View('item-option-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
