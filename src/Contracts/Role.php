<?php

namespace Recca0120\RBAC\Contracts;

interface Role
{
    /**
     * The nodes that belongs to role.
     */
    public function nodes();

    /**
     * permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions();

    /**
     * attach node.
     *
     * @param \Baum\Node|int|array $node
     *
     * @return void
     */
    public function attachNode($node);

    /**
     * detach node.
     *
     * @param \Baum\Node|int|array $node
     *
     * @return void
     */
    public function detachNode($node);

    /**
     * detach node.
     *
     * @param array $nodes
     *
     * @return void
     */
    public function syncNodes($nodes);
}
