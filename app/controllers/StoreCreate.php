<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class StoreCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Check for the plan limit */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `stores` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->stores_limit != -1 && $total_rows >= $this->user->plan_settings->stores_limit) {
            $_SESSION['info'][] = $this->language->store->error_message->stores_limit;
            redirect('dashboard');
        }

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url_is_enabled ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['title'] = trim(Database::clean_string($_POST['title']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['address'] = trim(Database::clean_string($_POST['address']));
            $_POST['currency'] = trim(Database::clean_string($_POST['currency']));
            $_POST['timezone'] = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? Database::clean_string($_POST['timezone']) : $this->settings->default_timezone;

            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_store'] = (bool) isset($_POST['is_main_store']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {

                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_store = $this->database->query("SELECT `store_id` FROM `stores` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if($is_existing_store) {
                    $_SESSION['error'][] = $this->language->store->error_message->url_exists;
                }

            }

            if(empty($_SESSION['error'])) {
                $details = json_encode([
                    'address' => $_POST['address'],
                    'phone' => '',
                    'website' => '',
                    'email' => '',
                    'hours' => [
                        '1' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ],
                        '2' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ],
                        '3' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ],
                        '4' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ],
                        '5' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ],
                        '6' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ],
                        '7' => [
                            'is_enabled' => 1,
                            'start' => '09:00',
                            'end' => '12:00'
                        ]
                    ]
                ]);
                $theme = 'new-york';

                if(!$_POST['url']) {
                    $is_existing_store = true;

                    /* Generate random url if not specified */
                    while($is_existing_store) {
                        $_POST['url'] = string_generate(10);

                        $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                        $is_existing_store = $this->database->query("SELECT `store_id` FROM `stores` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;
                    }

                }

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `stores`(`user_id`, `domain_id`, `url`, `name`, `title`, `description`, `details`, `currency`, `theme`, `timezone`, `email_reports_last_datetime`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssssssss', $this->user->user_id, $_POST['domain_id'], $_POST['url'], $_POST['name'], $_POST['title'], $_POST['description'], $details, $_POST['currency'], $theme, $_POST['timezone'], \Altum\Date::$date, \Altum\Date::$date);
                $stmt->execute();
                $store_id = $stmt->insert_id;
                $stmt->close();

                /* Update custom domain if needed */
                if($_POST['is_main_store']) {

                    $stmt = Database::$database->prepare("UPDATE `domains` SET `store_id` = ?, `last_datetime` = ? WHERE `domain_id` = ?");
                    $stmt->bind_param('sss', $store_id, \Altum\Date::$date, $_POST['domain_id']);
                    $stmt->execute();
                    $stmt->close();

                }

                $_SESSION['success'][] = $this->language->store_create->success_message;

                redirect('store/' . $store_id);
            }

        }

        /* Prepare the View */
        $data = [
            'domains' => $domains
        ];

        $view = new \Altum\Views\View('store-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
