<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class MenuCreate extends Controller {

    public function index() {

        Authentication::guard();

        $store_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$store = Database::get(['store_id', 'domain_id', 'url', 'currency'], 'stores', ['store_id' => $store_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        /* Genereate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Check for the plan limit */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `menus` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->menus_limit != -1 && $total_rows >= $this->user->plan_settings->menus_limit) {
            $_SESSION['info'][] = $this->language->menu->error_message->menus_limit;
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

                if(Database::exists('menu_id', 'menus', ['url' => $_POST['url'], 'store_id' => $store->store_id])) {
                    $_SESSION['error'][] = $this->language->menu->error_message->url_exists;
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

                if(!is_writable(UPLOADS_PATH . 'menu_images/')) {
                    $_SESSION['error'][] = sprintf($this->language->global->error_message->directory_not_writable, UPLOADS_PATH . 'menu_images/');
                }

                if(empty($_SESSION['error'])) {

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;

                    /* Upload the original */
                    move_uploaded_file($image_file_temp, UPLOADS_PATH . 'menu_images/' . $image_new_name);

                }
            }

            if(empty($_SESSION['error'])) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(Database::exists('menu_id', 'menus', ['url' => $_POST['url'], 'store_id' => $store->store_id])) {
                        $_POST['url'] = string_generate(10);
                    }
                }
                $image = $image_new_name ?? null;

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `menus`(`store_id`, `user_id`, `url`, `name`, `description`, `image`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssss', $store->store_id, $this->user->user_id, $_POST['url'], $_POST['name'], $_POST['description'], $image, \Altum\Date::$date);
                $stmt->execute();
                $menu_id = $stmt->insert_id;
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->menu_create->success_message;

                redirect('menu/' . $menu_id);
            }

        }

        /* Prepare the View */
        $data = [
            'store' => $store
        ];

        $view = new \Altum\Views\View('menu-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
