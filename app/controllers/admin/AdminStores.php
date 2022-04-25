<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Plan;
use Altum\Models\User;
use Altum\Middlewares\Authentication;
use Altum\Response;
use Altum\Routing\Router;

class AdminStores extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/stores/store_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $view = new \Altum\Views\View('admin/stores/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }


    public function read() {

        Authentication::guard('admin');

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['store_id', 'user_id', 'email', 'url', 'name', 'pageviews', 'is_enabled', 'datetime']);
        $datatable->process($_POST);

        $result = Database::$database->query("
            SELECT
                `store_id`, `stores`.`user_id`, `users`.`email`, `url`, `stores`.`name`, `pageviews`, `is_enabled`, `stores`.`datetime`,
                (SELECT COUNT(*) FROM `stores`) AS `total_before_filter`,
                (SELECT COUNT(*) FROM `stores` LEFT JOIN `users` ON `stores` . `user_id` = `users` . `user_id` WHERE `users` . `email` LIKE '%{$datatable->get_search()}%' OR `users` . `name` LIKE '%{$datatable->get_search()}%' OR `stores` . `url` LIKE '%{$datatable->get_search()}%' OR `stores` . `name` LIKE '%{$datatable->get_search()}%') AS `total_after_filter`
            FROM
                `stores`
            LEFT JOIN
                `users` ON `stores`.`user_id` = `users`.`user_id`
            WHERE 
                `users` . `email` LIKE '%{$datatable->get_search()}%' 
                OR `users` . `name` LIKE '%{$datatable->get_search()}%'
                OR `stores` . `url` LIKE '%{$datatable->get_search()}%'
                OR `stores` . `name` LIKE '%{$datatable->get_search()}%'
            ORDER BY
                " . $datatable->get_order() . "
            LIMIT
                {$datatable->get_start()}, {$datatable->get_length()}
        ");

        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];

        while($row = $result->fetch_object()):

            $row->email = '<a href="' . url('admin/user-view/' . $row->user_id) . '"> ' . $row->email . '</a>';

            /* URL */
            $url = '<img src="https://external-content.duckduckgo.com/ip3/' . parse_url(url('s/' . $row->url))['host'] . '.ico" class="img-fluid icon-favicon mr-1" />';
            $url .= '<a href="' . url('s/' . $row->url) . '" target="_blank">' . $row->url . '</a>';

            $row->url = $url;

            /* Pageviews */
            $row->pageviews = nr($row->pageviews);

            /* Is Enabled Status badge */
            $row->is_enabled = $row->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>';

            $row->datetime = '<span class="text-muted" data-toggle="tooltip" title="' . \Altum\Date::get($row->datetime, 1) . '">' . \Altum\Date::get($row->datetime, 2) . '</span>';

            $row->actions = include_view(THEME_PATH . 'views/admin/partials/admin_store_dropdown_button.php', ['id' => $row->store_id]);

            $data[] = $row;
            $total_before_filter = $row->total_before_filter;
            $total_after_filter = $row->total_after_filter;

        endwhile;

        Response::simple_json([
            'data' => $data,
            'draw' => $datatable->get_draw(),
            'recordsTotal' => $total_before_filter,
            'recordsFiltered' =>  $total_after_filter
        ]);

    }

    public function delete() {

        Authentication::guard();

        $store_id = (isset($this->params[0])) ? (int) $this->params[0] : false;

        if(!Csrf::check('global_token')) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
        }

        if(!$store = Database::get(['store_id', 'image', 'logo', 'favicon'], 'stores', ['store_id' => $store_id])) {
            redirect('admin/stores');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the items images */
            $result = $this->database->query("SELECT `image` FROM `items` WHERE `store_id` = {$store->store_id}");
            while($item = $result->fetch_object()) {
                if(!empty($item->image) && file_exists(UPLOADS_PATH . 'item_images/' . $item->image)) {
                    unlink(UPLOADS_PATH . 'item_images/' . $item->image);
                }
            }

            /* Delete the menu images */
            $result = $this->database->query("SELECT `image` FROM `menus` WHERE `store_id` = {$store->store_id}");
            while($menu = $result->fetch_object()) {
                if(!empty($menu->image) && file_exists(UPLOADS_PATH . 'menu_images/' . $menu->image)) {
                    unlink(UPLOADS_PATH . 'menu_images/' . $menu->image);
                }
            }

            /* Delete the image if needed */
            if(!empty($store->image) && file_exists(UPLOADS_PATH . 'store_images/' . $store->image)) {
                unlink(UPLOADS_PATH . 'store_images/' . $store->image);
            }

            if(!empty($store->favicon) && file_exists(UPLOADS_PATH . 'store_favicons/' . $store->favicon)) {
                unlink(UPLOADS_PATH . 'store_favicons/' . $store->favicon);
            }

            if(!empty($store->logo) && file_exists(UPLOADS_PATH . 'store_logos/' . $store->logo)) {
                unlink(UPLOADS_PATH . 'store_logos/' . $store->logo);
            }

            /* Delete the store */
            Database::$database->query("DELETE FROM `stores` WHERE `store_id` = {$store->store_id}");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store_id);

            /* Success message */
            $_SESSION['success'][] = $this->language->admin_store_delete_modal->success_message;

            redirect('admin/stores');

        }

        die();
    }

}
