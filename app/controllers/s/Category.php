<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Meta;
use Altum\Title;

class Category extends Controller {
    public $store;
    public $store_user = null;

    public $menu;

    public $category;

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

        /* Init the menu */
        require_once APP_PATH . 'controllers/s/Menu.php';
        $menu_controller = new \Altum\Controllers\Menu((array) $this);
        $menu_controller->init($this->store->store_id);
        $this->menu = $menu_controller->menu;

        /* Category */
        $this->init($this->store->store_id);

        /* Add statistics */
        $store_controller->create_statistics($this->store->store_id, $this->menu->menu_id, $this->category->category_id);

        /* Get the available items */
        $items = (new \Altum\Models\Item())->get_items_by_store_id_and_category_id($this->store->store_id, $this->category->category_id);

        /* Set a custom title */
        Title::set(sprintf($this->language->s_category->title, $this->category->name, $this->menu->name, $this->store->name));

        /* Set the meta tags */
        Meta::set_description(string_truncate($this->category->description, 200));
        Meta::set_social_url(url('s/' . $this->store->url . '/' . $this->menu->url . '/' . $this->category->url));
        Meta::set_social_title(sprintf($this->language->s_category->title, $this->category->name, $this->menu->name, $this->store->name));
        Meta::set_social_description(string_truncate($this->category->description, 200));

        /* Prepare the header */
        $view = new \Altum\Views\View('s/partials/header', (array) $this);
        $this->add_view_content('header', $view->run(['store' => $this->store]));

        /* Main View */
        $data = [
            'store' => $this->store,
            'store_user' => $this->store_user,
            'menu' => $this->menu,
            'category' => $this->category,
            'items' => $items
        ];

        $view = new \Altum\Views\View('s/category/' . $this->store->theme . '/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function init($store_id = null) {
        /* Get the Store details */
        $url = isset($this->params[2]) ? Database::clean_string($this->params[2]) : null;

        $category = $this->category = (new \Altum\Models\Category())->get_category_by_store_id_and_url($store_id, $url);

        if(!$category || ($category && !$category->is_enabled)) {
            redirect();
        }

    }

}
