<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Contact extends Page {
    public function __construct(Database $db) {
        parent::__construct($db);
    }

    public function run() {
        $this->renderPage("contact");
    }

    public function handleSubmit() {
        if (!$this->param('submit'))
            return 'For any enquiries, please contact us using the form below.';

        $name = $this->param('name');
        $email = $this->param('email');
        $telephone = $this->param('telephone');
        $enquiry = $this->param('enquiry');

        if (!($name && $email && $telephone && $enquiry))
            return 'Enqury not sent! Please fill in all fields.';

        // Using regular expressions to check if number is valid.
        if (!preg_match('/^(\+\d|0)7\d{9}$/', $telephone))
            return 'Enqury not sent! Phone number should start with 0 or country code (eg. +44) followed by 10 numbers.';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            // ref https://www.php.net/manual/en/filter.examples.validation.php
            return 'Enqury not sent! Please enter a valid email address.';

        $this->db->enquiry->insert([
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'enquiry' => $enquiry,
        ]);

        return 'Enquiry sent. Got something else to say? Fill in the form below.';
    }
}
