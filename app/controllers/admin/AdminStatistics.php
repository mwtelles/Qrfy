<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AdminStatistics extends Controller {
    public $type;
    public $date;

    public function index() {

        Authentication::guard('admin');

        $this->type = (isset($this->params[0])) && in_array($this->params[0], ['payments', 'growth', 'stores', 'statistics', 'email_reports']) ? Database::clean_string($this->params[0]) : 'growth';

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

        $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Process only data that is needed for that specific page */
        $type_data = $this->{$this->type}();

        /* Main View */
        $data = [
            'type' => $this->type,
            'date' => $this->date
        ];
        $data = array_merge($data, $type_data);

        $view = new \Altum\Views\View('admin/statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    protected function payments() {

        $payments_chart = [];
        $result = $this->database->query("SELECT COUNT(*) AS `total_payments`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, TRUNCATE(SUM(`total_amount`), 2) AS `total_amount` FROM `payments` WHERE `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

        }

        $payments_chart = get_chart_data($payments_chart);

        return [
            'payments_chart' => $payments_chart
        ];

    }

    protected function growth() {

        /* Users */
        $users_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `users`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $users_chart[$row->formatted_date] = [
                'users' => $row->total
            ];
        }

        $users_chart = get_chart_data($users_chart);

        /* Users logs */
        $users_logs_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `users_logs`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $users_logs_chart[$row->formatted_date] = [
                'users_logs' => $row->total
            ];
        }

        $users_logs_chart = get_chart_data($users_logs_chart);

        /* Redeemed codes */
        if(in_array($this->settings->license->type, ['SPECIAL', 'Extended License'])) {
            $redeemed_codes_chart = [];
            $result = $this->database->query("
                SELECT
                     COUNT(*) AS `total`,
                     DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
                FROM
                     `redeemed_codes`
                WHERE
                    `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ");
            while($row = $result->fetch_object()) {

                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

                $redeemed_codes_chart[$row->formatted_date] = [
                    'redeemed_codes' => $row->total
                ];
            }

            $redeemed_codes_chart = get_chart_data($redeemed_codes_chart);
        }

        return [
            'users_chart' => $users_chart,
            'users_logs_chart' => $users_logs_chart,
            'redeemed_codes_chart' => $redeemed_codes_chart ?? null
        ];
    }

    protected function stores() {

        /* Stores */
        $stores_chart = [];
        $result = $this->database->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                `stores`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $stores_chart[$row->formatted_date] = [
                'stores' => $row->total
            ];
        }

        $stores_chart = get_chart_data($stores_chart);

        /* Menus */
        $menus_chart = [];
        $result = $this->database->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                `menus`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $menus_chart[$row->formatted_date] = [
                'menus' => $row->total
            ];
        }

        $menus_chart = get_chart_data($menus_chart);

        /* Categories */
        $categories_chart = [];
        $result = $this->database->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                `categories`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $categories_chart[$row->formatted_date] = [
                'categories' => $row->total
            ];
        }

        $categories_chart = get_chart_data($categories_chart);

        /* Items */
        $items_chart = [];
        $result = $this->database->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                `items`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $items_chart[$row->formatted_date] = [
                'items' => $row->total
            ];
        }

        $items_chart = get_chart_data($items_chart);

        return [
            'stores_chart' => $stores_chart,
            'menus_chart' => $menus_chart,
            'categories_chart' => $categories_chart,
            'items_chart' => $items_chart
        ];

    }

    protected function statistics() {

        /* Stores */
        $statistics_chart = [];
        $result = $this->database->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                `statistics`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $statistics_chart[$row->formatted_date] = [
                'statistics' => $row->total
            ];
        }

        $statistics_chart = get_chart_data($statistics_chart);

        return [
            'statistics_chart' => $statistics_chart,
        ];

    }

    protected function email_reports() {

        $email_reports_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `email_reports`
            WHERE
                `datetime` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $email_reports_chart[$row->formatted_date] = [
                'email_reports' => $row->total
            ];

        }

        $email_reports_chart = get_chart_data($email_reports_chart);

        return [
            'email_reports_chart' => $email_reports_chart
        ];
    }
}
