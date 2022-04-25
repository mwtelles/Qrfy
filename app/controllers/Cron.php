<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Language;

class Cron extends Controller {

    public function index() {

        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != $this->settings->cron->key)) {
            die();
        }

        $date = \Altum\Date::$date;

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = (new \DateTime($this->settings->cron->reset_date))->format('m');
        $current_date = (new \DateTime())->format('m');

        if($reset_date != $current_date) {

            /* Update the settings with the updated time */
            $cron_settings = json_encode([
                'key' => $this->settings->cron->key,
                'reset_date' => $date
            ]);

            Database::$database->query("UPDATE `settings` SET `value` = '{$cron_settings}' WHERE `key` = 'cron'");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('settings');
        }

        $this->email_reports();

        die();
    }

    private function email_reports() {

        /* Only run this part if the email reports are enabled */
        if(!$this->settings->stores->email_reports_is_enabled) {
            return;
        }

        $date = \Altum\Date::$date;

        /* Determine the frequency of email reports */
        $days_interval = 7;

        switch($this->settings->stores->email_reports_is_enabled) {
            case 'weekly':
                $days_interval = 7;

                break;

            case 'monthly':
                $days_interval = 30;

                break;
        }

        /* Get potential stores from users that have almost all the conditions to get an email report right now */
        $result = Database::$database->query("
            SELECT
                `stores`.`store_id`,
                `stores`.`url`,
                `stores`.`name`,
                `stores`.`email_reports_last_datetime`,
                `users`.`user_id`,
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`
            FROM 
                `stores`
            LEFT JOIN 
                `users` ON `stores`.`user_id` = `users`.`user_id` 
            WHERE 
                `users`.`active` = 1
                AND `stores`.`is_enabled` = 1 
                AND `stores`.`email_reports_is_enabled` = 1
				AND DATE_ADD(`stores`.`email_reports_last_datetime`, INTERVAL {$days_interval} DAY) <= '{$date}'
            LIMIT 25
        ");

        /* Go through each result */
        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);

            /* Make sure the plan still lets the user get email reports */
            if(!$row->plan_settings->email_reports_is_enabled) {
                Database::$database->query("UPDATE `stores` SET `email_reports_is_enabled` = 0 WHERE `store_id` = {$row->store_id}");

                continue;
            }

            /* Prepare */
            $previous_start_date = (new \DateTime())->modify('-' . $days_interval * 2 . ' days')->format('Y-m-d H:i:s');
            $start_date = (new \DateTime())->modify('-' . $days_interval . ' days')->format('Y-m-d H:i:s');
            
            /* Start getting information about the store to generate the statistics */
            $basic_analytics = $this->database->query("
                SELECT 
                    COUNT(*) AS `pageviews`
                FROM 
                    `statistics`
                WHERE 
                    `store_id` = {$row->store_id} 
                    AND (`datetime` BETWEEN '{$start_date}' AND '{$date}')
            ")->fetch_object() ?? null;

            $previous_basic_analytics = $this->database->query("
                SELECT 
                    COUNT(*) AS `pageviews`
                FROM 
                    `statistics`
                WHERE 
                    `store_id` = {$row->store_id} 
                    AND (`datetime` BETWEEN '{$previous_start_date}' AND '{$start_date}')
            ")->fetch_object() ?? null;

            /* Get the language for the user */
            $language = Language::get($row->language);

            /* Prepare the email title */
            $email_title = sprintf(
                $language->cron->email_reports->title,
                $row->name,
                \Altum\Date::get($start_date, 5),
                \Altum\Date::get('', 5)
            );

            /* Prepare the View for the email content */
            $data = [
                'row'                       => $row,
                'basic_analytics'           => $basic_analytics,
                'previous_basic_analytics'  => $previous_basic_analytics,
                'language'                  => $language
            ];

            $email_content = (new \Altum\Views\View('partials/cron/email_reports', (array) $this))->run($data);

            /* Send the email */
            send_mail($this->settings, $row->email, $email_title, $email_content);

            /* Update the store */
            Database::update('stores', ['email_reports_last_datetime' => $date], ['store_id' => $row->store_id]);

            /* Insert email log */
            Database::insert('email_reports', ['user_id' => $row->user_id, 'store_id' => $row->store_id, 'datetime' => $date]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s and store_id %s', $row->user_id, $row->store_id);
            }
        }

    }

}
