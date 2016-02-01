<?php

namespace Recca0120\RBAC;

use Illuminate\Support\Str;

class Morph
{
    /**
     * morph users.
     *
     * @var array
     */
    public static $morphes = [];

    /**
     * add define morphes.
     *
     * @param string $className
     */
    public static function pushMorph($className)
    {
        $key = Str::plural(Str::camel(class_basename($className)));
        self::$morphes[$key] = $className;
    }
}
