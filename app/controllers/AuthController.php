<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    public function user()
    {
        $this->permissions([ 'auth' ], true);

        return auth()->user();
    }

    public function signin($request)
    {
        $this->permissions([ 'guest' ], true);

        $email = ($request->input->email ?? null);
        $password = ($request->input->password ?? null);

        if (!$email || !$password):
            $errors = [ ];

            if (!$email)
                $errors['email'] = 'Email field cannot be empty';

            if (!$password)
                $errors['password'] = 'Password field cannot be empty';

            return $this->sendJSONResponse(false, [ 'errors' => $errors ], 422);
        endif;

        $user_by_email = User::first('*', [ ['email', $email] ]);
        if (!$user_by_email)
            return $this->sendJSONResponse(false, [ 'errors' => ['email' => 'No users found with this email'] ], 422);
        elseif (!auth()->isPasswordCorrect($password, $user_by_email->password))
            return $this->sendJSONResponse(false, [ 'errors' => ['password' => 'Password mismatch'] ], 422);

        auth()->setUser($user_by_email, $password);

        return $this->sendJSONResponse(true, [
            'message' => 'User logged in successfully',
            'redirectPath' => route(config('app.route_after_login'))->path()
        ]);
    }

    public function signup($request)
    {
        $this->permissions([ 'guest' ], true);

        $username = ($request->input->username ?? null);
        $email = ($request->input->email ?? null);
        $password = ($request->input->password ?? null);

        $user_by_email = User::first('*', [ ['email', $email] ]);

        if (!$username || (!$email || $user_by_email) || !$password):
            $errors = [ ];

            if (!$username)
                $errors['username'] = 'Username field cannot be empty';

            if (!$email)
                $errors['email'] = 'Email field cannot be empty';
            elseif ($user_by_email)
                $errors['email'] = 'Email has already been taken';

            if (!$password)
                $errors['password'] = 'Password field cannot be empty';

            return $this->sendJSONResponse(false, [ 'errors' => $errors ], 422);
        endif;

        $user = new User;
        $user->username = $username;
        $user->password = auth()->createHash($password);
        $user->email = $email;
        $user->roles_id = 2;
        $user->created_at = date("Y-m-d H:i:s");
        $user->save();

        auth()->setUser($user, $password);

        return $this->sendJSONResponse(true, [
            'message' => 'User registered successfully',
            'redirectPath' => route(config('app.route_after_login'))->path()
        ]);
    }

    public function logout()
    {
        $this->permissions([ 'auth' ]);
        
        auth()->logout();
        header('Location: ' . config('app.url'));
    }
}
