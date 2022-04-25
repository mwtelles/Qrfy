<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class MenuUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $menu_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$menu = Database::get('*', 'menus', ['menu_id' => $menu_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $store = Database::get('*', 'stores', ['store_id' => $menu->store_id, 'user_id' => $this->user->user_id]);

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
            if($_POST['url'] && $_POST['url'] != $menu->url) {

                if(Database::exists('menu_id', 'menus', ['url' => $_POST['url'], 'store_id' => $menu->store_id])) {
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

                    /* Delete current image */
                    if(!empty($menu->image) && file_exists(UPLOADS_PATH . 'menu_images/' . $menu->image)) {
                        unlink(UPLOADS_PATH . 'menu_images/' . $menu->image);
                    }

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;

                    /* Upload the original */
                    move_uploaded_file($image_file_temp, UPLOADS_PATH . 'menu_images/' . $image_new_name);

                    /* Execute query */
                    Database::$database->query("UPDATE `menus` SET `image` = '{$image_new_name}' WHERE `menu_id` = {$menu->menu_id}");
                }
            }

            if(empty($_SESSION['error'])) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(Database::exists('menu_id', 'menus', ['url' => $_POST['url'], 'store_id' => $menu->store_id])) {
                        $_POST['url'] = string_generate(10);
                    }
                }

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `menus` SET `url` = ?, `name` = ?, `description` = ?, `is_enabled` = ?, `last_datetime` = ? WHERE `menu_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssssss', $_POST['url'], $_POST['name'], $_POST['description'], $_POST['is_enabled'], \Altum\Date::$date, $menu->menu_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->menu_update->success_message;

                redirect('menu-update/' . $menu->menu_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('menu/menu_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'menu_id' => $menu->menu_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->menu_update->title, $menu->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu
        ];

        $view = new \Altum\Views\View('menu-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
