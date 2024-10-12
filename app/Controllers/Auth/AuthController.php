<?php

namespace App\Controllers\Auth;

use bootstrap\Framework\Database\Database;
use bootstrap\Framework\Database\Query;
use bootstrap\Framework\Request;
use bootstrap\Framework\Session;
use Exception;

class AuthController
{
    public function register(Request $request)
    {
        $errors = [];
        $data = $request->all();

        if (empty($data['username'])) {
            $errors['username'] = 'Username is required.';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }

        if (!empty($errors)) {
            return [
                'view' => 'register',
                'errors' => $errors
            ];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $insertSuccess = Database::from('users')->insert([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => 'user' // Default role
        ]);

        if ($insertSuccess) {
            $_SESSION['user'] = [
                'username' => $data['username'],
                'email' => $data['email'],
                'role' => 'user'
            ];

            redirect('products');
            exit();
        } else {
            throw new Exception('Failed to register user. Please try again.');
        }
    }


    public function login(Request $request)
    {
        $data = $request->all();

        if (empty($data['email']) || empty($data['password'])) {
            throw new Exception('Email and Password are required.');
        }

        $user = Database::from('users')->where('email', '=', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new Exception('Invalid credentials.');
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        redirect('products');
    }

    public function logout()
    {
        // Clear session
        unset($_SESSION['user']);
        session_destroy();

        redirect('/products');
    }
}
