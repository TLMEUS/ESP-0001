<?php
/**
 * This file contains the Controllers/Plan.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Controllers
 * File Name: Plan.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 09/08/2023
 * File Authored on: 03/29/2023
 * File Copyright: 03/2023
 */
namespace App\Controllers;

/**
 * Import required classes
 */
use Core\Controller;
use App\Models\Category as CategoryModel;
use App\Models\Plan as PlanModel;
use Core\Error;
use Core\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *  Plan Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Plan extends Controller
{

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
        View::render(template: 'Plan/index.twig', args: ['categories' => $categories]);
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
        $category = CategoryModel::getSingle($_POST['colId']);
        $plans = PlanModel::getAllForCategory($_POST['colId']);
        View::render(template: 'Plan/list.twig', args: ['category' => $category, 'plans' => $plans]);
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
        $category = CategoryModel::getSingle($this->route_params['id']);
        View::render(template: 'Plan/add.twig', args: ['category' => $category]);
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
    public function addPlan(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateAddData($_POST);
        PlanModel::insertPlan($_POST['colCid'], $_POST);
        $category = CategoryModel::getSingle($_POST['colCid']);
        $plans = PlanModel::getAllForCategory($_POST['colCid']);
        View::render(template: 'Plan/list.twig', args: ['category' => $category, 'plans' => $plans]);
    }

    /**
     * deletePlanAction method
     *
     * This method will delete a plan from the database
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @noinspection PhpUnused
     */
    public function deletePlanAction(): void {
        $category = $this->route_params['grp'];
        $plan = $this->route_params['id'];
        PlanModel::deletePlan($category, $plan);
        $category = CategoryModel::getSingle($this->route_params['grp']);
        $plans = PlanModel::getAllForCategory($this->route_params['grp']);
        View::render(template: 'Plan/list.twig', args: ['category' => $category, 'plans' => $plans]);
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
        $plan = $this->route_params['id'];
        $planData = PlanModel::get($category, $plan);
        View::render(template: 'Plan/update.twig', args: ['plan' => $planData]);
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
    public function updatePlanAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        PlanModel::updatePlan($_POST['colCid'], $_POST['colId'], $_POST);
        $category = CategoryModel::getSingle($_POST['colCid']);
        $plans = PlanModel::getAllForCategory($_POST['colCid']);
        View::render(template: 'Plan/list.twig', args: ['category' => $category, 'plans' => $plans]);
    }

    /**
     * validateAddData Action
     *
     * This private method will validate the add plan data
     *
     * @param array $data Array containing the data
     * @return void
     * @throws Exception
     */
    private function validateAddData(array $data): void {
        if (strlen($data['colName']) > 100) {
            self::displayError(message: "Plan length is to long. Max of 100 characters.", code: 406);
        }
        if(!is_numeric($data['colMin']) && empty($data['colMin'])) {
            self::displayError(message: "The minimum cost is not a valid value.", code: 406);
        }
        if(!is_numeric($data['colMax']) && empty($data['colMax'])) {
            self::displayError(message: "The maximum cost is not a valid value.", code: 406);
        }
        if(empty($data['colTier1term'])) {
            self::displayError(message: "Tier 1 term is required.", code: 406);
        }
        if (empty($data['colTier1cost'])) {
            self::displayError(message: "Tier 1 cost is not a valid value.", code: 406);
        }
        if (empty($data['colTier1sku'])) {
            self::displayError(message: "Tier 1 sku is nat a valid value.", code: 406);
        }
        if (!empty($data['colTier2term'])) {
            if (empty($data['colTier2cost'])) {
                self::displayError(message: "Tier 2 cost is not a valid value.", code: 406);
            }
            if (empty($data['colTier2sku'])) {
                self::displayError(message: "Tier 2 sku is nat a valid value.", code: 406);
            }
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
        Error::displayError(title: "Plan Entry Error", message: $message, code: $code);
    }
}