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

$app->singleton('Com\Bootstrap\Validation\SignValidation', function () {
    $body = app('request')->get('body');
    $client = $body['client'];
    $class = 'Com\\Bootstrap\\Validation\\' . studly_case($client) .'SignValidation';
    return app()->make($class);
});


$app->middleware([
        Com\Bootstrap\Middleware\RuidMiddleware::class,                     # 生成请求唯一ID
        Com\Bootstrap\Middleware\ParameterValidationMiddleware::class,      # 参数验证
        Com\Bootstrap\Middleware\ClientValidationMiddleware::class,         # 客户端来源你验证
        Com\Bootstrap\Middleware\SignValidationMiddleware::class,           # 签名验证
        Com\Bootstrap\Middleware\LoadRoutesMiddleware::class,               # 延迟加载路由
    ]);


/**
 * load routes groups
 */
$app->routeGroups([
        'test' => 'routes/test',
    ]);


return $app;
