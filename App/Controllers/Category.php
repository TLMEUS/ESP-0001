<?php
/**
 * This file contains the Controllers/Category.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Controllers
 * File Name: Category.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 09/08/2023
 * File Authored on: 03/23/2023
 * File Copyright: 03/2023
 */
namespace App\Controllers;

/**
 * Import required classes
 */
use App\Models\Category as CategoryModel;
use Core\Controller;
use Core\View;
use Core\Error;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Category Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Category extends Controller {

    /**
     * Index Action
     *
     * This action will display a list of the categories
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): void {
        $categories = CategoryModel::getAll();
        View::render(template: 'Category/index.twig', args: ['categories' => $categories]);
    }

    /**
     * Add Action
     *
     * This action will display the add category page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpUnused
     */
    public function addAction(): void {
        View::render(template: 'Category/add.twig');
    }

    /**
     * Add Data Action
     *
     * This action will add the category to the database
     *
     * @return void
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function addDataAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateAddData($_POST);
        if(!CategoryModel::insert($_POST)) {
            self::displayError(message: "Unable to save category.", code: 500);
        }
        $this->indexAction();
    }

    /**
     * Update Action
     *
     * This action will display the update category page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function updateAction(): void {
        $old_cat = CategoryModel::getSingle($this->route_params['id']);
        if(!$old_cat) {
            self::displayError(message: "Unable to locate record", code: 404);
        }
        View::render('Category/update.twig', [
            "colId" => $old_cat['colId'],
            "colName" => $old_cat['colName'],
            "colTsFlag" => $old_cat['colTsFlag'],
            "colTsPercent" => $old_cat['colTsPercent']
        ]);
    }

    /**
     * Update Category Action
     *
     * This action will update the category information in the database
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function updateDataAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateUpdateData($_POST);
        if(!CategoryModel::update($_POST)) {
            self::displayError(message: "Unable to update category", code: 500);
        }
        $this->indexAction();
    }

    /**
     * Validate Add Data Method
     *
     * This method will validate the add category data
     *
     * @param array $data The category data to validate
     * @return void
     * @throws Exception
     * @noinspection PhpUnused
     */
    private function validateAddData(array $data): void {
        $colName = $data["colName"];
        if(empty($colName)) {
            self::displayError(message: "Category name can not be empty.", code: 406);
        }
        if(CategoryModel::checkName($colName)) {
            self::displayError(message: "Category exists in database.", code: 406);
        }
    }

    /**
     * Validate Update Data Method
     *
     * This method will validate the update category data
     *
     * @param array $data The category data to validate
     * @return void
     * @throws Exception
     * @noinspection PhpUnused
     */
    private function validateUpdateData(array $data): void {
        if(!array_key_exists(key: 'colId', array: $data)) {
            self::displayError(message: "Missing the category id.", code: 406);
        }
        $colName = $data["colName"];
        if(empty($colName)) {
            self::displayError(message: "Category name can not be empty.", code: 406);
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
    #[NoReturn] private function displayError(string $message, string $code): void {
        Error::displayError(title: "Category Entry Error", message: $message, code: $code);
    }
}