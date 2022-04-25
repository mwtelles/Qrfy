<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Middlewares\Authentication;
use Altum\Response;

class AdminDomains extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/domains/domain_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $view = new \Altum\Views\View('admin/domains/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }


    public function read() {

        Authentication::guard('admin');

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['domain_id', 'type', 'host', 'date', 'stores', 'email', 'name', 'is_enabled']);
        $datatable->process($_POST);

        $result = Database::$database->query("
            SELECT
                `domains`.*,
                `users`.`email`,
                COUNT(`stores`.`domain_id`) AS `stores`,
                (SELECT COUNT(*) FROM `domains`) AS `total_before_filter`,
                (SELECT COUNT(*) FROM `domains` LEFT JOIN `users` ON `domains` . `user_id` = `users` . `user_id` WHERE `users`.`name` LIKE '%{$datatable->get_search()}%' OR `users`.`email` LIKE '%{$datatable->get_search()}%' OR `domains`.`host` LIKE '%{$datatable->get_search()}%') AS `total_after_filter`
            FROM
                `domains`
            LEFT JOIN
                `stores` ON `domains`.`domain_id` = `stores`.`domain_id`
            LEFT JOIN
                `users` ON `domains`.`user_id` = `users`.`user_id`
            WHERE 
                `users`.`name` LIKE '%{$datatable->get_search()}%' 
                OR `users`.`email` LIKE '%{$datatable->get_search()}%' 
                OR`domains`.`host` LIKE '%{$datatable->get_search()}%'
            GROUP BY
                `domain_id`
            ORDER BY
                `domains`.`type` DESC,
                `domain_id` ASC,
                " . $datatable->get_order() . "
            LIMIT
                {$datatable->get_start()}, {$datatable->get_length()}
        ");

        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];

        while($row = $result->fetch_object()):

            /* Type */
            $row->type = $row->type == 1 ?
                    '<span class="badge badge-pill badge-success" data-toggle="tooltip" title="' . $this->language->admin_domains->main->type_global . '"><i class="fa fa-fw fa-globe"></i></span>'
                    : '<span class="badge badge-pill badge-secondary" data-toggle="tooltip" title="' . $this->language->admin_domains->main->type_user . '"><i class="fa fa-fw fa-user"></i></span>';

            /* Email */
            $row->email = '<a href="' . url('admin/user-view/' . $row->user_id) . '"> ' . $row->email . '</a>';

            /* host */
            $host_prepend = '<img src="https://external-content.duckduckgo.com/ip3/' . $row->host . '.ico" class="img-fluid icon-favicon mr-1" />';
            $row->host = $host_prepend . '<a href="' . url('admin/domain-update/' . $row->domain_id) . '">' . $row->host . '</a>';

            /* Links */
            $row->stores = '<i class="fa fa-fw fa-sm fa-store text-muted"></i> ' . nr($row->stores);

            /* is_enabled badge */
            $row->is_enabled = $row->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>';

            $row->datetime = '<span data-toggle="tooltip" title="' . \Altum\Date::get($row->datetime, 1) . '">' . \Altum\Date::get($row->datetime, 2) . '</span>';
            $row->actions = include_view(THEME_PATH . 'views/admin/partials/admin_domain_dropdown_button.php', ['id' => $row->domain_id]);

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

        $domain_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
        }

        if(!$domain = Database::get(['domain_id'], 'domains', ['domain_id' => $domain_id])) {
            redirect('admin/domains');
        }

        if(empty($_SESSION['error'])) {

            /* Delete everything related to the stores that the user owns */
            $result = $this->database->query("SELECT `store_id`, `image`, `logo`, `favicon` FROM `stores` WHERE `domain_id` = {$domain->domain_id}");

            while($store = $result->fetch_object()) {

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

                /* Clear cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

            }

            /* Delete the domain */
            $this->database->query("DELETE FROM `domains` WHERE `domain_id` = {$domain->domain_id}");

            /* Success message */
            $_SESSION['success'][] = $this->language->admin_domain_delete_modal->success_message;

            redirect('admin/domains');

        }

        die();
    }

}
