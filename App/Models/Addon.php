<?php
/**
 * This file contains the Models/Addon.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Models
 * File Name: Addon.php
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
 * Import required classes
 */
use Core\Model;

use PDO;

/**
 * Addons Model
 *
 * This model provides access to the addon table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Addon extends Model
{
    /**
     * getAllForCategory method
     *
     * This method will return an array containing the plan list from the database given the category
     *
     * @param string $colPid The category id
     * @return array The list of addons
     */
    public static function getAllForCategory(string $colPid): array {
        $db = static::getDB();
        $stmt = $db->prepare(query: "SELECT * FROM tbl_addons WHERE colPid = :colPid");
        $stmt->bindValue(param: ":colPid" , value: $colPid);
        $stmt->execute();
        return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
    }

    /**
     * insertAddon Method
     *
     * This method will add an addon into the Addon database table
     *
     * @param string $colPid The plan parent category
     * @param array $data The array containing the addon data
     * @return void
     */
    public static function insertAddon(string $colPid, array $data): void {
        $db = static::getDB();
        $sql = "INSERT INTO tbl_addons (colId, colPid, colTitle, colCost, colSku) VALUES (:colId, :colPid, :colTitle, :colCost, :colSku)";
        $stmt = $db->prepare($sql);
        $new_id = static::getNextAddonID($colPid);
        $stmt->bindValue(param: ":colId", value: $new_id);
        $stmt->bindValue(param: ":colPid", value: $colPid);
        $stmt->bindValue(param: ":colTitle", value: $data['colTitle']);
        $stmt->bindValue(param: ":colCost", value: $data['colCost']);
        $stmt->bindValue(param: ":colSku", value: $data['colSku']);
        $stmt->execute();
    }

    /**
     * deleteAddon Method
     *
     * This method will delete an addon from the database
     *
     * @param string $colPid
     * @param string $colId
     * @return void
     */
    public static function deleteAddon(string $colPid, string $colId): void {
        $db = static::getDB();
        $stmt = $db->prepare(query: "DELETE FROM tbl_addons WHERE colPid = :colPid AND colId = :colId");
        $stmt->bindValue(param: ":colPid", value: $colPid);
        $stmt->bindValue(param: ":colId", value: $colId);
        $stmt->execute();
    }

    /**
     * get Method
     *
     * This method returns a single addon given the parent and id
     *
     * @param string $colPid
     * @param string $colId
     * @return array The plan data
     */
    public static function get(string $colPid, string $colId): array {
        $db = static::getDB();
        $stmt = $db->prepare(query: "SELECT * FROM tbl_addons WHERE colPid = :colPid AND colId = :colId");
        $stmt->bindValue(param: ":colPid", value: $colPid);
        $stmt->bindValue(param: ":colId", value: $colId);
        $stmt->execute();
        return $stmt->fetch(mode: PDO::FETCH_ASSOC);

    }

    /**
     * updateAddon Method
     *
     * Method to update an addon in the database
     *
     * @param string $colPid
     * @param string $colId
     * @param array $data The new addon data
     * @return int Count of rows updated
     */
    public static function updateAddon(string $colPid, string $colId, array $data):int {
        $db = static::getDB();
        $fields = [];
        if (!empty($data["colTitle"])) {
            $fields["colTitle"] = [
                $data["colTitle"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colCost"])) {
            $fields["colCost"] = [
                $data["colCost"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colSku"])) {
            $fields["colSku"] = [
                $data["colSku"],
                PDO::PARAM_INT
            ];
        }
        if (empty($fields)) {
            return 0;
        } else {
            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));
            $sql = "UPDATE tbl_addons SET ".implode(separator: ", ", array: $sets)." WHERE colId = :colId and colPid = :colPid";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(param: ":colId", value: $colId, type: PDO::PARAM_INT);
            $stmt->bindValue(param: ":colPid", value: $colPid, type: PDO::PARAM_INT);
            foreach ($fields as $name => $values) {
                $stmt->bindValue(param: ":$name", value: $values[0], type: $values[1]);
            }
            $stmt->execute();
            return $stmt->rowCount();
        }
    }

    /**
     * getNextAddonId method
     *
     * Private method to get the next addon id in a category
     *
     * @param string $colPid
     * @return string The new addon id number
     */
    private static function getNextAddonId(string $colPid):string {
        $db = static::getDB();
        $sql = "SELECT count(*) FROM tbl_addons WHERE colPid = :colPid";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(param: ":colPid", value: $colPid);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result + 1;
    }
}