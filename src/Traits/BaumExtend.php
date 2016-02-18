<?php

namespace Recca0120\RBAC\Traits;

trait BaumExtend
{
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
