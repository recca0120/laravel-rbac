<?php

namespace Recca0120\RBAC\Contracts;

interface User
{
    /**
     * initialize morph.
     *
     * @return void
     */
    public static function bootUserTrait();

    /**
     * The roles that belong to the user.
     */
    public function roles();

    /**
     * user nodes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNodes();

    /**
     * user nodes attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNodesAttribute();

    /**
     * user permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions();

    /**
     * user permissions attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsAttribute();

    /**
     * attach role.
     *
     * @param \Recca0120\RBAC\Role|array|id $role
     *
     * @return void
     */
    public function attachRole($role);

    /**
     * detach role.
     *
     * @param \Recca0120\RBAC\Role|array|id $role
     *
     * @return void
     */
    public function detachRole($role);

    /**
     * sync roles.
     *
     * @param  array
     *
     * @return void
     */
    public function syncRoles($roles);

    /**
     * check user role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function is($role);
}
