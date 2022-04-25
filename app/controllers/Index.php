<?php

namespace Altum\Controllers;


class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty($this->settings->index_url)) {
            header('Location: ' . $this->settings->index_url);
            die();
        }

        /* Prepare the example demo QR */
        $qr = new \Endroid\QrCode\QrCode(url('s/demo?referrer=qr'));
        $qr->setSize(600);
        $qr->setWriterByName('png');
        $qr->setEncoding('UTF-8');

        /* Plans View */
        $view = new \Altum\Views\View('partials/plans', (array) $this);
        $this->add_view_content('plans', $view->run());

        /* Main View */
        $data = [
            'qr' => $qr
        ];

        $view = new \Altum\Views\View('index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
