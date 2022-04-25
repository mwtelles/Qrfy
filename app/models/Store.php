<?php

namespace Altum\Models;

use Altum\Database\Database;

class Store extends Model {

    public function get_store_full_url($store, $user, $domains = null) {

        /* Detect the URL of the store */
        if($store->domain_id) {

            /* Get available custom domains */
            if(!$domains) {
                $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($user, false);
            }

            if(isset($domains[$store->domain_id])) {

                if($store->store_id == $domains[$store->domain_id]->store_id) {

                    $store->full_url = $domains[$store->domain_id]->scheme . $domains[$store->domain_id]->host . '/';

                } else {

                    $store->full_url = $domains[$store->domain_id]->scheme . $domains[$store->domain_id]->host . '/' . $store->url . '/';

                }

            }

        } else {

            $store->full_url = url('s/' . $store->url . '/');

        }

        return $store->full_url;
    }

    public function get_store_by_url($store_url) {

        /* Get the store */
        $store = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_store?url=' . $store_url);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $store = Database::$database->query("SELECT * FROM `stores` WHERE `url` = '{$store_url}'")->fetch_object() ?? null;

            if($store) {
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($store)->expiresAfter(86400)->addTag('store_id=' . $store->store_id)
                );
            }

        } else {

            /* Get cache */
            $store = $cache_instance->get();

        }

        return $store;

    }

    public function get_store_by_url_and_domain_id($store_url, $domain_id) {

        /* Get the store */
        $store = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_store?url=' . $store_url . '&domain_id=' . $domain_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $store = Database::$database->query("SELECT * FROM `stores` WHERE `url` = '{$store_url}' AND `domain_id` = $domain_id")->fetch_object() ?? null;

            if($store) {
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($store)->expiresAfter(86400)->addTag('store_id=' . $store->store_id)
                );
            }

        } else {

            /* Get cache */
            $store = $cache_instance->get();

        }

        return $store;

    }

    public function get_store_by_store_id($store_id) {

        /* Get the store */
        $store = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_store?store_id=' . $store_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $store = Database::$database->query("SELECT * FROM `stores` WHERE `store_id` = '{$store_id}'")->fetch_object() ?? null;

            if($store) {
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($store)->expiresAfter(86400)->addTag('store_id=' . $store->store_id)
                );
            }

        } else {

            /* Get cache */
            $store = $cache_instance->get();

        }

        return $store;

    }

}
