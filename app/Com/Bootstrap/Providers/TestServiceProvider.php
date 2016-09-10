<?php 

namespace Com\Bootstrap\Providers;

use Illuminate\Support\ServiceProvider;

use Bll\Test\Network\Repository as NetworkRepository;

class TestServiceProvider extends ServiceProvider
{
    protected $defer = true;
    
    public function register()
    {

        $this->app->singleton(
            NetworkRepository\Interfaces\TestRepositoryInterface::class,
            NetworkRepository\Instances\TestLocalRepository::class
        );

    }


    public function provides()
    {
        return [
            NetworkRepository\Interfaces\TestRepositoryInterface::class,
        ];
    }

}
