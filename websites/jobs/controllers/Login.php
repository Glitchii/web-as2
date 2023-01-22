<?php

namespace Controllers;

use \Database;
use \Page;

class Login extends Page {
    public function __construct(Database $db) {
        parent::__construct($db);
        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        if (isset($_POST['password']) && isset($_POST['username']))
            if ($user = $this->db->account->select(['username' => $_POST['username']]))
                if (password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['loggedIn'] = $user['id'];
                    $this->redirect('/admin');
                }
                
        $this->renderPage("admin/login");
    }
}
