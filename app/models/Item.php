<?php

namespace Altum\Models;

use Altum\Database\Database;
use Altum\Date;

class Item extends Model {

    public function get_item_by_store_id_and_item_id($store_id, $item_id) {

        /* Get the item */
        $item = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_item?store_id=' . $store_id . '&item_id=' . $item_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item = Database::$database->query("SELECT * FROM `items` WHERE `store_id` = {$store_id} AND `item_id` = '{$item_id}'")->fetch_object() ?? null;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($item)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item = $cache_instance->get();

        }

        return $item;

    }

    public function get_item_by_store_id_and_url($store_id, $url) {

        /* Get the item */
        $item = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_item?store_id=' . $store_id . '&url=' . $url);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item = Database::$database->query("SELECT * FROM `items` WHERE `store_id` = {$store_id} AND `url` = '{$url}'")->fetch_object() ?? null;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($item)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item = $cache_instance->get();

        }

        return $item;

    }

    public function get_items_by_store_id_and_category_id($store_id, $category_id) {

        /* Get the store posts */
        $items = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_items?store_id=' . $store_id . '&category_id=' . $category_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $items_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `items` 
                WHERE 
                    `store_id` = {$store_id}
                    AND `category_id` = {$category_id} 
                    AND `is_enabled` = 1
            ");
            while($row = $items_result->fetch_object()) $items[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($items)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $items = $cache_instance->get();

        }

        return $items;

    }

    public function get_items_by_store_id_and_menu_id($store_id, $menu_id) {

        /* Get the store posts */
        $items = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_items?store_id=' . $store_id . '&menu_id=' . $menu_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $items_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `items` 
                WHERE 
                    `store_id` = {$store_id}
                    AND `menu_id` = {$menu_id} 
                    AND `is_enabled` = 1
            ");
            while($row = $items_result->fetch_object()) $items[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($items)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $items = $cache_instance->get();

        }

        return $items;

    }


}
