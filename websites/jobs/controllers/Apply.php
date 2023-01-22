<?php

namespace Controllers;

use \Database;
use \Page;

class About extends Page {
    public function __construct(Database $db) {
        parent::__construct($db);
        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        $this->renderPage("about");
    }
}