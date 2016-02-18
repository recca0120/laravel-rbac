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
<<<<<<< HEAD

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;

class Application extends Container
{
    public $aliases = [
        \Illuminate\Support\Facades\Facade::class  => 'Facade',
        \Illuminate\Support\Facades\App::class     => 'App',
        \Illuminate\Support\Facades\Schema::class  => 'Schema',
    ];

    public function __construct()
    {
        date_default_timezone_set('UTC');

        if (class_exists('\Carbon\Carbon') === true) {
            \Carbon\Carbon::setTestNow(\Carbon\Carbon::now());
        }

        $this['app'] = $this;
        $this->setupAliases();
        $this->setupDispatcher();
        $this->setupConnection();
        Facade::setFacadeApplication($this);
        Container::setInstance($this);
    }

    public function setupDispatcher()
    {
        if (class_exists('\Illuminate\Events\Dispatcher') === false) {
            return;
        }
        $this['events'] = new \Illuminate\Events\Dispatcher($this);
    }

    public function setupAliases()
    {
        foreach ($this->aliases as $className => $alias) {
            class_alias($className, $alias);
        }
    }

    public function setupConnection()
    {
        if (class_exists('\Illuminate\Database\Capsule\Manager') === false) {
            return;
        }

        $connection = new \Illuminate\Database\Capsule\Manager();
        $connection->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
        $connection->setEventDispatcher($this['events']);
        $connection->bootEloquent();
        $connection->setAsGlobal();

        $this['db'] = $connection;
    }

    public function migrate($method)
    {
        if (class_exists('\Illuminate\Database\Capsule\Manager') === false) {
            return;
        }

        switch ($method) {
            case 'up':
                Schema::create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->string('password', 60);
                    $table->rememberToken();
                    $table->timestamps();
                });
                Schema::create('members', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->string('password', 60);
                    $table->rememberToken();
                    $table->timestamps();
                });
                break;
            case 'down':
                Schema::drop('users');
                Schema::drop('members');
                break;
        }

        foreach (glob(__DIR__.'/../database/migrations/*.php') as $file) {
            include_once $file;
            if (preg_match('/\d+_\d+_\d+_\d+_(.*)\.php/', $file, $m)) {
                $className = Str::studly($m[1]);
                $migration = new $className();
                call_user_func_array([$migration, $method], []);
            }
        }
    }

    public function environment()
    {
        return 'testing';
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return (new \Illuminate\Hashing\BcryptHasher())->make($value, $options);
    }
}

if (!function_exists('app')) {
    function app()
    {
        return App::getInstance();
    }
}

if (Application::getInstance() == null) {
    new Application();
}
=======
require __DIR__.'/traits/Laravel.php';

use Carbon\Carbon;

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
>>>>>>> 07f27f3... add interface
