<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class CategoryCreate extends Controller {

    public function index() {

        Authentication::guard();

        $menu_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$menu = Database::get('*', 'menus', ['menu_id' => $menu_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $store = Database::get(['store_id', 'domain_id', 'url', 'currency'], 'stores', ['store_id' => $menu->store_id, 'user_id' => $this->user->user_id]);

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Check for the plan limit */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `categories` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->categories_limit != -1 && $total_rows >= $this->user->plan_settings->categories_limit) {
            $_SESSION['info'][] = $this->language->menu->error_message->categories_limit;
            redirect('dashboard');
        }

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {

                if(Database::exists('category_id', 'categories', ['url' => $_POST['url'], 'menu_id' => $menu->menu_id])) {
                    $_SESSION['error'][] = $this->language->category->error_message->url_exists;
                }

            }

            if(empty($_SESSION['error'])) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(Database::exists('category_id', 'categories', ['url' => $_POST['url'], 'menu_id' => $menu->menu_id])) {
                        $_POST['url'] = string_generate(10);
                    }
                }

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `categories` (`menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssss', $menu->menu_id, $menu->store_id, $this->user->user_id, $_POST['url'], $_POST['name'], $_POST['description'], \Altum\Date::$date);
                $stmt->execute();
                $category_id = $stmt->insert_id;
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->category_create->success_message;

                redirect('category/' . $category_id);
            }

        }

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu
        ];

        $view = new \Altum\Views\View('category-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
