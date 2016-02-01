<?php

namespace Recca0120\RBAC\Contracts;

interface Node
{
    /**
     * roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * get permission.
     * @return string
     */
    public function getPermission();

    /**
     * get permission attribute.
     *
     * @return string
     */
    public function getPermissionAttribute();
}
