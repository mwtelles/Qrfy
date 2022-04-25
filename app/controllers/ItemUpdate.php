<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class ItemUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $item_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item = Database::get('*', 'items', ['item_id' => $item_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item->category_id, 'user_id' => $this->user->user_id]);
        $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item->menu_id, 'user_id' => $this->user->user_id]);
        $store = Database::get(['store_id', 'url', 'currency'], 'stores', ['store_id' => $item->store_id, 'user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['price'] = (float) trim(Database::clean_string($_POST['price']));
            $_POST['variants_is_enabled'] = (int) (bool) isset($_POST['variants_is_enabled']);
            $_POST['is_enabled'] = (int) (bool) isset($_POST['is_enabled']);

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            /* Check for duplicate url if needed */
            if($_POST['url'] && $_POST['url'] != $item->url) {

                if(Database::exists('item_id', 'items', ['url' => $_POST['url'], 'category_id' => $item->category_id])) {
                    $_SESSION['error'][] = $this->language->category->error_message->url_exists;
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

                    /* Delete current image */
                    if(!empty($menu->image) && file_exists(UPLOADS_PATH . 'item_images/' . $menu->image)) {
                        unlink(UPLOADS_PATH . 'item_images/' . $menu->image);
                    }

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;

                    /* Upload the original */
                    move_uploaded_file($image_file_temp, UPLOADS_PATH . 'item_images/' . $image_new_name);

                    /* Execute query */
                    Database::$database->query("UPDATE `items` SET `image` = '{$image_new_name}' WHERE `item_id` = {$item->item_id}");
                }
            }

            if(empty($_SESSION['error'])) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(Database::exists('category_id', 'categories', ['url' => $_POST['url'], 'store_id' => $category->store_id])) {
                        $_POST['url'] = string_generate(10);
                    }
                }

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `items` SET `url` = ?, `name` = ?, `description` = ?, `price` = ?, `variants_is_enabled` = ?, `is_enabled` = ?, `last_datetime` = ? WHERE `item_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssssssss', $_POST['url'], $_POST['name'], $_POST['description'], $_POST['price'], $_POST['variants_is_enabled'], $_POST['is_enabled'], \Altum\Date::$date, $item->item_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                $_SESSION['success'][] = $this->language->item_update->success_message;

                redirect('item-update/' . $item->item_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('item/item_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'item_id' => $item->item_id,
            'external_url' => url('s/' . $store->url . '/' . $menu->url . '/' . $category->url . '/' . $item->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->item_update->title, $item->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item
        ];

        $view = new \Altum\Views\View('item-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
