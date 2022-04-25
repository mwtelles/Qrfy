<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class DomainCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Check for the plan limit */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `domains` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->domains_limit != -1 && $total_rows >= $this->user->plan_settings->domains_limit) {
            $_SESSION['info'][] = $this->language->domains->error_message->domains_limit;
            redirect('domains');
        }

        if(!empty($_POST)) {
            $_POST['scheme'] = isset($_POST['scheme']) && in_array($_POST['scheme'], ['http://', 'https://']) ? Database::clean_string($_POST['scheme']) : 'https://';
            $_POST['host'] = trim(Database::clean_string($_POST['host']));
            $_POST['custom_index_url'] = trim(Database::clean_string($_POST['custom_index_url']));
            $type = 0;

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("INSERT INTO `domains` (`user_id`, `scheme`, `host`, `custom_index_url`, `type`, `datetime`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssss', $this->user->user_id, $_POST['scheme'], $_POST['host'], $_POST['custom_index_url'], $type, \Altum\Date::$date);
                $stmt->execute();
                $stmt->close();

                $_SESSION['success'][] = $this->language->domain_create->success_message;

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('domains');
            }
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('domains/domain_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
        ];

        $view = new \Altum\Views\View('domain-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
