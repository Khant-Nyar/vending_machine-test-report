<?php

namespace App\Services;

class AuthService
{
    // public function register($username, $password)
    // {
    //     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    //     $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    //     $this->db->query($sql, ['username' => $username, 'password' => $hashedPassword]);
    // }

    // public function login($username, $password)
    // {
    //     $sql = "SELECT * FROM users WHERE username = :username";
    //     $user = $this->db->query($sql, ['username' => $username])->fetch();

    //     if ($user && password_verify($password, $user['password'])) {
    //         $_SESSION['user_id'] = $user['id'];
    //         $_SESSION['role'] = $user['role'];
    //         return true;
    //     }
    //     return false;
    // }

    // public function checkRole($role)
    // {
    //     return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    // }

    // public function logout()
    // {
    //     session_destroy();
    // }
}