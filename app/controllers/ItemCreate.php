<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class ItemCreate extends Controller {

    public function index() {

        Authentication::guard();

        $category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$category = Database::get(['category_id', 'menu_id', 'store_id', 'url'], 'categories', ['category_id' => $category_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $category->menu_id, 'user_id' => $this->user->user_id]);

        $store = Database::get(['store_id', 'domain_id', 'url', 'currency'], 'stores', ['store_id' => $category->store_id, 'user_id' => $this->user->user_id]);

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Check for the plan limit */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `items` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->items_limit != -1 && $total_rows >= $this->user->plan_settings->items_limit) {
            $_SESSION['info'][] = $this->language->menu->error_message->items_limit;
            redirect('dashboard');
        }

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['price'] = (float) trim(Database::clean_string($_POST['price']));

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {

                if(Database::exists('item_id', 'items', ['url' => $_POST['url'], 'category_id' => $category->category_id])) {
                    $_SESSION['error'][] = $this->language->item->error_message->url_exists;
                }

            }

            /* Image uploads */
            $image_allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            $image = (!empty($_FILES['image']['name']));

            /* Check for any errors on the image image */
            if($image) {
                $image_file_name = $_FILES['image']['name'];
                $image_file_extension = explode('.', $image_file_name);
                $image_file_extension = strtolower(end($image_file_extension));
                $image_file_temp = $_FILES['image']['tmp_name'];

                if(!in_array($image_file_extension, $image_allowed_extensions)) {
                    $_SESSION['error'][] = $this->language->global->error_message->invalid_file_type;
                }

                if(!is_writable(UPLOADS_PATH . 'item_images/')) {
                    $_SESSION['error'][] = sprintf($this->language->global->error_message->directory_not_writable, UPLOADS_PATH . 'item_images/');
                }

                if(empty($_SESSION['error'])) {

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;

                    /* Upload the original */
                    move_uploaded_file($image_file_temp, UPLOADS_PATH . 'item_images/' . $image_new_name);

                }
            }

            if(empty($_SESSION['error'])) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(Database::exists('item_id', 'items', ['url' => $_POST['url'], 'category_id' => $category->category_id])) {
                        $_POST['url'] = string_generate(10);
                    }
                }
                $image = $image_new_name ?? null;

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `items` (`category_id`, `menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `image`, `price`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssssss', $category->category_id, $menu->menu_id, $store->store_id, $this->user->user_id, $_POST['url'], $_POST['name'], $_POST['description'], $image, $_POST['price'], \Altum\Date::$date);
                $stmt->execute();
                $item_id = $stmt->insert_id;
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_create->success_message;

                redirect('item/' . $item_id);
            }

        }

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category
        ];

        $view = new \Altum\Views\View('item-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
