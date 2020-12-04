<?php

namespace PHPMaker2021\tooms;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // cf_user
    $app->any('/CfUserList[/{empl_id}]', CfUserController::class . ':list')->add(PermissionMiddleware::class)->setName('CfUserList-cf_user-list'); // list
    $app->any('/CfUserAdd[/{empl_id}]', CfUserController::class . ':add')->add(PermissionMiddleware::class)->setName('CfUserAdd-cf_user-add'); // add
    $app->any('/CfUserEdit[/{empl_id}]', CfUserController::class . ':edit')->add(PermissionMiddleware::class)->setName('CfUserEdit-cf_user-edit'); // edit
    $app->any('/CfUserSearch', CfUserController::class . ':search')->add(PermissionMiddleware::class)->setName('CfUserSearch-cf_user-search'); // search
    $app->group(
        '/cf_user',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{empl_id}]', CfUserController::class . ':list')->add(PermissionMiddleware::class)->setName('cf_user/list-cf_user-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{empl_id}]', CfUserController::class . ':add')->add(PermissionMiddleware::class)->setName('cf_user/add-cf_user-add-2'); // add
            $group->any('/' . Config("EDIT_ACTION") . '[/{empl_id}]', CfUserController::class . ':edit')->add(PermissionMiddleware::class)->setName('cf_user/edit-cf_user-edit-2'); // edit
            $group->any('/' . Config("SEARCH_ACTION") . '', CfUserController::class . ':search')->add(PermissionMiddleware::class)->setName('cf_user/search-cf_user-search-2'); // search
        }
    );

    // error
    $app->any('/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->any('/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->any('/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // logout
    $app->any('/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->any('/[index]', OthersController::class . ':index')->setName('index');
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
