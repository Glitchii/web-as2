<?php

namespace Controllers;

use \Database;
use \Page;

class Faqs extends Page {
    public $db;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        $this->renderPage("faqs", "FAQs");
    }
}
