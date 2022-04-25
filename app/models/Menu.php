<?php

namespace Altum\Models;

use Altum\Database\Database;
use Altum\Date;

class Menu extends Model {

    public function get_menu_by_store_id_and_url($store_id, $url) {

        /* Get the menu */
        $post = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('menu?store_id=' . $store_id . '&url=' . $url);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $post = Database::$database->query("SELECT * FROM `menus` WHERE `store_id` = {$store_id} AND `url` = '{$url}'")->fetch_object() ?? null;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($post)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $post = $cache_instance->get();

        }

        return $post;

    }

    public function get_menus_by_store_id($store_id) {

        /* Get the store posts */
        $menus = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('r_menus?store_id=' . $store_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $menus_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `menus` 
                WHERE 
                    `store_id` = {$store_id}
                    AND `is_enabled` = 1
            ");
            while($row = $menus_result->fetch_object()) $menus[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($menus)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $menus = $cache_instance->get();

        }

        return $menus;

    }

}
