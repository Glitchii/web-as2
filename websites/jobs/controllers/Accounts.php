<?php

/**
 * Controller for the accounts page.
 * It handles the logic for the accounts page and its subpages.
 * Some methods are not pages, only method names that end with "Page" are pages.
 * 
 * Manage accounts paths
 * =====================
 * /admin/accounts
 * 
 * Add account paths
 * =================
 * /admin/accounts/modify
 * 
 * Edit account paths
 * ==================
 * /admin/accounts/modify/?id=
 * 
 * Delete account paths
 * ====================
 * /admin/accounts/?action=delete&id=
 */

namespace Controllers;

use \Database;
use \Page;

class Accounts extends Page {
    protected array $uriSegments;
    protected $subpage;
    protected $user;

    public function __construct(Database $db, array $uriSegment) {
        parent::__construct($db);
        $this->uriSegments = $uriSegment;
        $this->subpage = $this->uriSegments[3] ?? '';
        $this->user = $this->staffOnly();

        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        $page = "{$this->subpage}Page";
        $action = $this->param('action', 0);
        $accountId = $this->param('id', 0);
        $account = $accountId ? $this->db->account->select(['id' => $accountId]) : 0;

        if ($accountId && !$account)
            $this->redirect('/admin/accounts', 'Account not found.');

        if (method_exists($this, $page))
            return $this->{$page}($account);

        if ($action)
            return $this->action($action, $account);

        $this->accountsPage($account);
    }

    public function edit($account) {
        $type = $this->param('type');
        $username = $this->param('username');
        $password = $this->param('password', 0);
        $account2 = $this->db->account->select(['username' => $username]);

        if ($account2 && $account2['id'] != $account['id'])
            exit('Username already exists');

        $this->db->account->update([
            'username' => $username,
            'password' => $password ? password_hash($password, PASSWORD_DEFAULT) : $account['password'],
            'isAdmin' => $type === 'staff' ? 1 : 0
        ], ['id' => $account['id']]);

        $this->redirect('/admin/accounts', 'Account updated.');
    }

    public function add() {
        $type = $this->param('type');
        $username = $this->param('username');
        $password = $this->param('password');

        if ($this->db->account->select(['username' => $username]))
            exit('Username exists, try another.');

        $this->db->account->insert([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'isAdmin' => $type === 'staff' ? 1 : 0
        ]);

        $this->redirect('/admin/accounts', 'Account created.');
    }

    public function action(string $action, $account) {
        switch ($action) {
            case 'delete':
                $this->db->account->delete(['id' => $account['id']]);
                $this->redirect('/admin/accounts', 'Account Deleted');
                break;
            default:
                $this->redirect('/admin/accounts', 'Invalid action.');
        }
    }

    public function accountsPage() {
        $accountType = $this->param('type', 0);
        $constraint = 'order by username asc limit 10';

        $this->renderPage('admin/accounts', 'Accounts', [
            'user' => $this->user,
            'accountType' => $accountType,
            'accounts' => $this->db->account->selectAll($accountType ? ['isAdmin' => $accountType === 'staff', $constraint] : $constraint)
        ]);
    }

    /** Page to add or edit an account depending on whether an id is specified. */
    public function modifyPage($account) {
        if (!$this->param('submit', 0))
            return $this->renderPage('admin/accountmodify', 'Account Management', [
                'account' => $account,
                'pageType' => $account ? 'Edit' : 'Add'
            ]);

        // $fields = $this->validateForm();
        $this->{$account ? 'edit' : 'add'}($account);
    }

    public function leftSection($account, $accountType) {
        $this->renderTemplate('leftsection-staff-accounts', [
            'account' => $account,
            'subpage' => $this->subpage,
            'accountType' => $accountType,
        ]);
    }
}
