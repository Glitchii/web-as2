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
    protected $uriSegments;
    protected $subpage;
    protected $user;

    public function __construct(Database $db, array $uriSegment, bool $testing = false) {
        parent::__construct($db, $testing);
        $this->uriSegments = $uriSegment;
        $this->subpage = $this->uriSegments[3] ?? '';
        $this->user = $this->staffOnly();
    }

    public function run() {
        $page = "{$this->subpage}Page";
        $accountId = $this->param('id');
        $account = $accountId ? $this->db->account->select(['id' => $accountId]) : 0;

        if ($accountId && !$account)
            $this->redirect('/admin/accounts', 'Account not found.');

        if (method_exists($this, $page))
            return $this->{$page}($account);

        $this->accountsPage($account);
    }

    public function add($account, $form) {
        if ($errors = $this->validateAddForm($form))
            return (new Error($this->db, $errors, 'Account Creation Error'))->run();

        $this->db->account->insert([
            'username' => $form['username'],
            'password' => password_hash($form['password'], PASSWORD_DEFAULT),
            'isAdmin' => $form['type'] === 'staff' ? 1 : 0
        ]);

        $this->redirect('/admin/accounts', 'Account created.');
    }

    public function edit($account, $form) {
        // Password field should be set, even if it's empty.
        $form['password'] = $form['password'] ?? '';
        $form['account'] = $account;

        if ($errors = $this->validateEditForm($form))
            return (new Error($this->db, $errors, 'Account Modification Error'))->run();

        $this->db->account->update([
            'username' => $form['username'],
            'password' => $form['password'] ? password_hash($form['password'], PASSWORD_DEFAULT) : $account->password,
            'isAdmin' => $form['type'] === 'staff' ? 1 : 0
        ], ['id' => $account->id]);

        $this->redirect('/admin/accounts', 'Account updated.');
    }

    public function validateAddForm($form) {
        $errors = [];

        if (empty($form['password']))
            $errors[] = 'Password is required.';
        if (empty($form['type']))
            $errors[] = 'Account type is required.';
        if (empty($form['username']))
            $errors[] = 'Username is required.';
        else if ($this->db->account->select(['username' => $form['username']]))
            $errors[] = 'Username already exists, try another.';

        return $errors;
    }

    public function validateEditForm($form) {
        $errors = [];
        
        if (empty($form['type']))
            $errors[] = 'Account type is required.';
        if (empty($form['username']))
            $errors[] = 'Username is required.';
        else if ($account2 = ($form['account2'] ?? $this->db->account->select(['username' => $form['username']])))
            if ($account2->id != $form['account']->id)
                $errors[] = 'Cannot change username to one that is already used by another account.';

        return $errors;
    }

    public function action(string $action, $account) {
        switch ($action) {
            case 'delete':
                $this->db->account->delete(['id' => $account->id]);
                $this->redirect('/admin/accounts', 'Account Deleted');
                break;
            case 'edit':
                $this->redirect('/admin/accounts/modify?id=' . $account->id);
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
        
        // Form is submitted as POST but we'll merge it with $_GET just in case.
        $form = array_merge($_POST, $_GET);
        
        $this->{$account ? 'edit' : 'add'}($account, $form);
    }

    public function leftSection($account, $accountType) {
        $this->renderTemplate('leftsection-staff-accounts', [
            'account' => $account,
            'subpage' => $this->subpage,
            'accountType' => $accountType,
        ]);
    }
}
