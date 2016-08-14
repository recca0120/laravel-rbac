<?php

namespace Recca0120\Rbac\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait UserTrait
{
    /**
     * bootUserTrait.
     *
     * @method bootUserTrait
     */
    public static function bootUserTrait()
    {
        static::saved(function ($model) {
            Cache::forget(static::cacheKey().'cacheRoles'.$model->id);
        }, 99);
    }

    /**
     * hasRole.
     *
     * @method hasRole
     *
     * @return bool
     */
    public function hasRole($role)
    {
        $roles = $this->cachedRoles();
        if (is_string($role) === true) {
            return $roles->filter(function ($model) use ($role) {
                return Str::slug($model->name) == $role;
            })->count() > 0;
        }

        return $role->intersect($roles)->count() > 0;
    }

    /**
     * cachedRoles.
     *
     * @method cachedRoles
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    protected function cachedRoles()
    {
        return Cache::rememberForever($this->cacheKey().'cacheRoles'.$this->id, function () {
            return $this->roles;
        });
    }

    /**
     * cacheKey.
     *
     * @method cacheKey
     *
     * @return string
     */
    public static function cacheKey()
    {
        return self::class;
    }
}
