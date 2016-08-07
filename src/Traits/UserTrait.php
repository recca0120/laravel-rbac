<?php

namespace Recca0120\Rbac\Traits;

use Illuminate\Support\Facades\Cache;

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
            return $roles->contains('name', $role);
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
        return static::class;
    }
}
