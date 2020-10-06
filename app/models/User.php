<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Models;

class User extends Model
{
    protected $hidden = [ 'password', 'roles_id' ];
    protected $functionsToShow = [ 'role' ];

    /**
     * Get user role
     *
     * @return \App\Models\Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }
}
