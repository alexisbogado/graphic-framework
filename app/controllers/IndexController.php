<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Controllers;

class IndexController extends Controller
{
    public function index($request)
    {
        $this->permissions([ 'guest' ]);

        return view('index', [
            'showGithubButton' => true
        ]);
    }

    public function home()
    {
        $this->permissions([ 'auth' ]);

        return view('home');
    }
}
