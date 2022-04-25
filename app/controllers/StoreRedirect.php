<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Title;

class StoreRedirect extends Controller {

    public function index() {

        if(isset($_GET['store_id'])) {
            $store_id = isset($_GET['store_id']) ? (int) $_GET['store_id'] : null;

            if(!$store = Database::get(['store_id', 'domain_id', 'user_id', 'url'], 'stores', ['store_id' => $store_id])) {
                redirect();
            }

            $this->store_user = (new User())->get_user_by_user_id($store->user_id);

            /* Genereate the store full URL base */
            $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->store_user);

            header('Location: ' . $store->full_url);

            die();
        }

        else if(isset($_GET['menu_id'])) {
            $menu_id = isset($_GET['menu_id']) ? (int) $_GET['menu_id'] : null;

            if(!$menu = Database::get(['store_id', 'url'], 'menus', ['menu_id' => $menu_id, 'user_id' => $this->user->user_id])) {
                redirect();
            }

            $store = Database::get(['store_id', 'domain_id', 'user_id', 'url'], 'stores', ['store_id' => $menu->store_id, 'user_id' => $this->user->user_id]);

            $this->store_user = (new User())->get_user_by_user_id($store->user_id);

            /* Genereate the store full URL base */
            $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->store_user);

            header('Location: ' . $store->full_url . $menu->url);

            die();
        }

        else if(isset($_GET['category_id'])) {
            $category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;

            if(!$category = Database::get(['menu_id', 'store_id', 'url'], 'categories', ['category_id' => $category_id, 'user_id' => $this->user->user_id])) {
                redirect();
            }

            $menu = Database::get(['url'], 'menus', ['menu_id' => $category->menu_id, 'user_id' => $this->user->user_id]);

            $store = Database::get(['store_id', 'domain_id', 'user_id', 'url'], 'stores', ['store_id' => $category->store_id, 'user_id' => $this->user->user_id]);

            $this->store_user = (new User())->get_user_by_user_id($store->user_id);

            /* Genereate the store full URL base */
            $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->store_user);

            header('Location: ' . $store->full_url . $menu->url . '/' . $category->url);

            die();
        }

        else if(isset($_GET['item_id'])) {
            $item_id = isset($_GET['item_id']) ? (int) $_GET['item_id'] : null;

            if(!$item = Database::get(['category_id', 'menu_id', 'store_id', 'url'], 'items', ['item_id' => $item_id, 'user_id' => $this->user->user_id])) {
                redirect();
            }

            $category = Database::get(['url'], 'categories', ['category_id' => $item->category_id, 'user_id' => $this->user->user_id]);

            $menu = Database::get(['url'], 'menus', ['menu_id' => $item->menu_id, 'user_id' => $this->user->user_id]);

            $store = Database::get(['store_id', 'domain_id', 'user_id', 'url'], 'stores', ['store_id' => $item->store_id, 'user_id' => $this->user->user_id]);

            $this->store_user = (new User())->get_user_by_user_id($store->user_id);

            /* Genereate the store full URL base */
            $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->store_user);

            header('Location: ' . $store->full_url . $menu->url . '/' . $category->url . '/' . $item->url);

            die();
        } else {

            redirect();

        }

    }
}
