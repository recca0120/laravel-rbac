<?php

namespace Recca0120\Rbac\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

trait UserTrait
{
    /**
     * hasRole.
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
     * @return \Illuminate\Database\Eloquent\Collection
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
     * @return string
     */
    public static function cacheKey()
    {
        return self::class;
    }
}
