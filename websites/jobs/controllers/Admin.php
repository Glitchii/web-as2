<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

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
            return new Login($this->db, $this->uriSegments);

        // Load controller with the same name as the subpage if it exist
        $class = '\\Controllers\\' . ucfirst($this->subpage);
        if (class_exists($class))
            return new $class($this->db, $this->uriSegments);

        // Call appropriate method for subpage if it exist or fallback to home
        $this->{method_exists($this, $this->subpage) ? $this->subpage : 'home'}();
    }

    public function home() {
        $this->renderPage('admin/index', 'Admin Home');
    }
}