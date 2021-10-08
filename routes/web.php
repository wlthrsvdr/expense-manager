<?php

use App\Http\Controllers\Residence;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return redirect()->route('user.login');
});


Route::group(

    array(
        'as' => "user.",
        'prefix' => "user",
        'namespace' => "User",
        'middleware' => ["system.prevent-back-history"],
    ),

    function () {

        Route::get('login/{redirect_uri?}', ['as' => "login", 'uses' => "AuthController@login"]);
        Route::post('login/{redirect_uri?}', ['as' => "authenticate", 'uses' => "AuthController@authenticate"]);
        Route::any('logout', ['as' => "logout", 'uses' => "AuthController@logout"]);

        

        Route::group(['middleware' => ["system.auth:client"]], function () {

            Route::any('/', ['as' => "index", 'uses' => "MainController@index"]);

            Route::get('chartData', ['uses' => "MainController@chartData"]);
        });
    }

);



Route::group(

    array(
        'as' => "admin.",
        'prefix' => "admin",
        'namespace' => "Admin",
        'middleware' => ["system.prevent-back-history"],
    ),

    function () {

        Route::get('login/{redirect_uri?}', ['as' => "login", 'uses' => "AuthController@login"]);
        Route::post('login/{redirect_uri?}', ['as' => "authenticate", 'uses' => "AuthController@authenticate"]);
        Route::any('logout', ['as' => "logout", 'uses' => "AuthController@logout"]);


        Route::get('expenses', ['as' => "expenses", 'uses' => "ExpenseManagement@expenses"]);
        Route::post('expenses', ['uses' => "ExpenseManagement@store"]);

        Route::get('get_expense/{id}', ['uses' => "ExpenseManagement@get_expense"]);
        Route::get('get_categories', ['uses' => "ExpenseManagement@get_categories"]);

        Route::post('update_expense', ['uses' => "ExpenseManagement@update"]);


        Route::group(['middleware' => ["system.auth:admin"]], function () {

            Route::any('/', ['as' => "dashboard", 'uses' => "MainController@index"]);
            Route::get('chartData', ['uses' => "MainController@chartData"]);


            Route::get('users', ['as' => "users", 'uses' => "UserManagement@users"]);
            Route::post('users', ['uses' => "UserManagement@store"]);

            Route::get('get_user/{id}', ['uses' => "UserManagement@get_user"]);
            Route::post('update_user', ['uses' => "UserManagement@update"]);
            Route::post('delete_user/{id}', ['uses' => "UserManagement@delete_user"]);


            Route::get('roles', ['as' => "goto_roles", 'uses' => "UserManagement@goto_roles"]);
            Route::post('roles', ['uses' => "UserManagement@store_roles"]);

            Route::get('get_role/{id}', ['uses' => "UserManagement@get_role"]);
            Route::get('get_user_role', ['uses' => "UserManagement@get_user_role"]);

            Route::post('update_role', ['uses' => "UserManagement@update_role"]);

            Route::post('delete_role/{id}', ['uses' => "UserManagement@delete_role"]);

            Route::get('categories', ['as' => "goto_category", 'uses' => "ExpenseManagement@category"]);
            Route::post('categories', ['uses' => "ExpenseManagement@store_category"]);

            Route::get('get_category/{id}', ['uses' => "ExpenseManagement@get_category"]);
            Route::post('update_category', ['uses' => "ExpenseManagement@update_category"]);

            Route::post('delete_category/{id}', ['uses' => "ExpenseManagement@delete_category"]);



            Route::post('delete_expense/{id}', ['uses' => "ExpenseManagement@delete_expense"]);
        });
    }

);
