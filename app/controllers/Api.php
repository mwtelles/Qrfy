<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Response;

class Api extends Controller {
    public $api_user = null;

    public function index() {

        http_response_code(400);
        die();

    }

    public function user() {

        $this->verify_request();
        
        /* Prepare the data */
        $data = [
            'type' => 'users',
            'id' => $this->api_user->user_id,

            'email' => $this->api_user->email,
            'billing' => json_decode($this->api_user->billing),
            'active' => (bool) $this->api_user->active,
            'plan_id' => $this->api_user->plan_id,
            'plan_expiration_date' => $this->api_user->plan_expiration_date,
            'plan_settings' => $this->api_user->plan_settings,
            'plan_trial_done' => (bool) $this->api_user->plan_trial_done,
            'language' => $this->api_user->language,
            'timezone' => $this->api_user->timezone,
            'country' => $this->api_user->country,
            'date' => $this->api_user->date,
            'last_activity' => $this->api_user->last_activity,
            'total_logins' => (int) $this->api_user->total_logins,
        ];

        Response::jsonapi_success($data);
    }

    /* Function to check the request authentication */
    private function verify_request() {

        /* Define the return content to be treated as JSON */
        header('Content-Type: application/json');

        /* Make sure to check for the Auth header */
        $api_key = (function() {
            $headers = getallheaders();

            if(!isset($headers['Authorization'])) {
                return null;
            }

            if(!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return null;
            }

            return $matches[1];
        })();

        if(!$api_key) {
            http_response_code(401);
            Response::jsonapi_error([[
                'title' => $this->language->api->error_message->no_bearer,
                'status' => '401'
            ]]);
        }

        /* Get the user data of the API key owner, if any */
        $this->api_user = Database::get('*', 'users', ['api_key' => $api_key, 'active' => 1]);

        if(!$this->api_user) {
            http_response_code(401);
            Response::jsonapi_error([[
                'title' => $this->language->api->error_message->no_access,
                'status' => '401'
            ]]);
        }

        $this->api_user->plan_settings = json_decode($this->api_user->plan_settings);

        /* Rate limiting */
        $rate_limit_limit = 60;
        $rate_limit_per_seconds = 60;

        /* Verify the limitation of the bearer */
        $cache_instance = \Altum\Cache::$adapter->getItem('api-' . $api_key);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Initial save */
            $cache_instance->set($rate_limit_limit)->expiresAfter($rate_limit_per_seconds);

        }

        /* Decrement */
        $cache_instance->decrement();

        /* Get the actual value */
        $rate_limit_remaining = $cache_instance->get();

        /* Get the reset time */
        $rate_limit_reset = $cache_instance->getTtl();

        /* Save it */
        \Altum\Cache::$adapter->save($cache_instance);

        /* Set the rate limit headers */
        header('X-RateLimit-Limit: ' . $rate_limit_limit);

        if($rate_limit_remaining >= 0) {
            header('X-RateLimit-Remaining: ' . $rate_limit_remaining);
        }

        if($rate_limit_remaining < 0) {
            http_response_code(429);
            header('X-RateLimit-Reset: ' . $rate_limit_reset);
            Response::jsonapi_error([[
                'title' => $this->language->api->error_message->rate_limit,
                'status' => '429'
            ]]);
        }

    }
}
