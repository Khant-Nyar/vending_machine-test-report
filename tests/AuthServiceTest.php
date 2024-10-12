<?php

use App\Services\AuthService;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    protected $authService;

    protected function setUp(): void
    {
        $this->authService = new AuthService();
    }

    public function testRegisterSuccess()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $result = $this->authService->register($data);
        $this->assertTrue($result);
    }

    public function testRegisterValidationFailure()
    {
        $data = [
            'username' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ];

        $result = $this->authService->register($data);
        $this->assertArrayHasKey('errors', $result);
        $this->assertCount(3, $result['errors']);
    }

    public function testLoginSuccess()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
        $this->authService->register($data);


        $user = $this->authService->login($data);
        $this->assertArrayHasKey('id', $user);
    }

    public function testLoginFailure()
    {
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ];

        $this->expectException(Exception::class);
        $this->authService->login($data);
    }
}
