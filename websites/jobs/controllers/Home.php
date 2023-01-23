<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Home extends Page {
    public function __construct(Database $db) {
        parent::__construct($db);
        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        $this->renderPage("index", "Home");
    }
}
