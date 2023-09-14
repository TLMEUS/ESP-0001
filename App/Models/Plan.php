<?php
/**
 * This file contains the Models/Plan.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Models
 * File Name: Plan.php
 * File Version: 1.1.0
 * Author: Troy L. Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 09/08/2023
 * File Authored on: 03/29/2023
 * File Copyright: 03/2023
 */

namespace App\Models;

/**
 * Import required classes
 */
use Core\Model;
use PDO;

/**
 * Plan Model
 *
 * This model provides access to the plan table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Plan extends Model
{
    /**
     * getAllForCategory method
     *
     * This method will return an array containing the plan list from the database given the category
     *
     * @param string $colCid
     * @return array The list of plans
     */
    public static function getAllForCategory(string $colPid): array {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM tbl_plan WHERE colPid = :colPid");
        $stmt->bindValue(":colPid" , $colPid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * insertPlan Method
     *
     * This method will add a plan into the Plan database table
     *
     * @param string $colCid The plan parent category
     * @param array $data The array containing the plan data
     * @return void
     */
    public static function insertPlan(string $colCid, array $data): void {
        $db = static::getDB();
        $sql = "INSERT INTO tbl_plan (colId, colCid, colName, colMin, colMax, colTier1term, colTier1cost, 
                    colTier1sku, colTier2term, colTier2cost, colTier2sku) VALUES (:colId, :colCid,
                    :colName, :colMin, :colMax, :colTier1term, :colTier1cost, :colTier1sku, :colTier2term,
                    :colTier2cost, :colTier2sku)";
        $stmt = $db->prepare($sql);
        $new_id = static::getNextPlanId($colCid);
        $stmt->bindValue(param: ":colId", value: $new_id);
        $stmt->bindValue(param: ":colCid", value: $colCid);
        $stmt->bindValue(param: ":colName", value: $data['colName']);
        $stmt->bindValue(param: ":colMin", value: $data['colMin']);
        $stmt->bindValue(param: ":colMax", value: $data['colMax']);
        $stmt->bindValue(param: ":colTier1term", value: $data['colTier1term']);
        $stmt->bindValue(param: ":colTier1cost", value: $data['colTier1cost']);
        $stmt->bindValue(param: ":colTier1sku", value: $data['colTier1sku']);
        $stmt->bindValue(param: ":colTier2term", value: $data['colTier2term']);
        $stmt->bindValue(param: ":colTier2cost", value: $data['colTier2cost']);
        $stmt->bindValue(param: ":colTier2sku", value: $data['colTier2sku']);
        $stmt->execute();
    }

    /**
     * deletePlan Method
     *
     * This method will delete a plan from the database
     *
     * @param string $colCid
     * @param string $colId
     * @return void
     */
    public static function deletePlan(string $colCid, string $colId): void {
        $db = static::getDB();
        $stmt = $db->prepare(query: "DELETE FROM tbl_plan WHERE colCid = :colCid AND colId = :colId");
        $stmt->bindValue(param: ":colCid", value: $colCid);
        $stmt->bindValue(param: ":colId", value: $colId);
        $stmt->execute();
    }

    /**
     * get Method
     *
     * This method returns a single plan given the parent and id
     *
     * @param string $colCid
     * @param string $colId
     * @return array The plan data
     */
    public static function get(string $colCid, string $colId): array {
        $db = static::getDB();
        $stmt = $db->prepare(query: "SELECT * FROM tbl_plan WHERE colCid = :colCid AND colId = :colId");
        $stmt->bindValue(param: ":colCid", value: $colCid);
        $stmt->bindValue(param: ":colId", value: $colId);
        $stmt->execute();
        return $stmt->fetch(mode: PDO::FETCH_ASSOC);

    }

    /**
     * updatePlan Method
     *
     * Method to update a plan in the database
     *
     * @param string $colCid
     * @param string $colId
     * @param array $data The new plan data
     * @return int Count of rows updated
     */
    public static function updatePlan(string $colCid, string $colId, array $data):int {
        $db = static::getDB();
        $fields = [];
        if (!empty($data["colName"])) {
            $fields["colName"] = [
                $data["colName"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colMin"])) {
            $fields["colMin"] = [
                $data["colMin"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colMax"])) {
            $fields["colMax"] = [
                $data["colMax"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colTier1term"])) {
            $fields["colTier1term"] = [
                $data["colTier1term"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colTier1cost"])) {
            $fields["colTier1cost"] = [
                $data["colTier1cost"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colTier1sku"])) {
            $fields["colTier1sku"] = [
                $data["colTier1sku"],
                PDO::PARAM_INT
            ];
        }
        if (!empty($data["colTier2term"])) {
            $fields["colTier2term"] = [
                $data["colTier2term"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colTier2cost"])) {
            $fields["colTier2cost"] = [
                $data["colTier2cost"],
                PDO::PARAM_STR
            ];
        }
        if (!empty($data["colTier2sku"])) {
            $fields["colTier2sku"] = [
                $data["colTier2sku"],
                PDO::PARAM_INT
            ];
        }
        if (empty($fields)) {
            return 0;
        } else {
            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));
            $sql = "UPDATE tbl_plan SET ".implode(separator: ", ", array: $sets)." WHERE colId = :colId and colCid = :colCid";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(param: ":colId", value: $colId, type: PDO::PARAM_INT);
            $stmt->bindValue(param: ":colCid", value: $colCid, type: PDO::PARAM_INT);
            foreach ($fields as $name => $values) {
                $stmt->bindValue(param: ":$name", value: $values[0], type: $values[1]);
            }
            $stmt->execute();
            return $stmt->rowCount();
        }
    }

    /**
     * getNextPlanId method
     *
     * Private method  to get the next plan id in a category
     *
     * @param string $colCid
     * @return string The new plan id number
     */
    private static function getNextPlanId(string $colCid):string {
        $db = static::getDB();
        $sql = "SELECT count(*) FROM tbl_plan WHERE colCid = :colCid";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(param: ":colCid", value: $colCid);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result + 1;
    }
}