<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class CategoryUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$category = Database::get('*', 'categories', ['category_id' => $category_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $menu = Database::get('*', 'menus', ['menu_id' => $category->menu_id, 'user_id' => $this->user->user_id]);
        $store = Database::get('*', 'stores', ['store_id' => $category->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['is_enabled'] = (int) (bool) isset($_POST['is_enabled']);

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            /* Check for duplicate url if needed */
            if($_POST['url'] && $_POST['url'] != $category->url) {

                if(Database::exists('category_id', 'categories', ['url' => $_POST['url'], 'menu_id' => $category->menu_id])) {
                    $_SESSION['error'][] = $this->language->category->error_message->url_exists;
                }

            }

            if(empty($_SESSION['error'])) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(Database::exists('category_id', 'categories', ['url' => $_POST['url'], 'menu_id' => $category->menu_id])) {
                        $_POST['url'] = string_generate(10);
                    }
                }

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `categories` SET `url` = ?, `name` = ?, `description` = ?,`is_enabled` = ?, `last_datetime` = ? WHERE `category_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssssss', $_POST['url'], $_POST['name'], $_POST['description'], $_POST['is_enabled'], \Altum\Date::$date, $category->category_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);
                
                $_SESSION['success'][] = $this->language->category_update->success_message;

                redirect('category-update/' . $category->category_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('category/category_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'category_id' => $category->category_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->category_update->title, $category->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category
        ];

        $view = new \Altum\Views\View('category-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
