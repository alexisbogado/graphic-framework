<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Controllers;

class Controller
{
    /**
     * Check permissions to show the page
     *
     * @param array $permissions
     * @param bool $api
     * 
     * @return void
     */
    public function permissions(array $permissions, $api = false)
    {
        $load_view = true;

        foreach ($permissions as $permission):
            switch ($permission):
                case 'auth':
                    $load_view = auth()->loggedIn();
                    
                    if (!$load_view && !$api):
                        header('Location: ' . config('app.url'));
                        exit;
                    endif;
                break;

                case 'guest':
                    $load_view = !auth()->loggedIn();

                    if (!$load_view && !$api):
                        header('Location: ' . route(config('app.route_after_login'))->path());
                        exit;
                    endif;
                break;
            endswitch;
        endforeach;

        if (!$load_view) die($api ? json_encode($this->sendJSONResponse(false, [ 'message' => 'Unauthorized' ], 401)) : view('error'));
    }

    /**
     * Send API response with status code
     * 
     * @param bool $success
     * @param array $data
     * @param int $code
     *
     * @return array
     */
    public function sendJSONResponse(bool $success, array $data, $code = 200)
    {
        http_response_code($code);
        return [
            'success' => $success,
            'data' => $data
        ];
    }
}
