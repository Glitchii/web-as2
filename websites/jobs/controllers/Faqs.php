<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Faqs extends Page {
    public function __construct(Database $db) {
        parent::__construct($db);
    }

    public function run() {
        $this->renderPage("faqs", "FAQs");
    }
}
