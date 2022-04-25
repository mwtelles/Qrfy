<?php

namespace Altum\Models;

use Altum\Database\Database;

class ItemExtras extends Model {

    public function get_item_extras_by_store_id_and_item_id($store_id, $item_id) {

        /* Get the item extras */
        $item_extras = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_item_extras?store_id=' . $store_id . '&item_id=' . $item_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item_extras_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `items_extras` 
                WHERE 
                    `item_id` = {$item_id} 
            ");
            while($row = $item_extras_result->fetch_object()) $item_extras[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($item_extras)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item_extras = $cache_instance->get();

        }

        return $item_extras;

    }

    public function get_item_extras_by_store_id_and_item_extras_ids($store_id, $item_extras_ids) {

        $item_extras_ids = implode(',', $item_extras_ids);

        /* Get the item extras */
        $item_extras = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_item_extras?store_id=' . $store_id . '&item_extras_ids=' . $item_extras_ids);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item_extras_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `items_extras` 
                WHERE 
                    `item_extra_id` IN ({$item_extras_ids})
            ");
            while($row = $item_extras_result->fetch_object()) $item_extras[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($item_extras)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item_extras = $cache_instance->get();

        }

        return $item_extras;

    }

}
