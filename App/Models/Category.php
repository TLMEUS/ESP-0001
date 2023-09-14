<?php
/**
 * This file contains the Models/Category.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Models
 * File Name: Category.php
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
use Core\Error;
use Core\Model;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Category Model
 *
 * This model provides access to the category table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Category extends Model {

    /**
     * getAll Method
     *
     * This method returns a list of all categories in an array
     *
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function getAll(): array {
        try {
            $db = static::getDB();
            $stmt = $db->query(query: 'SELECT * FROM tbl_category ORDER BY colId');
            return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage());
        }
    }

    /**
     * getSingle method
     *
     * This method will return a single category
     *
     * @param string $colId
     * @return array|false
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function getSingle(string $colId): false|array {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: 'SELECT * FROM tbl_category WHERE colId = :colId');
            $stmt->bindValue(param: ":colId", value: $colId);
            $stmt->execute();
            return $stmt->fetch(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage());
        }
    }

    /**
     * checkName Method
     *
     * This method checks is a category is already in the database
     *
     * @param string $colName The category name to check
     * @return bool true if category is in database, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function checkName(string $colName): bool {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "SELECT * FROM tbl_category WHERE colName = :colName");
            $stmt->bindValue(param: ":colName", value: $colName);
            $stmt->execute();
            $result = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage());
        }
    }

    /**
     * insert Method
     *
     * This method inserts a new category into the database
     *
     * @param array $data Array contain the category data
     * @return bool true is inserted, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function insert(array $data): bool {
        try {
            $colName = $data['colName'];
            $colTsPercent = $data['colTsPercent'];
            if($data['colTsFlag'] == 'true') {
                $colTsFlag = "1";
            } else {
                $colTsFlag = "0";
            }
            $db= static::getDB();
            $stmt = $db->prepare(query: "INSERT INTO tbl_category (colName, colTsFlag, colTsPercent)  VALUES (:colName, :colTsFlag, :colTsPercent)");
            $stmt->bindValue(param: ":colName", value: $colName);
            $stmt->bindValue(param: ":colTsFlag", value: $colTsFlag);
            $stmt->bindValue(param: ":colTsPercent", value: $colTsPercent);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage());
        }
    }

    /**
     * update Method
     *
     * This method will update a record in the category table
     *
     * @param array $data Array containing the new category data
     * @return bool true if updated, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function update(array $data): bool {
        $colId = $data['colId'];
        $colName = $data['colName'];
        if($data['colTsFlag'] == 'true') {
            $colTsFlag = "1";
        } else {
            $colTsFlag = "0";
        }
        $colTsPercent = $data['colTsPercent'];
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "UPDATE tbl_category SET colName = :colName, colTsFlag = :colTsFlag, colTsPercent = :colTsPercent WHERE colId = :colId");
            $stmt->bindValue(param: ":colId", value: $colId);
            $stmt->bindValue(param: ":colName", value: $colName);
            $stmt->bindValue(param: ":colTsFlag", value: $colTsFlag);
            $stmt->bindValue(param: ":colTsPercent", value: $colTsPercent);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage());
        }
    }

    /**
     * displayError Method
     *
     * This method will display an error for the category entry error
     *
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[NoReturn] private static function displayError(string $message): void {
        Error::displayError(title: "Category Database Error", message: $message, code: "500");
    }
}