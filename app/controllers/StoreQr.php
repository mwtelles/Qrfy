<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Title;

class StoreQr extends Controller {

    public function index() {

        Authentication::guard();

        $store_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$store = Database::get('*', 'stores', ['store_id' => $store_id, 'user_id' => $this->user->user_id])) {
            redirect('dashboard');
        }

        /* Delete Modal */
        $view = new \Altum\Views\View('store/store_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Establish the account sub menu view */
        $data = [
            'store_id' => $store->store_id,
            'external_url' => url('s/' . $store->url)
        ];
        $app_sub_menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->store_qr->title, $store->name));

        /* Prepare the View */
        $data = [
            'store' => $store
        ];

        $view = new \Altum\Views\View('store-qr/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
