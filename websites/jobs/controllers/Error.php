<?php

namespace Controllers;

use \Classes\Page;
use \Classes\Database;

class Error extends Page {
    protected array $errors;
    protected string $title;

    public function __construct(Database $db, array $errors, $title = "Error") {
        parent::__construct($db);
        $this->errors = $errors;
        $this->title = $title;
    }

    public function run() {
        $this->renderPage('error', $this->title, ['errors' => $this->errors]);
        $this->renderTemplate('footer'); // Footer wouldn't be auto-rendered since we exit before it.
        exit;
    }
}
