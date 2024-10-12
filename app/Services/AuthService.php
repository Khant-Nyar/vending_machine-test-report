<?php

namespace App\Services;

use bootstrap\Framework\Database\Database;
use Exception;

class AuthService
{
    protected $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function register(array $data)
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $errors = $this->validator->validate($rules, $data);

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $insertSuccess = Database::from('users')->insert([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => 'user'
        ]);

        if (!$insertSuccess) {
            throw new Exception('Failed to register user. Please try again.');
        }

        return true;
    }

    public function login(array $data)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $errors = $this->validator->validate($rules, $data);

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $user = Database::from('users')->where('email', '=', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new Exception('Invalid credentials.');
        }

        return $user;
    }
}
