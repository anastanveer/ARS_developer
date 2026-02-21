<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Resolve Paths For Local + cPanel Split Deploy
|--------------------------------------------------------------------------
|
| Local:   project/public -> ../vendor, ../bootstrap, ../storage
| cPanel:  domain_root     -> ../arsdeveloper_app/vendor, .../bootstrap, .../storage
|
*/
$appBasePath = is_dir(__DIR__.'/../vendor')
    ? dirname(__DIR__)
    : dirname(__DIR__).'/arsdeveloper_app';

$maintenancePath = $appBasePath.'/storage/framework/maintenance.php';
if (file_exists($maintenancePath)) {
    require $maintenancePath;
}

require $appBasePath.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appBasePath.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
