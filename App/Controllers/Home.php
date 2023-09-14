<?php
/**
 * This file contains the Controllers/Home.php file for project ESP-0001.
 *
 * File Information:
 * Project Name: ESP-0001
 * Module Name: Controllers
 * File Name: Home.php
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
use App\Models\Home as HomeModel;
use Core\Controller;
use Core\View;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Home Controller
 *
 * @extends Core\Controller
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */

class Home extends Controller {

    /**
     * Show the index page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpUnused
     */
    public function indexAction(): void
    {
        View::render('Home/index.twig');
    }

    /**
     * apikeyAction method
     *
     * This method will display the apikey registration page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpUnused
     */
    public function apikeyAction(): void {
        View::render('Home/apikey.twig');
    }

    /**
     * saveAPIKeyAction method
     *
     * This method will save the api key and present it to the user
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function saveAPIKeyAction(): void {
        if($_SERVER["REQUEST_METHOD"] !== "POST") {
            throw new Exception("Request method not allowed.", 405);
        }
        $result = HomeModel::saveAPIKey($_POST);
        if (!$result) {
            View::render('Home/noapikey.twig');
        } else {
            View::render('Home/keycreated.twig', ['apikey' => $result]);
        }
    }
}