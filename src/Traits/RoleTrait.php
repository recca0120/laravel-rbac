<?php

namespace Recca0120\RBAC\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Recca0120\RBAC\Node;

trait RoleTrait
{
    use Morphable;

    public static function bootRoleTrait()
    {
        static::saved(function ($role) {
            foreach (['cachedNodes'] as $key) {
                $cacheKey = static::class.$key.$role->id;
                Cache::driver('file')->forget($cacheKey);
            }
        });

        static::deleting(function ($role) {
            $role->users()->sync([]);
            $role->nodes()->sync([]);
        });
    }

    /**
     * The nodes that belongs to role.
     */
    public function nodes()
    {
        return $this->belongsToMany(
            Node::class,
            'role_nodes',
            'role_id',
            'node_id'
        );
    }

    public function cachedNodes()
    {
        $cacheKey = static::class.'cachedNodes'.$this->id;

        return Cache::driver('array')->rememberForever($cacheKey, function () use ($cacheKey) {
            return Cache::driver('file')->rememberForever($cacheKey, function () {
                return $this->nodes()->with('parent')->get();
            });
        });
    }

    /**
     * permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissionsAttribute()
    {
        return $this->cachedNodes()->filter(function ($node) {
            return is_null($node->permission) === false;
        });
    }

    /**
     * roles has permission.
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->permissions->contains('permission', $permission);
    }

    /**
     * attach node.
     *
     * @param \Baum\Node|int|array $node
     *
     * @return void
     */
    public function attachNode($node)
    {
        if (is_object($node) === true) {
            $node = $node->getKey();
        } elseif (is_array($node) === true) {
            $node = $node['id'];
        }
        $this->nodes()->attach($node);
    }

    /**
     * detach node.
     *
     * @param \Baum\Node|int|array $node
     *
     * @return void
     */
    public function detachNode($node)
    {
        if (is_object($node) === true) {
            $node = $node->getKey();
        } elseif (is_array($node) === true) {
            $node = $node['id'];
        }
        $this->nodes()->detach($node);
    }

    /**
     * detach node.
     *
     * @param array $nodes
     *
     * @return void
     */
    public function syncNodes($nodes)
    {
        if (empty($nodes) === false) {
            if ($nodes instanceof Collection) {
                $nodes = $nodes->toArray();
            }

            $this->nodes()->sync(array_map(function ($node) {
                if (is_object($node) === true) {
                    $node = $node->getKey();
                } elseif (is_array($node) === true) {
                    $node = $node['id'];
                }

                return $node;
            }, $nodes));
        } else {
            $this->nodes()->detach();
        }
    }
}
