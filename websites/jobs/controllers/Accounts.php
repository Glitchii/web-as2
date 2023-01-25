<?php

/**
 * Controller for the accounts page.
 * It handles the logic for the accounts page and its subpages.
 * Some methods are not pages, only method names that end with "Page" are pages.
 * 
 * Manage accounts path
 * =====================
 * /admin/accounts
 * 
 * Add account path
 * =================
 * /admin/accounts/modify
 * /admin/accounts/modify?action=add
 * 
 * Edit account path
 * ==================
 * /admin/accounts/modify?id=X
 * /admin/accounts/modify?id=X&action=edit
 * 
 * Delete account path
 * ====================
 * /admin/accounts/modify?id=X&action=delete
 */

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

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
        $accountId = $this->param('id');
        $account = $accountId ? $this->db->account->select(['id' => $accountId]) : 0;

        if ($accountId && !$account)
            $this->redirect('/admin/accounts', 'Account not found.');

        if (method_exists($this, $page))
            return $this->{$page}($account);

        $this->accountsPage($account);
    }

    public function edit($account) {
        $type = $this->param('type', 1);
        $username = $this->param('username', 1);
        $password = $this->param('password');
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
        $type = $this->param('type', 1);
        $username = $this->param('username', 1);
        $password = $this->param('password', 1);

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
            case 'edit':
                $this->redirect('/admin/accounts/modify?id=' . $account['id']);
                break;
            case 'add':
                $this->redirect('/admin/accounts/modify');
                break;
            default:
                $this->redirect('/admin/accounts', 'Invalid action.');
        }
    }

    public function accountsPage() {
        $accountType = $this->param('type');
        $constraint = 'order by username asc limit 10';

        $this->renderPage('admin/accounts', 'Accounts', [
            'user' => $this->user,
            'accountType' => $accountType,
            'accounts' => $this->db->account->selectAll($accountType ? ['isAdmin' => $accountType === 'staff', $constraint] : $constraint)
        ]);
    }

    /** Page to add or edit an account depending on whether an id is specified. */
    public function modifyPage($account) {
        $action = $this->param('action');

        if ($action)
            return $this->action($action, $account);

        if (!$this->param('submit'))
            return $this->renderPage('admin/accountmodify', 'Account Management', [
                'account' => $account,
                'pageType' => $account ? 'Edit' : 'Add'
            ]);

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
