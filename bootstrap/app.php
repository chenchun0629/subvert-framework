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

/**
 * load config
 */
$app->configure('database');
$app->configure('session');

/**
 * bind object
 */
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Subvert\Framework\Foundation\Exception\ExceptionHandler::class
);

$app->singleton('Com\Bootstrap\Validation\SignValidation', function () {
    $class = 'Com\\Bootstrap\\Validation\\' . studly_case(app('request_client')) .'SignValidation';
    return app()->make($class);
});


/**
 * set middleware
 */
$app->middleware([
        Com\Bootstrap\Middleware\InitMiddleware::class,                     # 初始化
        Com\Bootstrap\Middleware\LogRequestMiddleware::class,               # 记录请求
        Com\Bootstrap\Middleware\FilteResponseMiddleware::class,            # 过滤返回
        Com\Bootstrap\Middleware\ParameterValidationMiddleware::class,      # 参数验证
        Com\Bootstrap\Middleware\ClientValidationMiddleware::class,         # 客户端来源你验证
        Com\Bootstrap\Middleware\SignValidationMiddleware::class,           # 签名验证
        Com\Bootstrap\Middleware\LoadRoutesMiddleware::class,               # 延迟加载路由
        Com\Bootstrap\Middleware\ProcessDatatMiddleware::class,             # 处理数据
        Com\Bootstrap\Middleware\RegisterProviderMiddleware::class,         # 注册服务
    ]);

/**
 * register service
 */
$app->register(Illuminate\Redis\RedisServiceProvider::class);


/**
 * load routes groups
 */
$app->routeGroups([
        'test' => 'routes/test',
    ]);


return $app;
