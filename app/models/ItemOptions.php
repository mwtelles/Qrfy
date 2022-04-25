<?php

namespace Altum\Models;

use Altum\Database\Database;

class ItemOptions extends Model {

    public function get_item_options_by_store_id_and_item_options_ids($store_id, $item_options_ids) {

        if(!count($item_options_ids)) {
            return [];
        }

        $item_options_ids = implode(',', $item_options_ids);

        $item_options = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('s_item_options?store_id=' . $store_id . '&item_options_ids=' . $item_options_ids);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item_options_result = Database::$database->query("
                SELECT 
                    *
                FROM 
                    `items_options` 
                WHERE 
                    `item_option_id` IN ({$item_options_ids}) 
            ");
            while($row = $item_options_result->fetch_object()) $item_options[] = $row;

            \Altum\Cache::$adapter->save(
                $cache_instance->set($item_options)->expiresAfter(86400)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item_options = $cache_instance->get();

        }

        return $item_options;

    }

}
