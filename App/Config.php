<?php
/**
 * This file contains the App/Config.php file for the TLME-Framework.
 *
 * File Information:
 * Project Name: TLME-Framework
 * Module Name: App
 * File Name: Config.php
 * Author: Troy L. Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 08/14/23
 * File Authored on: 3/29/2023
 * File Copyright: 3/2023
 */
namespace App;

/**
 * Configuration settings
 */
class Config {

    /**
     * Database host name
     */
    const DB_HOST = 'localhost';

    /**
     * Database schema name
     */
    const DB_NAME = 'db_esp';

    /**
     * Database username
     */
    const DB_USERNAME = 'esp_user';

    /**
     * Database password
     */
    const DB_PASSWORD = 'Lynn#1968';

    /**
     * Flag to show errors
     */
    const SHOW_ERRORS = false;
}