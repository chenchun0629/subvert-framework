<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/
$app = new Subvert\Framework\Application(
    'api',
    realpath(__DIR__.'/../')
);

$app->withFacades();

// $app->singleton(
//     Illuminate\Contracts\Debug\ExceptionHandler::class,
//     App\Exceptions\Handler::class
// );



/**
 * load routes groups
 */
$app->routeGroups([
        'test' => 'routes/test.php',
    ]);


return $app;
