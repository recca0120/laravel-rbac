<?php

namespace Recca0120\RBAC\Traits;

use Recca0120\RBAC\Node;

trait RoleTrait
{
    use Morphable;

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

    /**
     * permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions()
    {
        return $this->nodes()
            ->with('parent')
            ->where('level', '=', 3);
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
