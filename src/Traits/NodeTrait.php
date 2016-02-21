<?php

namespace Recca0120\RBAC\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait as KalnoyNodeTrait;
use Recca0120\RBAC\Role;

trait NodeTrait
{
    use KalnoyNodeTrait {
        KalnoyNodeTrait::bootNodeTrait as KalnoyBootNodeTrait;
    }

    public static $isBootTrait = false;

    public static function boot()
    {
        if (static::$isBootTrait === true) {
            return;
        }

        static::KalnoyBootNodeTrait();
        static::saving(function ($node) {
            if ($node->level === 'node' && empty($node->slug) === true && empty($node->action) === false) {
                list($controller) = explode('@', basename($node->action));
                $slug = Str::snake(str_replace('Controller', '', $controller), '-');
                $node->slug = $slug;
            }
        });

        static::saved(function ($node) {
            foreach (['getCachedNodes', 'cachedPermissionNodes'] as $key) {
                $cacheKey = static::class.$key;
                Cache::driver('file')->forget($cacheKey);
            }
            foreach ($node->roles as $role) {
                $role->forgetCache();
            }
        });

        static::deleting(function ($node) {
            $node->roles()->sync([]);
        });

        static::$isBootTrait = true;
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

        return Cache::driver('array')->rememberForever($cacheKey, function () use ($cacheKey) {
            return Cache::driver('file')->rememberForever($cacheKey, function () {
                return static::with('parent')
                    ->get();
            });
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

        return Cache::driver('array')->rememberForever($cacheKey, function () use ($cacheKey) {
            return Cache::driver('file')->rememberForever($cacheKey, function () {
                return static::cachedNodes()->filter(function ($node) {
                    return is_null($node->permission) === false;
                });
            });
        });
    }

    public function move($parent, $position)
    {
        if ($parent instanceof static === false) {
            $parent = static::find($parent);
        }

        $position = (int) $position;
        $parent->prependNode($this);
        if ($position > 0) {
            $this->down($position);
        }
    }
}
