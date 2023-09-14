<?php
/**
 * This file contains the Models/Home.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Models
 * File Name: Home.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 09/08/2023
 * File Authored on: 06/15/2023
 * File Copyright: 06/2023
 */
namespace App\Models;

/**
 * Import needed classes
 */

use Core\Error;
use Core\Model;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * Home Model class
 *
 * This model provides access to the user table in the database
 */
class Home extends Model {

    /**
     * saveAPIKey method
     *
     * This method saves user record to the database
     *
     * @param array $data
     * @return string|null
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function saveAPIKey(array $data): ?string {
        $db = static::getDB();
        $sql = "INSERT INTO tbl_apikeys (colName, colUsername, colPassword, colApikey) VALUES (:name, :username, :password_hash, :api_key)";
        $stmt = $db->prepare($sql);
        $password_hash = password_hash(password: $data['colPassword'], algo: PASSWORD_DEFAULT);
        try {
            $api_key = bin2hex(string: random_bytes(16));
        } catch (Exception) {
        }
        try {
            $stmt->bindValue(param: ":name", value: $data['colName']);
            $stmt->bindValue(param: ":username", value: $data['colUsername']);
            $stmt->bindValue(param: ":password_hash", value: $password_hash);
            $stmt->bindValue(param: ":api_key", value: $api_key);
            $stmt->execute();
            $retval = $api_key;
        } catch (Exception $ex) {
            Error::displayError(title: "API KEY Creation Error", message: $ex, code: "500");
        }
        return $retval;
    }
}