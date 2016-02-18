<?php

namespace Recca0120\RBAC\Traits;

use Closure;

trait Cachable
{
    /**
     * cached.
     *
     * @var mixed
     */
    public static $cached = [];

    /**
     * cached.
     *
     * @param string  $key
     * @param Closure $closure
     * @param string  $driver
     *
     * @return mixed
     */
    public function cached($key, Closure $closure, $driver = 'static')
    {
        $cacheKey = static::class.$key.$this->id;
        switch ($driver) {
            default:
            case 'static':
                if (isset(static::$cached[$cacheKey]) === false) {
                    static::$cached[$cacheKey] = $closure();
                }

                return static::$cached[$cacheKey];
                break;
        }
    }
}
