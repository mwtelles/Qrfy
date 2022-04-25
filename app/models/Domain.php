<?php

namespace Altum\Models;

use Altum\Database\Database;

class Domain extends Model {

    public function get_available_domains_by_user($user, $check_store_id = true) {

        /* Get the domains */
        $domains = [];

        /* Try to check if the domain posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('domains?user_id=' . $user->user_id . '&check_store_id=' . $check_store_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Where */
            $user_plan_settings = $user->plan_settings;
            if(property_exists($user_plan_settings, 'additional_domains_is_enabled') && $user_plan_settings->additional_domains_is_enabled ) {
                $where = "(user_id = {$user->user_id} OR `type` = 1)";
            } else {
                $where = "user_id = {$user->user_id}";
            }

            $where .= " AND `is_enabled` = 1";

            if($check_store_id) {
                $where .= " AND `store_id` IS NULL";
            }

            /* Get data from the database */
            $domains_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `domains` 
                WHERE 
                    {$where}
            ");
            while($row = $domains_result->fetch_object()) {

                /* Build the url */
                $row->url = $row->scheme . $row->host . '/';

                $domains[$row->domain_id] = $row;
            }

            \Altum\Cache::$adapter->save(
                $cache_instance->set($domains)->expiresAfter(86400)->addTag('user_id=' . $user->user_id)
            );

        } else {

            /* Get cache */
            $domains = $cache_instance->get();

        }

        return $domains;

    }

    public function get_domain_by_host($host) {

        /* Get the domain */
        $domain = null;

        /* Try to check if the domain posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('domain?host=' . $host);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $domain = Database::$database->query("SELECT * FROM `domains` WHERE `host` = '{$host}'")->fetch_object() ?? null;

            if($domain) {
                /* Build the url */
                $domain->url = $domain->scheme . $domain->host . '/';

                \Altum\Cache::$adapter->save(
                    $cache_instance->set($domain)->expiresAfter(86400)->addTag('domain_id=' . $domain->domain_id)
                );
            }

        } else {

            /* Get cache */
            $domain = $cache_instance->get();

        }

        return $domain;

    }

}
