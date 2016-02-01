<?php

namespace Recca0120\RBAC;

use Baum\Node as BaumNode;
use Recca0120\RBAC\Contracts\Node as NodeContract;
use Recca0120\RBAC\Traits\NodeTrait;
use Recca0120\RBAC\Traits\Slugable;

class Node extends BaumNode implements NodeContract
{
    use Slugable, NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * level:
     * 	1: directory
     *  2: controller
     *  3: action
     * @var array
     */
    protected $fillable = ['name', 'slug', 'icon', 'action', 'level'];
}
