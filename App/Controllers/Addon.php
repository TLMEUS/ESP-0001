<?php
/**
 * This file contains the Controllers/Addon.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Controllers
 * File Name: Addon.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 09/08/2023
 * File Authored on: 06/15/2023
 * File Copyright: 06/2023
 */
namespace App\Controllers;

/**
 * Import required classes
 */
use App\Models\Addon as AddonModel;
use Core\Controller;
use App\Models\Category as CategoryModel;
use Core\Error;
use Core\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *  Addon Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Addon extends Controller {

    /**
     * index Action
     *
     * This action display a drop-down list of categories to display the plans in that category
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpUnused
     */
    public function indexAction(): void {
        $categories = CategoryModel::getAll();
        View::render(template: 'Addon/index.twig', args: ['categories' => $categories]);
    }

    /**
     * list Action
     *
     * This action display the plans in the selected category
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpUnused
     */
    public function listAction(): void {
        $category = CategoryModel::getSingle(colId: $_POST['id']);
        $addons = AddonModel::getAllForCategory(colPid: $_POST['id']);
        View::render(template: 'Addon/list.twig', args: ['category' => $category, 'addons' => $addons]);
    }

    /**
     * add Action
     *
     * This action display the add plan form
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpUnused
     */
    public function addAction(): void {
        $category = CategoryModel::getSingle(colId: $this->route_params['id']);
        View::render(template: 'Addon/add.twig', args: ['category' => $category]);
    }

    /**
     * addPlan Action
     *
     * This action will validate and write the plan to the database
     *
     * @return void
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function addAddonAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateAddonData(data: $_POST);
        AddonModel::insertAddon(colPid: $_POST['colId'], data: $_POST);
        $category = CategoryModel::getSingle(colId: $_POST['colId']);
        $addons = AddonModel::getAllForCategory(colPid: $_POST['colId']);
        View::render(template: 'Addon/list.twig', args: ['category' => $category, 'addons' => $addons]);
    }

    /**
     * deleteAddonAction method
     *
     * This method will delete an addon from the database
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @noinspection PhpUnused
     */
    public function deleteAddonAction(): void {
        $category = $this->route_params['grp'];
        $addon = $this->route_params['id'];
        AddonModel::deleteAddon(colPid: $category, colId: $addon);
        $category = CategoryModel::getSingle(colId: $this->route_params['grp']);
        $addons = AddonModel::getAllForCategory(colPid: $this->route_params['grp']);
        View::render(template: 'Addon/list.twig', args: ['category' => $category, 'addons' => $addons]);
    }

    /**
     * updateAction
     *
     * This method displays the update plan form
     *
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function updateAction(): void {
        $category = $this->route_params['grp'];
        $addon = $this->route_params['id'];
        $addonData = AddonModel::get(colPid: $category, colId: $addon);
        View::render(template: 'Addon/update.twig', args: ['addon' => $addonData]);
    }

    /**
     * updatePlan Action
     *
     * This action will validate and write the plan to the database
     *
     * @return void
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function updateAddonAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateAddonData(data: $_POST);
        AddonModel::updateAddon(colPid: $_POST['colPid'], colId: $_POST['colId'], data: $_POST);
        $category = CategoryModel::getSingle(colId: $_POST['colPid']);
        $addons = AddonModel::getAllForCategory(colPid: $_POST['colPid']);
        View::render(template: 'Addon/list.twig', args: ['category' => $category, 'addons' => $addons]);
    }

    /**
     * validateAddonData Action
     *
     * This private method will validate the addon data
     *
     * @param array $data Array containing the data
     * @return void
     * @throws Exception
     */
    private function validateAddonData(array $data): void {
        if (strlen(string: $data['colTitle']) > 100) {
            self::displayError(message: "Addon title length is to long. Max of 100 characters.", code: 406);
        }
        if(!is_numeric(value: $data['colCost']) && empty($data['colCost'])) {
            self::displayError(message: "Addon title cost is not a valid value.", code: 406);
        }
        if (empty($data['colSku'])) {
            self::displayError(message: "Addon SKU is nat a valid value.", code: 406);
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
        Error::displayError(title: "Addon Entry Error", message: $message, code: $code);
    }
}