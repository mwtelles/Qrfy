<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Meta;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Routing\Router;
use Altum\Title;
use MaxMind\Db\Reader;

class Store extends Controller {
    public $store = null;
    public $store_user = null;
    public $has_access = null;

    public function index() {

        $this->init();

        /* Check if the password form is submitted */
        if(!$this->has_access && !empty($_POST)) {

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(!password_verify($_POST['password'], $this->store->password)) {
                $_SESSION['error'][] = $this->language->s_store->password->error_message;
            }

            if(empty($_SESSION['error'])) {

                /* Set a cookie */
                setcookie('store_password', $this->store->password, time()+60*60*24*30);

                redirect('s/' . $this->store->url);

            }

        }

        /* Display the password form */
        if(!$this->has_access) {

            /* Set a custom title */
            Title::set($this->language->s_store->password->title);

            /* Main View */
            $data = [
                'store' => $this->store,
            ];

            $view = new \Altum\Views\View('s/store/' . $this->store->theme . '/password', (array) $this);

        }

        /* No password or access granted */
        else {

            $this->create_statistics($this->store->store_id);

            /* Calculate open hours */
            $store_is_potentially_open = false;
            $now = (new \DateTime())->setTimezone(new \DateTimeZone($this->store->timezone));
            $day = strtolower($now->format('N'));

            /* Get the available items */
            $menus = (new \Altum\Models\Menu())->get_menus_by_store_id($this->store->store_id);

            /* Set a custom title */
            Title::set($this->store->title);

            /* Set the meta tags */
            Meta::set_description(string_truncate($this->store->description, 200));
            Meta::set_social_url(url('s/' . $this->store->url));
            Meta::set_social_title($this->store->title);
            Meta::set_social_description(string_truncate($this->store->description, 200));
            Meta::set_social_image($this->store->image ? SITE_URL . UPLOADS_URL_PATH . 'store_images/' . $this->store->image : null);

            /* Prepare the header */
            $view = new \Altum\Views\View('s/partials/header', (array) $this);
            $this->add_view_content('header', $view->run(['store' => $this->store]));

            /* Main View */
            $data = [
                'store' => $this->store,
                'store_user' => $this->store_user,
                'menus' => $menus,

                'day' => $day
            ];

            $view = new \Altum\Views\View('s/store/' . $this->store->theme . '/index', (array) $this);
        }

        $this->add_view_content('content', $view->run($data));
    }

    public function init() {

        /* Check against potential custom domains */
        if(isset(Router::$data['domain'])) {

            /* Check if custom domain has 1 store or multiple */
            if(Router::$data['domain']->store_id) {

                $store = $this->store = (new \Altum\Models\Store())->get_store_by_store_id(Router::$data['domain']->store_id);

                /* Determine the store base url */
                $store->full_url = Router::$data['domain']->scheme . Router::$data['domain']->host . '/';

            } else {
                /* Get the Store details */
                $url = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

                $store = $this->store = (new \Altum\Models\Store())->get_store_by_url_and_domain_id($url, Router::$data['domain']->domain_id);

                /* Determine the store base url */
                $store->full_url = Router::$data['domain']->scheme . Router::$data['domain']->host . '/' . $store->url . '/';
            }

            if(!$store || ($store && !$store->is_enabled)) {
                redirect();
            }
        }

        /* Check the store via url */
        else {

            /* Get the Store details */
            $url = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

            $store = $this->store = (new \Altum\Models\Store())->get_store_by_url($url);

            if(!$store || ($store && (!$store->is_enabled || $store->domain_id))) {
                redirect();
            }

            $store->full_url = url('s/' . $store->url . '/');
        }

        $this->store_user = (new User())->get_user_by_user_id($this->store->user_id);

        /* Make sure to check if the user is active */
        if($this->store_user->active != 1) {
            redirect();
        }

        /* Check if the user has access to the store */
        $has_access = !$store->password || ($store->password && isset($_COOKIE['store_password']) && $_COOKIE['store_password'] == $store->password);

        /* Do not let the user have password protection if the plan doesnt allow it */
        if(!$this->store_user->plan_settings->password_protection_is_enabled) {
            $has_access = true;
        }

        $this->has_access = $has_access;

        /* Parse some details */
        $this->store->details = json_decode($this->store->details);
        $this->store->socials = json_decode($this->store->socials);

        $this->store->cart_is_enabled = $this->store_user->plan_settings->ordering_is_enabled && ($this->store->on_premise_ordering_is_enabled || $this->store->takeaway_ordering_is_enabled || $this->store->delivery_ordering_is_enabled);

        /* Set the default language of the user */
        \Altum\Language::set_by_name($this->store_user->language, false);
    }

    /* Insert statistics log */
    public function create_statistics($store_id = null, $menu_id = null, $category_id = null, $item_id = null) {

        $cookie_name = 'r_statistics_' . $store_id . '_' . $menu_id . '_' . $category_id . '_' . $item_id;

        if(isset($_COOKIE[$cookie_name])) {
            return;
        }

        if(!$this->store_user->plan_settings->analytics_is_enabled) {
            return;
        }

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        /* Do not track bots */
        if($whichbrowser->device->type == 'bot') {
            return;
        }

        $browser_name = $whichbrowser->browser->name;
        $os_name = $whichbrowser->os->name;
        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);

        /* Detect the location */
        $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-Country.mmdb'))->get(get_ip());
        $country_code = $maxmind ? $maxmind['country']['iso_code'] : null;

        /* Process referrer */
        $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

        /* Check if the referrer comes from the same location */
        if(isset($referrer) && isset($referrer['host']) && $referrer['host'] == parse_urL(url())['host']) {
            $referrer = [
                'host' => null,
                'path' => null
            ];
        }

        /* Check if referrer actually comes from the QR code */
        if(isset($_GET['referrer']) && $_GET['referrer'] == 'qr') {
            $referrer = [
                'host' => 'qr',
                'path' => null
            ];
        }

        /* Insert or update the log */
        $stmt = Database::$database->prepare("
            INSERT INTO 
                `statistics` (`store_id`, `menu_id`, `category_id`, `item_id`, `country_code`, `os_name`, `browser_name`, `referrer_host`, `referrer_path`, `device_type`, `browser_language`, `datetime`) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'ssssssssssss',
            $store_id,
            $menu_id,
            $category_id,
            $item_id,
            $country_code,
            $os_name,
            $browser_name,
            $referrer['host'],
            $referrer['path'],
            $device_type,
            $browser_language,
            Date::$date
        );
        $stmt->execute();
        $stmt->close();

        /* Add the unique hit to the store table as well */
        Database::$database->query("UPDATE `stores` SET `pageviews` = `pageviews` + 1 WHERE `store_id` = {$store_id}");

        /* Set cookie to try and avoid multiple entrances */
        setcookie($cookie_name, true, time()+60*60*24*1);
    }

}
