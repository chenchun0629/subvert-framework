<?php 

namespace Com\Bootstrap\Providers;

use Illuminate\Support\ServiceProvider;

use Repo\Test;

class TestServiceProvider extends ServiceProvider
{
    protected $defer = true;
    
    public function register()
    {

        $this->app->singleton(
            Test\Contracts\SqlTestContract::class,
            Test\Instances\SqlTestLocalRepository::class
        );

    }


    public function provides()
    {
        return [
            Test\Contracts\SqlTestContract::class,
        ];
    }

}
