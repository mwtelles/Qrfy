# QrMenuOnTable

![version](https://img.shields.io/badge/version-2.0-blue.svg?longCache=true&style=flat-square)

![App](/readme-full.png)

# [Instalação]()

## 1. Prepare the database

1. Create a new Database User for the upcoming new database (optional)
2. Create a new Database
3. Prepare the Database `Host`, `Name`, `Username` and `Password` for the upcoming steps.

## 2. Prepare the product files

You need to set the permissions (CHMOD) of the following files / folders to either 755, 775, or 777 which will depend on your actual server.


* /uploads/favicon/
* /uploads/logo/
* /uploads/opengraph/
* /uploads/cache/
* /uploads/offline_payment_proofs/
* /uploads/store_logos/
* /uploads/store_favicons/
* /uploads/store_images/
* /uploads/menu_images/
* /uploads/item_images/
* /config.php

## 3 Start the installation process

1. Access the product on your website and access the `/install path.`
2. Follow the steps in the installation process
3. You can now access the website and login with your admin account

## 4 Cron job setup

The cron job must be set up as it is responsible for handling background tasks.

1. Make sure to login and go to the `Admin Panel -> Website Settings -> Cron tab`
2. Create a new cron job with each cron job commands that you see in there.

## 5 How to update text or language

Any static text from the website can be changed from the
```
$ app/languages/english#en.php
```