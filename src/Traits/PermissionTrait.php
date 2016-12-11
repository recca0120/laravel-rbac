<?php

namespace Recca0120\Rbac\Traits;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Cache;

trait PermissionTrait
{
    use NodeTrait;

    /**
     * bootPermissionTrait.
     *
     * @method bootPermissionTrait
     */
    public static function bootPermissionTrait()
    {
        static::saved(function ($model) {
            Cache::forget(static::cacheKey());
        }, 99);

        static::deleted(function () {
            Cache::forget(static::cacheKey());
        }, 99);
    }

    /**
     * cacheAll.
     *
     * @method cachedAll
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    public function cachedAll()
    {
        return $this
            ->defaultOrder()
            ->with('parent')
            ->with('roles')
            ->get();
        // return Cache::rememberForever(static::cacheKey(), function () {
        //     return $this
        //         ->defaultOrder()
        //         ->with('parent')
        //         ->with('roles')
        //         ->get();
        // });
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

    /**
     * findAllByDirectory.
     *
     * @method findAllByDirectory
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    public function findAllByDirectory()
    {
        return $this->cachedAll()->filter(function ($permission) {
            return $permission->type !== 'permission';
        });
    }

    /**
     * findAllByPermission.
     *
     * @method findAllByPermission
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    public function findAllByPermission()
    {
        return $this->cachedAll()->filter(function ($permission) {
            return $permission->type === 'permission';
        });
    }

    /**
     * findOneByAs.
     *
     * @method findOneByAs
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    public function findOneByAs($as)
    {
        return $this->cachedAll()->filter(function ($permission) use ($as) {
            return $permission->as === $as;
        })->first();
    }
}
