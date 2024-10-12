<?php

namespace App\Controllers\Auth;

use App\Services\AuthService;
use bootstrap\Framework\Request;
use bootstrap\Framework\Session;
use Exception;

class AuthController
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register(Request $request)
    {
        $data = $request->all();

        // Use the AuthService for registration
        $result = $this->authService->register($data);

        if (isset($result['errors'])) {
            return [
                'view' => 'register',
                'errors' => $result['errors']
            ];
        }

        $_SESSION['user'] = [
            'username' => $data['username'],
            'email' => $data['email'],
            'role' => 'user'
        ];

        redirect('products');
        exit();
    }

    public function login(Request $request)
    {
        $data = $request->all();

        try {
            // Use the AuthService for login
            $user = $this->authService->login($data);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            redirect('products');
        } catch (Exception $e) {
            throw new Exception('Login failed: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        // Clear session
        unset($_SESSION['user']);
        session_destroy();

        redirect('/products');
    }
}
