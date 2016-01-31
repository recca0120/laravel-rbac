<?php

namespace Recca0120\Rbac;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Recca0120\Rbac\Traits\Slugable;

class Role extends Model
{
    use Slugable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * morph users.
     *
     * @var array
     */
    public static $morphes = [];

    /**
     * add define morphes.
     *
     * @param string$className
     */
    public static function pushMorph($className)
    {
        $key = Str::plural(Str::camel(class_basename($className)));
        static::$morphes[$key] = $className;
    }

    /**
     * get define morphes.
     *
     * @return array
     */
    public static function getMorphes()
    {
        return static::$morphes;
    }

    /**
     * The morphed users that belong to the role.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function morphedUsers($key)
    {
        return $this->morphedByMany(
            static::$morphes[$key],
            'user',
            'user_roles',
            'role_id',
            'user_id'
        );
    }

    /**
     * Get a relationship.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        $value = parent::getRelationValue($key);
        if ($value === null && isset(static::$morphes[$key]) === true) {
            return $this->getRelationshipFromMethod($key);
        }
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

    public function __call($method, $parameters)
    {
        if (isset(static::$morphes[$method]) === true) {
            return $this->morphedUsers($method);
        }

        return parent::__call($method, $parameters);
    }

    /*
     * The users that belong to the role.
     */
    // public function users()
    // {
    //     return $this->morphedByMany(
    //         User::class,
    //         'user',
    //         'user_roles',
    //         'role_id',
    //         'user_id'
    //     );
    // }
}
