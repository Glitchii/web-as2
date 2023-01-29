<?php

namespace Tests;

require '/websites/jobs/controllers/Login.php';

use PHPUnit\Framework\TestCase;
use Classes\Database;
use Controllers\Login;


class LoginTest extends TestCase {
    protected $db;
    protected $loginController;

    public function setUp(): void {
        $this->db = new Database('student', 'student', 'job');
        $this->loginController = new Login($this->db);
    }

    public function testLoginFormValidation() {
        $validForm = ['username' => 'test', 'password' => 'test'];
        $noUsername = ['username' => '', 'password' => 'test'];
        $noPassword = ['username' => 'test', 'password' => ''];
        $noUsernamePassword = ['username' => '', 'password' => ''];

        // validateLoginForm() returns an empty array if the form is valid otherwise it returns an array of error messages.
        $this->assertEquals([], $this->loginController->validateLoginForm($validForm));
        
        $noUsernameMessage = 'Username is required.';
        $this->assertContains($noUsernameMessage, $this->loginController->validateLoginForm($noUsername));

        $noPasswordMessage = 'Password is required.';
        $this->assertContains($noPasswordMessage, $this->loginController->validateLoginForm($noPassword));

        $noUsernamePasswordMessage = [
            'Username is required.',
            'Password is required.',
        ];
        
        $this->assertEquals($noUsernamePasswordMessage, $this->loginController->validateLoginForm($noUsernamePassword));
    }
}