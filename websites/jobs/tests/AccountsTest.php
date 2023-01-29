<?php

namespace Tests;

require '/websites/jobs/classes/Table.php';
require '/websites/jobs/classes/Database.php';
require '/websites/jobs/classes/Page.php';
require '/websites/jobs/controllers/Accounts.php';

use PHPUnit\Framework\TestCase;
use Classes\Database;
use Controllers\Accounts;

class AccountsTest extends TestCase {
    protected $db;
    protected $accountController;
    protected $uriSegments;

    public function setUp(): void {
        $REQUEST_URI = '/admin/accounts';

        $this->db = new Database('student', 'student', 'job');
        $this->uriSegments = explode('/', explode('?', $REQUEST_URI)[0]);
        $this->accountController = new Accounts($this->db, $this->uriSegments, true);
    }

    public function testAccountAddValidationForm() {
        $validForm = ['username' => 'test', 'password' => 'test', 'type' => 'test'];
        $noUsername = ['username' => '', 'password' => 'test', 'type' => 'test'];
        $noPassword = ['username' => 'test', 'password' => '', 'type' => 'test'];
        $noType = ['username' => 'test', 'password' => 'test', 'type' => ''];
        $usernameExists = ['username' => 'admin', 'password' => 'test', 'type' => 'test'];
        $noUsernamePasswordType = ['username' => '', 'password' => '', 'type' => ''];

        // validateForm() returns an empty array if the form is valid otherwise it returns an array of error messages.
        $this->assertEquals([], $this->accountController->validateAddForm($validForm));

        $noUsernameMessage = 'Username is required.';
        $this->assertContains($noUsernameMessage, $this->accountController->validateAddForm($noUsername));

        $noPasswordMessage = 'Password is required.';
        $this->assertContains($noPasswordMessage, $this->accountController->validateAddForm($noPassword));

        $noTypeMessage = 'Account type is required.';
        $this->assertContains($noTypeMessage, $this->accountController->validateAddForm($noType));

        $usernameExistsMessage = 'Username already exists, try another.';
        $this->assertContains($usernameExistsMessage, $this->accountController->validateAddForm($usernameExists));

        $noUsernamePasswordTypeMessage = [
            'Password is required.',
            'Account type is required.',
            'Username is required.',
        ];

        $this->assertEquals($noUsernamePasswordTypeMessage, $this->accountController->validateAddForm($noUsernamePasswordType));
    }

    public function testAccountEdiValidationtForm() {
        // Mock account that we are editing.
        $account = ['username' => 'admin', 'id' => 1];

        $validForm = ['username' => 'test', 'type' => 'test', 'account' => $account];
        $noUsername = ['username' => '', 'type' => 'test', 'account' => $account];
        $noType = ['username' => 'test', 'type' => '', 'account' => $account];

        $this->assertEquals([], $this->accountController->validateEditForm($validForm));

        $noUsernameMessage = 'Username is required.';
        $this->assertContains($noUsernameMessage, $this->accountController->validateEditForm($noUsername));

        $noTypeMessage = 'Account type is required.';
        $this->assertContains($noTypeMessage, $this->accountController->validateEditForm($noType));

        $usernameExistsFrom = [
            'username' => 'admin',
            'type' => 'test',
            'account' => $account,
            // In the actual function, 'account2' is an account queried from the database if the username is already taken.
            // The method first checks if 'account2' is set, if not it queries the database for an account with the that isn't the account we are editing.
            // Mock account that has the same username as the account we are editing.
            'account2' => ['id' => 2, 'username' => 'admin'] // Notice the id is different, it means it is a different account.
        ];

        $usernameExistsMessage = 'Cannot change username to one that is already used by another account.';
        $this->assertContains($usernameExistsMessage, $this->accountController->validateEditForm($usernameExistsFrom));

        $usernameExistsFrom2 = [
            'username' => 'admin',
            'type' => 'test',
            'account' => $account,
            'account2' => $account // Same id and everything, it is the same account.
        ];

        // Code below should not error because while new username is the same as the old one, it is the same account.
        // Same username is allowed in the form because maybe the user just wants to change the account type and left the username the same.
        $this->assertNotContains($usernameExistsMessage, $this->accountController->validateEditForm($usernameExistsFrom2));

        // 'account2' is false, it means the username is not taken.
        $this->assertNotContains($usernameExistsMessage, $this->accountController->validateEditForm(['account2' => false]));
        $this->assertEquals([], $this->accountController->validateEditForm(array_merge($validForm, ['account2' => false])));
    }
}
