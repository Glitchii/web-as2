<?php

namespace Controllers;

use \Database;
use \Page;

class Home extends Page {
    public $db;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        $this->renderPage("index", "Home");
    }
}
