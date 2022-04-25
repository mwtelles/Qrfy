<?php

namespace Altum\Routing;

use Altum\Database\Database;
use Altum\Language;

class Router {
    public static $params = [];
    public static $original_request = '';
    public static $language_code = '';
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'app_sub_menu'          => false,

        'wrapper'               => 'wrapper',
        'no_authentication_check' => false,

        /* If set on yes, ads wont show on these pages at all */
        'no_ads'                => false
    ];
    public static $method = 'index';
    public static $data = [];

    public static $routes = [
        's' => [
            'store' => [
                'controller' => 'Store',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'cart' => [
                'controller' => 'Cart',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'menu' => [
                'controller' => 'Menu',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'category' => [
                'controller' => 'Category',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'item' => [
                'controller' => 'Item',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ]
        ],

        '' => [
            'index' => [
                'controller' => 'Index'
            ],

            'login' => [
                'controller' => 'Login',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'register' => [
                'controller' => 'Register',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'pages' => [
                'controller' => 'Pages'
            ],

            'page' => [
                'controller' => 'Page'
            ],

            'api-documentation' => [
                'controller' => 'ApiDocumentation',
                'settings' => [
                    'no_ads'    => true
                ]
            ],

            'activate-user' => [
                'controller' => 'ActivateUser'
            ],

            'lost-password' => [
                'controller' => 'LostPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'reset-password' => [
                'controller' => 'ResetPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'resend-activation' => [
                'controller' => 'ResendActivation',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'logout' => [
                'controller' => 'Logout'
            ],

            'notfound' => [
                'controller' => 'NotFound'
            ],

            /* Logged in */
            'dashboard' => [
                'controller' => 'Dashboard',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'store' => [
                'controller' => 'Store',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'store-create' => [
                'controller' => 'StoreCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'store-update' => [
                'controller' => 'StoreUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'store-qr' => [
                'controller' => 'StoreQr',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'store-redirect' => [
                'controller' => 'StoreRedirect',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_authentication_check' => true
                ]
            ],

            'statistics' => [
                'controller' => 'Statistics',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'orders' => [
                'controller' => 'Orders',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'order' => [
                'controller' => 'Order',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'menu' => [
                'controller' => 'Menu',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'menu-create' => [
                'controller' => 'MenuCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'menu-update' => [
                'controller' => 'MenuUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'category' => [
                'controller' => 'Category',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'category-create' => [
                'controller' => 'CategoryCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'category-update' => [
                'controller' => 'CategoryUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'item' => [
                'controller' => 'Item',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'item-create' => [
                'controller' => 'ItemCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'item-update' => [
                'controller' => 'ItemUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'item-extra' => [
                'controller' => 'ItemExtra',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],


            'item-extra-create' => [
                'controller' => 'ItemExtraCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'item-extra-update' => [
                'controller' => 'ItemExtraUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'item-option' => [
                'controller' => 'ItemOption',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],


            'item-option-create' => [
                'controller' => 'ItemOptionCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'item-option-update' => [
                'controller' => 'ItemOptionUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'item-variant' => [
                'controller' => 'ItemVariant',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],


            'item-variant-create' => [
                'controller' => 'ItemVariantCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'item-variant-update' => [
                'controller' => 'ItemVariantUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'app_sub_menu' => true,
                ]
            ],

            'account' => [
                'controller' => 'Account',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'account-plan' => [
                'controller' => 'AccountPlan',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'account-payments' => [
                'controller' => 'AccountPayments',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'account-logs' => [
                'controller' => 'AccountLogs',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'account-api' => [
                'controller' => 'AccountApi',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'account-delete' => [
                'controller' => 'AccountDelete',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'domains' => [
                'controller' => 'Domains',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'app_sub_menu' => true,
                    'no_ads'    => true
                ]
            ],

            'domain-create' => [
                'controller' => 'DomainCreate',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'domain-update' => [
                'controller' => 'DomainUpdate',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'invoice' => [
                'controller' => 'Invoice',
                'settings' => [
                    'wrapper' => 'invoice/invoice_wrapper',
                    'no_ads' => true
                ]
            ],

            'plan' => [
                'controller' => 'Plan',
                'settings' => [
                    'no_ads'    => true
                ]
            ],

            'pay' => [
                'controller' => 'Pay',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'pay-thank-you' => [
                'controller' => 'PayThankYou',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],


            /* Webhooks */
            'webhook-paypal' => [
                'controller' => 'WebhookPaypal',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'webhook-stripe' => [
                'controller' => 'WebhookStripe',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            /* Others */
            'get-captcha' => [
                'controller' => 'GetCaptcha',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'sitemap' => [
                'controller' => 'Sitemap',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'cron' => [
                'controller' => 'Cron',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'api' => [
                'controller' => 'Api',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

        ],

        /* Admin Panel */
        'admin' => [
            'index' => [
                'controller' => 'AdminIndex'
            ],

            'users' => [
                'controller' => 'AdminUsers'
            ],

            'user-create' => [
                'controller' => 'AdminUserCreate'
            ],

            'user-view' => [
                'controller' => 'AdminUserView'
            ],

            'user-update' => [
                'controller' => 'AdminUserUpdate'
            ],

            'stores' => [
                'controller' => 'AdminStores'
            ],

            'domains' => [
                'controller' => 'AdminDomains'
            ],

            'domain-create' => [
                'controller' => 'AdminDomainCreate'
            ],

            'domain-update' => [
                'controller' => 'AdminDomainUpdate'
            ],

            'pages-categories' => [
                'controller' => 'AdminPagesCategories'
            ],

            'pages-category-create' => [
                'controller' => 'AdminPagesCategoryCreate'
            ],

            'pages-category-update' => [
                'controller' => 'AdminPagesCategoryUpdate'
            ],

            'pages' => [
                'controller' => 'AdminPages'
            ],

            'page-create' => [
                'controller' => 'AdminPageCreate'
            ],

            'page-update' => [
                'controller' => 'AdminPageUpdate'
            ],


            'plans' => [
                'controller' => 'AdminPlans'
            ],

            'plan-create' => [
                'controller' => 'AdminPlanCreate'
            ],

            'plan-update' => [
                'controller' => 'AdminPlanUpdate'
            ],


            'codes' => [
                'controller' => 'AdminCodes'
            ],

            'code-create' => [
                'controller' => 'AdminCodeCreate'
            ],

            'code-update' => [
                'controller' => 'AdminCodeUpdate'
            ],


            'taxes' => [
                'controller' => 'AdminTaxes'
            ],

            'tax-create' => [
                'controller' => 'AdminTaxCreate'
            ],

            'tax-update' => [
                'controller' => 'AdminTaxUpdate'
            ],


            'payments' => [
                'controller' => 'AdminPayments'
            ],


            'statistics' => [
                'controller' => 'AdminStatistics'
            ],


            'settings' => [
                'controller' => 'AdminSettings'
            ],
        ]
    ];



    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['altum'])) {
            $params = explode('/', filter_var(rtrim($_GET['altum'], '/'), FILTER_SANITIZE_URL));
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_language() {

        /* Check for potential language set in the first parameter */
        if(!empty(self::$params[0]) && array_key_exists(self::$params[0], Language::$languages)) {

            /* Set the language */
            Language::set_by_code(self::$params[0]);
            self::$language_code = self::$params[0];

            /* Unset the parameter so that it wont be used further */
            unset(self::$params[0]);
            self::$params = array_values(self::$params);

        }

    }

    public static function parse_controller() {

        self::$original_request = implode('/', self::$params);

        /* Check if the current link accessed is actually the original url or not (multi domain use) */
        $original_url_host = parse_url(url())['host'];
        $request_url_host = Database::clean_string($_SERVER['HTTP_HOST']);

        if($original_url_host != $request_url_host) {

            /* Make sure the custom domain is attached */
            $domain = (new \Altum\Models\Domain())->get_domain_by_host($request_url_host);;

            if($domain && $domain->is_enabled) {
                self::$path = 's';

                /* Set some route data */
                self::$data['domain'] = $domain;

            }

        }

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            if(in_array(self::$params[0], ['admin', 's'])) {
                self::$path = self::$params[0];

                unset(self::$params[0]);

                self::$params = array_values(self::$params);
            }

        }

        /* Check for potential Store link */
        if(self::$path == 's') {

            /* Store */
            self::$controller_key = 'store';
            self::$controller = 'Store';

            if(isset($_GET['page']) && $_GET['page'] == 'cart') {
                self::$controller_key = 'cart';
                self::$controller = 'Cart';
            }

            if(isset(self::$params[0], self::$params[1])) {

                /* Menu */
                self::$controller_key = 'menu';
                self::$controller = 'Menu';

                if(isset(self::$params[0], self::$params[1], self::$params[2])) {

                    /* Category */
                    self::$controller_key = 'category';
                    self::$controller = 'Category';

                    if(isset(self::$params[0], self::$params[1], self::$params[2], self::$params[3])) {

                        /* Category */
                        self::$controller_key = 'item';
                        self::$controller = 'Item';

                    }

                }
            }
        }

        else if(!empty(self::$params[0])) {

            if(array_key_exists(self::$params[0], self::$routes[self::$path]) && file_exists(APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php')) {

                self::$controller_key = self::$params[0];

                unset(self::$params[0]);

            } else {

                /* Not found controller */
                self::$path = '';
                self::$controller_key = 'notfound';

            }

        }

        /* Save the current controller */
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_ame, $path = '') {

        require_once APP_PATH . 'controllers/' . ($path != '' ? $path . '/' : null) . $controller_ame . '.php';

        /* Create a new instance of the controller */
        $class = 'Altum\\Controllers\\' . $controller_ame;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

    public static function parse_method($controller) {

        $method = self::$method;

        /* Make sure to check the class method if set in the url */
        if(isset(self::get_params()[0]) && method_exists($controller, self::get_params()[0])) {

            /* Make sure the method is not private */
            $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
            if($reflection->isPublic()) {
                $method = self::get_params()[0];

                unset(self::$params[0]);
            }

        }

        return $method;

    }

}
