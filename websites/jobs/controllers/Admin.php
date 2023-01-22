<?php

namespace Controllers;

use \Database;
use \Page;

class Admin extends Page {
    protected $uriSegments;
    protected $subpage;

    public function __construct(Database $db) {
        parent::__construct($db);
        $this->uriSegments = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);
        $this->subpage = $this->uriSegments[2] ?? '';
        
        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        if ($this->subpage == 'logout')
            return $this->logout();
            
        if ($this->subpage == 'login' || !$this->loggedIn())
            // All admin pages require a login. The Login controller will handle that.
            return new Login($this->db);

        if ($this->subpage == 'jobs')
            return new Jobs($this->db, $this->uriSegments);

        if ($this->subpage == 'accounts')
            return new Accounts($this->db, $this->uriSegments);

        if ($this->subpage == 'categories')
            return new Categories($this->db, $this->uriSegments);

        if ($this->subpage == 'enquiries')
            return new Enquiries($this->db, $this->uriSegments);

        // Call appropriate method for subpage if it exist or fallback to home
        $this->{method_exists($this, $this->subpage) ? $this->subpage : 'home'}();
    }

    protected function logout() {
        session_destroy();
        $this->redirect('/');
    }

    public function home() {
        $this->renderPage('admin/index', 'Admin Home');
    }
}