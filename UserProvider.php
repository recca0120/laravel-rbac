<?php

namespace Acme;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'provider', 'uid', 'nickname', 'name', 'email', 'avatar', 'raw', 'token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'raw' => 'json',
    ];

    /**
     * belongs to user.
     *
     * @method user
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
