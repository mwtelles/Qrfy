<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class Statistics extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->user->plan_settings->analytics_is_enabled) {
            redirect('dashboard');
        }

        if(isset($_GET['store_id'])) {
            $store_id = isset($_GET['store_id']) ? (int) $_GET['store_id'] : null;

            if(!$store = Database::get('*', 'stores', ['store_id' => $store_id, 'user_id' => $this->user->user_id])) {
                redirect('dashboard');
            }

            $identifier_name = 'store';
            $identifier_key = 'store_id';
            $identifier_value = $store->store_id;
            $external_url = url('s/' . $store->url);
        }

        else if(isset($_GET['menu_id'])) {
            $menu_id = isset($_GET['menu_id']) ? (int) $_GET['menu_id'] : null;

            if(!$menu = Database::get('*', 'menus', ['menu_id' => $menu_id, 'user_id' => $this->user->user_id])) {
                redirect('dashboard');
            }

            $store = Database::get(['store_id', 'url'], 'stores', ['store_id' => $menu->store_id, 'user_id' => $this->user->user_id]);

            $identifier_name = 'menu';
            $identifier_key = 'menu_id';
            $identifier_value = $menu->menu_id;
            $external_url = url('s/' . $store->url . '/' . $menu->url);
        }

        else if(isset($_GET['category_id'])) {
            $category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;

            if(!$category = Database::get('*', 'categories', ['category_id' => $category_id, 'user_id' => $this->user->user_id])) {
                redirect('dashboard');
            }

            $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $category->menu_id, 'user_id' => $this->user->user_id]);

            $store = Database::get(['store_id', 'url'], 'stores', ['store_id' => $category->store_id, 'user_id' => $this->user->user_id]);

            $identifier_name = 'category';
            $identifier_key = 'category_id';
            $identifier_value = $category->category_id;
            $external_url = url('s/' . $store->url . '/' . $menu->url . '/' . $category->url);
        }

        else if(isset($_GET['item_id'])) {
            $item_id = isset($_GET['item_id']) ? (int) $_GET['item_id'] : null;

            if(!$item = Database::get('*', 'items', ['item_id' => $item_id, 'user_id' => $this->user->user_id])) {
                redirect('dashboard');
            }

            $category = Database::get(['category_id', 'url'], 'categories', ['category_id' => $item->category_id, 'user_id' => $this->user->user_id]);

            $menu = Database::get(['menu_id', 'url'], 'menus', ['menu_id' => $item->menu_id, 'user_id' => $this->user->user_id]);

            $store = Database::get(['store_id', 'url'], 'stores', ['store_id' => $item->store_id, 'user_id' => $this->user->user_id]);

            $identifier_name = 'item';
            $identifier_key = 'item_id';
            $identifier_value = $item->item_id;
            $external_url = url('s/' . $store->url . '/' . $menu->url . '/' . $category->url . '/' . $item->url);
        } else {
            redirect('dashboard');
        }

        /* Statistics related variables */
        $type = isset($_GET['type']) && in_array($_GET['type'], ['latest', 'referrer', 'country', 'os', 'browser', 'device', 'language']) ? Database::clean_string($_GET['type']) : 'latest';
        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : null;
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : null;

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get the required statistics */
        $pageviews = [];
        $pageviews_chart = [];

        $pageviews_result = Database::$database->query("
            SELECT
                COUNT(`id`) AS `pageviews`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `statistics`
            WHERE
                `{$identifier_key}` = {$identifier_value}
                AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");

        /* Generate the raw chart data and save pageviews for later usage */
        while($row = $pageviews_result->fetch_object()) {
            $pageviews[] = $row;

            $label = \Altum\Date::get($row->formatted_date, 5);

            $pageviews_chart[$label] = [
                'pageviews' => $row->pageviews
            ];
        }

        $pageviews_chart = get_chart_data($pageviews_chart);

        /* Get data based on what statistics are needed */
        switch($type) {
            case 'latest':

                $result = Database::$database->query("
                    SELECT
                        *
                    FROM
                        `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    ORDER BY
                        `datetime` DESC
                    LIMIT 25
                ");

                break;

            case 'referrer':
            case 'country':
            case 'os':
            case 'browser':
            case 'device':
            case 'language':

                $columns = [
                    'referrer' => 'referrer_host',
                    'country' => 'country_code',
                    'os' => 'os_name',
                    'browser' => 'browser_name',
                    'device' => 'device_type',
                    'language' => 'browser_language'
                ];

                $result = Database::$database->query("
                    SELECT
                        `{$columns[$type]}`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND (`datetime` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    GROUP BY
                        `{$columns[$type]}`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;
        }

        /* Store all the results from the database */
        $statistics = [];
        $statistics_total_sum = 0;

        while($row = $result->fetch_object()) {
            $statistics[] = $row;

            if($type != 'latest') $statistics_total_sum += $row->total;
        }

        /* Prepare the statistics method View */
        $data = [
            'rows' => $statistics,
            'total_sum' => $statistics_total_sum
        ];

        $view = new \Altum\Views\View('statistics/statistics_' . $type, (array) $this);
        $this->add_view_content('statistics', $view->run($data));

        /* Delete Modal */
        $view = new \Altum\Views\View('store/store_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            $identifier_key => $identifier_value,
            'external_url' => $external_url
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->statistics->title, ${$identifier_name}->name));

        /* Prepare the View */
        $data = [
            'identifier_name' => $identifier_name,
            'identifier_key' => $identifier_key,
            'identifier_value' => $identifier_value,
            'external_url' => $external_url,
            $identifier_name => ${$identifier_name},

            'type' => $type,
            'date' => $date,
            'pageviews' => $pageviews,
            'pageviews_chart' => $pageviews_chart
        ];

        $view = new \Altum\Views\View('statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
