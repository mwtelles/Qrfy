<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;
use Altum\Title;

class DomainUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $domain_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$domain = Database::get('*', 'domains', ['domain_id' => $domain_id, 'user_id' => $this->user->user_id])) {
            redirect('domains');
        }

        if(!empty($_POST)) {
            $_POST['scheme'] = isset($_POST['scheme']) && in_array($_POST['scheme'], ['http://', 'https://']) ? Database::clean_string($_POST['scheme']) : 'https://';
            $_POST['host'] = trim(Database::clean_string($_POST['host']));
            $_POST['custom_index_url'] = trim(Database::clean_string($_POST['custom_index_url']));
            $type = 0;
            $is_enabled = 0;

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `domains` SET `scheme` = ?, `host` = ?, `custom_index_url` = ?, `is_enabled` = ?, `last_datetime` = ? WHERE `domain_id` = ?");
                $stmt->bind_param('ssssss', $_POST['scheme'], $_POST['host'], $_POST['custom_index_url'], $is_enabled, \Altum\Date::$date, $domain->domain_id);
                $stmt->execute();
                $stmt->close();

                $_SESSION['success'][] = $this->language->domain_update->success_message;

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
            'domain' => $domain
        ];

        $view = new \Altum\Views\View('domain-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
