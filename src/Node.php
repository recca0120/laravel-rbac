<?php

namespace Recca0120\Rbac;

use Baum\Node as BaseNode;
use Recca0120\Rbac\Traits\Slugable;

class Node extends BaseNode
{
    use Slugable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'icon', 'action', 'level'];

    /**
     * ability.
     *
     * @return string
     */
    public function getAbilityAttribute()
    {
        if ($this->level !== 3) {
            return;
        }

        if ($this->parent === null || $this->parent->level !== 2) {
            return $this->slug;
        }

        return implode('-', [$this->parent->slug, $this->slug]);
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
     * move to position.
     *
     * @param int $parent
     * @param  int$position
     *
     * @return bool
     */
    public function moveToPosition($parent, $position)
    {
        $parent = static::findOrFail($parent);
        $childrens = $parent->children;
        if ($childrens->count() === 0) {
            $this->makeChildOf($parent);
        } else {
            $node = $childrens->get($position);
            $this->moveToLeftOf($node);
        }

        return true;
    }

    // public function copyTo($parent)
    // {
    //     $parent = static::findOrFail($parent);
    //     $parent->makeTree($this->getDescendantsAndSelf()->toHierarchy()->toArray());
    // }
}
