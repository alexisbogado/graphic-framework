<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Controllers;

use App\Models\Role;

class RolesController extends Controller
{
    public function get($id)
    {
        if (!$id)
            return Role::all();
        
        return Role::find($id) ?? $this->sendJSONResponse(false, [ 'message' => 'No role found' ], 422);
    }
}
