<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('add-page');
});

Route::match(['get', 'post'], 'personalAssessment/crud_pratice_19042024/{controller}/{action?}/{params?}', function ($controller, $action = 'index', $params = '') {
    // echo $controller;exit;
    if ($action === 'edit') {
        $action = 'index';
    } 
    $params = explode('/', $params);
    $app = app();
    $controller = $app->make("\App\Http\Controllers\\" . ucwords($controller) . 'Controller',['action' => $action]);
    return $controller->callAction($action, $params);
});

/* ******************************AJAX Controller********************************** */
Route::match(['get', 'post'], '/Manage/{action}', function ($action) {
    $app = app();
    $controller = $app->make("\App\Http\Controllers\ManageController");
    return $controller->callAction($action, []);
});