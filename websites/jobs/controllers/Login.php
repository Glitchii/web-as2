<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Login extends Page {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function run() {
        if (!$this->param('submit'))
            return $this->renderPage("admin/login");

        // Login form is sent as POST but we'll merge it with $_GET just in case.
        $form = array_merge($_POST, $_GET);

        if ($errors = $this->validateLoginForm($form))
            return (new Error($this->db, $errors, 'Login Error'))->run();

        if ($user = $this->db->account->select(['username' => $form['username']]))
            if (password_verify($form['password'], $user->password))
                $_SESSION['loggedIn'] = $user->id;

        $this->redirect('/admin');
    }

    public function validateLoginForm($form) {
        $username = $form['username'];
        $password = $form['password'];
        $errors = [];

        !$username && $errors[] = 'Username is required.';
        !$password && $errors[] = 'Password is required.';

        return $errors;
    }
}
