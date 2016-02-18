<?php

namespace Recca0120\RBAC\Traits;

use Illuminate\Support\Facades\Cache;
use Recca0120\RBAC\Role;

trait NodeTrait
{
    use BaumExtend;

    public static function bootNodeTrait()
    {
        static::saved(function ($model) {
            foreach (['getCachedNodes', 'cachedPermissionNodes'] as $key) {
                $cacheKey = static::class.$key;
                Cache::driver('file')->forget($cacheKey);
            }
        });
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_nodes',
            'node_id',
            'role_id'
        );
    }

    /**
     * get permission.
     *
     * @return string
     */
    public function getPermission()
    {
        if ($this->level !== 'permission') {
            return;
        }

        if ($this->parent === null || $this->parent->level !== 'node') {
            return $this->slug;
        }

        return implode('-', [$this->parent->slug, $this->slug]);
    }

    /**
     * permission attribute.
     *
     * @return string
     */
    public function getPermissionAttribute()
    {
        return $this->getPermission();
    }

    /**
     * get all nodes from cache.
     *
     * @return \Baum\Extensions\Eloquent\Collection
     */
    public static function cachedNodes()
    {
        $cacheKey = static::class.'getCachedNodes';

        return Cache::driver('file')->rememberForever($cacheKey, function () {
            return static::with('parent')
                ->get();
        });
    }

    /**
     * get all permission nodes from cache.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function cachedPermissionNodes()
    {
        $cacheKey = static::class.'cachedPermissionNodes';

        return Cache::driver('file')->rememberForever($cacheKey, function () {
            return static::cachedNodes()->filter(function ($node) {
                return is_null($node->permission) === false;
            });
        });
    }
}
