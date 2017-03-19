<?php
/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../database/migrations/2014_10_13_100000_create_roles_table.php';
require __DIR__.'/../database/migrations/2014_10_13_200000_create_role_user_table.php';
require __DIR__.'/../database/migrations/2014_10_13_300000_create_permissions_table.php';
require __DIR__.'/../database/migrations/2014_10_13_400000_create_permission_role_table.php';

use Carbon\Carbon;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Capsule\Manager;

if (class_exists('PHPUnit\Framework\TestCase') === false) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| Here we will set the default timezone for PHP. PHP is notoriously mean
| if the timezone is not explicitly set. This will be used by each of
| the PHP date and date-time functions throughout the application.
|
*/

date_default_timezone_set('UTC');

Carbon::setTestNow(Carbon::now());

$container = new Container();
$container['events'] = new Dispatcher($container);
Facade::setFacadeApplication($container);

$manager = $container['db'] = new Manager;
$manager->addConnection([
    'driver' => 'sqlite',
    'database' => ':memory:',
]);

$manager->setEventDispatcher($container['events']);
$manager->setAsGlobal();
$manager->bootEloquent();

if (function_exists('env') === false) {
    function env($env)
    {
        switch ($env) {
            case 'APP_ENV':
                return 'local';
                break;

            case 'APP_DEBUG':
                return true;
                break;
        }
    }
}

if (class_exists('Route') === false) {
    class Route
    {
        public static function __callStatic($method, $arguments)
        {
            return new static;
        }

        public function __call($method, $arguments)
        {
        }
    }
}
