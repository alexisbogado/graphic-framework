<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Core;

use App\Models\User;

class Authenticator
{
    /**
     * Authenticated user variable
     *
     * @var App\Models\User $user
     */
    private $user;

    /**
     * Try to login when class is initialized
     */
    public function __construct()
    {
        if (isset($_SESSION['email']) && isset($_SESSION['password'])):
            $user = User::first('*', [ ['email', $_SESSION['email']] ]);
            if (!$user || !$this->isPasswordCorrect($_SESSION['password'], $user->password)):
                $this->logout();
                return;
            endif;

            $this->user = $user;
        elseif ($this->user):
            $this->logout();
        endif;
    }

    /**
     * Store current logged user
     *
     * @param App\Models\User $user
     * @param string $password
     * 
     * @return void
     */
    public function setUser($user, $password)
    {
        $this->user = $user;
        
        $_SESSION['email'] = $this->user->email;
        $_SESSION['password'] = $password;
    }

    /**
     * Get authenticated user instance
     *
     * @return App\Models\User|null
     */
    public function user()
    {
        return $this->user ?? null;
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function loggedIn()
    {
        return !is_null($this->user);
    }

    /**
     * Encrypt user password
     *
     * @param string $keyword
     * 
     * @return string
     */
    public function createHash($keyword)
    {
        return password_hash($keyword, CRYPT_BLOWFISH, [ 'cost' => 12 ]);
    }
    
    /**
     * Check password
     *
     * @param string $keyword
     * @param string $hash
     * 
     * @return bool
     */
    public function isPasswordCorrect($keyword, $hash)
    {
        return password_verify($keyword, $hash);
    }

    /**
     * Close current user session
     *
     * @return void
     */
    public function logout()
    {
        $this->user = null;
            
        unset($_SESSION);
        session_destroy();
    }
}
